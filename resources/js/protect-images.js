export function initImageProtection() {
  const selectors = [
    '.site-logo',
    '.home-visual-frame',
    '.about-hero-img-wrap',
  ];

  document.addEventListener('contextmenu', (e) => {
    if (selectors.some((sel) => e.target.closest(sel))) {
      e.preventDefault();
    }
  });

  document.addEventListener('dragstart', (e) => {
    if (selectors.some((sel) => e.target.closest(sel))) {
      e.preventDefault();
    }
  });
}
