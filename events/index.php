<?php
$page_title = 'Events';
$body_class = 'events-page';
$current_page = 'events';
$base_url = '/';
$meta_description = 'See upcoming events at Declaration Church in Spring, Texas, including prayer nights, next steps, family events, and churchwide gatherings.';
$canonical_url = 'https://www.declaration.org/events/';
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

require_once __DIR__ . '/../includes/planning-center.php';

$upcoming_events = pc_get_upcoming_events(12);
$has_live_events = !empty($upcoming_events);

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

$event_count = $has_live_events ? count($upcoming_events) : count($fallback_events);
$next_event = $has_live_events ? $upcoming_events[0] : null;

$format_event_time = static function (string $iso_date): string {
    if ($iso_date === '') {
        return '';
    }

    try {
        $dt = new DateTime($iso_date);
        $dt->setTimezone(new DateTimeZone('America/Chicago'));
        return $dt->format('g:i A');
    } catch (Exception $e) {
        return '';
    }
};

include __DIR__ . '/../includes/header.php';
?>

    <div class="page-title dark-background declaration-page-title" style="background-image: url(assets/img/events/showcase-8.webp);">
      <div class="container position-relative" data-aos="fade-up" data-aos-delay="70">
        <h1>Upcoming Events</h1>
        <p>Churchwide gatherings, next-step moments, family rhythms, and special events happening across Declaration.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Events</li>
          </ol>
        </nav>
      </div>
    </div>

    <section class="events-overview section">
      <div class="container">
        <div class="events-highlight" data-aos="fade-up" data-aos-delay="80">
          <div class="events-highlight-copy">
            <span class="section-tag">Stay in the Rhythm</span>
            <h2>What is happening at <span class="accent-serif">Declaration</span> right now.</h2>
            <p>We are using Planning Center Registrations as the source of truth for live upcoming events, so this page can become the main place people check for churchwide opportunities, prayer nights, family events, and next steps.</p>
          </div>
          <div class="events-highlight-meta">
            <div class="events-meta-block">
              <span class="meta-label">Upcoming</span>
              <strong class="meta-value"><?= htmlspecialchars((string) $event_count) ?></strong>
              <span class="meta-note"><?= $has_live_events ? 'Live events loaded' : 'Featured rhythms for now' ?></span>
            </div>
            <div class="events-meta-block">
              <span class="meta-label">Next up</span>
              <strong class="meta-value"><?= htmlspecialchars($has_live_events && $next_event ? pc_format_date($next_event['starts_at'] ?? '') : 'Every Sunday') ?></strong>
              <span class="meta-note"><?= htmlspecialchars($has_live_events && $next_event ? ($next_event['name'] ?? '') : 'Gatherings at 9:00am & 11:00am') ?></span>
            </div>
            <div class="events-highlight-actions">
              <a href="index.php#visit" class="btn-primary-action">Plan Your Visit</a>
              <a href="index.php#upcoming-events" class="btn-agenda">Back to Homepage Preview</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="featured-speakers section light-background events-listing">

      <div class="container section-title" data-aos="fade-up">
        <span class="subtitle"><?= $has_live_events ? 'Live Feed' : 'Featured Rhythms' ?></span>
        <h2><?= $has_live_events ? 'All Upcoming Events' : 'Featured Church Rhythms' ?></h2>
        <p><?= $has_live_events ? 'These cards are being pulled directly from Planning Center Registrations so the site can stay current without manual event edits.' : 'Until the live feed is fully available on the hosted site, these featured rhythms can stand in as the public events layer.' ?></p>
      </div>

      <div class="container">
        <div class="row g-5">
<?php if ($has_live_events): ?>
  <?php foreach ($upcoming_events as $index => $event): ?>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?= htmlspecialchars((string) (80 + (($index % 3) * 40))) ?>">
            <div class="speaker-item event-card">
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
                    <div class="session-type"><?= htmlspecialchars(pc_date_range($event['starts_at'] ?? '', $event['ends_at'] ?? '')) ?></div>
                    <h4><?= htmlspecialchars($event['name'] ?? '') ?></h4>
<?php if (!empty($event['starts_at'])): ?>
                    <div class="event-meta-row">
                      <span><i class="bi bi-clock"></i> <?= htmlspecialchars($format_event_time((string) ($event['starts_at'] ?? ''))) ?></span>
                    </div>
<?php endif; ?>
<?php if (!empty($event['description'])): ?>
                    <p><?= htmlspecialchars(function_exists('mb_strimwidth') ? mb_strimwidth(strip_tags((string) $event['description']), 0, 220, '...') : substr(strip_tags((string) $event['description']), 0, 220) . '...') ?></p>
<?php else: ?>
                    <p>See event details, timing, and registration information for this upcoming Declaration gathering.</p>
<?php endif; ?>
                    <a href="<?= htmlspecialchars(($event['public_url'] ?? '') ?: (($event['registration_url'] ?? '') ?: 'events/')) ?>" class="profile-btn" target="_blank" rel="noopener">View Event <i class="bi bi-arrow-right-short"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
  <?php endforeach; ?>
<?php else: ?>
  <?php foreach ($fallback_events as $index => $event): ?>
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="<?= htmlspecialchars((string) (80 + ($index * 40))) ?>">
            <div class="speaker-item event-card">
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
      </div>

    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
