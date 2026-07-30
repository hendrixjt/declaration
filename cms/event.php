<?php
require_once __DIR__ . '/../includes/cms.php';
cms_require_auth();

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$event = $id ? cms_get_event($id) : null;
if ($id && !$event) {
    http_response_code(404);
    exit('Event not found');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cms_verify_csrf();
    $action = (string) ($_POST['action'] ?? 'save');
    if ($action === 'delete' && $id) {
        cms_delete_event($id);
        header('Location: /cms/?notice=deleted');
        exit;
    }

    try {
        $payload = $_POST;
        $payload['starts_at'] = cms_datetime_storage_value((string) ($_POST['starts_at'] ?? ''));
        $payload['ends_at'] = cms_datetime_storage_value((string) ($_POST['ends_at'] ?? ''));
        $savedId = cms_save_event($payload, $id);
        header('Location: /cms/event.php?id=' . $savedId . '&notice=saved');
        exit;
    } catch (Throwable $exception) {
        $error = $exception->getMessage();
        $event = array_merge($event ?? [], $_POST);
    }
}

$event = $event ?? [
    'title' => '',
    'slug' => '',
    'summary' => '',
    'body' => '',
    'starts_at' => '',
    'ends_at' => '',
    'location_name' => '',
    'location_address' => '',
    'image_url' => '',
    'registration_url' => '',
    'registration_label' => 'Register',
    'status' => 'draft',
    'is_featured' => 0,
    'planning_center_id' => null,
];
$cmsPageTitle = $id ? 'Edit event' : 'Add event';
$cmsCurrent = 'events';
include __DIR__ . '/_header.php';
?>
<header class="cms-page-header cms-page-header--editor">
  <div>
    <a class="cms-back" href="/cms/">← All events</a>
    <p class="cms-eyebrow"><?= $id ? 'Edit event' : 'New event' ?></p>
    <h1><?= cms_escape($event['title'] ?: 'Untitled event') ?></h1>
  </div>
  <?php if ($id && $event['status'] === 'published'): ?>
    <a class="cms-button cms-button--secondary" href="/events/<?= rawurlencode($event['slug']) ?>/" target="_blank" rel="noopener">View event ↗</a>
  <?php endif; ?>
</header>

<?php if (($_GET['notice'] ?? '') === 'saved'): ?><div class="cms-alert cms-alert--success">Event saved.</div><?php endif; ?>
<?php if ($error): ?><div class="cms-alert cms-alert--error"><?= cms_escape($error) ?></div><?php endif; ?>

<form method="post" class="cms-editor">
  <input type="hidden" name="csrf_token" value="<?= cms_escape(cms_csrf_token()) ?>">
  <section class="cms-editor__main">
    <div class="cms-panel cms-fields">
      <div class="cms-section-title"><span>01</span><div><h2>Event details</h2><p>The information visitors need to understand the event.</p></div></div>
      <label>Event title
        <input type="text" name="title" required maxlength="255" value="<?= cms_escape($event['title']) ?>">
      </label>
      <label>URL slug
        <div class="cms-input-prefix"><span>/events/</span><input type="text" name="slug" value="<?= cms_escape($event['slug']) ?>" placeholder="created-from-title"></div>
      </label>
      <label>Short summary
        <textarea name="summary" rows="3" maxlength="500"><?= cms_escape($event['summary']) ?></textarea>
        <small>Used on event cards and search previews.</small>
      </label>
      <label>Full description
        <textarea name="body" rows="12"><?= cms_escape($event['body']) ?></textarea>
      </label>
    </div>

    <div class="cms-panel cms-fields">
      <div class="cms-section-title"><span>02</span><div><h2>When and where</h2><p>Dates use the church’s Central time zone.</p></div></div>
      <div class="cms-field-grid">
        <label>Starts
          <input type="datetime-local" name="starts_at" required value="<?= cms_escape(cms_datetime_local_value($event['starts_at'])) ?>">
        </label>
        <label>Ends
          <input type="datetime-local" name="ends_at" value="<?= cms_escape(cms_datetime_local_value($event['ends_at'])) ?>">
        </label>
      </div>
      <div class="cms-field-grid">
        <label>Location name
          <input type="text" name="location_name" value="<?= cms_escape($event['location_name']) ?>" placeholder="Snyder Elementary">
        </label>
        <label>Address
          <input type="text" name="location_address" value="<?= cms_escape($event['location_address']) ?>">
        </label>
      </div>
    </div>

    <div class="cms-panel cms-fields">
      <div class="cms-section-title"><span>03</span><div><h2>Image and registration</h2><p>For this prototype, use an existing image URL and optional external signup link.</p></div></div>
      <label>Event image URL
        <input type="url" name="image_url" value="<?= cms_escape($event['image_url']) ?>">
      </label>
      <?php if ($event['image_url']): ?>
        <img class="cms-image-preview" src="<?= cms_escape($event['image_url']) ?>" alt="">
      <?php endif; ?>
      <div class="cms-field-grid">
        <label>Registration URL
          <input type="url" name="registration_url" value="<?= cms_escape($event['registration_url']) ?>">
        </label>
        <label>Button label
          <input type="text" name="registration_label" value="<?= cms_escape($event['registration_label']) ?>">
        </label>
      </div>
    </div>
  </section>

  <aside class="cms-editor__sidebar">
    <div class="cms-panel cms-publish-box">
      <p class="cms-eyebrow">Publish</p>
      <label>Status
        <select name="status">
          <option value="draft"<?= $event['status'] === 'draft' ? ' selected' : '' ?>>Draft</option>
          <option value="published"<?= $event['status'] === 'published' ? ' selected' : '' ?>>Published</option>
        </select>
      </label>
      <label class="cms-check">
        <input type="checkbox" name="is_featured" value="1"<?= !empty($event['is_featured']) ? ' checked' : '' ?>>
        <span>Feature this event</span>
      </label>
      <button class="cms-button cms-button--primary cms-button--block" type="submit" name="action" value="save">Save event</button>
      <?php if ($event['planning_center_id']): ?><small>Imported from Planning Center. Your edits are preserved on future imports.</small><?php endif; ?>
    </div>
    <?php if ($id): ?>
      <div class="cms-danger">
        <button type="submit" name="action" value="delete" onclick="return confirm('Delete this event? This cannot be undone.')">Delete event</button>
      </div>
    <?php endif; ?>
  </aside>
</form>
<?php include __DIR__ . '/_footer.php'; ?>
