<section id="services" aria-labelledby="services-title" class="scroll-mt-8 space-y-6">
  <?php section_heading('services-title', 'Services', 'What we build'); ?>

  <ul class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 lg:grid-cols-4">
    <?php foreach ($SERVICES as $service): ?>
      <li class="flex">
        <?php bento_open('flex w-full flex-col gap-4'); ?>
          <span class="grid size-12 place-items-center rounded-2xl bg-secondary-container text-on-secondary-container">
            <?= icon($service['icon'], 'size-6') ?>
          </span>
          <h3 class="text-xl font-semibold"><?= e($service['title']) ?></h3>
          <p class="leading-relaxed text-on-surface-variant"><?= e($service['desc']) ?></p>
        <?php bento_close(); ?>
      </li>
    <?php endforeach; ?>
  </ul>
</section>
