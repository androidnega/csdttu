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
    --h-bg:#FFFFFF;
    --h-ink:#0F2D5C;
    --h-ink-soft:#475569;
    --h-ink-mute:#64748B;
    --h-accent:#2563EB;
    --h-accent-soft:#EFF6FF;
    --h-line:#E2E8F0;

    min-height:100vh;
    background:var(--h-bg);
    color:var(--h-ink);
    font-family:'Inter',system-ui,sans-serif;
  }
  html,body.home-screen{overflow-x:hidden}
  body.home-screen .page-wrap{min-height:100vh}
  body.home-screen ::selection{background:rgba(37,99,235,.16)}

  /* ── Animations ── */
  @keyframes home-slide-down{
    from{opacity:0;transform:translateY(-14px)}
    to{opacity:1;transform:none}
  }
  @keyframes home-fade-up{
    from{opacity:0;transform:translateY(22px);filter:blur(6px)}
    to{opacity:1;transform:none;filter:none}
  }
  @keyframes home-fade-left{
    from{opacity:0;transform:translateX(-20px)}
    to{opacity:1;transform:none}
  }
  @keyframes home-fade-right{
    from{opacity:0;transform:translateX(28px);filter:blur(4px)}
    to{opacity:1;transform:none;filter:none}
  }
  @keyframes home-rule-grow{
    from{width:0;opacity:0}
    to{width:52px;opacity:1}
  }
  @keyframes home-float{
    0%,100%{transform:translateY(0)}
    50%{transform:translateY(-12px)}
  }
  @keyframes home-glow{
    0%,100%{opacity:.55;transform:scale(1)}
    50%{opacity:.9;transform:scale(1.06)}
  }
  @keyframes home-blob-drift{
    0%,100%{transform:translate(0,0) scale(1)}
    33%{transform:translate(24px,-18px) scale(1.05)}
    66%{transform:translate(-16px,12px) scale(.96)}
  }
  @keyframes home-cta-shine{
    0%{background-position:200% center}
    100%{background-position:-200% center}
  }
  @keyframes home-arrow-nudge{
    0%,100%{transform:translateX(0)}
    50%{transform:translateX(4px)}
  }

  .home-anim{
    opacity:0;
    animation:home-fade-up .7s cubic-bezier(.22,1,.36,1) forwards;
  }
  .home-anim-down{animation-name:home-slide-down}
  .home-anim-left{animation-name:home-fade-left}
  .home-anim-right{animation-name:home-fade-right}
  .home-anim-rule{animation:home-rule-grow .55s cubic-bezier(.22,1,.36,1) .35s forwards;width:0;opacity:0}
  .ha-d0{animation-delay:.05s}.ha-d1{animation-delay:.12s}.ha-d2{animation-delay:.22s}
  .ha-d3{animation-delay:.34s}.ha-d4{animation-delay:.46s}.ha-d5{animation-delay:.58s}
  .ha-d6{animation-delay:.7s}.ha-d7{animation-delay:.85s}

  .home-shell{
    position:relative;z-index:1;
    min-height:100vh;
    display:flex;flex-direction:column;
    overflow:hidden;
  }

  /* Ambient background blobs */
  .home-ambient{
    position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden;
  }
  .home-blob{
    position:absolute;border-radius:50%;filter:blur(72px);
    animation:home-blob-drift 22s ease-in-out infinite;
  }
  .home-blob-a{
    width:min(42vw,420px);height:min(42vw,420px);
    top:-8%;right:12%;
    background:rgba(37,99,235,.11);
  }
  .home-blob-b{
    width:min(36vw,360px);height:min(36vw,360px);
    bottom:8%;left:-6%;
    background:rgba(8,145,178,.09);
    animation-delay:-8s;animation-duration:26s;
  }
  .home-blob-c{
    width:min(28vw,280px);height:min(28vw,280px);
    top:42%;left:38%;
    background:rgba(124,58,237,.07);
    animation-delay:-14s;animation-duration:30s;
  }

  /* Shared site-width container — header, hero, footer align */
  .home-container{
    width:100%;max-width:var(--site-max);margin:0 auto;
    padding-inline:var(--site-pad);
  }

  /* ── Top bar ── */
  .home-top{
    flex-shrink:0;
    background:#fff;
    padding-top:14px;
  }
  .home-top-inner{
    display:flex;align-items:center;justify-content:space-between;gap:16px;
    padding-bottom:14px;
    border-bottom:1px solid var(--h-line);
  }
  .home-brand{
    display:flex;align-items:center;gap:14px;text-decoration:none;color:inherit;
    transition:transform .25s ease;
  }
  .home-brand:hover{transform:translateY(-1px)}
  .home-brand .site-logo{
    height:clamp(64px,9vw,88px)!important;width:auto!important;
    transition:transform .35s cubic-bezier(.22,1,.36,1);
  }
  .home-brand:hover .site-logo{transform:scale(1.03)}
  .home-brand-text{display:flex;flex-direction:column;gap:1px}
  .home-brand-name{font-size:14px;font-weight:700;color:var(--h-ink);line-height:1.2}
  .home-brand-sub{font-size:11px;font-weight:500;color:var(--h-ink-mute);line-height:1.2}

  .home-about-btn{
    display:inline-flex;align-items:center;gap:7px;
    padding:8px 16px;border-radius:10px;
    border:1.5px solid rgba(37,99,235,.35);
    background:#fff;color:var(--h-accent);
    font-size:13px;font-weight:600;text-decoration:none;
    transition:background .25s,border-color .25s,transform .25s,box-shadow .25s;
    white-space:nowrap;
  }
  .home-about-btn:hover{
    background:var(--h-accent-soft);border-color:var(--h-accent);
    transform:translateY(-1px);
    box-shadow:0 4px 14px rgba(37,99,235,.12);
  }

  /* ── Hero: two columns ── */
  .home-hero{
    flex:1;
    display:flex;align-items:center;
    padding-block:clamp(28px,5vh,56px);
  }
  .home-hero-inner{
    width:100%;
    display:grid;
    grid-template-columns:1fr 1fr;
    align-items:center;
    gap:clamp(32px,5vw,64px);
  }

  .home-copy{
    display:flex;flex-direction:column;align-items:flex-start;
    gap:clamp(14px,2.2vh,20px);
  }
  .home-welcome{
    display:inline-flex;padding:6px 14px;border-radius:100px;
    font-size:11px;font-weight:600;letter-spacing:.04em;
    color:var(--h-accent);background:var(--h-accent-soft);
    border:1px solid rgba(37,99,235,.12);
    transition:box-shadow .3s,transform .3s;
  }
  .home-welcome:hover{
    box-shadow:0 0 0 4px rgba(37,99,235,.08);
    transform:translateY(-1px);
  }
  .home-title{
    font-family:'Space Grotesk','Inter',sans-serif;
    font-size:clamp(32px,4.5vw,48px);font-weight:700;
    letter-spacing:-.03em;line-height:1.1;color:var(--h-ink);
  }
  .home-title .blue{
    color:var(--h-accent);
    display:inline-block;
    background:linear-gradient(90deg,var(--h-accent),#3B82F6,#0891B2,var(--h-accent));
    background-size:200% auto;
    -webkit-background-clip:text;background-clip:text;
    -webkit-text-fill-color:transparent;
    animation:home-cta-shine 6s linear infinite;
  }
  .home-rule{
    width:52px;height:4px;border-radius:2px;
    background:var(--h-accent);
    transform-origin:left center;
  }
  .home-lead{
    font-size:clamp(14px,1.4vw,16px);line-height:1.65;
    color:var(--h-ink-soft);max-width:38ch;
  }
  .home-cta{
    display:inline-flex;align-items:center;gap:12px;
    margin-top:4px;padding:14px 22px 14px 18px;
    border-radius:100px;border:none;
    background:var(--h-accent);color:#fff;
    font-size:14px;font-weight:600;text-decoration:none;
    box-shadow:0 8px 24px rgba(37,99,235,.28);
    transition:transform .3s cubic-bezier(.22,1,.36,1),box-shadow .3s,background .3s;
    position:relative;overflow:hidden;
  }
  .home-cta::after{
    content:'';position:absolute;inset:0;
    background:linear-gradient(105deg,transparent 40%,rgba(255,255,255,.18) 50%,transparent 60%);
    transform:translateX(-100%);
    transition:transform .55s ease;
  }
  .home-cta:hover::after{transform:translateX(100%)}
  .home-cta:hover{
    background:#1D4ED8;
    transform:translateY(-3px);
    box-shadow:0 14px 32px rgba(37,99,235,.36);
  }
  .home-cta-icon{
    width:36px;height:36px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    background:rgba(255,255,255,.18);flex-shrink:0;
    transition:transform .4s ease;
  }
  .home-cta:hover .home-cta-icon{transform:rotate(90deg)}
  .home-cta-arrow{
    width:30px;height:30px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    background:rgba(255,255,255,.22);margin-left:4px;
  }
  .home-cta-arrow svg{
    transition:transform .3s cubic-bezier(.22,1,.36,1);
    animation:home-arrow-nudge 2s ease-in-out 1.2s infinite;
  }
  .home-cta:hover .home-cta-arrow svg{transform:translateX(3px);animation:none}

  .home-visual{
    position:relative;display:flex;align-items:center;justify-content:center;
    max-height:min(72vh,580px);
  }
  .home-visual-frame{
    position:relative;display:inline-block;border-radius:16px;overflow:hidden;
    box-shadow:0 14px 36px rgba(15,45,92,.12);
    animation:home-float 5s ease-in-out 1s infinite;
    transition:box-shadow .4s ease,transform .4s ease;
  }
  .home-visual:hover .home-visual-frame{
    box-shadow:0 22px 48px rgba(15,45,92,.16);
    animation-play-state:paused;
    transform:translateY(-6px) scale(1.02);
  }
  .home-visual img{
    display:block;width:100%;height:auto;
    max-width:min(100%,560px);
    max-height:min(68vh,520px);
    object-fit:contain;
    border-radius:16px;
    -webkit-user-select:none;user-select:none;
    -webkit-user-drag:none;pointer-events:none;
  }
  .home-visual-shield{
    position:absolute;inset:0;z-index:1;cursor:default;
  }
  .home-visual-bg{
    position:absolute;inset:-6% -4%;z-index:-1;pointer-events:none;
    background:
      radial-gradient(ellipse 80% 70% at 50% 50%,rgba(37,99,235,.08),transparent 70%);
    animation:home-glow 4s ease-in-out infinite;
  }

  .home-foot{
    margin-top:auto;
    text-align:center;padding-block:20px 28px;
    font-size:11px;color:var(--h-ink-mute);line-height:1.6;
    background:#fff;
  }
  .home-foot-inner{
    padding-top:20px;
    border-top:1px solid var(--h-line);
  }

  @media(max-width:900px){
    .home-hero-inner{grid-template-columns:1fr;text-align:center}
    .home-copy{align-items:center}
    .home-lead{max-width:48ch}
    .home-visual{order:-1}
    .home-visual img{max-width:min(100%,480px);max-height:min(50vh,400px)}
  }
  @media(max-width:540px){
    .home-about-btn span{display:none}
    .home-cta{width:100%;justify-content:center}
  }
  @media(prefers-reduced-motion:reduce){
    .home-anim,.home-anim-rule{opacity:1;animation:none!important;filter:none!important;transform:none!important;width:52px}
    .home-blob,.home-visual-frame,.home-visual-bg,.home-title .blue,.home-cta-arrow svg{animation:none!important}
    .home-cta:hover,.home-brand:hover,.home-about-btn:hover,.home-visual:hover .home-visual-frame{transform:none}
    .home-cta::after{display:none}
  }
</style>

<div class="home-ambient" aria-hidden="true">
  <span class="home-blob home-blob-a"></span>
  <span class="home-blob home-blob-b"></span>
  <span class="home-blob home-blob-c"></span>
</div>

<div class="home-shell">

  <header class="home-top" role="banner">
    <div class="home-container home-top-inner">
      <a href="<?= url('/') ?>" class="home-brand home-anim home-anim-left ha-d0" aria-label="CS Department home">
        <?= siteLogo(88) ?>
        <span class="home-brand-text">
          <span class="home-brand-name">Computer Science Department</span>
          <span class="home-brand-sub">Takoradi Technical University</span>
        </span>
      </a>
      <a href="<?= url('/about') ?>" class="home-about-btn home-anim home-anim-right ha-d1">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <span>About Department</span>
      </a>
    </div>
  </header>

  <section class="home-hero" id="about" aria-labelledby="intro-h">
    <div class="home-container home-hero-inner">
      <div class="home-copy">
        <span class="home-welcome home-anim ha-d2">Welcome</span>
        <h1 class="home-title home-anim ha-d3" id="intro-h">
          Computer Science<br><span class="blue">Department Portal</span>
        </h1>
        <div class="home-rule home-anim-rule" aria-hidden="true"></div>
        <p class="home-lead home-anim ha-d4">
          Your gateway to departmental platforms, student services, and academic resources.
        </p>
        <a href="<?= esc($exploreUrl) ?>" class="home-cta home-anim ha-d5">
          <span class="home-cta-icon" aria-hidden="true">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
          </span>
          Explore Platforms
          <span class="home-cta-arrow" aria-hidden="true">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </span>
        </a>
      </div>

      <div class="home-visual home-anim home-anim-right ha-d4">
        <div class="home-visual-bg"></div>
        <div class="home-visual-frame">
          <img class="home-hero-img"
               src="<?= esc(heroIllustrationUrl()) ?>"
               alt="IT Student Union academic team, Takoradi Technical University"
               width="560" height="420"
               loading="eager" decoding="async" draggable="false"/>
          <span class="home-visual-shield" aria-hidden="true"></span>
        </div>
      </div>
    </div>
  </section>

  <footer class="home-foot home-anim ha-d6" id="contact" role="contentinfo">
    <div class="home-container home-foot-inner">
      Department of Computer Science &middot; Takoradi Technical University<br>
      &copy; <?= date('Y') ?> CSD&ndash;TTU
    </div>
  </footer>

</div>
<script>
document.addEventListener('contextmenu',function(e){
  if(e.target.closest('.home-visual-frame'))e.preventDefault();
});
document.addEventListener('dragstart',function(e){
  if(e.target.closest('.home-visual-frame'))e.preventDefault();
});
</script>
<?php
}, ['noOverflow' => false, 'activeNav' => 'home', 'bodyClass' => 'home-screen', 'topbar' => false]);
?>
