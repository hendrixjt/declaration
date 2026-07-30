<?php
require_once __DIR__ . '/../includes/cms.php';
require_once __DIR__ . '/../includes/planning-center.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
$event = $slug !== '' ? cms_get_event_by_slug($slug) : null;
if (!$event) {
    http_response_code(404);
    include __DIR__ . '/../404/index.php';
    exit;
}

$page_title = $event['title'];
$body_class = 'event-detail-page declaration-interior';
$current_page = 'events';
$base_url = '/';
$meta_description = $event['summary'] ?: cms_plain_summary((string) $event['body']);
$canonical_url = 'https://www.declaration.org/events/' . rawurlencode($event['slug']) . '/';
$og_image = cms_safe_url($event['image_url'], '/assets/img/events/showcase-8.webp');
$registrationUrl = cms_safe_url($event['registration_url']);
$church_phone = '+1-281-661-4279';
$church_address = ['street' => '28601 Birnham Woods Drive', 'city' => 'Spring', 'state' => 'TX', 'zip' => '77386', 'country' => 'US'];

include __DIR__ . '/../includes/header.php';
?>
    <section class="event-detail-hero">
      <div class="event-detail-hero__media">
        <img src="<?= cms_escape(cms_safe_url($event['image_url'], '/assets/img/events/showcase-8.webp')) ?>" alt="<?= cms_escape($event['title']) ?>">
      </div>
      <div class="event-detail-hero__veil"></div>
      <div class="container-fluid declaration-shell event-detail-hero__content">
        <a class="event-detail-back" href="/events/">← All events</a>
        <p class="section-kicker section-kicker--light"><?= cms_escape(pc_date_range($event['starts_at'], $event['ends_at'] ?? '')) ?></p>
        <h1><?= cms_escape($event['title']) ?></h1>
      </div>
    </section>

    <section class="event-detail-content section-white">
      <div class="container-fluid declaration-shell event-detail-grid">
        <article>
          <?php if ($event['summary']): ?><p class="event-detail-lead"><?= cms_escape($event['summary']) ?></p><?php endif; ?>
          <?php if ($event['body']): ?><div class="event-detail-body"><?= nl2br(cms_escape(strip_tags($event['body']))) ?></div><?php endif; ?>
        </article>
        <aside class="event-detail-meta">
          <div>
            <span>When</span>
            <strong><?= cms_escape(pc_date_range($event['starts_at'], $event['ends_at'] ?? '')) ?></strong>
            <p><?= cms_escape(pc_format_time($event['starts_at'])) ?><?php if ($event['ends_at']): ?> – <?= cms_escape(pc_format_time($event['ends_at'])) ?><?php endif; ?></p>
          </div>
          <?php if ($event['location_name'] || $event['location_address']): ?>
            <div>
              <span>Where</span>
              <?php if ($event['location_name']): ?><strong><?= cms_escape($event['location_name']) ?></strong><?php endif; ?>
              <?php if ($event['location_address']): ?><p><?= cms_escape($event['location_address']) ?></p><?php endif; ?>
            </div>
          <?php endif; ?>
          <?php if ($registrationUrl): ?>
            <a class="button button--black event-detail-register" href="<?= cms_escape($registrationUrl) ?>" target="_blank" rel="noopener">
              <?= cms_escape($event['registration_label'] ?: 'Register') ?> <span aria-hidden="true">↗</span>
            </a>
          <?php endif; ?>
        </aside>
      </div>
    </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
