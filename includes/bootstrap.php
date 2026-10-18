<?php
declare(strict_types=1);

/**
 * Shared bootstrap: env loading, session, helpers.
 * Included by index.php and contact.php.
 */

define('ROOT_DIR', dirname(__DIR__));

/** Minimal .env loader (KEY=value, # comments, optional quotes). */
function load_env(string $file): array
{
    $vars = [];
    if (!is_readable($file)) {
        return $vars;
    }
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $vars[trim($key)] = trim(trim($value), "\"'");
    }
    return $vars;
}

$GLOBALS['ENV'] = load_env(ROOT_DIR . '/.env');

function env(string $key, string $default = ''): string
{
    return $GLOBALS['ENV'][$key] ?? (getenv($key) !== false ? (string) getenv($key) : $default);
}

if (filter_var(env('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN)) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}

/** Session: used for the CSRF token, the send cooldown and "flash" messages. */
function start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/** HTML-escape. Use on every dynamic value that reaches the page. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Base URL path of the app, so it also works from a sub-folder. */
function base_path(): string
{
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    return rtrim($dir, '/');
}

/** URL for a file under /assets, with a cache-busting ?v= timestamp. */
function asset(string $path): string
{
    $file = ROOT_DIR . '/assets/' . $path;
    $v = is_file($file) ? '?v=' . filemtime($file) : '';
    return base_path() . '/assets/' . $path . $v;
}

/**
 * Inline an SVG icon from includes/icons/<name>.svg.
 * Icons are decorative here, so they are hidden from assistive tech.
 */
function icon(string $name, string $class = ''): string
{
    static $cache = [];
    if (!preg_match('/^[a-z0-9-]+$/', $name)) {
        return '';
    }
    $svg = $cache[$name] ??= (string) @file_get_contents(__DIR__ . "/icons/{$name}.svg");
    if ($svg === '') {
        return '';
    }
    $attrs = 'aria-hidden="true" focusable="false"' . ($class !== '' ? ' class="' . e($class) . '"' : '');
    return preg_replace('/^<svg/', '<svg ' . $attrs, $svg, 1);
}

/** Join class names, skipping empty values. */
function cn(string|false|null ...$classes): string
{
    return implode(' ', array_filter($classes, static fn($c) => is_string($c) && $c !== ''));
}

/* ------------------------------------------------------------------ *
 * UI primitives (the React <Button>, <BentoCard>, <SectionHeading>)
 * ------------------------------------------------------------------ */

/** Class string for the Button component (variant: default|secondary|ghost, size: default|icon|iconSmall). */
function button_class(string $variant = 'default', string $size = 'default', string $extra = ''): string
{
    $base = 'inline-flex shrink-0 items-center justify-center gap-2 whitespace-nowrap rounded-2xl font-medium cursor-pointer select-none '
        . 'transition-all duration-200 active:scale-[0.98] '
        . 'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 '
        . 'disabled:pointer-events-none disabled:cursor-not-allowed '
        . '[&_svg]:size-5 [&_svg]:shrink-0 [&_svg]:pointer-events-none';

    $variants = [
        'default'   => 'bg-primary text-on-primary shadow-lg hover:opacity-90 disabled:bg-on-surface/10 disabled:text-on-surface/40 disabled:shadow-none',
        'secondary' => 'bg-secondary-container text-on-secondary-container hover:opacity-90 disabled:bg-on-surface/10 disabled:text-on-surface/40',
        'ghost'     => 'text-on-surface-variant hover:bg-on-surface-variant/10 disabled:text-on-surface/40',
    ];
    $sizes = [
        'default'   => 'h-14 px-8',
        'icon'      => 'size-14',
        'iconSmall' => 'size-10 rounded-xl',
    ];

    return cn($base, $variants[$variant] ?? $variants['default'], $sizes[$size] ?? $sizes['default'], $extra);
}

/**
 * Open a bento tile. Pass a custom `bg-*` class in $class and set $defaultBg
 * to false so it replaces the default surface colour (tailwind-merge's job in React).
 */
function bento_open(string $class = '', bool $defaultBg = true, string $extraAttrs = ''): void
{
    echo '<div class="' . e(cn(
        'reveal overflow-hidden rounded-3xl p-6 md:p-8',
        $defaultBg ? 'bg-surface-container' : '',
        $class
    )) . '"' . ($extraAttrs !== '' ? ' ' . $extraAttrs : '') . '>';
}

function bento_close(): void
{
    echo '</div>';
}

function section_heading(string $id, string $eyebrow, string $title): void
{
    ?>
    <div class="space-y-2">
      <p class="text-sm font-semibold uppercase tracking-wider text-primary"><?= e($eyebrow) ?></p>
      <h2 id="<?= e($id) ?>" class="text-3xl font-bold tracking-tight md:text-4xl"><?= e($title) ?></h2>
    </div>
    <?php
}

/* ------------------------------------------------------------------ *
 * Contact form: validation shared by contact.php and the page.
 * ------------------------------------------------------------------ */

const MESSAGE_MIN = 20;
const MESSAGE_MAX = 2000;

/** @param array<string,string> $f  name, email, projectType, budget, message */
function validate_contact(array $f, array $projectTypes): array
{
    $errors = [];
    $name    = trim($f['name'] ?? '');
    $email   = trim($f['email'] ?? '');
    $message = trim($f['message'] ?? '');

    if (mb_strlen($name) < 2) {
        $errors['name'] = 'Please enter your name.';
    } elseif (mb_strlen($name) > 120) {
        $errors['name'] = 'That name is a little long.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/', $email)) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if (!in_array($f['projectType'] ?? '', $projectTypes, true)) {
        $errors['projectType'] = 'Please choose a project type.';
    }
    $len = mb_strlen($message);
    if ($len < MESSAGE_MIN) {
        $errors['message'] = 'Tell us a bit more (at least ' . MESSAGE_MIN . ' characters).';
    } elseif ($len > MESSAGE_MAX) {
        $errors['message'] = 'Please keep it under ' . MESSAGE_MAX . ' characters.';
    }
    return $errors;
}

function csrf_token(): string
{
    start_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/** Security headers (the PHP equivalent of the headers block in vercel.json). */
function send_security_headers(): void
{
    if (headers_sent()) {
        return;
    }
    header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self'; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
    if ($https) {
        header('Strict-Transport-Security: max-age=63072000; includeSubDomains');
    }
}
