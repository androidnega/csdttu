<?php
/**
 * Front-end layout renderer.
 */
function frontLayout(string $title, callable $body, array $opts = []): void {
    $showTopbar = $opts['topbar'] ?? true;
    $noOverflow = $opts['noOverflow'] ?? true;
    $activeNav  = $opts['activeNav'] ?? '';
    $bodyClass  = $opts['bodyClass'] ?? '';
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="Department of Computer Science, Takoradi Technical University — digital hub for all departmental platforms and student services."/>
  <title><?= esc($title) ?> | CS Dept — TTU</title>
  <link rel="icon" type="image/png" href="<?= esc(siteFaviconUrl()) ?>"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet"/>
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    :root{
      --bg:#F8FAFC;
      --bg2:#FFFFFF;
      --text:#1E293B;
      --muted:#64748B;
      --border:#E2E8F0;
      --card:#FFFFFF;
      --accent:#1D4ED8;
      --navy:#0F2D5C;
      --hero-blue:#0B2D6B;
      --site-max:1200px;
      --site-pad:clamp(20px,4vw,40px);
      --r:.2s ease;
    }
    html,body{
      min-height:100%;
      font-family:'Inter',system-ui,sans-serif;
      background:var(--bg);
      color:var(--text);
      <?= $noOverflow ? 'overflow:hidden;' : 'overflow-x:hidden;' ?>
      -webkit-font-smoothing:antialiased;
    }
    @media(max-width:960px){html,body{overflow:auto!important}}
    .page-wrap{position:relative;z-index:1}

    .site-header{
      position:sticky;top:0;z-index:100;
      width:100%;background:var(--bg2);
      border-bottom:1px solid var(--border);
      box-shadow:0 1px 4px rgba(15,23,42,.06);
    }
    .site-header-inner{
      max-width:var(--site-max);margin:0 auto;
      padding:10px var(--site-pad);
      display:flex;
      align-items:center;justify-content:space-between;gap:16px;
    }
    .header-brand{
      display:flex;align-items:center;gap:11px;
      text-decoration:none;justify-self:start;
    }
    .site-logo{
      display:block;flex-shrink:0;height:auto;
      -webkit-user-select:none;user-select:none;
      -webkit-user-drag:none;
    }
    .header-brand-text{display:flex;flex-direction:column;gap:1px}
    .header-name{font-size:14px;font-weight:700;color:var(--navy);line-height:1.2}
    .header-sub{font-size:11px;font-weight:500;color:var(--muted);line-height:1.2}

    .header-nav{
      display:flex;align-items:center;gap:2px;
      list-style:none;
    }
    .header-nav a{
      display:inline-flex;align-items:center;gap:4px;
      padding:8px 14px;font-size:13px;font-weight:500;
      color:var(--muted);text-decoration:none;
      border-bottom:2px solid transparent;
      transition:color var(--r),border-color var(--r);
    }
    .header-nav a:hover{color:var(--navy)}
    .header-nav a.active{color:var(--accent);font-weight:600;border-bottom-color:var(--accent)}
    .header-nav .nav-chev{width:12px;height:12px;opacity:.6}

    .chip{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:100px;font-size:10px;font-weight:600;white-space:nowrap}
    .chip-live{background:#ECFDF5;color:#059669;border:1px solid #A7F3D0}
    .chip-beta{background:#FFFBEB;color:#D97706;border:1px solid #FDE68A}
    .chip-soon{background:#F1F5F9;color:#64748B;border:1px solid #E2E8F0}
    .chip-dot{width:5px;height:5px;border-radius:50%;background:currentColor}

    @keyframes fade-up{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
    .afu{opacity:0;animation:fade-up .45s ease forwards}
    .d0{animation-delay:.04s}.d1{animation-delay:.1s}.d2{animation-delay:.16s}
    .d3{animation-delay:.22s}.d4{animation-delay:.28s}.d5{animation-delay:.34s}
    :focus-visible{outline:2px solid var(--accent);outline-offset:2px}
    @media(prefers-reduced-motion:reduce){.afu{opacity:1;transform:none}}

    @media(max-width:960px){
      .header-nav{display:none}
    }
  </style>
</head>
<body<?= $bodyClass ? ' class="' . esc($bodyClass) . '"' : '' ?>>

<?php if ($showTopbar): ?>
<header class="site-header" role="banner">
  <div class="site-header-inner">
    <a href="<?= url('/') ?>" class="header-brand" aria-label="CS Department Portal home">
      <?= siteLogo(52) ?>
      <div class="header-brand-text">
        <span class="header-name">CS Dept &mdash; TTU</span>
        <span class="header-sub">Takoradi Technical University</span>
      </div>
    </a>

    <nav aria-label="Main navigation">
      <ul class="header-nav">
        <li><a href="<?= url('/') ?>" class="<?= $activeNav === 'home' ? 'active' : '' ?>">Home</a></li>
        <li><a href="<?= url('/about') ?>" class="<?= $activeNav === 'about' ? 'active' : '' ?>">About</a></li>
        <li>
          <a href="<?= url('/hub/csd') ?>" class="<?= $activeNav === 'platforms' ? 'active' : '' ?>">
            Platforms
            <svg class="nav-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
          </a>
        </li>
        <li><a href="<?= url('/about#contact') ?>">Contact</a></li>
      </ul>
    </nav>
  </div>
</header>
<?php endif; ?>

<div class="page-wrap">
<?php $body(); ?>
</div>

<script>
document.addEventListener('contextmenu',function(e){if(e.target.closest('.site-logo'))e.preventDefault()});
document.addEventListener('dragstart',function(e){if(e.target.closest('.site-logo'))e.preventDefault()});
</script>
</body>
</html>
<?php
}
