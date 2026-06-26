<?php
/**
 * Shared layout for the Admin Panel.
 * Usage: adminLayout($title, $bodyCallback)
 */
function adminLayout(string $title, callable $body): void {
    $currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $activeTab = '';
    if (strpos($currentUri, '/admin/dashboard') !== false) {
        $activeTab = 'dashboard';
    } elseif (strpos($currentUri, '/admin/cards') !== false) {
        $activeTab = 'cards';
    } elseif (strpos($currentUri, '/admin/settings') !== false) {
        $activeTab = 'settings';
    }
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title) ?> | Admin Portal</title>
  <link rel="icon" type="image/webp" href="<?= esc(siteFaviconUrl()) ?>"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <style>
    /* ── Reset & Theme ───────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --bg: #F4F6F9;
      --panel: #FFFFFF;
      --panel-border: #E2E8F0;
      --accent: #1D4ED8;
      --accent-hover: #1E40AF;
      --text: #1E293B;
      --text-muted: #64748B;
      --navy: #1E3A5F;
      --r: 0.2s ease;
    }
    
    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Inter', system-ui, sans-serif;
      min-height: 100vh;
      display: flex;
      -webkit-font-smoothing: antialiased;
    }

    .bg-grid { display: none; }

    /* ── Admin Container ────────────────────────────── */
    .admin-container {
      display: flex;
      width: 100%;
      min-height: 100vh;
      position: relative;
      z-index: 1;
    }

    /* ── Sidebar ─────────────────────────────────────── */
    .sidebar {
      width: 260px;
      background: var(--panel);
      border-right: 1px solid var(--panel-border);
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      position: sticky;
      top: 0;
      height: 100vh;
      z-index: 20;
      transition: transform var(--r);
    }

    .sb-brand {
      padding: 24px;
      display: flex;
      align-items: center;
      gap: 12px;
      border-bottom: 1px solid var(--panel-border);
      text-decoration: none;
      color: var(--text);
    }

    .sb-brand-logo {
      flex-shrink: 0;
    }
    .sb-brand-logo .site-logo{
      height:44px;width:auto;display:block;
      -webkit-user-select:none;user-select:none;
      -webkit-user-drag:none;
    }

    .sb-brand-text {
      display: flex;
      flex-direction: column;
    }

    .sb-brand-title {
      font-size: 14px;
      font-weight: 700;
      letter-spacing: -0.01em;
    }

    .sb-brand-sub {
      font-size: 10px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--text-muted);
    }

    .sb-nav {
      flex: 1;
      padding: 20px 16px;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .sb-nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 600;
      color: var(--text-muted);
      text-decoration: none;
      transition: all var(--r);
    }

    .sb-nav-item svg {
      transition: color var(--r);
    }

    .sb-nav-item:hover {
      color: var(--text);
      background: #F8FAFC;
    }

    .sb-nav-item.active {
      background: #EFF6FF;
      color: var(--accent);
      border: 1px solid #BFDBFE;
    }

    .sb-nav-item.active svg {
      color: var(--accent);
    }

    .sb-nav-div {
      height: 1px;
      background: var(--panel-border);
      margin: 10px 0;
    }

    .sb-footer {
      padding: 20px 24px;
      border-top: 1px solid var(--panel-border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 12px;
    }

    .sb-user {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .sb-avatar {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      background: #EFF6FF;
      color: var(--accent);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 11px;
    }

    .sb-logout {
      color: var(--text-muted);
      text-decoration: none;
      transition: color var(--r);
      display: flex;
      align-items: center;
    }

    .sb-logout:hover {
      color: #DC2626;
    }

    /* ── Main Content Area ──────────────────────────── */
    .main-area {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    /* Mobile Header */
    .mob-header {
      display: none;
      height: 56px;
      background: var(--panel);
      border-bottom: 1px solid var(--panel-border);
      align-items: center;
      justify-content: space-between;
      padding: 0 16px;
      position: sticky;
      top: 0;
      z-index: 30;
    }

    .mob-menu-btn {
      background: none;
      border: none;
      color: var(--text);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .content-viewport {
      padding: clamp(20px, 4vw, 40px);
      flex: 1;
    }

    /* ── Common Admin UI Elements ─────────────────── */
    .admin-card {
      background: var(--panel);
      border: 1px solid var(--panel-border);
      border-radius: 8px;
      padding: 24px;
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
    }

    /* ── Shared form & button styles ───────────────── */
    .form-label {
      font-size: 12px;
      font-weight: 600;
      color: var(--text);
      letter-spacing: 0.02em;
    }

    .input-field {
      width: 100%;
      background: var(--bg);
      border: 1px solid var(--panel-border);
      border-radius: 6px;
      padding: 10px 14px;
      font-size: 14px;
      color: var(--text);
      font-family: inherit;
      transition: all var(--r);
    }

    .input-field:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.1);
      background: var(--panel);
    }

    .btn-primary {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--accent);
      color: #FFFFFF;
      border: none;
      border-radius: 6px;
      padding: 9px 16px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: all var(--r);
    }

    .btn-primary:hover {
      background: var(--accent-hover);
    }

    .btn-secondary {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--panel);
      border: 1px solid var(--panel-border);
      color: var(--text);
      border-radius: 6px;
      padding: 9px 16px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: all var(--r);
    }

    .btn-secondary:hover {
      background: #F8FAFC;
      border-color: #CBD5E1;
    }

    .chip { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600; letter-spacing: 0.03em; }
    .chip-live { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }
    .chip-beta { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }
    .chip-soon { background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0; }
    .chip-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

    .page-title {
      font-size: clamp(20px, 3vw, 26px);
      font-weight: 700;
      letter-spacing: -0.01em;
      margin-bottom: 8px;
      color: var(--navy);
    }

    .page-subtitle {
      font-size: 13.5px;
      color: var(--text-muted);
      margin-bottom: 30px;
    }

    /* ── Responsive ─────────────────────────────────── */
    @media (max-width: 900px) {
      body {
        flex-direction: column;
      }
      .sidebar {
        position: fixed;
        left: 0;
        bottom: 0;
        top: 56px;
        height: calc(100vh - 56px);
        transform: translateX(-100%);
      }
      .sidebar.open {
        transform: translateX(0);
      }
      .mob-header {
        display: flex;
      }
    }
  </style>
</head>
<body>
  <div class="bg-grid"></div>

  <div class="admin-container">
    <!-- Sidebar -->
    <aside class="sidebar" id="admin-sidebar" role="complementary" aria-label="Admin Navigation">
      <a href="/admin/dashboard" class="sb-brand">
        <div class="sb-brand-logo"><?= siteLogo(44) ?></div>
        <div class="sb-brand-text">
          <span class="sb-brand-title">CS Dept Portal</span>
          <span class="sb-brand-sub">Admin Portal</span>
        </div>
      </a>

      <nav class="sb-nav" role="navigation">
        <a href="/admin/dashboard" class="sb-nav-item <?= $activeTab === 'dashboard' ? 'active' : '' ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
          Dashboard
        </a>
        <a href="/admin/cards" class="sb-nav-item <?= $activeTab === 'cards' ? 'active' : '' ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
          Manage Cards
        </a>
        <a href="/admin/settings" class="sb-nav-item <?= $activeTab === 'settings' ? 'active' : '' ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
          Security Settings
        </a>
        <div class="sb-nav-div"></div>
        <a href="/" class="sb-nav-item" target="_blank">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
          View Portal
        </a>
      </nav>

      <div class="sb-footer">
        <div class="sb-user">
          <div class="sb-avatar" aria-hidden="true">
            <?= strtoupper(substr(authUsername(), 0, 1)) ?>
          </div>
          <span><?= esc(authUsername()) ?></span>
        </div>
        <a href="/admin/logout" class="sb-logout" title="Sign out" aria-label="Sign out">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </a>
      </div>
    </aside>

    <!-- Main View Area -->
    <div class="main-area">
      <!-- Mobile Header toggle -->
      <header class="mob-header">
        <a href="/admin/dashboard" style="text-decoration:none;color:var(--text);font-weight:700;font-size:14px;">CS Dept Admin</a>
        <button class="mob-menu-btn" id="menu-toggle" aria-label="Toggle navigation menu">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
      </header>

      <main class="content-viewport">
        <?php $body(); ?>
      </main>
    </div>
  </div>

  <script>
    document.getElementById('menu-toggle')?.addEventListener('click', function() {
      document.getElementById('admin-sidebar')?.classList.toggle('open');
    });
    document.addEventListener('contextmenu',function(e){if(e.target.closest('.site-logo'))e.preventDefault()});
    document.addEventListener('dragstart',function(e){if(e.target.closest('.site-logo'))e.preventDefault()});
  </script>
</body>
</html>
<?php
}
?>
