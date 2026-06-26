export function initMobileNav() {
  const toggle = document.getElementById('navToggle');
  const panel = document.getElementById('mobileNav');
  if (!toggle || !panel) return;

  toggle.addEventListener('click', () => {
    const open = panel.hasAttribute('hidden');
    if (open) {
      panel.removeAttribute('hidden');
      toggle.setAttribute('aria-expanded', 'true');
    } else {
      panel.setAttribute('hidden', '');
      toggle.setAttribute('aria-expanded', 'false');
    }
  });

  panel.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
      panel.setAttribute('hidden', '');
      toggle.setAttribute('aria-expanded', 'false');
    });
  });
}
