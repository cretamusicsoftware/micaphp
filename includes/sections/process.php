<section id="process" aria-labelledby="process-title" class="scroll-mt-8 space-y-6">
  <?php section_heading('process-title', 'How we work', 'From idea to launch'); ?>

  <?php bento_open(); ?>
    <ol class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
      <?php foreach ($PROCESS_STEPS as $i => $step): ?>
        <li class="space-y-3">
          <span class="grid size-10 place-items-center rounded-full bg-primary text-sm font-bold text-on-primary" aria-hidden="true"><?= $i + 1 ?></span>
          <h3 class="text-xl font-semibold"><?= e($step['title']) ?></h3>
          <p class="leading-relaxed text-on-surface-variant"><?= e($step['desc']) ?></p>
        </li>
      <?php endforeach; ?>
    </ol>
  <?php bento_close(); ?>
</section>
