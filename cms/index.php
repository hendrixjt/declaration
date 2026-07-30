<?php
require_once __DIR__ . '/../includes/cms.php';
require_once __DIR__ . '/../includes/planning-center.php';
cms_require_auth();
try {
    cms_sync_planning_center_events();
} catch (Throwable $exception) {
    // Keep the CMS available if Planning Center is temporarily unavailable.
}
$events = cms_get_events_for_admin();
$publishedCount = count(array_filter($events, static fn(array $event): bool => $event['status'] === 'published'));
$draftCount = count($events) - $publishedCount;
$notice = (string) ($_GET['notice'] ?? '');
$cmsPageTitle = 'Events';
$cmsCurrent = 'events';
include __DIR__ . '/_header.php';
?>
<header class="cms-page-header">
  <div>
    <p class="cms-eyebrow">Content</p>
    <h1>Events</h1>
    <p>Planning Center events appear automatically. Use the CMS only when the website needs something different.</p>
  </div>
  <a class="cms-button cms-button--primary" href="/cms/event.php">Add event</a>
</header>

<?php if ($notice === 'saved'): ?><div class="cms-alert cms-alert--success">Event saved.</div><?php endif; ?>
<?php if ($notice === 'deleted'): ?><div class="cms-alert cms-alert--success">Event deleted.</div><?php endif; ?>
<?php if ($notice === 'imported'): ?>
  <div class="cms-alert cms-alert--success">
    Planning Center import complete: <?= (int) ($_GET['added'] ?? 0) ?> added,
    <?= (int) ($_GET['updated'] ?? 0) ?> updated,
    <?= (int) ($_GET['skipped'] ?? 0) ?> skipped.
  </div>
<?php endif; ?>
<?php if ($notice === 'import-error'): ?><div class="cms-alert cms-alert--error"><?= cms_escape($_GET['message'] ?? 'The import could not be completed.') ?></div><?php endif; ?>

<section class="cms-stats" aria-label="Event summary">
  <div><span>All events</span><strong><?= count($events) ?></strong></div>
  <div><span>Published</span><strong><?= $publishedCount ?></strong></div>
  <div><span>Hidden / drafts</span><strong><?= $draftCount ?></strong></div>
</section>

<section class="cms-panel">
  <div class="cms-panel__header">
    <div>
      <h2>Event calendar</h2>
      <p>Planning Center is the source. New events are visible automatically, and website changes are preserved.</p>
    </div>
    <form method="post" action="/cms/import.php">
      <input type="hidden" name="csrf_token" value="<?= cms_escape(cms_csrf_token()) ?>">
      <button class="cms-button cms-button--secondary" type="submit">Import from Planning Center</button>
    </form>
  </div>

  <?php if (!$events): ?>
    <div class="cms-empty">
      <span>01</span>
      <h2>Bring in the current calendar.</h2>
      <p>Sync the existing Planning Center registrations. They will appear on the website automatically.</p>
    </div>
  <?php else: ?>
    <div class="cms-event-list">
      <?php foreach ($events as $event): ?>
        <a class="cms-event-row" href="/cms/event.php?id=<?= (int) $event['id'] ?>">
          <div class="cms-event-row__date">
            <span><?= cms_escape(date('M', strtotime($event['starts_at']))) ?></span>
            <strong><?= cms_escape(date('j', strtotime($event['starts_at']))) ?></strong>
          </div>
          <div class="cms-event-row__content">
            <div class="cms-event-row__title">
              <h3><?= cms_escape($event['title']) ?></h3>
              <span class="cms-status cms-status--<?= cms_escape($event['status']) ?>">
                <?= $event['status'] === 'published' ? 'Visible' : ($event['planning_center_id'] ? 'Hidden' : 'Draft') ?>
              </span>
            </div>
            <p>
              <?= cms_escape(date('M j, Y \a\t g:i A', strtotime($event['starts_at']))) ?>
              <?php if ($event['planning_center_id']): ?> · Planning Center<?php endif; ?>
              <?php if (!empty($event['local_override'])): ?> · Website override<?php endif; ?>
            </p>
          </div>
          <span class="cms-event-row__arrow" aria-hidden="true">›</span>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/_footer.php'; ?>
