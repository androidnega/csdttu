<?php
require VIEWS . '/layout.php';

frontLayout('About Us', function() {
?>
<style>
  .about-page{
    min-height:100vh;
    background:#F8FAFC;
  }
  .about-hero{
    background:linear-gradient(135deg,#0F2D5C 0%,#1E40AF 50%,#2563EB 100%);
    color:#fff;
    padding-block:clamp(40px,7vh,64px);
    position:relative;overflow:hidden;
  }
  .about-hero::after{
    content:'';position:absolute;inset:0;pointer-events:none;
    background:radial-gradient(ellipse 70% 80% at 100% 0%,rgba(255,255,255,.08),transparent 55%);
  }
  .about-hero-inner{
    position:relative;z-index:1;
    display:grid;grid-template-columns:1fr 1fr;
    gap:clamp(28px,4vw,48px);align-items:center;
  }
  .about-hero-copy h1{
    font-family:'Space Grotesk','Inter',sans-serif;
    font-size:clamp(26px,3.8vw,38px);font-weight:700;
    letter-spacing:-.03em;line-height:1.15;margin-bottom:12px;
  }
  .about-hero-copy p{
    font-size:clamp(14px,1.35vw,16px);line-height:1.7;
    color:rgba(255,255,255,.88);max-width:48ch;
  }
  .about-tag{
    display:inline-block;margin-bottom:12px;padding:6px 14px;border-radius:100px;
    font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;
    background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);
  }
  .about-hero-img-wrap{
    position:relative;border-radius:14px;overflow:hidden;
    box-shadow:0 20px 48px rgba(0,0,0,.22);
  }
  .about-hero-img-wrap img{
    display:block;width:100%;height:auto;max-height:300px;object-fit:cover;
    -webkit-user-select:none;user-select:none;-webkit-user-drag:none;pointer-events:none;
  }
  .about-img-shield{position:absolute;inset:0;z-index:1}

  .about-body{
    padding-block:clamp(40px,6vh,64px);
    display:flex;flex-direction:column;gap:clamp(36px,5vh,48px);
  }
  .about-section h2{
    font-family:'Space Grotesk','Inter',sans-serif;
    font-size:clamp(19px,2.2vw,24px);font-weight:600;
    color:var(--navy);letter-spacing:-.02em;margin-bottom:10px;
  }
  .about-section p{
    font-size:15px;line-height:1.75;color:var(--muted);max-width:68ch;
  }
  .about-pillars{
    display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:18px;
  }
  .about-pillar{
    padding:18px;border-radius:12px;background:#fff;
    border:1px solid var(--border);
  }
  .about-pillar h3{
    font-size:14px;font-weight:700;color:var(--navy);margin-bottom:5px;
  }
  .about-pillar p{font-size:13px;line-height:1.55;color:var(--muted)}

  .about-motto{
    text-align:center;padding:24px;border-radius:14px;
    background:linear-gradient(135deg,#EFF6FF,#fff);
    border:1px solid var(--border);
  }
  .about-motto p{
    font-family:'Space Grotesk','Inter',sans-serif;
    font-size:clamp(15px,1.8vw,18px);font-weight:600;color:var(--accent);
    letter-spacing:.1em;text-transform:uppercase;
  }

  @media(max-width:800px){
    .about-hero-inner{grid-template-columns:1fr}
    .about-hero-img-wrap{max-width:400px;margin:0 auto}
    .about-pillars{grid-template-columns:1fr}
  }
</style>

<div class="about-page">
  <section class="about-hero afu d0">
    <div class="site-container">
      <div class="about-hero-inner">
        <div class="about-hero-copy afu d1">
          <span class="about-tag">About Us</span>
          <h1>Department of Computer Science</h1>
          <p>
            At Takoradi Technical University, the Department of Computer Science equips students
            with practical skills in software engineering, networking, data science, and emerging
            technologies — bridging academic excellence with industry-ready outcomes.
          </p>
        </div>
        <div class="about-hero-img-wrap afu d2">
          <img src="<?= esc(heroIllustrationUrl()) ?>"
               alt="IT Student Union academic team, Takoradi Technical University"
               width="480" height="300" loading="lazy" draggable="false"/>
          <span class="about-img-shield" aria-hidden="true"></span>
        </div>
      </div>
    </div>
  </section>

  <div class="site-container about-body" id="contact">
    <section class="about-section afu d2">
      <h2>Our Mission</h2>
      <p>
        We provide a central digital gateway for all departmental platforms, student services,
        and academic resources — from final-year projects to student union activities,
        innovation programmes, and national tech events.
      </p>
      <div class="about-pillars">
        <div class="about-pillar">
          <h3>Research</h3>
          <p>Applied research, project work, and academic inquiry.</p>
        </div>
        <div class="about-pillar">
          <h3>Innovate</h3>
          <p>Hackathons, startups, and tech community events.</p>
        </div>
        <div class="about-pillar">
          <h3>Build</h3>
          <p>Real-world software solutions for students and industry.</p>
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
        Takoradi Technical University &middot; Takoradi, Ghana
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
