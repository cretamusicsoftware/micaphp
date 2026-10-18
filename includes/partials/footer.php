<footer class="flex flex-col gap-6 border-t border-outline-variant pt-8 text-sm text-on-surface-variant md:flex-row md:items-center md:justify-between">
  <p>© <?= date('Y') ?> <?= e($SITE['name']) ?>. All rights reserved.</p>

  <nav aria-label="Footer">
    <ul class="flex flex-wrap gap-x-6 gap-y-2">
      <?php foreach ($NAV_LINKS as $link): ?>
        <li>
          <a href="<?= e($link['url']) ?>"
             class="rounded transition-colors hover:text-primary focus-visible:outline-2 focus-visible:outline-primary"><?= e($link['label']) ?></a>
        </li>
      <?php endforeach; ?>
      <?php foreach ($SOCIAL_LINKS as $link): ?>
        <li>
          <a href="<?= e($link['url']) ?>" target="_blank" rel="noopener noreferrer"
             class="rounded transition-colors hover:text-primary focus-visible:outline-2 focus-visible:outline-primary"><?= e($link['label']) ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>
</footer>
