<?php
/**
 * Contact form. Works without JavaScript (plain POST → contact.php → redirect back),
 * and app.js upgrades it to an inline fetch() submit.
 *
 * Expects: $SITE, $PROJECT_TYPES, $BUDGETS, $flash (array|null)
 */
$flash    = $flash ?? null;
$old      = $flash['old'] ?? [];
$errors   = $flash['errors'] ?? [];
$success  = ($flash['status'] ?? '') === 'success';
$failed   = ($flash['status'] ?? '') === 'error';
$errMsg   = $failed ? ($flash['message'] ?? '') : '';

$fieldClass = 'w-full rounded-2xl border border-outline-variant bg-surface-container-lowest px-4 py-3.5 text-on-surface placeholder:text-on-surface-variant/60 outline-none transition-colors focus:border-primary focus:ring-2 focus:ring-primary/30 aria-[invalid=true]:border-error aria-[invalid=true]:focus:ring-error/30';

$val = static fn(string $k): string => (string) ($old[$k] ?? '');
$invalid = static fn(string $k): string => isset($errors[$k]) ? ' aria-invalid="true" aria-describedby="' . $k . '-error"' : '';
$fieldError = static function (string $k) use ($errors): void {
    $has = isset($errors[$k]);
    echo '<p id="' . $k . '-error" data-error-for="' . $k . '" class="text-sm text-error"' . ($has ? '' : ' hidden') . '>'
        . ($has ? e($errors[$k]) : '') . '</p>';
};
?>

<!-- Success panel -->
<div data-form-success role="status" <?= $success ? '' : 'hidden' ?>
     class="flex h-full flex-col items-start justify-center gap-4 rounded-2xl bg-primary-container p-6 text-on-primary-container">
  <?= icon('circle-check', 'size-10') ?>
  <h3 class="text-2xl font-bold">Message sent. Thank you!</h3>
  <p class="leading-relaxed opacity-90">
    We&rsquo;ve received your project details and will reply to the email address you provided.
  </p>
  <button type="button" data-form-reset class="<?= e(button_class('secondary')) ?>">Send another message</button>
</div>

<form action="<?= e(base_path()) ?>/contact.php" method="post" novalidate data-contact-form
      class="relative space-y-5" <?= $success ? 'hidden' : '' ?>>
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

  <!-- Honeypot — hidden from people and assistive tech -->
  <div aria-hidden="true" class="absolute -left-[9999px] h-0 w-0 overflow-hidden">
    <label for="website">Leave this field empty</label>
    <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
  </div>

  <div class="grid gap-5 sm:grid-cols-2">
    <div class="space-y-2">
      <label for="name" class="block text-sm font-medium">Your name</label>
      <input id="name" name="name" type="text" autocomplete="name" placeholder="Jane Doe"
             value="<?= e($val('name')) ?>" class="<?= e($fieldClass) ?>"<?= $invalid('name') ?>>
      <?php $fieldError('name'); ?>
    </div>

    <div class="space-y-2">
      <label for="email" class="block text-sm font-medium">Email</label>
      <input id="email" name="email" type="email" autocomplete="email" inputmode="email" placeholder="jane@company.com"
             value="<?= e($val('email')) ?>" class="<?= e($fieldClass) ?>"<?= $invalid('email') ?>>
      <?php $fieldError('email'); ?>
    </div>
  </div>

  <div class="grid gap-5 sm:grid-cols-2">
    <div class="space-y-2">
      <label for="projectType" class="block text-sm font-medium">Project type</label>
      <select id="projectType" name="projectType" class="<?= e($fieldClass) ?>"<?= $invalid('projectType') ?>>
        <option value="">Select one…</option>
        <?php foreach ($PROJECT_TYPES as $type): ?>
          <option value="<?= e($type) ?>"<?= $val('projectType') === $type ? ' selected' : '' ?>><?= e($type) ?></option>
        <?php endforeach; ?>
      </select>
      <?php $fieldError('projectType'); ?>
    </div>

    <div class="space-y-2">
      <label for="budget" class="block text-sm font-medium">
        Budget<span class="font-normal text-on-surface-variant"> (optional)</span>
      </label>
      <select id="budget" name="budget" class="<?= e($fieldClass) ?>">
        <option value="">Prefer not to say</option>
        <?php foreach ($BUDGETS as $budget): ?>
          <option value="<?= e($budget) ?>"<?= $val('budget') === $budget ? ' selected' : '' ?>><?= e($budget) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="space-y-2">
    <label for="message" class="block text-sm font-medium">Tell us about your project</label>
    <textarea id="message" name="message" rows="5" maxlength="<?= MESSAGE_MAX ?>"
              placeholder="What are you building, who is it for, and when do you need it?"
              class="<?= e($fieldClass) ?> resize-y"<?= $invalid('message') ?>><?= e($val('message')) ?></textarea>
    <?php $fieldError('message'); ?>
  </div>

  <div data-form-alert role="alert" <?= $failed ? '' : 'hidden' ?>
       class="flex items-start gap-3 rounded-2xl bg-error-container p-4 text-on-error-container">
    <?= icon('circle-alert', 'mt-0.5 size-5 shrink-0') ?>
    <p class="text-sm leading-relaxed">
      <span data-form-alert-text><?= e($errMsg) ?></span>
      <?php if ($SITE['email'] !== ''): ?>
        You can also email us directly at
        <a href="mailto:<?= e($SITE['email']) ?>" class="font-semibold underline"><?= e($SITE['email']) ?></a>.
      <?php endif; ?>
    </p>
  </div>

  <button type="submit" data-form-submit class="<?= e(button_class('default', 'default', 'w-full sm:w-auto')) ?>">
    <span data-label-idle class="inline-flex items-center gap-2"><?= icon('send') ?> Send message</span>
    <span data-label-sending hidden class="inline-flex items-center gap-2"><?= icon('loader-circle', 'animate-spin') ?> Sending…</span>
  </button>
</form>
