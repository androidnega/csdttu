<?php
/**
 * Front-end layout renderer.
 */
function frontLayout(string $title, callable $body, array $opts = []): void {
    $showTopbar = $opts['topbar'] ?? true;
    $showFooter = $opts['footer'] ?? true;
    $noOverflow = $opts['noOverflow'] ?? true;
    $activeNav  = $opts['activeNav'] ?? '';
    $bodyClass  = $opts['bodyClass'] ?? '';
    $overflowClass = $noOverflow ? 'overflow-hidden max-lg:overflow-auto' : 'overflow-x-hidden';

    $navItems = [
        ['id' => 'home',      'label' => 'Home',      'href' => url('/')],
        ['id' => 'about',     'label' => 'About',     'href' => url('/about')],
        ['id' => 'platforms', 'label' => 'Platforms', 'href' => url('/hub/csd')],
        ['id' => 'contact',   'label' => 'Contact',   'href' => url('/about#contact')],
    ];
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"/>
  <meta name="description" content="Department of Computer Science, Takoradi Technical University — digital hub for all departmental platforms and student services."/>
  <title><?= esc($title) ?> | CS Dept — TTU</title>
  <link rel="icon" type="image/png" href="<?= esc(siteFaviconUrl()) ?>"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet"/>
  <?= tailwindHead() ?>
</head>
<body class="<?= esc(trim($bodyClass . ' ' . $overflowClass)) ?>">

<?php if ($showTopbar): ?>
<header class="site-header" role="banner">
  <div class="site-header-inner">
    <a href="<?= url('/') ?>" class="group flex min-w-0 items-center gap-3 no-underline" aria-label="CS Department Portal home">
      <?= siteLogo(68) ?>
      <div class="site-brand-text flex min-w-0 flex-col gap-0.5">
        <span class="truncate text-xs font-bold leading-tight text-navy group-hover:text-brand sm:text-sm">CS Dept &mdash; TTU</span>
        <span class="truncate text-[10px] font-medium leading-tight text-muted sm:text-[11px]">Takoradi Technical University</span>
      </div>
    </a>

    <nav aria-label="Main navigation" class="hidden lg:flex">
      <ul class="flex list-none items-center gap-0.5">
        <?php foreach ($navItems as $item): ?>
        <li>
          <a href="<?= esc($item['href']) ?>"
             class="nav-link <?= $activeNav === $item['id'] ? 'nav-link-active' : '' ?>">
            <?= esc($item['label']) ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <button type="button" id="navToggle" class="btn btn-ghost btn-sm btn-icon lg:hidden" aria-expanded="false" aria-controls="mobileNav" aria-label="Open menu">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
    </button>
  </div>

  <nav id="mobileNav" class="mobile-nav-panel" aria-label="Mobile navigation" hidden>
    <ul class="flex list-none flex-col gap-1">
      <?php foreach ($navItems as $item): ?>
      <li>
        <a href="<?= esc($item['href']) ?>"
           class="nav-link w-full <?= $activeNav === $item['id'] ? 'nav-link-active' : '' ?>">
          <?= esc($item['label']) ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </nav>
</header>
<?php endif; ?>

<main class="site-main relative z-[1] flex flex-col">
<?php $body(); ?>
</main>

<?php if ($showFooter): ?>
<footer class="site-footer" role="contentinfo">
  <div class="site-container">
    <p class="truncate whitespace-nowrap">&copy; <?= date('Y') ?> CSD- Takoradi Technical University</p>
  </div>
</footer>
<?php endif; ?>

<?= siteScripts() ?>
</body>
</html>
<?php
}
