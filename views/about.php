<?php
require VIEWS . '/layout.php';

frontLayout('About Us', function() {
?>
<div class="flex flex-1 flex-col">
  <section class="page-hero afu d0">
    <div class="site-container relative z-[1]">
      <div class="grid grid-cols-1 items-stretch gap-10 lg:grid-cols-2 lg:gap-14">
        <div class="afu d1 flex flex-col justify-center space-y-5">
          <span class="eyebrow eyebrow-light">About Us</span>
          <h1 class="heading-page text-white">Department of Computer Science</h1>
          <p class="text-lead max-w-xl text-white/90">
            At Takoradi Technical University, the Department of Computer Science trains skilled graduates
            through hands-on learning in software engineering, networking, data science, and emerging technologies.
          </p>
          <div class="flex flex-col gap-3 pt-1 sm:flex-row">
            <a href="<?= url('/hub/csd') ?>" class="btn btn-white">View Platforms</a>
            <a href="#contact" class="btn btn-outline-light">Contact Us</a>
          </div>
        </div>

        <div class="about-hero-img-wrap afu d2 w-full lg:h-full">
          <img src="<?= esc(heroIllustrationUrl()) ?>"
               alt="<?= esc(heroIllustrationAlt()) ?>"
               width="1024" height="636"
               loading="lazy" draggable="false"/>
          <span class="absolute inset-0 z-[1]" aria-hidden="true"></span>
        </div>
      </div>
    </div>
  </section>

  <div class="about-page-body flex flex-col gap-16 lg:gap-20">
    <section class="afu d1 grid grid-cols-1 gap-8 lg:grid-cols-[1fr_1.1fr] lg:gap-14">
      <div>
        <span class="eyebrow eyebrow-dark mb-4">Who We Are</span>
        <h2 class="heading-section">A hub for computing excellence at TTU</h2>
        <p class="text-body mt-3">
          The Department of Computer Science at Takoradi Technical University is committed to producing
          industry-ready graduates who can design, build, and maintain modern digital systems.
        </p>
        <p class="text-body mt-3">
          Our programmes blend strong theoretical foundations with practical, project-based learning —
          preparing students for careers in software development, IT administration, data analytics,
          cybersecurity, and tech entrepreneurship.
        </p>
      </div>
      <div class="card border-blue-100 bg-gradient-to-br from-blue-50/80 to-white">
        <p class="text-label mb-4">At a Glance</p>
        <dl class="grid grid-cols-2 gap-4">
          <div>
            <dt class="text-caption">Institution</dt>
            <dd class="heading-card mt-1 text-sm">Takoradi Technical University</dd>
          </div>
          <div>
            <dt class="text-caption">Focus</dt>
            <dd class="heading-card mt-1 text-sm">Applied Computing &amp; IT</dd>
          </div>
          <div>
            <dt class="text-caption">Motto</dt>
            <dd class="heading-card mt-1 text-sm">Research &middot; Innovate &middot; Build</dd>
          </div>
          <div>
            <dt class="text-caption">Portal</dt>
            <dd class="heading-card mt-1 text-sm">Unified digital services hub</dd>
          </div>
        </dl>
      </div>
    </section>

    <section class="afu d2">
      <span class="eyebrow eyebrow-dark mb-4">Our Mission</span>
      <h2 class="heading-section">Empowering the next generation of technologists</h2>
      <p class="text-body mt-3 max-w-3xl">
        We provide a central digital gateway for all departmental platforms, student services,
        and academic resources — from final-year projects and document management to student union
        activities, innovation programmes, and national tech events.
      </p>

      <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <article class="card card-interactive h-full">
          <div class="card-icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </div>
          <h3 class="heading-card">Research</h3>
          <p class="text-body mt-2 text-sm">Applied research, final-year projects, and academic inquiry aligned with industry needs.</p>
        </article>
        <article class="card card-interactive h-full">
          <div class="card-icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          </div>
          <h3 class="heading-card">Innovate</h3>
          <p class="text-body mt-2 text-sm">Hackathons, tech communities, startups, and events such as QLIQ TECH that inspire new ideas.</p>
        </article>
        <article class="card card-interactive h-full">
          <div class="card-icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
          </div>
          <h3 class="heading-card">Build</h3>
          <p class="text-body mt-2 text-sm">Real-world software, web platforms, and tools used by students, staff, and external partners.</p>
        </article>
      </div>
    </section>

    <section class="afu d3">
      <span class="eyebrow eyebrow-dark mb-4">What We Offer</span>
      <h2 class="heading-section">Programmes &amp; areas of study</h2>
      <p class="text-body mt-3 max-w-3xl">
        Students gain competencies across core computing disciplines, supported by labs, projects,
        and access to departmental digital platforms.
      </p>
      <ul class="info-list mt-6">
        <li class="info-list-item">
          <span class="info-list-icon" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
          </span>
          <div>
            <p class="heading-card text-sm">Software Engineering &amp; Web Development</p>
            <p class="text-caption mt-0.5">Full-stack applications, APIs, and modern software practices.</p>
          </div>
        </li>
        <li class="info-list-item">
          <span class="info-list-icon" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
          </span>
          <div>
            <p class="heading-card text-sm">Computer Networks &amp; Systems</p>
            <p class="text-caption mt-0.5">Network design, administration, and infrastructure management.</p>
          </div>
        </li>
        <li class="info-list-item">
          <span class="info-list-icon" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          </span>
          <div>
            <p class="heading-card text-sm">Data Science &amp; Analytics</p>
            <p class="text-caption mt-0.5">Data processing, visualisation, and evidence-based decision making.</p>
          </div>
        </li>
        <li class="info-list-item">
          <span class="info-list-icon" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </span>
          <div>
            <p class="heading-card text-sm">Cybersecurity &amp; Information Assurance</p>
            <p class="text-caption mt-0.5">Protecting systems, data, and digital assets in modern environments.</p>
          </div>
        </li>
        <li class="info-list-item">
          <span class="info-list-icon" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
          </span>
          <div>
            <p class="heading-card text-sm">Artificial Intelligence &amp; Emerging Tech</p>
            <p class="text-caption mt-0.5">Machine learning foundations and exploration of new technologies.</p>
          </div>
        </li>
        <li class="info-list-item">
          <span class="info-list-icon" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </span>
          <div>
            <p class="heading-card text-sm">Student Union &amp; Community (iTSU)</p>
            <p class="text-caption mt-0.5">Leadership, events, and peer support through the IT Students Union.</p>
          </div>
        </li>
      </ul>
    </section>

    <section class="afu d4 card border-blue-100 bg-gradient-to-br from-blue-50 to-white">
      <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:items-center">
        <div>
          <span class="eyebrow eyebrow-dark mb-3">Digital Platforms</span>
          <h2 class="heading-section">One portal, every service</h2>
          <p class="text-body mt-3">
            This portal connects you to all departmental systems — including Documento for project submissions,
            iTSU for student union activities, CIP for innovation programmes, and QLIQ TECH for national tech events.
          </p>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row lg:justify-end">
          <a href="<?= url('/hub/csd') ?>" class="btn btn-primary w-full sm:w-auto">Browse All Platforms</a>
          <a href="<?= url('/') ?>" class="btn btn-ghost w-full sm:w-auto">Back to Home</a>
        </div>
      </div>
    </section>

    <section class="afu d5 card border-blue-100 bg-gradient-to-br from-blue-50 to-white py-8 text-center">
      <p class="font-display text-lg font-semibold tracking-[0.1em] text-brand uppercase md:text-xl">
        Research &bull; Innovate &bull; Build
      </p>
      <p class="text-body mx-auto mt-2 max-w-lg text-sm">The guiding principles behind everything we do at CSD-TTU.</p>
    </section>

    <section class="afu d5 grid grid-cols-1 gap-8 lg:grid-cols-2 lg:gap-12" id="contact">
      <div>
        <span class="eyebrow eyebrow-dark mb-4">Get in Touch</span>
        <h2 class="heading-section">Contact the department</h2>
        <p class="text-body mt-3">
          For academic enquiries, platform access, project submissions, or general departmental information,
          please reach out through the university or visit the department office on campus.
        </p>
      </div>
      <div class="card border-blue-100 bg-white">
        <dl class="space-y-5">
          <div>
            <dt class="text-label">Department</dt>
            <dd class="heading-card mt-1 text-lg">Computer Science</dd>
          </div>
          <div>
            <dt class="text-label">Institution</dt>
            <dd class="text-body mt-1">Takoradi Technical University</dd>
          </div>
          <div>
            <dt class="text-label">Location</dt>
            <dd class="text-body mt-1">Takoradi, Western Region, Ghana</dd>
          </div>
          <div>
            <dt class="text-label">Office Hours</dt>
            <dd class="text-body mt-1">Monday – Friday, 8:00 AM – 5:00 PM (GMT)</dd>
          </div>
        </dl>
        <a href="<?= url('/hub/csd') ?>" class="btn btn-primary mt-6 w-full sm:w-auto sm:min-w-[10rem]">Explore Platforms</a>
      </div>
    </section>
  </div>
</div>
<?php
}, ['noOverflow' => false, 'activeNav' => 'about', 'topbar' => true, 'footer' => true, 'bodyClass' => 'bg-white']);
?>
