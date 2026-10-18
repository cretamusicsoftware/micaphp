<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/data.php';

send_security_headers();
start_session();

/** One-time message from contact.php when the form was submitted without JavaScript. */
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= e($SITE['title']) ?></title>
    <meta name="description" content="<?= e($SITE['meta_description']) ?>">
    <meta name="theme-color" content="#fef7ff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#141218" media="(prefers-color-scheme: dark)">
    <link rel="icon" type="image/svg+xml" href="<?= e(asset('img/devs.svg')) ?>">

    <!-- Social previews -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($SITE['title']) ?>">
    <meta property="og:description" content="<?= e($SITE['description']) ?>">
    <meta name="twitter:card" content="summary">

    <!-- Sets the theme class before first paint (no flash). External so the CSP needs no inline-script exceptions. -->
    <script src="<?= e(asset('js/theme-init.js')) ?>"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
    <script src="<?= e(asset('js/app.js')) ?>" defer></script>
  </head>
  <body>
    <div class="min-h-screen bg-background p-4 font-sans text-on-background selection:bg-primary-container selection:text-on-primary-container md:p-8 lg:p-12">
      <a href="#main"
         class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-xl focus:bg-primary focus:px-4 focus:py-2 focus:text-on-primary">
        Skip to content
      </a>

      <div class="mx-auto flex max-w-7xl flex-col gap-12">
        <?php require __DIR__ . '/includes/partials/header.php'; ?>

        <main id="main" class="flex flex-col gap-16">
          <?php
          require __DIR__ . '/includes/sections/overview.php';
          require __DIR__ . '/includes/sections/services.php';
          require __DIR__ . '/includes/sections/work.php';
          require __DIR__ . '/includes/sections/process.php';
          require __DIR__ . '/includes/sections/contact.php';
          ?>
        </main>

        <?php require __DIR__ . '/includes/partials/footer.php'; ?>
      </div>
    </div>
  </body>
</html>
