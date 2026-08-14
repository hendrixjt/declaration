<?php
require_once __DIR__ . '/../includes/cms.php';
require_once __DIR__ . '/../includes/google-drive.php';
cms_require_auth();

try {
    $fileId = cms_google_drive_validate_id((string) ($_GET['id'] ?? ''));
    $cacheDirectory = __DIR__ . '/../cache/drive-thumbnails';
    $cacheKey = hash('sha256', $fileId);
    $cacheFile = $cacheDirectory . '/' . $cacheKey . '.bin';
    $mimeFile = $cacheDirectory . '/' . $cacheKey . '.mime';
    if (is_file($cacheFile) && is_file($mimeFile) && filemtime($cacheFile) > time() - 86400) {
        $cachedMime = trim((string) file_get_contents($mimeFile));
        header('Content-Type: ' . ($cachedMime ?: 'image/jpeg'));
        header('Cache-Control: private, max-age=3600');
        readfile($cacheFile);
        exit;
    }

    $thumbnail = cms_google_drive_thumbnail($fileId);
    if (!is_dir($cacheDirectory)) {
        @mkdir($cacheDirectory, 0750, true);
    }
    if (is_dir($cacheDirectory) && is_writable($cacheDirectory)) {
        @file_put_contents($cacheFile, $thumbnail['body']);
        @file_put_contents($mimeFile, (string) $thumbnail['content_type']);
    }
    header('Content-Type: ' . preg_replace('/[^a-zA-Z0-9+\.\/-]/', '', (string) $thumbnail['content_type']));
    header('Cache-Control: private, max-age=3600');
    echo $thumbnail['body'];
} catch (Throwable $exception) {
    http_response_code(404);
    header('Content-Type: image/svg+xml');
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 400"><rect width="640" height="400" fill="#e8e7e2"/><path d="M270 160h100v80H270z" fill="none" stroke="#999" stroke-width="8"/><circle cx="300" cy="187" r="12" fill="#999"/><path d="m278 226 31-29 22 20 17-14 15 23z" fill="#999"/></svg>';
}
