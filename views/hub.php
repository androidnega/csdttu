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
<style>
  .hub-page{min-height:100vh;background:#F8FAFC}

  /* ── Hero banner ── */
  .hub-hero{
    background:linear-gradient(135deg,#0F2D5C 0%,#1E40AF 50%,#2563EB 100%);
    padding:clamp(36px,6vh,56px) var(--site-pad) clamp(32px,5vh,48px);
    position:relative;overflow:hidden;
  }
  .hub-hero::before{
    content:'';position:absolute;inset:0;pointer-events:none;
    background:
      radial-gradient(ellipse 60% 80% at 100% 0%,rgba(255,255,255,.08),transparent 55%),
      radial-gradient(ellipse 50% 60% at 0% 100%,rgba(0,0,0,.12),transparent 50%);
  }
  .hub-hero-inner{
    position:relative;z-index:1;
    max-width:var(--site-max);margin:0 auto;
    display:flex;flex-direction:column;gap:20px;
  }
  .hub-crumb{
    display:inline-flex;align-items:center;gap:6px;
    font-size:12px;font-weight:600;color:rgba(255,255,255,.7);
    text-decoration:none;align-self:flex-start;
    transition:color .2s;
  }
  .hub-crumb:hover{color:#fff}
  .hub-hero-main{
    display:flex;align-items:center;gap:clamp(16px,3vw,24px);flex-wrap:wrap;
  }
  .hub-hero-icon{
    width:clamp(52px,8vw,64px);height:clamp(52px,8vw,64px);
    border-radius:14px;display:flex;align-items:center;justify-content:center;
    background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.22);
    color:#fff;flex-shrink:0;
  }
  .hub-hero-text h1{
    font-family:'Space Grotesk','Inter',sans-serif;
    font-size:clamp(24px,3.8vw,36px);font-weight:700;
    color:#fff;letter-spacing:-.03em;line-height:1.15;
  }
  .hub-hero-text p{
    font-size:clamp(13px,1.3vw,15px);color:rgba(255,255,255,.82);
    margin-top:8px;line-height:1.6;max-width:56ch;
  }
  .hub-stats{
    display:flex;flex-wrap:wrap;gap:10px;margin-top:4px;
  }
  .hub-stat{
    display:inline-flex;align-items:center;gap:8px;
    padding:8px 14px;border-radius:10px;
    background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.16);
    font-size:12px;font-weight:600;color:#fff;
  }
  .hub-stat strong{font-size:16px;font-weight:700}

  /* ── Platforms grid ── */
  .hub-body{
    max-width:var(--site-max);margin:0 auto;
    padding:clamp(28px,5vh,40px) var(--site-pad) clamp(40px,6vh,56px);
  }
  .hub-section-head{
    display:flex;align-items:flex-end;justify-content:space-between;
    gap:12px;margin-bottom:20px;flex-wrap:wrap;
  }
  .hub-section-head h2{
    font-family:'Space Grotesk','Inter',sans-serif;
    font-size:clamp(18px,2vw,22px);font-weight:600;
    color:var(--navy);letter-spacing:-.02em;
  }
  .hub-section-head p{font-size:13px;color:var(--muted)}

  .hub-grid{
    display:grid;
    grid-template-columns:repeat(4,minmax(0,1fr));
    gap:14px;
  }

  .hub-card{
    background:#fff;border:1px solid var(--border);border-radius:14px;
    padding:16px;display:flex;flex-direction:column;gap:10px;
    text-decoration:none;color:inherit;
    box-shadow:0 1px 3px rgba(15,23,42,.05);
    transition:transform .22s,box-shadow .22s,border-color .22s;
    position:relative;overflow:hidden;
  }
  .hub-card::before{
    content:'';position:absolute;top:0;left:0;right:0;height:3px;
    background:var(--ca,#2563EB);opacity:.85;
  }
  .hub-card:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 28px rgba(15,23,42,.1);
    border-color:rgba(var(--cr,37,99,235),.3);
  }
  .hub-card-top{
    display:flex;align-items:flex-start;justify-content:space-between;gap:8px;
  }
  .hub-card-icon{
    width:40px;height:40px;border-radius:10px;
    display:flex;align-items:center;justify-content:center;
    background:rgba(var(--cr,37,99,235),.08);
    border:1px solid rgba(var(--cr,37,99,235),.14);
    color:var(--ca);
    flex-shrink:0;
  }
  .hub-card-top .chip{font-size:9px;padding:3px 8px}
  .hub-card-name{
    font-family:'Space Grotesk','Inter',sans-serif;
    font-size:15px;font-weight:600;color:var(--navy);
    letter-spacing:-.02em;line-height:1.2;
  }
  .hub-card-type{font-size:11px;color:var(--muted);margin-top:2px}
  .hub-card-desc{
    font-size:11.5px;color:var(--muted);line-height:1.5;flex:1;
    display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;
  }
  .hub-card-tags{
    display:flex;flex-wrap:wrap;gap:4px;
  }
  .hub-card-tags span{
    font-size:9px;padding:3px 7px;border-radius:4px;
    background:#F1F5F9;border:1px solid var(--border);color:var(--muted);
    white-space:nowrap;
  }
  .hub-card-btn{
    display:inline-flex;align-items:center;justify-content:center;gap:5px;
    margin-top:auto;padding:9px 12px;border-radius:8px;
    font-size:12px;font-weight:600;
    background:var(--ca);color:#fff;
    transition:background .2s,transform .2s;
  }
  .hub-card:hover .hub-card-btn{background:color-mix(in srgb,var(--ca) 88%,#000)}
  .hub-card-btn svg{transition:transform .2s}
  .hub-card:hover .hub-card-btn svg{transform:translate(2px,-2px)}
  .hub-card-btn.soon{background:#F1F5F9;color:var(--muted);cursor:not-allowed}

  .hub-empty{
    grid-column:1/-1;text-align:center;padding:48px 24px;
    background:#fff;border:1px dashed var(--border);border-radius:14px;
    color:var(--muted);font-size:14px;
  }

  @media(max-width:1024px){.hub-grid{grid-template-columns:repeat(3,1fr)}}
  @media(max-width:768px){.hub-grid{grid-template-columns:repeat(2,1fr)}}
  @media(max-width:480px){.hub-grid{grid-template-columns:1fr}}
  @media(prefers-reduced-motion:reduce){.hub-card:hover{transform:none}}
</style>

<div class="hub-page" style="--ca:<?= esc($clr['accent']) ?>;--cr:<?= esc($clr['rgb']) ?>">
  <section class="hub-hero afu d0">
    <div class="hub-hero-inner">
      <a href="<?= url('/') ?>" class="hub-crumb">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/><path d="M20 12H9"/></svg>
        Back to Portal
      </a>
      <div class="hub-hero-main afu d1">
        <div class="hub-hero-icon" aria-hidden="true">
          <?= iconSvg($hub['icon'] ?? 'circuit', 30) ?>
        </div>
        <div class="hub-hero-text">
          <h1><?= esc($hub['full_name'] ?: $hub['title']) ?></h1>
          <p><?= esc($hub['description']) ?></p>
        </div>
      </div>
      <div class="hub-stats afu d2">
        <span class="hub-stat"><strong><?= count($apps) ?></strong> Platforms</span>
        <span class="hub-stat"><strong><?= $liveCount ?></strong> Live</span>
        <span class="hub-stat">Takoradi Technical University</span>
      </div>
    </div>
  </section>

  <div class="hub-body">
    <div class="hub-section-head afu d2">
      <div>
        <h2>Department Platforms</h2>
        <p>Select a platform to launch</p>
      </div>
    </div>

    <div class="hub-grid">
    <?php foreach ($apps as $i => $app):
      $appClr = colorConfig($app['color'] ?? $hub['color'] ?? 'blue');
      $isSoon = ($app['status'] ?? '') === 'coming_soon';
      $delayClass = 'd' . min(5, $i + 2);
      $url = $app['url'] ?: '#';
    ?>
      <?php if ($isSoon): ?>
      <article class="hub-card afu <?= $delayClass ?>"
               style="--ca:<?= esc($appClr['accent']) ?>;--cr:<?= esc($appClr['rgb']) ?>">
        <div class="hub-card-top">
          <div class="hub-card-icon" aria-hidden="true"><?= iconSvg($app['icon'] ?? 'document', 20) ?></div>
          <span class="chip <?= esc(statusChipClass($app['status'] ?? 'live')) ?>">
            <span class="chip-dot" aria-hidden="true"></span><?= esc(statusLabel($app['status'] ?? 'live')) ?>
          </span>
        </div>
        <div>
          <div class="hub-card-name"><?= esc($app['title']) ?></div>
          <div class="hub-card-type"><?= esc($app['full_name'] ?: 'Platform') ?></div>
        </div>
        <p class="hub-card-desc"><?= esc($app['description']) ?></p>
        <?php if (!empty($app['features'])): ?>
        <div class="hub-card-tags">
          <?php foreach (array_slice($app['features'], 0, 3) as $feat): ?>
          <span><?= esc($feat) ?></span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <span class="hub-card-btn soon">Coming Soon</span>
      </article>
      <?php else: ?>
      <a href="<?= esc($url) ?>"
         class="hub-card afu <?= $delayClass ?>"
         style="--ca:<?= esc($appClr['accent']) ?>;--cr:<?= esc($appClr['rgb']) ?>"
         target="_blank" rel="noopener noreferrer"
         aria-label="Launch <?= esc($app['title']) ?>">
        <div class="hub-card-top">
          <div class="hub-card-icon" aria-hidden="true"><?= iconSvg($app['icon'] ?? 'document', 20) ?></div>
          <span class="chip <?= esc(statusChipClass($app['status'] ?? 'live')) ?>">
            <span class="chip-dot" aria-hidden="true"></span><?= esc(statusLabel($app['status'] ?? 'live')) ?>
          </span>
        </div>
        <div>
          <div class="hub-card-name"><?= esc($app['title']) ?></div>
          <div class="hub-card-type"><?= esc($app['full_name'] ?: 'Platform') ?></div>
        </div>
        <p class="hub-card-desc"><?= esc($app['description']) ?></p>
        <?php if (!empty($app['features'])): ?>
        <div class="hub-card-tags">
          <?php foreach (array_slice($app['features'], 0, 3) as $feat): ?>
          <span><?= esc($feat) ?></span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <span class="hub-card-btn">
          Launch
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
        </span>
      </a>
      <?php endif; ?>
    <?php endforeach; ?>

    <?php if (empty($apps)): ?>
    <div class="hub-empty">No platforms are available at this time. Please check back later.</div>
    <?php endif; ?>
    </div>
  </div>
</div>
<?php
}, ['noOverflow' => false, 'activeNav' => 'platforms', 'topbar' => true]);
?>
