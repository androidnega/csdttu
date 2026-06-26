(function () {
  /* Mobile nav */
  var toggle = document.getElementById('navToggle');
  var panel = document.getElementById('mobileNav');
  if (toggle && panel) {
    toggle.addEventListener('click', function () {
      var open = panel.hasAttribute('hidden');
      if (open) {
        panel.removeAttribute('hidden');
        toggle.setAttribute('aria-expanded', 'true');
      } else {
        panel.setAttribute('hidden', '');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
    panel.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        panel.setAttribute('hidden', '');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  /* Homepage interactive mesh background */
  var bg = document.getElementById('homeBg');
  if (!bg) return;

  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var orbs = bg.querySelectorAll('[data-parallax]');
  var tx = 0.5, ty = 0.45, cx = 0.5, cy = 0.45;

  function setPos(x, y) {
    tx = Math.max(0, Math.min(1, x));
    ty = Math.max(0, Math.min(1, y));
  }

  function onMove(e) {
    setPos(e.clientX / window.innerWidth, e.clientY / window.innerHeight);
  }

  function tick() {
    cx += (tx - cx) * 0.07;
    cy += (ty - cy) * 0.07;

    var mx = (cx * 100).toFixed(2) + '%';
    var my = (cy * 100).toFixed(2) + '%';
    bg.style.setProperty('--mx', mx);
    bg.style.setProperty('--my', my);

    var dx = (cx - 0.5) * 2;
    var dy = (cy - 0.5) * 2;
    bg.style.setProperty('--gx', (dx * 18).toFixed(1) + 'px');
    bg.style.setProperty('--gy', (dy * 14).toFixed(1) + 'px');

    if (!reduced) {
      orbs.forEach(function (el) {
        var f = parseFloat(el.getAttribute('data-parallax')) || 0;
        el.style.transform = 'translate(' + (dx * f) + 'px,' + (dy * f) + 'px)';
      });
    }

    requestAnimationFrame(tick);
  }

  window.addEventListener('mousemove', onMove, { passive: true });
  window.addEventListener('mouseleave', function () { tx = 0.5; ty = 0.45; }, { passive: true });

  if (!reduced) {
    requestAnimationFrame(tick);
  } else {
    bg.style.setProperty('--mx', '50%');
    bg.style.setProperty('--my', '45%');
  }

  /* Image protection */
  var protect = ['.site-logo', '.home-visual-frame', '.home-visual-ring', '.about-hero-img-wrap'];
  document.addEventListener('contextmenu', function (e) {
    if (protect.some(function (s) { return e.target.closest(s); })) e.preventDefault();
  });
  document.addEventListener('dragstart', function (e) {
    if (protect.some(function (s) { return e.target.closest(s); })) e.preventDefault();
  });
})();
