<?php
require VIEWS . '/layout.php';

frontLayout('Page Not Found', function() {
?>
<style>
  .error-container {
    height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    text-align: center;
  }

  .error-code {
    font-size: clamp(64px, 12vw, 120px);
    font-weight: 700;
    line-height: 1;
    color: var(--navy);
    letter-spacing: -0.03em;
  }

  .error-title {
    font-size: clamp(18px, 3vw, 24px);
    font-weight: 600;
    margin: 12px 0 12px;
    letter-spacing: -0.01em;
    color: var(--text);
  }

  .error-desc {
    font-size: 14px;
    color: var(--muted);
    max-width: 420px;
    line-height: 1.6;
    margin-bottom: 28px;
  }

  .error-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    color: #FFFFFF;
    background: var(--accent);
    border: 1px solid var(--accent);
    transition: all var(--r);
  }

  .error-btn:hover {
    background: #1E40AF;
    border-color: #1E40AF;
  }

  .error-btn svg {
    transition: transform var(--r);
  }

  .error-btn:hover svg {
    transform: translateX(-2px);
  }
</style>

<div class="error-container">
  <div class="error-code afu d0">404</div>
  <h1 class="error-title afu d1">Page Not Found</h1>
  <p class="error-desc afu d2">The page or platform you are looking for may have been moved, removed, or does not exist on this portal.</p>
  <div class="afu d3">
    <a href="/" class="error-btn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
      </svg>
      Return to Portal
    </a>
  </div>
</div>
<?php
}, ['topbar' => false, 'noOverflow' => true]);
?>
