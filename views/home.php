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
<div class="home-bg" id="homeBg" aria-hidden="true">
  <div class="home-bg-base"></div>
  <div class="home-bg-grid"></div>
  <div class="home-bg-mesh"></div>
  <div class="home-bg-vector"></div>
  <div class="home-bg-orb h-[min(38vw,380px)] w-[min(38vw,380px)] -top-[8%] right-[5%] bg-blue-500/12" data-parallax="28"></div>
  <div class="home-bg-orb h-[min(32vw,320px)] w-[min(32vw,320px)] -bottom-[5%] -left-[4%] bg-cyan-400/10" data-parallax="-22"></div>
  <div class="home-bg-orb h-[min(24vw,240px)] w-[min(24vw,240px)] top-[40%] left-[45%] bg-indigo-400/8" data-parallax="14"></div>
  <div class="home-bg-spotlight"></div>
</div>

<div class="home-page-bg">
  <section class="home-hero-section flex flex-1 items-center page-section" aria-labelledby="intro-h">
    <div class="site-container grid w-full grid-cols-1 items-center gap-8 lg:grid-cols-2 lg:gap-16">
      <div class="flex flex-col items-start gap-6 max-lg:items-center max-lg:text-center">
        <span class="home-anim ha-d1 eyebrow eyebrow-dark">
          <span class="h-1.5 w-1.5 rounded-full bg-brand" style="animation:home-pulse 2.4s ease-in-out infinite" aria-hidden="true"></span>
          Welcome
        </span>

        <div class="space-y-4">
          <h1 class="home-anim ha-d2 title-gradient heading-hero" id="intro-h">
            Computer Science<br><em>Department Portal</em>
          </h1>
          <p class="home-anim ha-d3 text-lead text-balance max-w-lg">
            Your gateway to departmental platforms, student services, and academic resources at Takoradi Technical University.
          </p>
        </div>

        <div class="home-anim ha-d4 flex w-full flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center max-lg:justify-center">
          <span class="home-cta-wrap w-full sm:w-auto">
            <a href="<?= esc($exploreUrl) ?>" class="home-cta w-full sm:w-auto">
              <span class="relative z-[1]">Explore Platforms</span>
              <span class="home-cta-arrow" aria-hidden="true">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
              </span>
            </a>
          </span>
          <a href="<?= url('/about') ?>" class="btn btn-ghost w-full sm:w-auto">About the Department</a>
        </div>

        <div class="home-anim ha-d5 flex flex-wrap gap-2 max-lg:justify-center">
          <span class="tag">Research</span>
          <span class="tag">Innovate</span>
          <span class="tag">Build</span>
        </div>
      </div>

      <div class="home-anim ha-d3 home-visual max-lg:order-first">
        <span class="home-visual-dot home-visual-dot-a" aria-hidden="true"></span>
        <span class="home-visual-dot home-visual-dot-b" aria-hidden="true"></span>
        <span class="home-visual-glow" aria-hidden="true"></span>
        <span class="home-visual-back" aria-hidden="true"></span>
        <div class="home-visual-ring">
          <div class="home-visual-frame">
            <img src="<?= esc(heroIllustrationUrl()) ?>"
                 alt="<?= esc(heroIllustrationAlt()) ?>"
                 width="1024" height="636"
                 loading="eager" decoding="async" draggable="false"/>
            <span class="home-visual-corner home-visual-corner-tl" aria-hidden="true"></span>
            <span class="home-visual-corner home-visual-corner-br" aria-hidden="true"></span>
            <div class="home-visual-badge">
              <span class="home-visual-badge-dot" aria-hidden="true"></span>
              <span>
                <strong>Takoradi Technical University</strong>
                <span>Main Campus</span>
              </span>
            </div>
            <span class="absolute inset-0 z-[4]" aria-hidden="true"></span>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php
}, [
    'noOverflow' => false,
    'activeNav' => 'home',
    'bodyClass' => 'page-home bg-[#fafbff]',
    'topbar' => true,
    'footer' => true,
]);
?>
