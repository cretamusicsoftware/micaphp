<section id="work" aria-labelledby="work-title" class="scroll-mt-8 space-y-6">
  <?php section_heading('work-title', 'Portfolio', 'Selected work'); ?>

  <ul class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 lg:grid-cols-4">
    <?php foreach ($PROJECTS as $project): ?>
      <li class="<?= e(cn('flex', $project['featured'] ? 'md:col-span-2' : '')) ?>">
        <?php bento_open(cn('hover-lift flex w-full flex-col justify-between gap-8', $TONES[$project['tone']] ?? $TONES['neutral']), false); ?>
          <div class="flex items-start justify-between">
            <span class="grid size-12 place-items-center rounded-2xl bg-on-surface/10">
              <?= icon($project['icon'], 'size-6') ?>
            </span>

            <?php if ($project['url'] !== ''): ?>
              <a href="<?= e($project['url']) ?>" target="_blank" rel="noopener noreferrer"
                 aria-label="Open <?= e($project['title']) ?>"
                 class="grid size-10 place-items-center rounded-full bg-on-surface/10 transition-colors hover:bg-on-surface/20 focus-visible:outline-2 focus-visible:outline-primary">
                <?= icon('arrow-up-right', 'size-5') ?>
              </a>
            <?php endif; ?>
          </div>

          <div class="space-y-2">
            <h3 class="text-2xl font-bold tracking-tight"><?= e($project['title']) ?></h3>
            <p class="leading-relaxed opacity-85"><?= e($project['desc']) ?></p>
          </div>

          <ul class="flex flex-wrap gap-2" aria-label="<?= e($project['title']) ?> technologies">
            <?php foreach ($project['tech'] as $tech): ?>
              <li class="rounded-full bg-on-surface/10 px-3 py-1 text-sm font-medium"><?= e($tech) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php bento_close(); ?>
      </li>
    <?php endforeach; ?>
  </ul>
</section>
