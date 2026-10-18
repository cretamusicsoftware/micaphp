<section id="about" aria-label="About <?= e($SITE['name']) ?>" class="scroll-mt-8">
  <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 lg:grid-cols-4">

    <!-- About -->
    <?php bento_open('flex flex-col justify-between gap-10 bg-primary-container text-on-primary-container md:col-span-2 lg:row-span-2', false); ?>
      <div class="space-y-4">
        <p class="text-sm font-semibold uppercase tracking-wider opacity-80">About us</p>
        <h2 class="text-3xl font-bold tracking-tight md:text-4xl">We turn ideas into fast, reliable software.</h2>
        <p class="text-lg leading-relaxed opacity-90">
          <?= e($SITE['name']) ?> is a full-stack agency. We handle design, front end,
          back end and deployment, so you work with one focused team from
          first sketch to production.
        </p>
      </div>

      <ul class="flex flex-wrap gap-2" aria-label="Core technologies">
        <?php foreach ($PROFILE_TAGS as $tag): ?>
          <li class="rounded-full bg-on-primary-container/10 px-4 py-1.5 text-sm font-medium"><?= e($tag) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php bento_close(); ?>

    <!-- Availability -->
    <?php bento_open('flex flex-col justify-between gap-6 bg-tertiary-container text-on-tertiary-container md:col-span-2 lg:col-span-2', false); ?>
      <div class="flex items-center gap-3">
        <span class="relative flex size-3" aria-hidden="true">
          <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-500 opacity-75 motion-reduce:animate-none"></span>
          <span class="relative inline-flex size-3 rounded-full bg-emerald-500"></span>
        </span>
        <p class="font-semibold">Open for new projects</p>
      </div>

      <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <p class="max-w-sm text-lg leading-relaxed opacity-90">Tell us about your idea and we&rsquo;ll get back to you.</p>
        <a href="#contact" class="<?= e(button_class('secondary', 'default', 'shrink-0')) ?>">
          Get in touch
          <?= icon('arrow-right') ?>
        </a>
      </div>
    <?php bento_close(); ?>

    <!-- Tech stack -->
    <?php bento_open('space-y-5 md:col-span-2 lg:col-span-2'); ?>
      <h3 class="text-lg font-semibold">Our stack</h3>
      <ul class="grid grid-cols-3 gap-3 sm:grid-cols-6 lg:grid-cols-3 xl:grid-cols-6">
        <?php foreach ($TECH_STACKS as $tech): ?>
          <li class="flex flex-col items-center gap-2">
            <div class="grid size-14 place-items-center rounded-2xl bg-white p-2.5 shadow-sm" aria-hidden="true">
              <?= icon($tech['icon'], 'size-full') ?>
            </div>
            <span class="text-center text-xs font-medium text-on-surface-variant"><?= e($tech['name']) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php bento_close(); ?>

  </div>
</section>
