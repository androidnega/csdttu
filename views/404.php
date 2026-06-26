<?php
require VIEWS . '/layout.php';

frontLayout('Page Not Found', function() {
?>
<div class="page-content flex flex-1 flex-col items-center justify-center text-center">
  <p class="afu d0 font-display text-[clamp(4rem,16vw,7rem)] font-bold leading-none tracking-tighter text-slate-200 select-none" aria-hidden="true">404</p>
  <h1 class="afu d1 heading-section -mt-6">Page Not Found</h1>
  <p class="afu d2 text-body mx-auto mt-3 max-w-md">
    The page or platform you are looking for may have been moved, removed, or does not exist on this portal.
  </p>
  <div class="afu d3 mt-8 flex w-full max-w-sm flex-col gap-3 sm:max-w-none sm:flex-row sm:justify-center">
    <a href="<?= url('/') ?>" class="btn btn-primary w-full sm:w-auto">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Return to Portal
    </a>
    <a href="<?= url('/hub/csd') ?>" class="btn btn-ghost w-full sm:w-auto">View Platforms</a>
  </div>
</div>
<?php
}, ['topbar' => true, 'noOverflow' => false, 'footer' => true, 'activeNav' => '']);
?>
