<?php
require VIEWS . '/layout.php';

$hub = findCard($hubSlug);
if (!$hub || !($hub['active'] ?? true) || $hub['parent_id'] !== null || $hub['type'] !== 'hub') {
    redirect('/');
}

$apps = getChildCards($hub['id']);

frontLayout($hub['title'] . ' Hub', function() use ($hub, $apps) {
    $clr = colorConfig($hub['color'] ?? 'blue');
?>
<style>
  /* ── Hub Detail Shell ──────────────────── */
  .hub-detail {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    padding: 72px clamp(16px, 4vw, 40px) 40px;
    max-width: var(--site-max);
    margin: 0 auto;
    gap: 24px;
  }

  /* ── Header Area ───────────────────────── */
  .hub-header {
    display: flex;
    flex-direction: column;
    gap: 16px;
    position: relative;
  }

  .back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--muted);
    text-decoration: none;
    transition: color var(--r), transform var(--r);
    align-self: flex-start;
  }
  .back-link:hover {
    color: var(--accent);
    transform: translateX(-2px);
  }

  .header-main {
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .header-icon {
    width: 56px;
    height: 56px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #F8FAFC;
    border: 1px solid var(--border);
    color: var(--ca);
    flex-shrink: 0;
  }

  .header-title h1 {
    font-size: clamp(22px, 4vw, 30px);
    font-weight: 700;
    letter-spacing: -0.02em;
    line-height: 1.2;
    color: var(--navy);
  }

  .header-title p {
    font-size: clamp(12px, 1.5vw, 15px);
    color: var(--muted);
    margin-top: 6px;
    line-height: 1.5;
    max-width: 600px;
  }

  /* ── Sub-App Grid ──────────────────────── */
  .app-detail-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
    width: 100%;
  }

  .app-detail-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    position: relative;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
    transition: border-color var(--r), box-shadow var(--r);
  }

  .app-detail-card:hover {
    border-color: #BFDBFE;
    box-shadow: 0 3px 10px rgba(15, 23, 42, 0.07);
  }

  .app-card-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 8px;
  }

  .app-card-head .header-main {
    gap: 8px;
    min-width: 0;
  }

  .app-card-icon {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #F8FAFC;
    border: 1px solid var(--border);
    color: var(--ca);
    flex-shrink: 0;
  }

  .app-card-title {
    min-width: 0;
  }

  .app-card-title h3 {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: -0.01em;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .app-card-title p {
    font-size: 10px;
    color: var(--muted);
    margin-top: 1px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .app-card-head .chip {
    font-size: 9px;
    padding: 2px 7px;
    flex-shrink: 0;
  }

  .app-desc {
    font-size: 11px;
    color: var(--muted);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .app-features-wrap {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .app-features-title {
    font-size: 9px;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--muted);
  }

  .app-features {
    list-style: none;
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
  }

  .app-features li {
    font-size: 9.5px;
    color: var(--muted);
    line-height: 1.2;
    padding: 2px 6px;
    background: #F8FAFC;
    border: 1px solid var(--border);
    border-radius: 4px;
    white-space: nowrap;
  }

  .app-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    text-decoration: none;
    background: var(--accent);
    color: #FFFFFF;
    border: 1px solid var(--accent);
    transition: all var(--r);
    margin-top: auto;
    align-self: stretch;
    text-align: center;
  }

  .app-action-btn:hover:not(.btn-soon) {
    background: #1E40AF;
    border-color: #1E40AF;
  }

  .app-action-btn svg {
    transition: transform var(--r);
  }

  .app-action-btn:hover:not(.btn-soon) svg {
    transform: translate(1px, -1px);
  }

  .btn-soon {
    background: #F1F5F9;
    border-color: var(--border);
    color: var(--muted);
    cursor: not-allowed;
  }

  /* ── Responsive ────────────────────────── */
  @media (max-width: 1024px) {
    .app-detail-grid {
      grid-template-columns: repeat(3, minmax(0, 1fr));
    }
  }
  @media (max-width: 780px) {
    .hub-detail {
      padding: 80px 16px 40px;
    }
    .header-main {
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
    }
    .app-detail-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }
  @media (max-width: 480px) {
    .app-detail-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="hub-detail" style="--ca:<?= esc($clr['accent']) ?>;--cr:<?= esc($clr['rgb']) ?>">
  <!-- Header / Breadcrumbs -->
  <header class="hub-header">
    <a href="<?= url('/') ?>" class="back-link afu d0">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
      </svg>
      Back to Portal
    </a>

    <div class="header-main afu d1">
      <div class="header-icon">
        <?= iconSvg($hub['icon'] ?? 'document', 28) ?>
      </div>
      <div class="header-title">
        <h1><?= esc($hub['title']) ?> Platforms</h1>
        <p><?= esc($hub['description'] ?: 'Browse through the dedicated services and custom-built applications under the ' . ($hub['full_name'] ?: $hub['title']) . '.') ?></p>
      </div>
    </div>
  </header>

  <!-- Applications Grid -->
  <main class="app-detail-grid">
    <?php 
    foreach ($apps as $i => $app): 
      $appClr = colorConfig($app['color'] ?? $hub['color'] ?? 'blue');
      $delayClass = 'd' . min(5, $i + 2);
      $isSoon = ($app['status'] ?? '') === 'coming_soon';
    ?>
    <article class="app-detail-card afu <?= $delayClass ?>" 
             style="--ca:<?= esc($appClr['accent']) ?>;--cr:<?= esc($appClr['rgb']) ?>"
             aria-label="<?= esc($app['title']) ?> — <?= esc($app['full_name'] ?? '') ?>">
      
      <!-- Head -->
      <div class="app-card-head">
        <div class="header-main" style="gap:8px;">
          <div class="app-card-icon" aria-hidden="true">
            <?= iconSvg($app['icon'] ?? 'document', 16) ?>
          </div>
          <div class="app-card-title">
            <h3><?= esc($app['title']) ?></h3>
            <p><?= esc($app['full_name'] ?: 'Department Service') ?></p>
          </div>
        </div>
        
        <span class="chip <?= esc(statusChipClass($app['status'] ?? 'live')) ?>">
          <span class="chip-dot" aria-hidden="true"></span>
          <?= esc(statusLabel($app['status'] ?? 'live')) ?>
        </span>
      </div>

      <!-- Divider -->
      <div style="height:1px;background:var(--border);" aria-hidden="true"></div>

      <!-- Description -->
      <p class="app-desc"><?= esc($app['description'] ?: 'Access resources and tools for the ' . ($app['full_name'] ?: $app['title']) . ' application.') ?></p>

      <!-- Key features -->
      <?php if (!empty($app['features'])): ?>
      <div class="app-features-wrap">
        <span class="app-features-title">Highlights</span>
        <ul class="app-features">
          <?php foreach (array_slice($app['features'], 0, 4) as $feat): ?>
          <li><?= esc($feat) ?></li>
          <?php endforeach; ?>
          <?php if (count($app['features']) > 4): ?>
          <li>+<?= count($app['features']) - 4 ?> more</li>
          <?php endif; ?>
        </ul>
      </div>
      <?php endif; ?>

      <!-- Action -->
      <?php if ($isSoon): ?>
      <button class="app-action-btn btn-soon" disabled aria-label="Launching soon">
        Coming Soon
      </button>
      <?php else: ?>
      <a href="<?= esc($app['url'] ?: '#') ?>" 
         class="app-action-btn" 
         target="_blank" 
         rel="noopener noreferrer" 
         aria-label="Open <?= esc($app['title']) ?> in new tab">
        Launch
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <line x1="7" y1="17" x2="17" y2="7"></line>
          <polyline points="7 7 17 7 17 17"></polyline>
        </svg>
      </a>
      <?php endif; ?>

    </article>
    <?php endforeach; ?>

    <?php if (empty($apps)): ?>
    <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: var(--muted); background: var(--card); border: 1px solid var(--border); border-radius: 8px;">
      <p style="font-size: 14px;">No applications have been added to this hub yet.</p>
      <a href="<?= url('/admin') ?>" style="color: var(--accent); text-decoration: none; font-size: 13px; font-weight: 600; margin-top: 12px; display: inline-block;">Manage Portal Apps</a>
    </div>
    <?php endif; ?>
  </main>
</div>
<?php
}, ['noOverflow' => false]);
?>
