<?php
$page_title = 'Declaration Church';
$body_class = 'index-page declaration-home';
$current_page = 'index';
$base_url = '/';
$meta_description = 'Declaration Church gathers in Spring, Texas on Sundays at 9:00am and 11:00am. Come encounter Jesus, find community, and take your next step.';
$canonical_url = 'https://www.declaration.org/';
$geo_region = 'US-TX';
$geo_placename = 'Spring, Texas';
$church_phone = '+1-281-661-4279';
$church_address = [
    'street' => '28601 Birnham Woods Drive',
    'city' => 'Spring',
    'state' => 'TX',
    'zip' => '77386',
    'country' => 'US',
];

require_once __DIR__ . '/includes/planning-center.php';
$cms_is_calendar_source = false;
try {
    require_once __DIR__ . '/includes/cms.php';
    $upcoming_events = cms_get_website_events(3);
    $cms_is_calendar_source = true;
} catch (Throwable $exception) {
    $cms_is_calendar_source = false;
    $upcoming_events = [];
}
$enable_background_event_sync = true;
$has_live_events = !empty($upcoming_events);

$fallback_events = [
    [
        'name' => 'First Tuesday Prayer & Worship',
        'date_label' => 'Monthly at 7:00pm',
        'description' => 'A night to seek the Lord together through prayer, worship, and His presence.',
        'image' => 'assets/img/events/gallery-4.webp',
        'url' => 'https://www.declaration.org/',
        'cta_label' => 'Learn More',
    ],
    [
        'name' => 'DNA',
        'date_label' => 'Your next step',
        'description' => 'Learn the heart and vision of Declaration, discover purpose, and find your place.',
        'image' => 'assets/img/events/showcase-5.webp',
        'url' => 'dna/',
        'cta_label' => 'Explore DNA',
    ],
    [
        'name' => 'Groups',
        'date_label' => 'Community all year',
        'description' => 'Find freedom, grow in faith, and build meaningful relationships in every season.',
        'image' => 'assets/img/events/gallery-8.webp',
        'url' => 'https://www.declaration.org/groups',
        'cta_label' => 'Find a Group',
    ],
];

$next_steps = [
    ['number' => '01', 'title' => 'Plan a Visit', 'copy' => 'Service times, directions, kids check-in, and what to expect.', 'url' => 'https://www.declaration.org/new-here', 'image' => 'assets/img/events/gallery-2.webp'],
    ['number' => '02', 'title' => 'Find Your People', 'copy' => 'Groups for real community, freedom, and growth in every season.', 'url' => 'https://www.declaration.org/groups', 'image' => 'assets/img/events/gallery-7.webp'],
    ['number' => '03', 'title' => 'Discover DNA', 'copy' => 'Know our heart, understand our culture, and discover your purpose.', 'url' => 'dna/', 'image' => 'assets/img/events/gallery-5.webp'],
    ['number' => '04', 'title' => 'Make a Difference', 'copy' => 'Use your gifts through serve teams, missions, and life together.', 'url' => 'https://www.declaration.org/teams', 'image' => 'assets/img/events/gallery-9.webp'],
];

include __DIR__ . '/includes/header.php';
?>

    <section id="hero" class="declaration-hero">
      <div class="declaration-hero__media" aria-hidden="true">
        <video autoplay muted loop playsinline preload="none" poster="assets/img/events/showcase-8.webp" data-deferred-video>
          <source data-src="assets/img/events/video-3.mp4" type="video/mp4">
        </video>
      </div>
      <div class="declaration-hero__veil"></div>
      <div class="declaration-hero__content container-fluid">
        <p class="eyebrow">Declaration Church <span>Spring, Texas</span></p>
        <h1>For Jesus.<br>For People.</h1>
        <div class="declaration-hero__footer">
          <p>Helping people encounter and follow Jesus.</p>
          <a class="arrow-link arrow-link--light" href="#visit">Plan your visit <i class="bi bi-arrow-up-right" aria-hidden="true"></i></a>
        </div>
      </div>
      <div class="declaration-hero__service">
        <span>Sundays</span>
        <strong>9:00 + 11:00 AM</strong>
        <span>Snyder Elementary</span>
      </div>
    </section>

    <section class="declaration-statement section-white">
      <div class="container-fluid declaration-shell">
        <p class="section-kicker" data-aos="fade-up">Welcome home</p>
        <div class="declaration-statement__grid">
          <h2 data-aos="fade-up" data-aos-delay="80">A church for the curious, the committed, and everyone in between.</h2>
          <div class="declaration-statement__copy" data-aos="fade-up" data-aos-delay="160">
            <p>Declaration is a multi-denominational church family in Spring, Texas. We are devoted to Scripture and the Holy Spirit, prayer and His presence, communion and community, generosity and grace.</p>
            <a class="arrow-link" href="https://www.declaration.org/what-we-believe" target="_blank" rel="noopener">Meet Declaration <span aria-hidden="true">&#8594;</span></a>
          </div>
        </div>
      </div>
    </section>

    <section id="visit" class="visit-editorial section-black">
      <div class="visit-editorial__image" data-image-reveal>
        <img src="/uploads/media/20260814-90faa7525f6158be-1917w.webp" alt="A church family gathered in worship" loading="lazy">
        <span class="image-caption">Come as you are. There is room for you.</span>
      </div>
      <div class="visit-editorial__content" data-aos="fade-up">
        <p class="section-kicker section-kicker--light">This Sunday</p>
        <h2>Your first visit should feel simple.</h2>
        <p>We meet at Snyder Elementary. Friendly faces will help you find your way, and Declaration Kids is available during both Sunday services.</p>
        <dl class="visit-details">
          <div><dt>When</dt><dd>Sundays at 9:00 + 11:00am</dd></div>
          <div><dt>Where</dt><dd>28601 Birnham Woods Drive<br>Spring, TX 77386</dd></div>
          <div><dt>For families</dt><dd>Kids ministry from birth through 4th grade</dd></div>
        </dl>
        <a class="button button--white" href="https://www.declaration.org/new-here" target="_blank" rel="noopener">Plan your visit</a>
      </div>
    </section>

    <section id="next-steps" class="next-steps-editorial section-white">
      <div class="container-fluid declaration-shell">
        <div class="editorial-heading" data-aos="fade-up">
          <div>
            <p class="section-kicker">There is more</p>
            <h2>Take your<br>next step.</h2>
          </div>
          <p>Wherever you are in your faith journey, there is a meaningful way to belong, grow, and make a difference.</p>
        </div>
        <div class="pathway-grid">
<?php foreach ($next_steps as $index => $step): ?>
          <a class="pathway-card" href="<?= htmlspecialchars($step['url']) ?>" target="_blank" rel="noopener" data-aos="fade-up" data-aos-delay="<?= ($index % 2) * 90 ?>">
            <img src="<?= htmlspecialchars($step['image']) ?>" alt="" loading="lazy">
            <span class="pathway-card__veil"></span>
            <span class="pathway-card__number"><?= htmlspecialchars($step['number']) ?></span>
            <span class="pathway-card__content">
              <strong><?= htmlspecialchars($step['title']) ?></strong>
              <span><?= htmlspecialchars($step['copy']) ?></span>
            </span>
            <span class="pathway-card__arrow" aria-hidden="true"><i class="bi bi-arrow-up-right"></i></span>
          </a>
<?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="declaration-values section-black">
      <div class="container-fluid declaration-shell">
        <p class="section-kicker section-kicker--light" data-aos="fade-up">The kind of church we are becoming</p>
        <div class="values-list">
          <div data-aos="fade-up"><span>01</span><h3>Authenticity</h3><p>over appearance</p></div>
          <div data-aos="fade-up"><span>02</span><h3>Intimacy</h3><p>over intellect</p></div>
          <div data-aos="fade-up"><span>03</span><h3>Passion</h3><p>over performance</p></div>
          <div data-aos="fade-up"><span>04</span><h3>Kingdom</h3><p>over consumerism</p></div>
          <div data-aos="fade-up"><span>05</span><h3>Service</h3><p>over selfishness</p></div>
        </div>
        <a class="arrow-link arrow-link--light" href="https://www.declaration.org/what-we-believe" target="_blank" rel="noopener">What we believe <span aria-hidden="true">&#8594;</span></a>
      </div>
    </section>

    <section id="upcoming-events" class="events-editorial section-white">
      <div class="container-fluid declaration-shell">
        <div class="editorial-heading editorial-heading--compact" data-aos="fade-up">
          <div>
            <p class="section-kicker"><?= $has_live_events ? 'Coming up' : 'Our rhythms' ?></p>
            <h2>Life together.</h2>
          </div>
          <a class="arrow-link" href="events/">View all events <span aria-hidden="true">&#8594;</span></a>
        </div>
        <div class="event-editorial-grid">
<?php if ($has_live_events): ?>
  <?php foreach ($upcoming_events as $index => $event): ?>
          <article class="event-editorial-card" data-aos="fade-up" data-aos-delay="<?= $index * 80 ?>">
            <a href="<?= htmlspecialchars(($event['public_url'] ?? '') ?: ($event['registration_url'] ?: 'https://www.declaration.org/')) ?>"<?= $cms_is_calendar_source ? '' : ' target="_blank" rel="noopener"' ?>>
              <div class="event-editorial-card__image">
                <img src="<?= htmlspecialchars($event['logo_url'] ?: 'assets/img/events/gallery-' . (4 + $index) . '.webp') ?>" alt="<?= htmlspecialchars($event['name']) ?>" loading="lazy">
              </div>
              <p><?= htmlspecialchars(pc_date_range($event['starts_at'], $event['ends_at'])) ?></p>
              <h3><?= htmlspecialchars($event['name']) ?></h3>
              <span>Details <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span>
            </a>
          </article>
  <?php endforeach; ?>
<?php else: ?>
  <?php foreach ($fallback_events as $index => $event): ?>
          <article class="event-editorial-card" data-aos="fade-up" data-aos-delay="<?= $index * 80 ?>">
            <a href="<?= htmlspecialchars($event['url']) ?>" target="_blank" rel="noopener">
              <div class="event-editorial-card__image">
                <img src="<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['name']) ?>" loading="lazy">
              </div>
              <p><?= htmlspecialchars($event['date_label']) ?></p>
              <h3><?= htmlspecialchars($event['name']) ?></h3>
              <span><?= htmlspecialchars($event['cta_label']) ?> <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span>
            </a>
          </article>
  <?php endforeach; ?>
<?php endif; ?>
        </div>
      </div>
    </section>

    <section class="communion-feature">
      <div class="communion-feature__image" data-image-reveal>
        <img src="assets/img/declaration/holiding bread and cup_edited.jpg" alt="Bread and cup held during communion" loading="lazy">
      </div>
      <div class="communion-feature__copy" data-aos="fade-up">
        <p class="section-kicker">Our devotion</p>
        <blockquote>“For Jesus and for people” is not just what we say. It is how we choose to live.</blockquote>
        <p>We believe spiritual family is built through remembrance and redemption, prayer and presence, and lives offered generously to one another.</p>
      </div>
    </section>

    <section id="contact" class="closing-invitation section-black">
      <div class="container-fluid declaration-shell">
        <p class="section-kicker section-kicker--light" data-aos="fade-up">See you Sunday</p>
        <h2 data-aos="fade-up" data-aos-delay="80">There is a<br>place for you.</h2>
        <div class="closing-invitation__details" data-aos="fade-up" data-aos-delay="160">
          <div><span>Gathering</span><strong>9:00 + 11:00am</strong></div>
          <div><span>Location</span><strong>Snyder Elementary<br>Spring, Texas</strong></div>
          <div><span>Questions</span><strong><a href="mailto:hello@declaration.org">hello@declaration.org</a><br><a href="tel:+12816614279">(281) 661-4279</a></strong></div>
        </div>
        <a class="button button--white" href="https://www.declaration.org/new-here" target="_blank" rel="noopener">Plan your visit</a>
      </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
