export function initHomeBg() {
  const bg = document.getElementById('homeBg');
  if (!bg) return;

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const orbs = bg.querySelectorAll('[data-parallax]');
  let tx = 0.5;
  let ty = 0.45;
  let cx = 0.5;
  let cy = 0.45;

  function setPos(x, y) {
    tx = Math.max(0, Math.min(1, x));
    ty = Math.max(0, Math.min(1, y));
  }

  function onMove(e) {
    setPos(e.clientX / window.innerWidth, e.clientY / window.innerHeight);
  }

  function tick() {
    cx += (tx - cx) * 0.08;
    cy += (ty - cy) * 0.08;

    bg.style.setProperty('--mx', `${(cx * 100).toFixed(2)}%`);
    bg.style.setProperty('--my', `${(cy * 100).toFixed(2)}%`);

    if (!reduced) {
      const dx = (cx - 0.5) * 2;
      const dy = (cy - 0.5) * 2;
      orbs.forEach((el) => {
        const f = parseFloat(el.getAttribute('data-parallax')) || 0;
        el.style.transform = `translate(${dx * f}px, ${dy * f}px)`;
      });
    }

    requestAnimationFrame(tick);
  }

  document.body.classList.add('home-bg-ready');
  window.addEventListener('mousemove', onMove, { passive: true });
  window.addEventListener('mouseleave', () => {
    tx = 0.5;
    ty = 0.45;
  }, { passive: true });

  if (!reduced) {
    requestAnimationFrame(tick);
  } else {
    bg.style.setProperty('--mx', '50%');
    bg.style.setProperty('--my', '45%');
  }
}
