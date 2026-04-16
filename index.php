<?php
$page_title = 'Spring, TX Church';
$body_class = 'index-page';
$current_page = 'index';
$base_url = '/';
$meta_description = 'Declaration Church gathers in Spring, Texas on Sundays at 9:00am and 11:00am. Find service times, next steps, ministries, and upcoming events.';
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
$homepage_upcoming_events = pc_get_upcoming_events(3);
$upcoming_events = $homepage_upcoming_events;
$has_live_events = !empty($upcoming_events);
$homepage_events_column_class = count($upcoming_events) >= 3 ? 'col-lg-4' : 'col-lg-6';
$next_sunday_countdown = date('Y/m/d 09:00:00', strtotime('next sunday'));

$fallback_events = [
    [
        'name' => 'First Tuesday Prayer & Worship',
        'date_label' => 'Monthly rhythm',
        'description' => 'A night to seek the Lord together in prayer and worship at 7:00pm in various locations.',
        'image' => 'assets/img/events/gallery-4.webp',
        'url' => 'https://www.declaration.org/',
        'cta_label' => 'Learn More',
    ],
    [
        'name' => 'DNA',
        'date_label' => 'Next step',
        'description' => 'Learn the heart and vision of Declaration, discover purpose, and find your place to serve.',
        'image' => 'assets/img/events/showcase-5.webp',
        'url' => 'https://www.declaration.org/dna',
        'cta_label' => 'Explore DNA',
    ],
    [
        'name' => 'Groups',
        'date_label' => 'Year-round community',
        'description' => 'Find real community through groups for men, women, students, families, freedom, and more.',
        'image' => 'assets/img/events/gallery-8.webp',
        'url' => 'https://www.declaration.org/groups',
        'cta_label' => 'Find a Group',
    ],
];

$rhythm_tabs = [
    [
        'id' => 'sundays',
        'label' => 'Weekly',
        'title' => 'Sundays',
        'date' => '9:00am & 11:00am',
        'items' => [
            [
                'time' => '09:00 AM',
                'duration' => 'In person',
                'title' => 'Sunday gathering',
                'location' => 'Snyder Elementary',
                'description' => 'Join us for worship, biblical teaching, prayer, and a welcoming church family every Sunday morning.',
                'image' => 'assets/img/events/speaker-1.webp',
                'name' => 'Declaration Church',
                'role' => 'Spring, Texas',
            ],
            [
                'time' => '11:00 AM',
                'duration' => 'In person',
                'title' => 'Second Sunday gathering',
                'location' => 'Snyder Elementary',
                'description' => 'The 11:00am service carries the same teaching and worship with space to connect and pray.',
                'image' => 'assets/img/events/speaker-3.webp',
                'name' => 'Declaration Kids',
                'role' => 'Available during services',
            ],
            [
                'time' => '05:00 PM',
                'duration' => 'Online stream',
                'title' => 'Watch online',
                'location' => 'Online',
                'description' => 'If you cannot join in person, the Sunday message is available online later in the day.',
                'image' => 'assets/img/events/speaker-5.webp',
                'name' => 'Stay Connected',
                'role' => 'Worship from wherever you are',
            ],
        ],
    ],
    [
        'id' => 'generations',
        'label' => 'For Families',
        'title' => 'Kids + YTH',
        'date' => 'Birth through 12th grade',
        'items' => [
            [
                'time' => '09:00 AM',
                'duration' => 'Sunday ministry',
                'title' => 'Declaration Kids',
                'location' => 'Snyder Elementary',
                'description' => 'Kids from birth through 4th grade are invited into a safe, joyful environment to encounter and follow Jesus.',
                'image' => 'assets/img/events/speaker-7.webp',
                'name' => 'Birth through 4th grade',
                'role' => 'Biblical teaching and worship',
            ],
            [
                'time' => '11:00 AM',
                'duration' => 'Sunday ministry',
                'title' => 'Thrive support',
                'location' => 'Snyder Elementary',
                'description' => 'Families with special needs can find practical support and a caring ministry environment through Thrive.',
                'image' => 'assets/img/events/speaker-9.webp',
                'name' => 'Support for families',
                'role' => 'Accessible ministry care',
            ],
            [
                'time' => '05:00 PM',
                'duration' => 'Sunday evening',
                'title' => 'YTH',
                'location' => 'The Warehouse',
                'description' => 'Students in 7th through 12th grade have a place to belong, grow, and encounter and follow Jesus.',
                'image' => 'assets/img/events/speaker-11.webp',
                'name' => '7th through 12th grade',
                'role' => 'yth@declaration.org',
            ],
        ],
    ],
    [
        'id' => 'next-steps',
        'label' => 'Grow Here',
        'title' => 'Next Steps',
        'date' => 'Groups, DNA, Serve',
        'items' => [
            [
                'time' => 'Anytime',
                'duration' => 'Year-round',
                'title' => 'Groups',
                'location' => 'Across the church family',
                'description' => 'Groups help people find freedom, grow in faith, and build real community in every season of life.',
                'image' => 'assets/img/events/speaker-13.webp',
                'name' => 'Community',
                'role' => 'Men, women, families, students, freedom',
            ],
            [
                'time' => 'Quarterly',
                'duration' => 'Purpose pathway',
                'title' => 'DNA',
                'location' => 'Declaration Church',
                'description' => 'DNA introduces the vision, culture, and calling of Declaration and helps people find where they can serve.',
                'image' => 'assets/img/events/speaker-15.webp',
                'name' => 'Purpose',
                'role' => 'Discover the heart of Declaration',
            ],
            [
                'time' => 'Ongoing',
                'duration' => 'Serve teams',
                'title' => 'Serve',
                'location' => 'Sunday, events, missions, and more',
                'description' => 'Serve teams create space for people to encounter Jesus through hospitality, ministry, operations, generations, and outreach.',
                'image' => 'assets/img/events/speaker-2.webp',
                'name' => 'Make a difference',
                'role' => 'Use your gifts in community',
            ],
        ],
    ],
];

$pathway_cards = [
    ['icon' => 'bi-heart', 'title' => 'Plan Your Visit', 'description' => 'Start with service times, directions, and what to expect on a Sunday.'],
    ['icon' => 'bi-people', 'title' => 'Groups', 'description' => 'Find your people through year-round community and discipleship.'],
    ['icon' => 'bi-stars', 'title' => 'DNA', 'description' => 'Learn the culture, purpose, and next steps of Declaration.'],
    ['icon' => 'bi-hand-index-thumb', 'title' => 'Serve', 'description' => 'Jump into a team where your gifts can strengthen the church family.'],
    ['icon' => 'bi-balloon-heart', 'title' => 'Kids + YTH', 'description' => 'Help every generation encounter and follow Jesus.'],
    ['icon' => 'bi-globe2', 'title' => 'Missions', 'description' => 'Join local and global outreach partnerships that serve people in Jesus’ name.'],
];

include __DIR__ . '/includes/header.php';
?>

    <section id="hero" class="hero section dark-background">

      <div class="video-background">
        <video autoplay muted loop playsinline>
          <source src="assets/img/events/video-3.mp4" type="video/mp4">
        </video>
        <div class="overlay"></div>
      </div>

      <div class="content-wrapper">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-7" data-aos="fade-up" data-aos-delay="80">
              <div class="main-content">
                <span class="event-badge">For Jesus &amp; For People</span>
                <h1 class="main-title">Helping People Encounter and <span class="accent-serif">Follow Jesus</span> in Spring, Texas.</h1>
                <p class="main-description">Declaration is a welcoming church for people who are exploring faith, planting roots, raising families, and looking for a place to belong. Join us this Sunday and take your next step with confidence.</p>

                <div class="info-badges">
                  <div class="info-badge">
                    <div class="info-text">
                      <span class="label">Sundays</span>
                      <span class="value">9:00am &amp; 11:00am</span>
                    </div>
                  </div>
                  <div class="info-badge">
                    <div class="info-text">
                      <span class="label">Location</span>
                      <span class="value">Snyder Elementary, Spring, TX</span>
                    </div>
                  </div>
                </div>

                <div class="action-area">
                  <a href="#visit" class="btn-register">Plan Your Visit</a>
                  <a href="#upcoming-events" class="btn-agenda">See What&rsquo;s Coming Up</a>
                </div>
              </div>
            </div>

            <div class="col-lg-5" data-aos="fade-left" data-aos-delay="180">
              <div class="countdown-card">
                <h4 class="card-title">This Week at Declaration</h4>

                <div class="countdown" data-count="<?= htmlspecialchars($next_sunday_countdown) ?>">
                  <div class="time-block">
                    <h3 class="count-days">0</h3>
                    <span>Days</span>
                  </div>
                  <div class="time-block">
                    <h3 class="count-hours">0</h3>
                    <span>Hours</span>
                  </div>
                  <div class="time-block">
                    <h3 class="count-minutes">0</h3>
                    <span>Minutes</span>
                  </div>
                  <div class="time-block">
                    <h3 class="count-seconds">0</h3>
                    <span>Seconds</span>
                  </div>
                </div>

                <div class="ticket-info">
                  <div class="ticket-row">
                    <span class="ticket-label">In Person</span>
                    <span class="ticket-value">Sundays at 9:00am &amp; 11:00am</span>
                  </div>
                  <div class="ticket-row">
                    <span class="ticket-label">Prayer &amp; Worship</span>
                    <span class="ticket-value highlight">First Tuesday at 7:00pm</span>
                  </div>
                </div>

                <div class="featured-speakers">
                  <span class="speakers-label">Quick links for families and next steps</span>
                  <div class="hero-quick-links">
                    <a href="https://www.declaration.org/kids" class="hero-quick-link" target="_blank" rel="noopener">
                      <i class="bi bi-balloon-heart"></i>
                      <span>Kids</span>
                    </a>
                    <a href="https://www.declaration.org/youth" class="hero-quick-link" target="_blank" rel="noopener">
                      <i class="bi bi-stars"></i>
                      <span>YTH</span>
                    </a>
                    <a href="index.php#next-steps" class="hero-quick-link">
                      <span class="hero-link-mark" aria-hidden="true">56</span>
                      <span>56</span>
                    </a>
                    <a href="https://www.declaration.org/groups" class="hero-quick-link" target="_blank" rel="noopener">
                      <i class="bi bi-people"></i>
                      <span>Groups</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>

    <section id="visit" class="intro section">

      <div class="container">
        <div class="about-wrapper">
          <div class="image-showcase" data-aos="fade-right" data-aos-delay="60">
            <div class="main-image">
              <img src="assets/img/events/showcase-8.webp" alt="Declaration Church gathering" class="img-fluid">
              <div class="experience-badge">
                <span class="years">9 + 11</span>
                <span class="label">Sunday Service Times</span>
              </div>
            </div>
            <div class="floating-card">
              <div class="card-icon">
                <i class="bi bi-heart-pulse"></i>
              </div>
              <div class="card-content">
                <strong>Prayer &amp; His Presence</strong>
                <span>First Tuesday at 7:00pm</span>
              </div>
            </div>
          </div>

          <div class="content-block" data-aos="fade-up" data-aos-delay="140">
            <span class="section-tag">Who We Are</span>
            <h2>A church in Spring with a heart for Jesus, people, prayer, and <span class="accent-serif">community</span>.</h2>
            <p class="intro-text">Declaration exists to help people encounter and follow Jesus. The heart of the church is shaped by Scripture and the Holy Spirit, prayer and His presence, communion and community, and generosity and grace.</p>

            <div class="feature-list">
              <div class="feature-item">
                <div class="feature-icon">
                  <i class="bi bi-signpost-split"></i>
                </div>
                <div class="feature-content">
                  <h4>Plan Your Visit</h4>
                  <p>Join us at Snyder Elementary and know exactly where to go, what time to arrive, and what to expect when you walk in.</p>
                </div>
              </div>
              <div class="feature-item">
                <div class="feature-icon">
                  <i class="bi bi-people-fill"></i>
                </div>
                <div class="feature-content">
                  <h4>Find Community</h4>
                  <p>Groups are available year-round and help people build meaningful relationships while growing in faith.</p>
                </div>
              </div>
              <div class="feature-item">
                <div class="feature-icon">
                  <i class="bi bi-stars"></i>
                </div>
                <div class="feature-content">
                  <h4>Take Your Next Step</h4>
                  <p>DNA and serve teams help people discover purpose, understand the vision of Declaration, and get connected.</p>
                </div>
              </div>
            </div>

            <div class="action-area">
              <a href="#next-steps" class="btn-primary-action">Explore Next Steps</a>
              <div class="contact-info">
                <i class="bi bi-telephone"></i>
                <div class="info-text">
                  <span>Questions before Sunday?</span>
                  <strong>(281) 661-4279</strong>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="stats-banner" data-aos="fade-up" data-aos-delay="180">
          <div class="stat-item">
            <i class="bi bi-clock-history"></i>
            <div class="stat-content">
              <span class="stat-number">9:00</span>
              <span class="stat-text">Sunday Gathering</span>
            </div>
          </div>
          <div class="stat-item">
            <i class="bi bi-clock-history"></i>
            <div class="stat-content">
              <span class="stat-number">11:00</span>
              <span class="stat-text">Sunday Gathering</span>
            </div>
          </div>
          <div class="stat-item">
            <i class="bi bi-balloon-heart"></i>
            <div class="stat-content">
              <span class="stat-number">5:00</span>
              <span class="stat-text">YTH on Sundays</span>
            </div>
          </div>
          <div class="stat-item">
            <i class="bi bi-music-note-beamed"></i>
            <div class="stat-content">
              <span class="stat-number">7:00</span>
              <span class="stat-text">First Tuesday Prayer</span>
            </div>
          </div>
        </div>
      </div>

    </section>

    <section id="upcoming-events" class="featured-speakers section light-background section-band">

      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle"><?= $has_live_events ? 'Upcoming Events' : 'Featured Rhythms' ?></span>
        <h2><?= $has_live_events ? 'Coming Up at Declaration' : 'What to Know Right Now' ?></h2>
        <p><?= $has_live_events ? 'Here is a quick look at upcoming events from Planning Center. Visit the Events page for the full list and event details.' : 'While the live event feed is being connected, these are a few important rhythms and next steps to know.' ?></p>
      </div>

      <div class="container">
        <div class="row g-5 homepage-events-grid">
<?php if ($has_live_events): ?>
  <?php foreach ($upcoming_events as $event): ?>
          <div class="<?= htmlspecialchars($homepage_events_column_class) ?>" data-aos="fade-up" data-aos-delay="80">
            <div class="speaker-item event-preview-card">
              <div class="row g-0">
                <div class="col-md-5">
                  <div class="speaker-photo">
<?php if (!empty($event['logo_url'])): ?>
                    <img src="<?= htmlspecialchars($event['logo_url']) ?>" alt="<?= htmlspecialchars($event['name']) ?>" class="img-fluid" loading="lazy">
<?php else: ?>
                    <img src="assets/img/events/gallery-6.webp" alt="Declaration event" class="img-fluid" loading="lazy">
<?php endif; ?>
                  </div>
                </div>
                <div class="col-md-7">
                  <div class="speaker-info">
                    <div class="session-type"><?= htmlspecialchars(pc_date_range($event['starts_at'], $event['ends_at'])) ?></div>
                    <h4><?= htmlspecialchars($event['name']) ?></h4>
<?php if (!empty($event['starts_at'])): ?>
                    <div class="event-meta-row">
                      <span><i class="bi bi-clock"></i> <?= htmlspecialchars(pc_format_time($event['starts_at'])) ?></span>
                    </div>
<?php endif; ?>
<?php if (!empty($event['description'])): ?>
                    <p><?= htmlspecialchars(function_exists('mb_strimwidth') ? mb_strimwidth(strip_tags($event['description']), 0, 170, '...') : substr(strip_tags($event['description']), 0, 170) . '...') ?></p>
<?php else: ?>
                    <p>Join the Declaration family for this upcoming event and use the link below to see details or register.</p>
<?php endif; ?>
                    <a href="<?= htmlspecialchars(($event['public_url'] ?? '') ?: ($event['registration_url'] ?: 'https://www.declaration.org/')) ?>" class="profile-btn" target="_blank" rel="noopener">Learn More <i class="bi bi-arrow-right-short"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
  <?php endforeach; ?>
<?php else: ?>
  <?php foreach ($fallback_events as $event): ?>
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="80">
            <div class="speaker-item">
              <div class="row g-0">
                <div class="col-md-5">
                  <div class="speaker-photo">
                    <img src="<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['name']) ?>" class="img-fluid" loading="lazy">
                  </div>
                </div>
                <div class="col-md-7">
                  <div class="speaker-info">
                    <div class="session-type"><?= htmlspecialchars($event['date_label']) ?></div>
                    <h4><?= htmlspecialchars($event['name']) ?></h4>
                    <p><?= htmlspecialchars($event['description']) ?></p>
                    <a href="<?= htmlspecialchars($event['url']) ?>" class="profile-btn" target="_blank" rel="noopener"><?= htmlspecialchars($event['cta_label']) ?> <i class="bi bi-arrow-right-short"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
  <?php endforeach; ?>
<?php endif; ?>
        </div>
<?php if ($has_live_events): ?>
        <div class="events-preview-cta" data-aos="fade-up" data-aos-delay="120">
          <a href="events/" class="btn-primary-action">See All Events</a>
        </div>
<?php endif; ?>
      </div>

    </section>

    <section id="rhythms" class="schedule-2 section section-band">

      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle">Life at Declaration</span>
        <h2>Rhythms for the whole church family</h2>
        <p>From Sundays to student ministry to next steps, these are some of the most important rhythms that help people belong, grow, and serve.</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="80">
        <ul class="schedule-tabs nav nav-tabs" role="tablist">
<?php foreach ($rhythm_tabs as $index => $tab): ?>
          <li class="nav-item" role="presentation">
            <button class="tab-btn nav-link<?= $index === 0 ? ' active' : '' ?>" data-bs-toggle="tab" data-bs-target="#<?= htmlspecialchars($tab['id']) ?>" type="button" role="tab" aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">
              <span class="day-label"><?= htmlspecialchars($tab['label']) ?></span>
              <?= htmlspecialchars($tab['title']) ?>
              <span class="day-date"><?= htmlspecialchars($tab['date']) ?></span>
            </button>
          </li>
<?php endforeach; ?>
        </ul>

        <div class="tab-content">
<?php foreach ($rhythm_tabs as $index => $tab): ?>
          <div class="tab-pane fade<?= $index === 0 ? ' show active' : '' ?>" id="<?= htmlspecialchars($tab['id']) ?>" role="tabpanel">
<?php foreach ($tab['items'] as $item): ?>
            <div class="schedule-item">
              <div class="time-slot">
                <span class="time"><?= htmlspecialchars($item['time']) ?></span>
                <span class="duration"><?= htmlspecialchars($item['duration']) ?></span>
              </div>
              <div class="item-content">
                <h4><a href="#contact"><?= htmlspecialchars($item['title']) ?></a></h4>
                <div class="location"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($item['location']) ?></div>
                <p><?= htmlspecialchars($item['description']) ?></p>
              </div>
              <div class="speaker-info">
                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                <div class="speaker-details">
                  <span class="name"><?= htmlspecialchars($item['name']) ?></span>
                  <span class="role"><?= htmlspecialchars($item['role']) ?></span>
                </div>
              </div>
            </div>
<?php endforeach; ?>
          </div>
<?php endforeach; ?>
        </div>
      </div>

    </section>

    <section id="next-steps" class="about section section-band">

      <div class="container">
        <div class="intro-banner" data-aos="fade-up">
          <div class="banner-content">
            <span class="badge-label">Your Next Step Starts Here</span>
            <h3>More than a <span class="accent-serif">Sunday service</span></h3>
            <p>Declaration is building a church family where people can know God, grow in freedom, discover purpose, and make a difference. The current live site spreads that story across multiple pages, but the heart of it belongs right here on the homepage.</p>
            <div class="banner-stats">
              <div class="single-stat">
                <span class="stat-number">Jesus</span>
                <span class="stat-label">At the center</span>
              </div>
              <div class="single-stat">
                <span class="stat-number">People</span>
                <span class="stat-label">To belong with</span>
              </div>
              <div class="single-stat">
                <span class="stat-number">Purpose</span>
                <span class="stat-label">To walk in</span>
              </div>
            </div>
          </div>
        </div>

        <div class="features-row">
          <div class="row gy-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="60">
              <div class="feature-block">
                <div class="feature-icon">
                  <i class="bi bi-compass"></i>
                </div>
                <h4>Plan Your Visit</h4>
                <p>For first-time guests, we want the experience to feel clear, warm, and easy from the moment you arrive.</p>
              </div>
            </div>

            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="120">
              <div class="feature-block featured">
                <div class="feature-icon">
                  <i class="bi bi-book"></i>
                </div>
                <h4>What We Believe</h4>
                <p>Declaration is a multi-denominational church with a strong devotion to Scripture, the Holy Spirit, prayer, community, and grace.</p>
              </div>
            </div>

            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="180">
              <div class="feature-block">
                <div class="feature-icon">
                  <i class="bi bi-person-heart"></i>
                </div>
                <h4>Lead Pastors</h4>
                <p>John and Kelly Sherrill lead Declaration with a desire to see people encounter Jesus and grow in purpose.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="audience-wrapper">
          <div class="row align-items-center">
            <div class="col-lg-5" data-aos="fade-right" data-aos-delay="80">
              <div class="audience-intro">
                <h3>Find where you <span class="accent-serif">belong</span></h3>
                <p>Whether you are new to church, raising kids, looking for a group, or ready to step into serving, there is a clear pathway into the life of Declaration.</p>
                <blockquote>
                  <p>"For Jesus and for people" is more than a slogan. It is the kind of church experience this homepage should make visible right away.</p>
                  <cite>— Direction for the new site</cite>
                </blockquote>
                <div class="action-links">
                  <a href="https://www.declaration.org/groups" class="primary-btn" target="_blank" rel="noopener">Explore Groups</a>
                  <a href="https://www.declaration.org/teams" class="text-link" target="_blank" rel="noopener">See Serve Teams <i class="bi bi-arrow-right"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-7" data-aos="fade-left" data-aos-delay="140">
              <div class="audience-grid">
<?php foreach ($pathway_cards as $card): ?>
                <div class="audience-card">
                  <i class="bi <?= htmlspecialchars($card['icon']) ?>"></i>
                  <h5><?= htmlspecialchars($card['title']) ?></h5>
                  <p><?= htmlspecialchars($card['description']) ?></p>
                </div>
<?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>

    <section id="call-to-action" class="call-to-action section light-background section-band">

      <div class="container">
        <div class="row align-items-center g-5">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="80">
            <div class="content-block">
              <span class="badge-label">New Here?</span>
              <h2 class="section-heading">Start your first Sunday with <span class="accent-serif">confidence</span>.</h2>
              <p class="description">We want it to be easy for first-time guests and families to know where to go, what to expect, and how to take a simple next step after the service.</p>

              <div class="feature-list">
                <div class="feature-item">
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Kids ministry during 9:00am and 11:00am Sunday services</span>
                </div>
                <div class="feature-item">
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Groups, DNA, and serve pathways to help you get connected</span>
                </div>
                <div class="feature-item">
                  <i class="bi bi-check-circle-fill"></i>
                  <span>A church family that wants to know you, not just host you</span>
                </div>
              </div>

              <div class="action-area">
                <a href="https://www.declaration.org/new-here" class="btn-main" target="_blank" rel="noopener">Plan Your Visit</a>
                <a href="#contact" class="btn-secondary">Get the Details</a>
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="160">
            <div class="stats-grid">
              <div class="stat-card">
                <div class="stat-icon-wrapper">
                  <i class="bi bi-clock-fill"></i>
                </div>
                <div class="stat-info">
                  <span class="stat-value">9 &amp; 11</span>
                  <span class="stat-title">Sunday Services</span>
                </div>
              </div>

              <div class="stat-card">
                <div class="stat-icon-wrapper">
                  <i class="bi bi-balloon-heart-fill"></i>
                </div>
                <div class="stat-info">
                  <span class="stat-value">5 PM</span>
                  <span class="stat-title">YTH on Sundays</span>
                </div>
              </div>

              <div class="stat-card">
                <div class="stat-icon-wrapper">
                  <i class="bi bi-music-note"></i>
                </div>
                <div class="stat-info">
                  <span class="stat-value">7 PM</span>
                  <span class="stat-title">First Tuesday Prayer</span>
                </div>
              </div>

              <div class="stat-card">
                <div class="stat-icon-wrapper">
                  <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div class="stat-info">
                  <span class="stat-value">Spring</span>
                  <span class="stat-title">Snyder Elementary</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>

    <section id="contact" class="contact section light-background section-band">

      <div class="container">
        <div class="contact-main-wrapper">
          <div class="map-wrapper" data-aos="fade-right" data-aos-delay="60">
            <iframe src="https://www.google.com/maps?q=28601+Birnham+Woods+Drive,+Spring,+TX+77386&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>

          <div class="contact-content" data-aos="fade-left" data-aos-delay="140">
            <div class="contact-cards-container">
              <div class="contact-card">
                <div class="icon-box">
                  <i class="bi bi-geo-alt"></i>
                </div>
                <div class="contact-text">
                  <h4>Meet At</h4>
                  <p>Snyder Elementary<br>28601 Birnham Woods Drive<br>Spring, TX 77386</p>
                </div>
              </div>

              <div class="contact-card">
                <div class="icon-box">
                  <i class="bi bi-envelope"></i>
                </div>
                <div class="contact-text">
                  <h4>Email</h4>
                  <p>hello@declaration.org</p>
                </div>
              </div>

              <div class="contact-card">
                <div class="icon-box">
                  <i class="bi bi-telephone"></i>
                </div>
                <div class="contact-text">
                  <h4>Call</h4>
                  <p>(281) 661-4279</p>
                </div>
              </div>

              <div class="contact-card">
                <div class="icon-box">
                  <i class="bi bi-mailbox"></i>
                </div>
                <div class="contact-text">
                  <h4>Mail</h4>
                  <p>330 Rayford Road, Ste 369<br>Spring, TX 77386</p>
                </div>
              </div>
            </div>

            <div class="contact-form-container">
              <h3>Make your first Sunday simple</h3>
              <p>Declaration meets in Spring, Texas and has made the core first-visit details easy to find: service times, kids ministry, next steps, and practical contact info.</p>
              <p>As we continue rebuilding this site, the goal is to make the homepage carry more of the real ministry story instead of hiding key information deep in the sitemap.</p>
              <p><strong>Helpful starting points:</strong> visit on Sunday, explore groups, learn about DNA, or connect with a serve team when you are ready.</p>
            </div>
          </div>
        </div>
      </div>

    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
