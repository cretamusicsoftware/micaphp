<header class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
  <div class="space-y-2 anim-in-left">
    <div class="flex items-center gap-2 font-semibold uppercase tracking-tight text-primary">
      <?= icon('sparkle', 'size-5') ?>
      <p><?= e($SITE['eyebrow']) ?></p>
    </div>
    <h1 class="text-5xl font-bold tracking-tight text-on-background md:text-7xl">
      Mica <span class="text-primary">Devs</span>
    </h1>
    <p class="max-w-xl text-xl leading-relaxed text-on-surface-variant"><?= e($SITE['description']) ?></p>
  </div>

  <div class="flex flex-wrap items-center gap-4 anim-in-right">
    <a href="#contact" class="<?= e(button_class()) ?>">
      <?= icon('mail') ?>
      Start a project
    </a>

    <div class="flex gap-2">
      <?php foreach ($SOCIAL_LINKS as $link): ?>
        <a href="<?= e($link['url']) ?>" target="_blank" rel="noopener noreferrer"
           aria-label="<?= e($link['label']) ?>"
           class="<?= e(button_class('secondary', 'icon')) ?>">
          <?= icon($link['icon']) ?>
        </a>
      <?php endforeach; ?>

      <button type="button" data-theme-toggle
              class="<?= e(button_class('secondary', 'icon')) ?>"
              aria-label="Toggle theme">
        <?= icon('moon', 'dark:hidden') ?>
        <?= icon('sun', 'hidden dark:block') ?>
      </button>
    </div>
  </div>
</header>
