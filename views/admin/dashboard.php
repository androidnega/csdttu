<?php
require VIEWS . '/admin/layout.php';

$allCards = readCards();
$hubs     = getAllHubs();
$apps     = array_filter($allCards, fn($c) => $c['parent_id'] !== null);

$liveCount   = count(array_filter($allCards, fn($c) => ($c['status'] ?? 'live') === 'live'));
$betaCount   = count(array_filter($allCards, fn($c) => ($c['status'] ?? '') === 'beta'));
$soonCount   = count(array_filter($allCards, fn($c) => ($c['status'] ?? '') === 'coming_soon'));
$inactive    = count(array_filter($allCards, fn($c) => !($c['active'] ?? true)));

adminLayout('Dashboard', function() use ($allCards, $hubs, $apps, $liveCount, $betaCount, $soonCount, $inactive) {
?>
<style>
  /* ── Dashboard Styles ─────────────────────────── */
  .db-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }

  .stat-card {
    background: var(--panel);
    border: 1px solid var(--panel-border);
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
  }

  .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #EFF6FF;
    color: var(--accent);
  }

  .stat-info {
    display: flex;
    flex-direction: column;
  }

  .stat-val {
    font-size: 24px;
    font-weight: 800;
    line-height: 1.2;
  }

  .stat-label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 500;
  }

  /* ── Row Layout ───────────────────────────────── */
  .db-row {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
  }

  @media (max-width: 1024px) {
    .db-row {
      grid-template-columns: 1fr;
    }
  }

  /* ── List view ────────────────────────────────── */
  .panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
  }

  .panel-title {
    font-size: 16px;
    font-weight: 700;
  }

  .hub-summary-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .hub-summary-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: #F8FAFC;
    border: 1px solid var(--panel-border);
    border-radius: 6px;
  }

  .hub-summary-left {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .hub-summary-icon {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #FFFFFF;
    border: 1px solid var(--panel-border);
    color: var(--text-muted);
  }

  .hub-summary-name {
    font-size: 13.5px;
    font-weight: 600;
  }

  .hub-summary-count {
    font-size: 11px;
    font-weight: 600;
    background: #EFF6FF;
    color: var(--accent);
    padding: 3px 8px;
    border-radius: 4px;
    border: 1px solid #BFDBFE;
  }

  .quick-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .quick-link-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    background: #F8FAFC;
    border: 1px solid var(--panel-border);
    border-radius: 6px;
    text-decoration: none;
    color: var(--text);
    transition: all var(--r);
    font-size: 13.5px;
    font-weight: 600;
  }

  .quick-link-btn:hover {
    background: #EFF6FF;
    border-color: #BFDBFE;
    color: var(--accent);
  }

  .quick-link-btn svg {
    color: var(--text-muted);
    transition: color var(--r);
  }

  .quick-link-btn:hover svg {
    color: var(--accent);
  }
</style>

<header>
  <h1 class="page-title">Admin Dashboard</h1>
  <p class="page-subtitle">Portal status overview and control panel metrics.</p>
</header>

<!-- Stats Grid -->
<section class="db-grid" aria-label="Portal Metrics">
  <div class="stat-card">
    <div class="stat-icon" style="background: #EFF6FF; color: #1D4ED8;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
    </div>
    <div class="stat-info">
      <span class="stat-val"><?= count($hubs) ?></span>
      <span class="stat-label">Total Hub Cards</span>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #F5F3FF; color: #7C3AED;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
    </div>
    <div class="stat-info">
      <span class="stat-val"><?= count($apps) ?></span>
      <span class="stat-label">Sub-Applications</span>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #ECFDF5; color: #059669;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
    <div class="stat-info">
      <span class="stat-val"><?= $liveCount ?></span>
      <span class="stat-label">Live Systems</span>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #FEF2F2; color: #DC2626;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
    </div>
    <div class="stat-info">
      <span class="stat-val"><?= $inactive ?></span>
      <span class="stat-label">Hidden Cards</span>
    </div>
  </div>
</section>

<!-- Columns -->
<div class="db-row">
  <!-- Hub Overview -->
  <section class="admin-card">
    <div class="panel-header">
      <h2 class="panel-title">Hub Hierarchy Overview</h2>
      <a href="/admin/cards" class="btn-secondary">Manage Cards</a>
    </div>

    <div class="hub-summary-list">
      <?php foreach ($hubs as $hub): 
        $children = getChildCards($hub['id'], false); 
        $clr = colorConfig($hub['color'] ?? 'blue');
      ?>
      <div class="hub-summary-item">
        <div class="hub-summary-left">
          <div class="hub-summary-icon" style="color: <?= esc($clr['accent']) ?>; background: rgba(<?= esc($clr['rgb']) ?>, 0.06)">
            <?= iconSvg($hub['icon'] ?? 'document', 18) ?>
          </div>
          <div>
            <span class="hub-summary-name"><?= esc($hub['title']) ?></span>
            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;"><?= esc($hub['full_name'] ?: 'No full name') ?></div>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:12px">
          <?php if (!($hub['active'] ?? true)): ?>
          <span style="font-size:10px;font-weight:700;color:#DC2626;text-transform:uppercase;letter-spacing:0.04em;">Hidden</span>
          <?php endif; ?>
          <span class="hub-summary-count">
            <?= count($children) ?> child apps
          </span>
        </div>
      </div>
      <?php endforeach; ?>

      <?php if (empty($hubs)): ?>
      <div style="text-align:center;padding:30px;color:var(--text-muted);font-size:13px">
        No hub cards created yet.
      </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Quick Tasks / Settings -->
  <section class="admin-card">
    <div class="panel-header">
      <h2 class="panel-title">Quick Actions</h2>
    </div>

    <div class="quick-links">
      <a href="/admin/cards/new" class="quick-link-btn">
        <span>Create New Card</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      </a>
      <a href="/admin/cards" class="quick-link-btn">
        <span>Edit App Links</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
      </a>
      <a href="/admin/settings" class="quick-link-btn">
        <span>Update Credentials</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      </a>
    </div>
  </section>
</div>
<?php
});
?>
