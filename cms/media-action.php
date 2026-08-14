<?php
require_once __DIR__ . '/../includes/cms.php';
require_once __DIR__ . '/../includes/google-drive.php';
cms_require_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /cms/media.php');
    exit;
}

cms_verify_csrf();
$action = (string) ($_POST['action'] ?? '');

try {
    if ($action === 'upload') {
        $files = cms_media_normalize_uploads($_FILES['media_files'] ?? []);
        if (!$files) {
            throw new InvalidArgumentException('Choose at least one image to upload.');
        }
        $imported = 0;
        $lastId = null;
        foreach ($files as $file) {
            if ((int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $lastId = cms_media_store_upload($file, ['tags' => $_POST['tags'] ?? '']);
            $imported++;
        }
        if ($imported === 0) {
            throw new InvalidArgumentException('Choose at least one image to upload.');
        }
        header('Location: /cms/media.php?notice=uploaded&count=' . $imported . ($lastId ? '&id=' . $lastId : ''));
        exit;
    }

    if ($action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        cms_media_update_asset($id, $_POST);
        header('Location: /cms/media.php?notice=saved&id=' . $id);
        exit;
    }

    if ($action === 'import-drive') {
        $fileId = (string) ($_POST['drive_id'] ?? '');
        $download = cms_google_drive_download($fileId);
        $file = $download['file'];
        $id = cms_media_store_binary((string) $download['bytes'], (string) ($file['name'] ?? 'Drive image'), [
            'source_type' => 'drive',
            'source_id' => (string) ($file['id'] ?? ''),
            'source_revision' => (string) (($file['md5Checksum'] ?? '') ?: ($file['version'] ?? $file['modifiedTime'] ?? '')),
            'title' => $_POST['title'] ?? '',
            'alt_text' => $_POST['alt_text'] ?? '',
            'caption' => $_POST['caption'] ?? '',
            'credit' => $_POST['credit'] ?? '',
            'tags' => $_POST['tags'] ?? '',
        ]);
        header('Location: /cms/media.php?notice=drive-imported&id=' . $id);
        exit;
    }

    throw new InvalidArgumentException('Choose a valid media action.');
} catch (Throwable $exception) {
    $message = rawurlencode($exception->getMessage());
    $return = $action === 'import-drive' ? '&source=drive' : '';
    header('Location: /cms/media.php?notice=error&message=' . $message . $return);
    exit;
}
