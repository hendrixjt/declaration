<?php
$page_title = 'Events';
$body_class = 'events-page declaration-interior';
$current_page = 'events';
$base_url = '/';
$meta_description = 'See upcoming events at Declaration Church in Spring, Texas, including prayer nights, next steps, family events, and churchwide gatherings.';
$canonical_url = 'https://www.declaration.org/events/';
$geo_region = 'US-TX';
$geo_placename = 'Spring, Texas';
$church_phone = '+1-281-661-4279';
$church_address = ['street' => '28601 Birnham Woods Drive', 'city' => 'Spring', 'state' => 'TX', 'zip' => '77386', 'country' => 'US'];

require_once __DIR__ . '/../includes/planning-center.php';
$cms_is_calendar_source = false;
try {
    require_once __DIR__ . '/../includes/cms.php';
    $upcoming_events = cms_get_website_events(12);
    $cms_is_calendar_source = true;
} catch (Throwable $exception) {
    $cms_is_calendar_source = false;
    $upcoming_events = [];
}
$enable_background_event_sync = true;
$has_live_events = !empty($upcoming_events);

$fallback_events = [
    ['name' => 'Serve Team Conference', 'date_label' => 'Details coming soon', 'description' => 'A day to refresh, encourage, and connect the people who faithfully serve at Declaration.', 'image' => 'assets/img/events/gallery-1.webp', 'url' => 'serve-team-conference/', 'cta_label' => 'Explore the conference'],
    ['name' => 'First Tuesday Prayer & Worship', 'date_label' => 'Monthly at 7:00pm', 'description' => 'A night to seek the Lord together through prayer, worship, and His presence.', 'image' => 'assets/img/events/gallery-4.webp', 'url' => 'https://www.declaration.org/', 'cta_label' => 'Learn More'],
    ['name' => 'DNA', 'date_label' => 'Your next step', 'description' => 'Learn the heart and vision of Declaration, discover purpose, and find your place.', 'image' => 'assets/img/events/showcase-5.webp', 'url' => 'dna/', 'cta_label' => 'Explore DNA'],
    ['name' => 'Groups', 'date_label' => 'Community all year', 'description' => 'Find freedom, grow in faith, and build meaningful relationships in every season.', 'image' => 'assets/img/events/gallery-8.webp', 'url' => 'https://www.declaration.org/groups', 'cta_label' => 'Find a Group'],
];

$event_count = $has_live_events ? count($upcoming_events) : count($fallback_events);
$next_event = $has_live_events ? $upcoming_events[0] : null;

include __DIR__ . '/../includes/header.php';
?>

    <section class="interior-hero interior-hero--events">
      <div class="interior-hero__media">
        <img src="assets/img/events/showcase-8.webp" alt="Church family gathered for worship">
      </div>
      <div class="interior-hero__veil"></div>
      <div class="container-fluid declaration-shell interior-hero__content">
        <p class="section-kicker section-kicker--light">Life together</p>
        <h1>Gather.<br>Grow. Go.</h1>
        <p>Churchwide gatherings, prayer nights, next steps, and meaningful ways to belong.</p>
      </div>
    </section>

    <section class="events-intro interior-section section-white">
      <div class="container-fluid declaration-shell">
        <div class="interior-intro-grid">
          <p class="section-kicker">Coming up</p>
          <div>
            <h2>Make room for what God is doing.</h2>
            <div class="events-intro__meta">
              <div><span>Upcoming</span><strong><?= htmlspecialchars((string) $event_count) ?></strong><p><?= $has_live_events ? ($cms_is_calendar_source ? 'Declaration calendar' : 'Live registrations') : 'Featured rhythms' ?></p></div>
              <div><span>Next up</span><strong><?= htmlspecialchars($has_live_events && $next_event ? pc_format_date($next_event['starts_at'] ?? '') : 'Sunday') ?></strong><p><?= htmlspecialchars($has_live_events && $next_event ? ($next_event['name'] ?? '') : '9:00 + 11:00am') ?></p></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="events-archive events-listing section-black">
      <div class="container-fluid declaration-shell">
        <div class="events-archive__heading">
          <p class="section-kicker section-kicker--light"><?= $has_live_events ? 'Live calendar' : 'Church rhythms' ?></p>
          <h2>What’s happening.</h2>
        </div>
        <div class="events-archive__grid">
<?php if ($has_live_events): ?>
  <?php foreach ($upcoming_events as $index => $event): ?>
          <article class="archive-event<?= $index >= 6 ? ' event-item-hidden' : '' ?>"<?= $index >= 6 ? ' data-event-hidden="true"' : '' ?>>
            <a href="<?= htmlspecialchars(($event['public_url'] ?? '') ?: (($event['registration_url'] ?? '') ?: 'events/')) ?>"<?= $cms_is_calendar_source ? '' : ' target="_blank" rel="noopener"' ?>>
              <div class="archive-event__image">
                <img src="<?= htmlspecialchars(($event['logo_url'] ?? '') ?: 'assets/img/events/gallery-6.webp') ?>" alt="<?= htmlspecialchars($event['name'] ?? 'Declaration event') ?>" loading="lazy">
              </div>
              <div class="archive-event__meta">
                <p><?= htmlspecialchars(pc_date_range($event['starts_at'] ?? '', $event['ends_at'] ?? '')) ?></p>
                <h3><?= htmlspecialchars($event['name'] ?? '') ?></h3>
                <span>Event details <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span>
              </div>
            </a>
          </article>
  <?php endforeach; ?>
<?php else: ?>
  <?php foreach ($fallback_events as $index => $event): ?>
          <article class="archive-event">
            <a href="<?= htmlspecialchars($event['url']) ?>" target="_blank" rel="noopener">
              <div class="archive-event__image"><img src="<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['name']) ?>" loading="lazy"></div>
              <div class="archive-event__meta">
                <p><?= htmlspecialchars($event['date_label']) ?></p>
                <h3><?= htmlspecialchars($event['name']) ?></h3>
                <span><?= htmlspecialchars($event['cta_label']) ?> <i class="bi bi-arrow-up-right" aria-hidden="true"></i></span>
              </div>
            </a>
          </article>
  <?php endforeach; ?>
<?php endif; ?>
        </div>
<?php if ($event_count > 6): ?>
        <div class="events-load-more">
          <button type="button" class="button button--white" data-load-more-events data-load-step="6">Show more events</button>
        </div>
<?php endif; ?>
      </div>
    </section>

    <section class="events-weekly section-white">
      <div class="container-fluid declaration-shell">
        <p class="section-kicker">Every week</p>
        <div class="events-weekly__grid">
          <h2>Sundays are the heartbeat.</h2>
          <div>
            <p>Worship, biblical teaching, prayer, kids ministry, and a church family ready to welcome you.</p>
            <strong>9:00 + 11:00am</strong>
            <span>Snyder Elementary, Spring, Texas</span>
            <a class="arrow-link" href="index.php#visit">Plan your visit <span aria-hidden="true">&#8594;</span></a>
          </div>
        </div>
      </div>
    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
