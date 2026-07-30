<?php
require_once __DIR__ . '/../includes/cms.php';
cms_require_auth();
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
    <p>Build the calendar visitors see on the Declaration website.</p>
  </div>
  <a class="cms-button cms-button--primary" href="/cms/event.php">Add event</a>
</header>

<?php if ($notice === 'saved'): ?><div class="cms-alert cms-alert--success">Event saved.</div><?php endif; ?>
<?php if ($notice === 'deleted'): ?><div class="cms-alert cms-alert--success">Event deleted.</div><?php endif; ?>
<?php if ($notice === 'imported'): ?>
  <div class="cms-alert cms-alert--success">
    Planning Center import complete: <?= (int) ($_GET['added'] ?? 0) ?> added,
    <?= (int) ($_GET['updated'] ?? 0) ?> updated,
    <?= (int) ($_GET['skipped'] ?? 0) ?> preserved.
  </div>
<?php endif; ?>
<?php if ($notice === 'import-error'): ?><div class="cms-alert cms-alert--error"><?= cms_escape($_GET['message'] ?? 'The import could not be completed.') ?></div><?php endif; ?>

<section class="cms-stats" aria-label="Event summary">
  <div><span>All events</span><strong><?= count($events) ?></strong></div>
  <div><span>Published</span><strong><?= $publishedCount ?></strong></div>
  <div><span>Drafts</span><strong><?= $draftCount ?></strong></div>
</section>

<section class="cms-panel">
  <div class="cms-panel__header">
    <div>
      <h2>Event calendar</h2>
      <p>Imported events begin as drafts. Publish them when they are ready for the website.</p>
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
      <p>Import the existing Planning Center registrations, then edit and publish the events you want to show.</p>
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
              <span class="cms-status cms-status--<?= cms_escape($event['status']) ?>"><?= cms_escape(ucfirst($event['status'])) ?></span>
            </div>
            <p>
              <?= cms_escape(date('M j, Y \a\t g:i A', strtotime($event['starts_at']))) ?>
              <?php if ($event['planning_center_id']): ?> · Imported<?php endif; ?>
            </p>
          </div>
          <span class="cms-event-row__arrow" aria-hidden="true">›</span>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/_footer.php'; ?>
