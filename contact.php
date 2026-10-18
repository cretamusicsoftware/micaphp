<?php
declare(strict_types=1);

/**
 * Contact form handler — replaces the EmailJS call in the React version.
 *
 * - POST only, CSRF-protected, honeypot, server-side validation, 60 s cooldown
 * - Answers JSON when called by app.js (fetch), otherwise redirects back to /#contact
 */

require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/data.php';

send_security_headers();
start_session();

const COOLDOWN_SECONDS = 60;

$wantsJson = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');

/** Send the result back as JSON or as a flash message + redirect. */
function respond(bool $wantsJson, int $status, array $payload, array $old = []): never
{
    if ($wantsJson) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $_SESSION['flash'] = [
        'status'  => !empty($payload['ok']) ? 'success' : 'error',
        'message' => $payload['message'] ?? '',
        'errors'  => $payload['errors'] ?? [],
        'old'     => !empty($payload['ok']) ? [] : $old,
    ];
    header('Location: ' . base_path() . '/#contact', true, 303);
    exit;
}

/** Strip anything that could inject extra mail headers. */
function clean_header(string $value): string
{
    return trim(preg_replace('/[\r\n\t]+/', ' ', $value));
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    echo 'Method not allowed.';
    exit;
}

$input = [
    'name'        => (string) ($_POST['name'] ?? ''),
    'email'       => (string) ($_POST['email'] ?? ''),
    'projectType' => (string) ($_POST['projectType'] ?? ''),
    'budget'      => (string) ($_POST['budget'] ?? ''),
    'message'     => (string) ($_POST['message'] ?? ''),
];

// CSRF
$token = (string) ($_POST['csrf'] ?? '');
if ($token === '' || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
    respond($wantsJson, 403, [
        'ok' => false,
        'message' => 'Your session expired. Please reload the page and try again.',
    ], $input);
}

// Honeypot: real visitors never fill this. Pretend it worked so bots get no signal.
if (trim((string) ($_POST['website'] ?? '')) !== '') {
    respond($wantsJson, 200, ['ok' => true]);
}

// Validation
$errors = validate_contact($input, $PROJECT_TYPES);
if ($errors) {
    respond($wantsJson, 422, ['ok' => false, 'errors' => $errors, 'message' => 'Please fix the highlighted fields.'], $input);
}
if ($input['budget'] !== '' && !in_array($input['budget'], $BUDGETS, true)) {
    $input['budget'] = '';
}

// Cooldown (server-side, per session)
$last = (int) ($_SESSION['last_submit'] ?? 0);
$wait = COOLDOWN_SECONDS - (time() - $last);
if ($wait > 0) {
    respond($wantsJson, 429, [
        'ok' => false,
        'message' => "You just sent a message. Please wait {$wait}s before sending another.",
    ], $input);
}

// Delivery
$to = env('CONTACT_EMAIL');
if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
    respond($wantsJson, 500, ['ok' => false, 'message' => 'The contact form is not set up yet.'], $input);
}

$fromName  = clean_header(trim($input['name']));
$fromEmail = clean_header(trim($input['email']));
$from      = filter_var(env('MAIL_FROM'), FILTER_VALIDATE_EMAIL) ? env('MAIL_FROM') : $to;

$subject = 'New project inquiry from ' . $fromName;
$body = implode("\n", [
    'Name: ' . $fromName,
    'Email: ' . $fromEmail,
    'Project type: ' . $input['projectType'],
    'Budget: ' . ($input['budget'] !== '' ? $input['budget'] : 'Not specified'),
    'Sent: ' . date('j M Y, H:i T'),
    '',
    str_replace(["\r\n", "\r"], "\n", trim($input['message'])),
]);

$headers = [
    'From' => mb_encode_mimeheader($SITE['name'], 'UTF-8') . " <{$from}>",
    'Reply-To' => mb_encode_mimeheader($fromName, 'UTF-8') . " <{$fromEmail}>",
    'MIME-Version' => '1.0',
    'Content-Type' => 'text/plain; charset=UTF-8',
    'X-Mailer' => 'PHP/' . PHP_VERSION,
];

if (env('MAIL_DRIVER', 'mail') === 'log') {
    // Development: write the message to storage/mail.log instead of sending it.
    $entry = "=== " . date('c') . " ===\nTo: {$to}\nSubject: {$subject}\n"
        . implode("\n", array_map(static fn($k, $v) => "{$k}: {$v}", array_keys($headers), $headers))
        . "\n\n{$body}\n\n";
    $sent = @file_put_contents(ROOT_DIR . '/storage/mail.log', $entry, FILE_APPEND | LOCK_EX) !== false;
} else {
    $sent = @mail($to, mb_encode_mimeheader($subject, 'UTF-8'), $body, $headers);
}

if (!$sent) {
    error_log('[contact form] mail delivery failed');
    respond($wantsJson, 500, ['ok' => false, 'message' => 'Something went wrong while sending your message.'], $input);
}

$_SESSION['last_submit'] = time();
respond($wantsJson, 200, ['ok' => true]);
