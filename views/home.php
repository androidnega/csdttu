<?php
require VIEWS . '/layout.php';

$hubs = getHubs();
$exploreUrl = url('/');
if (!empty($hubs)) {
    $first = $hubs[0];
    $exploreUrl = ($first['type'] === 'hub' || empty($first['url']))
        ? url('/hub/' . $first['id'])
        : $first['url'];
}

frontLayout('CS Department Portal', function() use ($exploreUrl) {
?>
<style>
  body.home-screen{
    --h-ink:#0F2D5C;
    --h-ink-soft:#475569;
    --h-ink-mute:#94A3B8;
    --h-accent:#2563EB;
    --h-accent-soft:#EFF6FF;
    --h-line:rgba(226,232,240,.9);
  }
  body.home-screen{background:#EFF6FF}
  body.home-screen .page-wrap{min-height:calc(100vh - 73px);position:relative}
  body.home-screen .site-header{
    background:rgba(255,255,255,.75);
    backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);
    border-bottom-color:rgba(37,99,235,.1);
  }

  @keyframes home-rise{
    from{opacity:0;transform:translateY(16px)}
    to{opacity:1;transform:none}
  }
  @keyframes home-float{
    0%,100%{transform:translateY(0)}
    50%{transform:translateY(-8px)}
  }
  @keyframes home-drift{
    0%,100%{transform:translate(0,0)}
    50%{transform:translate(12px,-8px)}
  }
  @keyframes cta-shine{
    0%{transform:translateX(-120%) skewX(-18deg)}
    55%,100%{transform:translateX(220%) skewX(-18deg)}
  }
  @keyframes cta-ring{
    0%{box-shadow:0 0 0 0 rgba(37,99,235,.45)}
    70%{box-shadow:0 0 0 10px rgba(37,99,235,0)}
    100%{box-shadow:0 0 0 0 rgba(37,99,235,0)}
  }
  @keyframes cta-arrow{
    0%,100%{transform:translateX(0)}
    50%{transform:translateX(5px)}
  }
  @keyframes cta-border{
    0%{background-position:0% 50%}
    100%{background-position:200% 50%}
  }

  .home-anim{opacity:0;animation:home-rise .7s cubic-bezier(.22,1,.36,1) forwards}
  .ha-d1{animation-delay:.06s}.ha-d2{animation-delay:.14s}.ha-d3{animation-delay:.22s}
  .ha-d4{animation-delay:.3s}.ha-d5{animation-delay:.38s}

  /* ── Interactive background ── */
  .home-bg{
    position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden;
    --mx:50%;--my:45%;
  }
  .home-bg-base{
    position:absolute;inset:0;
    background:linear-gradient(160deg,#F8FAFF 0%,#FFFFFF 42%,#F0F7FF 100%);
  }
  .home-bg-dots{
    position:absolute;inset:-20px;
    background-image:radial-gradient(circle,rgba(37,99,235,.14) 1.2px,transparent 1.2px);
    background-size:28px 28px;
    mask-image:radial-gradient(ellipse 90% 80% at 50% 40%,#000 20%,transparent 75%);
    -webkit-mask-image:radial-gradient(ellipse 90% 80% at 50% 40%,#000 20%,transparent 75%);
    animation:home-drift 28s ease-in-out infinite;
  }
  .home-bg-lines{
    position:absolute;inset:0;
    background-image:repeating-linear-gradient(
      -55deg,
      transparent 0,
      transparent 47px,
      rgba(37,99,235,.045) 47px,
      rgba(37,99,235,.045) 48px
    );
    opacity:.9;
  }
  .home-bg-mesh{
    position:absolute;inset:0;
    background:
      radial-gradient(ellipse 48% 42% at 92% 8%,rgba(37,99,235,.14),transparent 58%),
      radial-gradient(ellipse 44% 40% at 4% 92%,rgba(6,182,212,.1),transparent 55%);
  }
  .home-bg-spotlight{
    position:absolute;inset:0;
    background:radial-gradient(
      560px circle at var(--mx) var(--my),
      rgba(37,99,235,.16) 0%,
      rgba(59,130,246,.07) 35%,
      transparent 68%
    );
  }
  .home-bg-orb{
    position:absolute;border-radius:50%;filter:blur(64px);
    will-change:transform;
  }
  .home-bg-orb-1{
    width:min(46vw,460px);height:min(46vw,460px);
    top:-16%;right:0;
    background:rgba(37,99,235,.2);
  }
  .home-bg-orb-2{
    width:min(38vw,380px);height:min(38vw,380px);
    bottom:-8%;left:-6%;
    background:rgba(6,182,212,.15);
  }

  .home-shell{
    position:relative;z-index:1;
    min-height:calc(100vh - 73px);
    display:flex;flex-direction:column;
  }

  .home-hero{
    flex:1;display:flex;align-items:center;
    padding-block:clamp(40px,7vh,80px);
  }
  .home-hero-inner{
    display:grid;grid-template-columns:1.08fr .92fr;
    align-items:center;gap:clamp(28px,5vw,52px);
  }

  .home-copy{display:flex;flex-direction:column;align-items:flex-start;gap:18px}
  .home-welcome{
    display:inline-flex;align-items:center;gap:7px;
    padding:6px 14px 6px 10px;border-radius:100px;
    font-size:11px;font-weight:600;letter-spacing:.04em;
    color:var(--h-accent);background:rgba(255,255,255,.7);
    border:1px solid rgba(37,99,235,.14);
    backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);
    box-shadow:0 1px 2px rgba(15,45,92,.04);
  }
  .home-welcome-dot{
    width:6px;height:6px;border-radius:50%;
    background:var(--h-accent);
    box-shadow:0 0 0 3px rgba(37,99,235,.15);
    animation:home-pulse 2.4s ease-in-out infinite;
  }
  @keyframes home-pulse{
    0%,100%{opacity:1;transform:scale(1)}
    50%{opacity:.7;transform:scale(.88)}
  }
  .home-title{
    font-family:'Space Grotesk','Inter',sans-serif;
    font-size:clamp(32px,4.4vw,48px);font-weight:700;
    letter-spacing:-.035em;line-height:1.1;color:var(--h-ink);
  }
  .home-title em{
    font-style:normal;
    background:linear-gradient(135deg,#2563EB 0%,#3B82F6 45%,#0891B2 100%);
    -webkit-background-clip:text;background-clip:text;
    -webkit-text-fill-color:transparent;
  }
  .home-lead{
    font-size:clamp(14px,1.35vw,16px);line-height:1.7;
    color:var(--h-ink-soft);max-width:42ch;
  }
  .home-actions{
    display:flex;flex-wrap:wrap;align-items:center;gap:16px;margin-top:2px;
  }
  .home-cta-wrap{
    position:relative;display:inline-flex;padding:2px;border-radius:14px;
    background:linear-gradient(90deg,#2563EB,#3B82F6,#06B6D4,#3B82F6,#2563EB);
    background-size:200% 100%;
    animation:cta-border 4s linear infinite;
  }
  .home-cta{
    position:relative;z-index:1;
    display:inline-flex;align-items:center;gap:10px;
    padding:12px 20px;border-radius:12px;border:none;
    background:linear-gradient(135deg,#2563EB 0%,#1D4ED8 100%);
    color:#fff;font-size:14px;font-weight:600;text-decoration:none;
    box-shadow:0 6px 20px rgba(37,99,235,.28);
    overflow:hidden;
    animation:cta-ring 2.8s ease-out infinite;
    transition:transform .28s cubic-bezier(.22,1,.36,1),box-shadow .28s;
  }
  .home-cta::after{
    content:'';position:absolute;top:0;left:0;
    width:45%;height:100%;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.32),transparent);
    animation:cta-shine 3.2s ease-in-out infinite;
    pointer-events:none;
  }
  .home-cta:hover{
    transform:translateY(-3px) scale(1.02);
    box-shadow:0 12px 32px rgba(37,99,235,.35);
    animation:none;
  }
  .home-cta-label{position:relative;z-index:1}
  .home-cta-arrow{
    position:relative;z-index:1;
    display:flex;align-items:center;justify-content:center;
    width:28px;height:28px;border-radius:8px;
    background:rgba(255,255,255,.18);
    animation:cta-arrow 1.6s ease-in-out infinite;
  }
  .home-cta:hover .home-cta-arrow{animation:none;transform:translateX(4px)}
  .home-link{
    font-size:14px;font-weight:500;color:var(--h-ink-soft);
    text-decoration:none;transition:color .2s;
  }
  .home-link:hover{color:var(--h-accent)}

  .home-visual{position:relative;display:flex;justify-content:center}
  .home-visual-glow{
    position:absolute;inset:10% 5%;z-index:0;
    background:radial-gradient(ellipse at center,rgba(37,99,235,.12),transparent 70%);
    filter:blur(24px);pointer-events:none;
  }
  .home-visual-frame{
    position:relative;z-index:1;
    border-radius:18px;overflow:hidden;
    background:rgba(255,255,255,.55);
    border:1px solid rgba(255,255,255,.8);
    box-shadow:
      0 1px 2px rgba(15,45,92,.04),
      0 20px 48px rgba(15,45,92,.08);
    backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);
    animation:home-float 7s ease-in-out 1.2s infinite;
    transition:transform .4s cubic-bezier(.22,1,.36,1),box-shadow .4s;
  }
  .home-visual:hover .home-visual-frame{
    transform:translateY(-4px) scale(1.01);
    box-shadow:0 28px 56px rgba(15,45,92,.12);
    animation-play-state:paused;
  }
  .home-visual img{
    display:block;width:100%;height:auto;
    max-width:min(100%,500px);max-height:min(60vh,460px);
    object-fit:contain;
    -webkit-user-select:none;user-select:none;
    -webkit-user-drag:none;pointer-events:none;
  }
  .home-visual-shield{position:absolute;inset:0;z-index:2}

  .home-foot{
    padding-block:20px 26px;text-align:center;
    font-size:11px;color:var(--h-ink-mute);line-height:1.6;
    border-top:1px solid var(--h-line);
    background:rgba(255,255,255,.5);
    backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);
  }
  .home-foot-line{
    white-space:nowrap;
    overflow:hidden;text-overflow:ellipsis;
  }

  @media(max-width:900px){
    .home-hero-inner{grid-template-columns:1fr;text-align:center}
    .home-copy{align-items:center}
    .home-lead{max-width:48ch}
    .home-visual{order:-1}
    .home-actions{justify-content:center}
  }
  @media(max-width:480px){
    .home-cta{width:100%;justify-content:center}
  }
  @media(prefers-reduced-motion:reduce){
    .home-anim{opacity:1;animation:none}
    .home-visual-frame,.home-welcome-dot,.home-bg-dots{animation:none}
    .home-cta,.home-cta-wrap,.home-cta::after,.home-cta-arrow{animation:none}
    .home-cta:hover,.home-visual:hover .home-visual-frame{transform:none}
  }
</style>

<div class="home-bg" id="homeBg" aria-hidden="true">
  <div class="home-bg-base"></div>
  <div class="home-bg-lines"></div>
  <div class="home-bg-dots"></div>
  <div class="home-bg-mesh"></div>
  <div class="home-bg-orb home-bg-orb-1" data-parallax="32"></div>
  <div class="home-bg-orb home-bg-orb-2" data-parallax="-26"></div>
  <div class="home-bg-spotlight"></div>
</div>

<div class="home-shell">
  <section class="home-hero" aria-labelledby="intro-h">
    <div class="site-container home-hero-inner">
      <div class="home-copy">
        <span class="home-welcome home-anim ha-d1">
          <span class="home-welcome-dot" aria-hidden="true"></span>
          Welcome
        </span>
        <h1 class="home-title home-anim ha-d2" id="intro-h">
          Computer Science<br><em>Department Portal</em>
        </h1>
        <p class="home-lead home-anim ha-d3">
          Your gateway to departmental platforms, student services, and academic resources.
        </p>
        <div class="home-actions home-anim ha-d4">
          <span class="home-cta-wrap">
            <a href="<?= esc($exploreUrl) ?>" class="home-cta">
              <span class="home-cta-label">Explore Platforms</span>
              <span class="home-cta-arrow" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
              </span>
            </a>
          </span>
          <a href="<?= url('/about') ?>" class="home-link">About the department</a>
        </div>
      </div>

      <div class="home-visual home-anim ha-d3">
        <div class="home-visual-glow" aria-hidden="true"></div>
        <div class="home-visual-frame">
          <img src="<?= esc(heroIllustrationUrl()) ?>"
               alt="IT Student Union academic team, Takoradi Technical University"
               width="500" height="400"
               loading="eager" decoding="async" draggable="false"/>
          <span class="home-visual-shield" aria-hidden="true"></span>
        </div>
      </div>
    </div>
  </section>

  <footer class="home-foot home-anim ha-d5" role="contentinfo">
    <div class="site-container">
      <p class="home-foot-line">&copy; <?= date('Y') ?> CSD- Takoradi Technical University</p>
    </div>
  </footer>
</div>
<script>
(function(){
  var bg=document.getElementById('homeBg');
  if(!bg)return;

  var reduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var orbs=bg.querySelectorAll('[data-parallax]');
  var tx=0.5,ty=0.45,cx=0.5,cy=0.45;
  var active=false;

  function setPos(x,y){
    tx=Math.max(0,Math.min(1,x));
    ty=Math.max(0,Math.min(1,y));
    active=true;
  }

  function onMove(e){
    setPos(e.clientX/window.innerWidth,e.clientY/window.innerHeight);
  }

  function tick(){
    cx+=(tx-cx)*0.08;
    cy+=(ty-cy)*0.08;

    bg.style.setProperty('--mx',(cx*100).toFixed(2)+'%');
    bg.style.setProperty('--my',(cy*100).toFixed(2)+'%');

    if(!reduced){
      var dx=(cx-0.5)*2,dy=(cy-0.5)*2;
      orbs.forEach(function(el){
        var f=parseFloat(el.getAttribute('data-parallax'))||0;
        el.style.transform='translate('+(dx*f)+'px,'+(dy*f)+'px)';
      });
    }

    requestAnimationFrame(tick);
  }

  document.body.classList.add('home-bg-ready');
  window.addEventListener('mousemove',onMove,{passive:true});
  window.addEventListener('mouseleave',function(){tx=0.5;ty=0.45},{passive:true});

  if(!reduced){
    requestAnimationFrame(tick);
  }else{
    bg.style.setProperty('--mx','50%');
    bg.style.setProperty('--my','45%');
  }

  document.addEventListener('contextmenu',function(e){
    if(e.target.closest('.home-visual-frame'))e.preventDefault();
  });
  document.addEventListener('dragstart',function(e){
    if(e.target.closest('.home-visual-frame'))e.preventDefault();
  });
})();
</script>
<?php
}, ['noOverflow' => false, 'activeNav' => 'home', 'bodyClass' => 'home-screen', 'topbar' => true]);
?>
