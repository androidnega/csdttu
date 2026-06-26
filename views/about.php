<?php
require VIEWS . '/layout.php';

frontLayout('About Us', function() {
?>
<style>
  .about-page{
    min-height:100vh;
    background:linear-gradient(180deg,#F8FAFC 0%,#fff 40%);
  }
  .about-hero{
    background:linear-gradient(135deg,#0F2D5C 0%,#1D4ED8 55%,#2563EB 100%);
    color:#fff;padding:clamp(48px,8vh,72px) var(--site-pad);
  }
  .about-hero-inner{
    max-width:var(--site-max);margin:0 auto;
    display:grid;grid-template-columns:1fr 1fr;gap:clamp(32px,5vw,56px);align-items:center;
  }
  .about-hero-copy h1{
    font-family:'Space Grotesk','Inter',sans-serif;
    font-size:clamp(28px,4vw,40px);font-weight:700;
    letter-spacing:-.03em;line-height:1.15;margin-bottom:14px;
  }
  .about-hero-copy p{
    font-size:clamp(14px,1.4vw,16px);line-height:1.7;color:rgba(255,255,255,.88);max-width:48ch;
  }
  .about-tag{
    display:inline-block;margin-bottom:12px;padding:6px 14px;border-radius:100px;
    font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;
    background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);
  }
  .about-hero-img{
    border-radius:16px;overflow:hidden;box-shadow:0 20px 48px rgba(0,0,0,.22);
  }
  .about-hero-img img{
    display:block;width:100%;height:auto;max-height:320px;object-fit:cover;
    -webkit-user-select:none;user-select:none;-webkit-user-drag:none;pointer-events:none;
  }
  .about-hero-img-wrap{position:relative}
  .about-img-shield{position:absolute;inset:0;z-index:1}

  .about-body{
    max-width:var(--site-max);margin:0 auto;
    padding:clamp(40px,6vh,64px) var(--site-pad);
    display:flex;flex-direction:column;gap:clamp(40px,6vh,56px);
  }
  .about-section h2{
    font-family:'Space Grotesk','Inter',sans-serif;
    font-size:clamp(20px,2.4vw,26px);font-weight:600;
    color:var(--navy);letter-spacing:-.02em;margin-bottom:12px;
  }
  .about-section p{
    font-size:15px;line-height:1.75;color:var(--muted);max-width:68ch;
  }
  .about-pillars{
    display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:20px;
  }
  .about-pillar{
    padding:20px;border-radius:14px;background:#fff;
    border:1px solid var(--border);box-shadow:0 2px 8px rgba(15,23,42,.04);
  }
  .about-pillar h3{
    font-size:14px;font-weight:700;color:var(--navy);margin-bottom:6px;
  }
  .about-pillar p{font-size:13px;line-height:1.55;color:var(--muted);max-width:none}

  .about-motto{
    text-align:center;padding:28px;border-radius:16px;
    background:linear-gradient(135deg,#EFF6FF,#F8FAFC);
    border:1px solid var(--border);
  }
  .about-motto p{
    font-family:'Space Grotesk','Inter',sans-serif;
    font-size:clamp(16px,2vw,20px);font-weight:600;color:var(--accent);
    letter-spacing:.12em;text-transform:uppercase;max-width:none;
  }

  @media(max-width:800px){
    .about-hero-inner{grid-template-columns:1fr}
    .about-hero-img{max-width:400px;margin:0 auto}
    .about-pillars{grid-template-columns:1fr}
  }
</style>

<div class="about-page">
  <section class="about-hero afu d0">
    <div class="about-hero-inner">
      <div class="about-hero-copy afu d1">
        <span class="about-tag">About Us</span>
        <h1>Department of Computer Science</h1>
        <p>
          At Takoradi Technical University, the Department of Computer Science is dedicated to
          equipping students with practical skills in software engineering, networking, data science,
          and emerging technologies — bridging academic excellence with industry-ready outcomes.
        </p>
      </div>
      <div class="about-hero-img-wrap about-hero-img afu d2">
        <img src="<?= esc(heroIllustrationUrl()) ?>"
             alt="IT Student Union academic team, Takoradi Technical University"
             width="480" height="320" loading="lazy" draggable="false"/>
        <span class="about-img-shield" aria-hidden="true"></span>
      </div>
    </div>
  </section>

  <div class="about-body" id="contact">
    <section class="about-section afu d2">
      <h2>Our Mission</h2>
      <p>
        We provide a central digital gateway for all departmental platforms, student services,
        and academic resources. From final-year project management to student union activities,
        innovation programmes, and national tech events — everything connects through one portal
        built for the CS community at TTU.
      </p>
      <div class="about-pillars">
        <div class="about-pillar">
          <h3>Research</h3>
          <p>Advancing knowledge through applied research, project work, and academic inquiry.</p>
        </div>
        <div class="about-pillar">
          <h3>Innovate</h3>
          <p>Encouraging creative problem-solving through hackathons, startups, and tech hangouts.</p>
        </div>
        <div class="about-pillar">
          <h3>Build</h3>
          <p>Developing real-world software solutions and platforms that serve students and industry.</p>
        </div>
      </div>
    </section>

    <section class="about-motto afu d3">
      <p>Research &bull; Innovate &bull; Build</p>
    </section>

    <section class="about-section afu d4">
      <h2>Contact</h2>
      <p>
        <strong>Department of Computer Science</strong><br>
        Takoradi Technical University<br>
        Takoradi, Ghana
      </p>
    </section>
  </div>
</div>
<script>
document.addEventListener('contextmenu',function(e){if(e.target.closest('.about-hero-img-wrap'))e.preventDefault()});
document.addEventListener('dragstart',function(e){if(e.target.closest('.about-hero-img-wrap'))e.preventDefault()});
</script>
<?php
}, ['noOverflow' => false, 'activeNav' => 'about', 'topbar' => true]);
?>
