<?php
require VIEWS . '/layout.php';

$hub = findCard($hubSlug);
if (!$hub || !($hub['active'] ?? true) || $hub['parent_id'] !== null || $hub['type'] !== 'hub') {
    redirect('/');
}

$apps = getChildCards($hub['id']);
$liveCount = count(array_filter($apps, fn($a) => ($a['status'] ?? 'live') === 'live'));

frontLayout($hub['title'] . ' Platforms', function() use ($hub, $apps, $liveCount) {
    $clr = colorConfig($hub['color'] ?? 'blue');
?>
<div class="flex flex-1 flex-col" style="--ca:<?= esc($clr['accent']) ?>;--cr:<?= esc($clr['rgb']) ?>">
  <section class="page-hero afu d0">
    <div class="site-container relative z-[1]">
      <div class="flex flex-col gap-6">
        <a href="<?= url('/') ?>" class="btn btn-outline-light btn-sm w-fit">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/><path d="M20 12H9"/></svg>
          Back to Portal
        </a>

        <div class="afu d1 flex flex-col gap-5 md:flex-row md:items-center">
          <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-white/25 bg-white/15 text-white" aria-hidden="true">
            <?= iconSvg($hub['icon'] ?? 'circuit', 28) ?>
          </div>
          <div class="max-w-2xl">
            <h1 class="heading-page text-white"><?= esc($hub['full_name'] ?: $hub['title']) ?></h1>
            <p class="text-lead mt-3 text-white/90"><?= esc($hub['description']) ?></p>
          </div>
        </div>

        <div class="afu d2 flex flex-wrap gap-2">
          <span class="stat-pill"><strong class="text-base font-bold"><?= count($apps) ?></strong> Platforms</span>
          <span class="stat-pill"><strong class="text-base font-bold"><?= $liveCount ?></strong> Live</span>
          <span class="stat-pill">Takoradi Technical University</span>
        </div>
      </div>
    </div>
  </section>

  <div class="page-content">
    <div class="afu d2 mb-8 flex flex-col gap-4 border-b border-slate-200 pb-8 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <span class="eyebrow eyebrow-dark mb-3">Platforms</span>
        <h2 class="heading-section">Department Platforms</h2>
        <p class="text-body mt-2">Select a platform below to launch. All services open in a new tab.</p>
      </div>
      <a href="<?= url('/about') ?>" class="btn btn-ghost w-full sm:w-auto">About Department</a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    <?php foreach ($apps as $i => $app):
      $appClr = colorConfig($app['color'] ?? $hub['color'] ?? 'blue');
      $isSoon = ($app['status'] ?? '') === 'coming_soon';
      $delayClass = 'd' . min(5, $i + 2);
      $url = $app['url'] ?: '#';
      $cardStyle = '--ca:' . esc($appClr['accent']) . ';--cr:' . esc($appClr['rgb']);
    ?>
      <?php if ($isSoon): ?>
      <article class="hub-card afu <?= $delayClass ?>" style="<?= $cardStyle ?>">
        <div class="flex items-start justify-between gap-3">
          <div class="hub-card-icon" aria-hidden="true"><?= iconSvg($app['icon'] ?? 'document', 20) ?></div>
          <span class="chip <?= esc(statusChipClass($app['status'] ?? 'live')) ?>">
            <span class="chip-dot" aria-hidden="true"></span><?= esc(statusLabel($app['status'] ?? 'live')) ?>
          </span>
        </div>
        <div>
          <h3 class="heading-card"><?= esc($app['title']) ?></h3>
          <p class="text-caption mt-1"><?= esc($app['full_name'] ?: 'Platform') ?></p>
        </div>
        <p class="text-body flex-1 text-sm leading-snug"><?= esc($app['description']) ?></p>
        <?php if (!empty($app['features'])): ?>
        <div class="flex flex-wrap gap-1.5">
          <?php foreach (array_slice($app['features'], 0, 3) as $feat): ?>
          <span class="tag"><?= esc($feat) ?></span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <span class="hub-card-btn soon">Coming Soon</span>
      </article>
      <?php else: ?>
      <a href="<?= esc($url) ?>"
         class="hub-card group afu <?= $delayClass ?>"
         style="<?= $cardStyle ?>"
         target="_blank" rel="noopener noreferrer"
         aria-label="Launch <?= esc($app['title']) ?>">
        <div class="flex items-start justify-between gap-3">
          <div class="hub-card-icon transition-transform duration-200 group-hover:scale-105" aria-hidden="true"><?= iconSvg($app['icon'] ?? 'document', 20) ?></div>
          <span class="chip <?= esc(statusChipClass($app['status'] ?? 'live')) ?>">
            <span class="chip-dot" aria-hidden="true"></span><?= esc(statusLabel($app['status'] ?? 'live')) ?>
          </span>
        </div>
        <div>
          <h3 class="heading-card"><?= esc($app['title']) ?></h3>
          <p class="text-caption mt-1"><?= esc($app['full_name'] ?: 'Platform') ?></p>
        </div>
        <p class="text-body line-clamp-2 flex-1 text-sm leading-snug"><?= esc($app['description']) ?></p>
        <?php if (!empty($app['features'])): ?>
        <div class="flex flex-wrap gap-1.5">
          <?php foreach (array_slice($app['features'], 0, 3) as $feat): ?>
          <span class="tag"><?= esc($feat) ?></span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <span class="hub-card-btn">
          Launch
          <svg class="transition-transform duration-200" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
        </span>
      </a>
      <?php endif; ?>
    <?php endforeach; ?>

    <?php if (empty($apps)): ?>
    <div class="col-span-full card flex flex-col items-center py-12 text-center">
      <div class="card-icon !mb-3" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      </div>
      <h3 class="heading-section text-xl">No platforms available</h3>
      <p class="text-body mt-2 max-w-sm text-sm">Please check back later — new services are added regularly.</p>
      <a href="<?= url('/') ?>" class="btn btn-primary mt-6">Return Home</a>
    </div>
    <?php endif; ?>
    </div>
  </div>
</div>
<?php
}, ['noOverflow' => false, 'activeNav' => 'platforms', 'topbar' => true, 'footer' => true]);
?>
