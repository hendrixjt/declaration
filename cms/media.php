<?php
require_once __DIR__ . '/../includes/cms.php';
require_once __DIR__ . '/../includes/google-drive.php';
cms_require_auth();

$source = ($_GET['source'] ?? '') === 'drive' ? 'drive' : 'library';
$query = trim((string) ($_GET['q'] ?? ''));
$orientation = (string) ($_GET['orientation'] ?? '');
$tagSlug = (string) ($_GET['tag'] ?? '');
$status = (string) ($_GET['status'] ?? 'active');
$usageFilter = in_array(($_GET['usage'] ?? ''), ['used', 'unused'], true) ? (string) $_GET['usage'] : '';
$notice = (string) ($_GET['notice'] ?? '');
$counts = cms_media_counts();
$tags = cms_media_all_tags();
$allMediaAssets = cms_media_search(['status' => 'all']);
$usageMap = cms_media_usage_map($allMediaAssets);
$counts['used'] = count(array_filter($allMediaAssets, static fn(array $asset): bool =>
    ($asset['status'] ?? '') === 'active' && !empty($usageMap[(int) $asset['id']])
));
$assets = [];
$selectedAsset = null;
$driveFiles = [];
$driveMap = [];
$selectedDrive = null;
$driveError = '';
$nextPageToken = null;

if ($source === 'library') {
    $assets = cms_media_search([
        'q' => $query,
        'orientation' => $orientation,
        'tag' => $tagSlug,
        'status' => $status,
    ]);
    foreach ($assets as &$asset) {
        $asset['placements'] = $usageMap[(int) $asset['id']] ?? [];
    }
    unset($asset);
    if ($usageFilter !== '') {
        $assets = array_values(array_filter($assets, static fn(array $asset): bool =>
            $usageFilter === 'used' ? !empty($asset['placements']) : empty($asset['placements'])
        ));
    }
    $selectedId = (int) ($_GET['id'] ?? 0);
    if ($selectedId) {
        $selectedAsset = cms_media_get_asset($selectedId);
        if ($selectedAsset) {
            $selectedAsset['placements'] = $usageMap[(int) $selectedAsset['id']] ?? [];
        }
    }
    if (!$selectedAsset && $assets) {
        $selectedAsset = $assets[0];
    }
} elseif (cms_google_drive_is_configured()) {
    try {
        $driveResult = cms_google_drive_list_images((string) ($_GET['page_token'] ?? '') ?: null);
        $driveFiles = $driveResult['files'];
        if ($query !== '') {
            $driveFiles = array_values(array_filter($driveFiles, static fn(array $file): bool => str_contains(
                mb_strtolower((string) ($file['name'] ?? '')),
                mb_strtolower($query)
            )));
        }
        $nextPageToken = $driveResult['next_page_token'];
        $driveMap = cms_media_source_map('drive', array_column($driveFiles, 'id'));
        $selectedDriveId = (string) ($_GET['drive_id'] ?? '');
        if ($selectedDriveId !== '') {
            foreach ($driveFiles as $driveFile) {
                if (($driveFile['id'] ?? '') === $selectedDriveId) {
                    $selectedDrive = $driveFile;
                    break;
                }
            }
            $selectedDrive = $selectedDrive ?: cms_google_drive_get_file($selectedDriveId);
        }
        if (!$selectedDrive && $driveFiles) {
            $selectedDrive = $driveFiles[0];
        }
    } catch (Throwable $exception) {
        $driveError = $exception->getMessage();
    }
}

$cmsPageTitle = 'Media';
$cmsCurrent = 'media';
$cmsBodyClass = 'cms-body--media';
include __DIR__ . '/_header.php';
?>
<header class="cms-page-header cms-media-header">
  <div>
    <p class="cms-eyebrow">Content</p>
    <h1>Media</h1>
    <p>Find an image, update its details, or bring one in from Google Drive.</p>
  </div>
  <dl class="cms-media-counts" aria-label="Media summary">
    <div><dt>Total</dt><dd><?= $counts['active'] ?></dd></div>
    <div><dt>Landscape</dt><dd><?= $counts['landscape'] ?></dd></div>
    <div><dt>Portrait</dt><dd><?= $counts['portrait'] ?></dd></div>
    <div><dt>Square</dt><dd><?= $counts['square'] ?></dd></div>
    <div><dt>In use</dt><dd><?= $counts['used'] ?></dd></div>
  </dl>
</header>

<?php if ($notice === 'saved'): ?><div class="cms-alert cms-alert--success">Image details saved.</div><?php endif; ?>
<?php if ($notice === 'uploaded'): ?><div class="cms-alert cms-alert--success"><?= (int) ($_GET['count'] ?? 1) ?> image<?= (int) ($_GET['count'] ?? 1) === 1 ? '' : 's' ?> uploaded and optimized.</div><?php endif; ?>
<?php if ($notice === 'drive-imported'): ?><div class="cms-alert cms-alert--success">Google Drive image imported, optimized, and added to the Site Library.</div><?php endif; ?>
<?php if ($notice === 'error'): ?><div class="cms-alert cms-alert--error"><?= cms_escape($_GET['message'] ?? 'The media action could not be completed.') ?></div><?php endif; ?>

<nav class="cms-media-tabs" aria-label="Media sources">
  <a href="/cms/media.php"<?= $source === 'library' ? ' class="is-active" aria-current="page"' : '' ?>>Site Library <span><?= $counts['active'] ?></span></a>
  <a href="/cms/media.php?source=drive"<?= $source === 'drive' ? ' class="is-active" aria-current="page"' : '' ?>>Google Drive</a>
</nav>

<?php if ($source === 'library'): ?>
  <details class="cms-panel cms-media-upload"<?= !$assets ? ' open' : '' ?>>
    <summary>Upload from computer</summary>
    <form method="post" action="/cms/media-action.php" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= cms_escape(cms_csrf_token()) ?>">
      <input type="hidden" name="action" value="upload">
      <label>Choose JPEG, PNG, or WebP images
        <input type="file" name="media_files[]" accept="image/jpeg,image/png,image/webp" multiple required>
      </label>
      <label>Apply tags to every selected image
        <input type="text" name="tags" placeholder="Missions, Students">
      </label>
      <button class="cms-button cms-button--primary" type="submit">Upload and optimize</button>
    </form>
  </details>

  <form class="cms-media-filters" method="get" action="/cms/media.php">
    <label class="cms-media-search"><span class="visually-hidden">Search media</span><input type="search" name="q" value="<?= cms_escape($query) ?>" placeholder="Search titles, alt text, captions, tags"></label>
    <select name="orientation" aria-label="Orientation">
      <option value="">Any orientation</option>
      <?php foreach (['landscape' => 'Landscape', 'portrait' => 'Portrait', 'square' => 'Square'] as $value => $label): ?>
        <option value="<?= $value ?>"<?= $orientation === $value ? ' selected' : '' ?>><?= $label ?></option>
      <?php endforeach; ?>
    </select>
    <select name="status" aria-label="Status">
      <option value="active"<?= $status !== 'archived' ? ' selected' : '' ?>>Active only</option>
      <option value="archived"<?= $status === 'archived' ? ' selected' : '' ?>>Archived</option>
      <option value="all"<?= $status === 'all' ? ' selected' : '' ?>>All statuses</option>
    </select>
    <select name="usage" aria-label="Website usage">
      <option value=""<?= $usageFilter === '' ? ' selected' : '' ?>>Any usage</option>
      <option value="used"<?= $usageFilter === 'used' ? ' selected' : '' ?>>In use</option>
      <option value="unused"<?= $usageFilter === 'unused' ? ' selected' : '' ?>>Unused</option>
    </select>
    <button class="cms-button cms-button--secondary" type="submit">Search</button>
  </form>

  <div class="cms-media-tags" aria-label="Filter by tag">
    <a href="?<?= http_build_query(array_filter(['q' => $query, 'orientation' => $orientation, 'status' => $status, 'usage' => $usageFilter])) ?>"<?= $tagSlug === '' ? ' class="is-active"' : '' ?>>All tags</a>
    <?php foreach ($tags as $tag): ?>
      <a href="?<?= http_build_query(array_filter(['q' => $query, 'orientation' => $orientation, 'status' => $status, 'usage' => $usageFilter, 'tag' => $tag['slug']])) ?>"<?= $tagSlug === $tag['slug'] ? ' class="is-active"' : '' ?>><?= cms_escape($tag['name']) ?><?php if ((int) $tag['asset_count'] > 0): ?> <span><?= (int) $tag['asset_count'] ?></span><?php endif; ?></a>
    <?php endforeach; ?>
  </div>

  <div class="cms-media-workspace">
    <section class="cms-media-grid" aria-label="Website media">
      <?php if (!$assets): ?>
        <div class="cms-empty cms-media-empty">
          <span>MEDIA</span><h2>No images found.</h2><p>Adjust the filters, upload an image, or import one from Google Drive.</p>
        </div>
      <?php endif; ?>
      <?php foreach ($assets as $asset): ?>
        <?php $cardQuery = array_filter(['q' => $query, 'orientation' => $orientation, 'status' => $status, 'usage' => $usageFilter, 'tag' => $tagSlug, 'id' => $asset['id']]); ?>
        <a class="cms-media-card<?= $selectedAsset && (int) $selectedAsset['id'] === (int) $asset['id'] ? ' is-selected' : '' ?>" href="?<?= http_build_query($cardQuery) ?>">
          <span class="cms-media-card__image"><img src="<?= cms_escape($asset['public_url']) ?>" srcset="<?= cms_escape(cms_media_srcset($asset)) ?>" sizes="(max-width: 700px) 50vw, 280px" alt=""></span>
          <?php if (!empty($asset['placements'])): ?><span class="cms-media-card__badge">In use · <?= count($asset['placements']) ?></span><?php endif; ?>
          <strong><?= cms_escape($asset['title']) ?></strong>
          <small><?= cms_escape(ucfirst($asset['orientation'])) ?> · <?= cms_escape($asset['source_type'] === 'drive' ? 'Google Drive' : 'Upload') ?></small>
        </a>
      <?php endforeach; ?>
    </section>

    <?php if ($selectedAsset): ?>
      <aside class="cms-media-inspector">
        <img class="cms-media-inspector__preview" src="<?= cms_escape($selectedAsset['public_url']) ?>" srcset="<?= cms_escape(cms_media_srcset($selectedAsset)) ?>" sizes="360px" alt="">
        <div class="cms-media-inspector__actions">
          <button class="cms-button cms-button--secondary" type="button" data-copy-value="<?= cms_escape($selectedAsset['public_url']) ?>">Copy URL</button>
          <a class="cms-button cms-button--secondary" href="<?= cms_escape($selectedAsset['public_url']) ?>" download>Download</a>
        </div>
        <div class="cms-media-inspector__summary">
          <span>Selected asset</span>
          <h2><?= cms_escape($selectedAsset['title']) ?></h2>
          <p><?= cms_escape($selectedAsset['original_name']) ?></p>
          <dl><div><dt>Orientation</dt><dd><?= cms_escape($selectedAsset['orientation']) ?></dd></div><div><dt>Size</dt><dd><?= (int) $selectedAsset['width'] ?> × <?= (int) $selectedAsset['height'] ?></dd></div><div><dt>Placements</dt><dd><?= count($selectedAsset['placements'] ?? []) ?></dd></div></dl>
        </div>
        <div class="cms-media-placements">
          <h3><?= !empty($selectedAsset['placements']) ? 'Used on the website' : 'Not currently in use' ?></h3>
          <?php if (!empty($selectedAsset['placements'])): ?>
            <ul>
              <?php foreach ($selectedAsset['placements'] as $placement): ?>
                <li><strong><?= cms_escape($placement['label']) ?></strong><span><?= cms_escape($placement['path']) ?></span></li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p>This image is available in the library but is not referenced by a website page.</p>
          <?php endif; ?>
        </div>
        <form class="cms-media-inspector__form" method="post" action="/cms/media-action.php">
          <input type="hidden" name="csrf_token" value="<?= cms_escape(cms_csrf_token()) ?>">
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="id" value="<?= (int) $selectedAsset['id'] ?>">
          <label>Title<input type="text" name="title" required maxlength="255" value="<?= cms_escape($selectedAsset['title']) ?>"></label>
          <label>Alt text<textarea name="alt_text" rows="3" maxlength="500" placeholder="Describe what the image communicates"><?= cms_escape($selectedAsset['alt_text']) ?></textarea><small>Leave empty only when the image is purely decorative.</small></label>
          <label>Caption<textarea name="caption" rows="3"><?= cms_escape($selectedAsset['caption']) ?></textarea></label>
          <label>Tags<input type="text" name="tags" value="<?= cms_escape(implode(', ', array_column($selectedAsset['tags'], 'name'))) ?>" placeholder="Missions, Students"><small>Separate tags with commas.</small></label>
          <label>Photographer or source<input type="text" name="credit" maxlength="255" value="<?= cms_escape($selectedAsset['credit']) ?>"></label>
          <label>Status<select name="status"><option value="active"<?= $selectedAsset['status'] === 'active' ? ' selected' : '' ?>>Active</option><option value="archived"<?= $selectedAsset['status'] === 'archived' ? ' selected' : '' ?>>Archived</option></select></label>
          <button class="cms-button cms-button--primary cms-button--block" type="submit">Save details</button>
        </form>
      </aside>
    <?php endif; ?>
  </div>

<?php else: ?>
  <?php if (!cms_google_drive_is_configured()): ?>
    <div class="cms-panel cms-drive-setup">
      <p class="cms-eyebrow">Setup required</p>
      <h2>Connect Declaration’s Google Drive folder.</h2>
      <p>Add the service-account email, private key, and folder ID to the site configuration, then share the image folder with that service account.</p>
      <p>The website receives read-only access and imports its own optimized copy of every selected image.</p>
    </div>
  <?php elseif ($driveError): ?>
    <div class="cms-alert cms-alert--error"><?= cms_escape($driveError) ?></div>
  <?php else: ?>
    <form class="cms-media-filters cms-media-filters--drive" method="get" action="/cms/media.php">
      <input type="hidden" name="source" value="drive">
      <label class="cms-media-search"><span class="visually-hidden">Search Google Drive</span><input type="search" name="q" value="<?= cms_escape($query) ?>" placeholder="Search this page of Google Drive images"></label>
      <button class="cms-button cms-button--secondary" type="submit">Search</button>
    </form>
    <div class="cms-media-workspace">
      <section class="cms-media-grid" aria-label="Google Drive images">
        <?php if (!$driveFiles): ?><div class="cms-empty cms-media-empty"><span>DRIVE</span><h2>No images found.</h2><p>Add images to the configured Drive folder or clear the search.</p></div><?php endif; ?>
        <?php foreach ($driveFiles as $file): ?>
          <a class="cms-media-card<?= $selectedDrive && ($selectedDrive['id'] ?? '') === ($file['id'] ?? '') ? ' is-selected' : '' ?>" href="?<?= http_build_query(['source' => 'drive', 'drive_id' => $file['id'], 'q' => $query]) ?>">
            <span class="cms-media-card__image"><img src="/cms/drive-thumbnail.php?id=<?= rawurlencode($file['id']) ?>" alt=""></span>
            <?php if (isset($driveMap[$file['id']])): ?><span class="cms-media-card__badge">Imported</span><?php endif; ?>
            <strong><?= cms_escape($file['name']) ?></strong>
            <small><?= cms_escape(isset($file['modifiedTime']) ? date('M j, Y', strtotime($file['modifiedTime'])) : 'Google Drive') ?></small>
          </a>
        <?php endforeach; ?>
        <?php if ($nextPageToken): ?><a class="cms-button cms-button--secondary cms-drive-more" href="?<?= http_build_query(['source' => 'drive', 'page_token' => $nextPageToken]) ?>">Next Drive page →</a><?php endif; ?>
      </section>

      <?php if ($selectedDrive): ?>
        <?php $importedAsset = cms_media_get_asset_by_source('drive', (string) $selectedDrive['id']); ?>
        <aside class="cms-media-inspector">
          <img class="cms-media-inspector__preview" src="/cms/drive-thumbnail.php?id=<?= rawurlencode($selectedDrive['id']) ?>" alt="">
          <div class="cms-media-inspector__summary">
            <span>Google Drive image</span>
            <h2><?= cms_escape($selectedDrive['name']) ?></h2>
            <p><?= cms_escape($selectedDrive['mimeType'] ?? 'Image') ?></p>
            <?php if (!empty($selectedDrive['imageMediaMetadata'])): ?><dl><div><dt>Dimensions</dt><dd><?= (int) ($selectedDrive['imageMediaMetadata']['width'] ?? 0) ?> × <?= (int) ($selectedDrive['imageMediaMetadata']['height'] ?? 0) ?></dd></div></dl><?php endif; ?>
          </div>
          <?php if ($importedAsset): ?>
            <div class="cms-alert cms-alert--success">This image is already in the Site Library.</div>
            <a class="cms-button cms-button--primary cms-button--block" href="/cms/media.php?id=<?= (int) $importedAsset['id'] ?>">View imported image</a>
          <?php else: ?>
            <form class="cms-media-inspector__form" method="post" action="/cms/media-action.php">
              <input type="hidden" name="csrf_token" value="<?= cms_escape(cms_csrf_token()) ?>">
              <input type="hidden" name="action" value="import-drive">
              <input type="hidden" name="drive_id" value="<?= cms_escape($selectedDrive['id']) ?>">
              <label>Title<input type="text" name="title" required maxlength="255" value="<?= cms_escape(cms_media_filename_title($selectedDrive['name'])) ?>"></label>
              <label>Alt text<textarea name="alt_text" rows="3" maxlength="500" required placeholder="Describe what the image communicates"></textarea></label>
              <label>Caption<textarea name="caption" rows="3"></textarea></label>
              <label>Tags<input type="text" name="tags" placeholder="Missions, Students"><small>Separate tags with commas.</small></label>
              <label>Photographer or source<input type="text" name="credit" maxlength="255"></label>
              <button class="cms-button cms-button--primary cms-button--block" type="submit">Import and optimize</button>
            </form>
          <?php endif; ?>
        </aside>
      <?php endif; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>

<?php include __DIR__ . '/_footer.php'; ?>
