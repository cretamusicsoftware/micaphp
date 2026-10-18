<section id="contact" aria-labelledby="contact-title" class="grid scroll-mt-8 grid-cols-1 gap-4 md:gap-6 lg:grid-cols-5">
  <?php bento_open('flex flex-col justify-between gap-10 bg-primary text-on-primary lg:col-span-2', false); ?>
    <div class="space-y-4">
      <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Contact</p>
      <h2 id="contact-title" class="text-3xl font-bold tracking-tight md:text-4xl">Let&rsquo;s build something great.</h2>
      <p class="text-lg leading-relaxed opacity-90">
        Share a few details about your project. Your message goes straight
        to our inbox and we&rsquo;ll reply by email.
      </p>
    </div>

    <div class="space-y-4">
      <?php if ($SITE['email'] !== ''): ?>
        <a href="mailto:<?= e($SITE['email']) ?>"
           class="inline-flex items-center gap-2 font-semibold underline-offset-4 hover:underline">
          <?= icon('mail', 'size-5') ?>
          <?= e($SITE['email']) ?>
        </a>
      <?php endif; ?>

      <?php if ($SOCIAL_LINKS): ?>
        <ul class="flex gap-3">
          <?php foreach ($SOCIAL_LINKS as $link): ?>
            <li>
              <a href="<?= e($link['url']) ?>" target="_blank" rel="noopener noreferrer"
                 aria-label="<?= e($link['label']) ?>"
                 class="grid size-11 place-items-center rounded-full bg-on-primary/15 transition-colors hover:bg-on-primary/25">
                <?= icon($link['icon'], 'size-5') ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  <?php bento_close(); ?>

  <?php bento_open('lg:col-span-3'); ?>
    <?php require __DIR__ . '/../partials/contact-form.php'; ?>
  <?php bento_close(); ?>
</section>
