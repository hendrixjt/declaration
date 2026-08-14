<?php
/**
 * Read-only Google Drive source for the CMS media library.
 *
 * The configured folder must be shared with the service account email. The
 * website never serves Drive URLs directly; selected images are imported into
 * the local media library.
 */

function cms_google_drive_is_configured(): bool
{
    return defined('GOOGLE_DRIVE_SERVICE_ACCOUNT_EMAIL')
        && trim((string) GOOGLE_DRIVE_SERVICE_ACCOUNT_EMAIL) !== ''
        && defined('GOOGLE_DRIVE_PRIVATE_KEY')
        && trim((string) GOOGLE_DRIVE_PRIVATE_KEY) !== ''
        && defined('GOOGLE_DRIVE_FOLDER_ID')
        && trim((string) GOOGLE_DRIVE_FOLDER_ID) !== '';
}

function cms_google_drive_base64url(string $value): string
{
    return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
}

function cms_google_drive_access_token(): string
{
    static $cached = null;
    if (is_array($cached) && ($cached['expires_at'] ?? 0) > time() + 60) {
        return (string) $cached['token'];
    }
    if (!cms_google_drive_is_configured()) {
        throw new RuntimeException('Google Drive has not been configured for this site.');
    }

    $now = time();
    $header = cms_google_drive_base64url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    $claims = cms_google_drive_base64url(json_encode([
        'iss' => trim((string) GOOGLE_DRIVE_SERVICE_ACCOUNT_EMAIL),
        'scope' => 'https://www.googleapis.com/auth/drive.readonly',
        'aud' => 'https://oauth2.googleapis.com/token',
        'iat' => $now,
        'exp' => $now + 3600,
    ]));
    $unsigned = $header . '.' . $claims;
    $privateKey = str_replace('\\n', "\n", (string) GOOGLE_DRIVE_PRIVATE_KEY);
    $signature = '';
    if (!openssl_sign($unsigned, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
        throw new RuntimeException('Google Drive authentication could not sign the service request.');
    }
    $assertion = $unsigned . '.' . cms_google_drive_base64url($signature);

    $response = cms_google_drive_http(
        'https://oauth2.googleapis.com/token',
        ['Content-Type: application/x-www-form-urlencoded'],
        http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $assertion,
        ])
    );
    $payload = json_decode($response['body'], true);
    if ($response['status'] < 200 || $response['status'] >= 300 || empty($payload['access_token'])) {
        $message = is_array($payload) ? (string) ($payload['error_description'] ?? $payload['error'] ?? '') : '';
        throw new RuntimeException('Google Drive authentication failed.' . ($message ? ' ' . $message : ''));
    }
    $cached = [
        'token' => (string) $payload['access_token'],
        'expires_at' => time() + max(300, (int) ($payload['expires_in'] ?? 3600)),
    ];
    return $cached['token'];
}

function cms_google_drive_http(string $url, array $headers = [], ?string $postFields = null): array
{
    if (!function_exists('curl_init')) {
        throw new RuntimeException('The PHP cURL extension is required for Google Drive.');
    }
    $handle = curl_init($url);
    curl_setopt_array($handle, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_USERAGENT => 'DeclarationChurchCMS/1.0',
    ]);
    if ($postFields !== null) {
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $postFields);
    }
    $body = curl_exec($handle);
    $error = curl_error($handle);
    $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
    $contentType = (string) curl_getinfo($handle, CURLINFO_CONTENT_TYPE);
    curl_close($handle);
    if ($body === false) {
        throw new RuntimeException('Google Drive could not be reached.' . ($error ? ' ' . $error : ''));
    }
    return ['status' => $status, 'body' => (string) $body, 'content_type' => $contentType];
}

function cms_google_drive_validate_id(string $id): string
{
    $id = trim($id);
    if ($id === '' || !preg_match('/^[A-Za-z0-9_-]+$/', $id)) {
        throw new InvalidArgumentException('The Google Drive file ID is invalid.');
    }
    return $id;
}

function cms_google_drive_list_images(?string $pageToken = null, int $pageSize = 40): array
{
    if (!cms_google_drive_is_configured()) {
        return ['files' => [], 'next_page_token' => null];
    }
    $folderId = cms_google_drive_validate_id((string) GOOGLE_DRIVE_FOLDER_ID);
    $query = "'{$folderId}' in parents and trashed = false and mimeType contains 'image/'";
    $params = [
        'q' => $query,
        'pageSize' => max(1, min(100, $pageSize)),
        'orderBy' => 'modifiedTime desc',
        'spaces' => 'drive',
        'supportsAllDrives' => 'true',
        'includeItemsFromAllDrives' => 'true',
        'fields' => 'nextPageToken,files(id,name,mimeType,size,modifiedTime,thumbnailLink,webViewLink,md5Checksum,version,imageMediaMetadata(width,height))',
    ];
    if ($pageToken) {
        $params['pageToken'] = $pageToken;
    }
    if (defined('GOOGLE_DRIVE_SHARED_DRIVE_ID') && trim((string) GOOGLE_DRIVE_SHARED_DRIVE_ID) !== '') {
        $params['corpora'] = 'drive';
        $params['driveId'] = cms_google_drive_validate_id((string) GOOGLE_DRIVE_SHARED_DRIVE_ID);
    }

    $response = cms_google_drive_http(
        'https://www.googleapis.com/drive/v3/files?' . http_build_query($params),
        ['Authorization: Bearer ' . cms_google_drive_access_token(), 'Accept: application/json']
    );
    $payload = json_decode($response['body'], true);
    if ($response['status'] < 200 || $response['status'] >= 300 || !is_array($payload)) {
        $message = (string) ($payload['error']['message'] ?? 'The Drive folder could not be read.');
        throw new RuntimeException($message);
    }
    return [
        'files' => array_values(array_filter($payload['files'] ?? [], 'is_array')),
        'next_page_token' => $payload['nextPageToken'] ?? null,
    ];
}

function cms_google_drive_get_file(string $fileId): array
{
    $fileId = cms_google_drive_validate_id($fileId);
    $fields = 'id,name,mimeType,size,modifiedTime,thumbnailLink,webViewLink,md5Checksum,version,imageMediaMetadata(width,height)';
    $response = cms_google_drive_http(
        'https://www.googleapis.com/drive/v3/files/' . rawurlencode($fileId)
        . '?' . http_build_query(['fields' => $fields, 'supportsAllDrives' => 'true']),
        ['Authorization: Bearer ' . cms_google_drive_access_token(), 'Accept: application/json']
    );
    $payload = json_decode($response['body'], true);
    if ($response['status'] < 200 || $response['status'] >= 300 || !is_array($payload)) {
        throw new RuntimeException((string) ($payload['error']['message'] ?? 'The Drive image could not be found.'));
    }
    if (!str_starts_with((string) ($payload['mimeType'] ?? ''), 'image/')) {
        throw new InvalidArgumentException('Only images can be imported from Google Drive.');
    }
    return $payload;
}

function cms_google_drive_download(string $fileId): array
{
    $file = cms_google_drive_get_file($fileId);
    $response = cms_google_drive_http(
        'https://www.googleapis.com/drive/v3/files/' . rawurlencode($file['id'])
        . '?' . http_build_query(['alt' => 'media', 'supportsAllDrives' => 'true']),
        ['Authorization: Bearer ' . cms_google_drive_access_token(), 'Accept: image/*']
    );
    if ($response['status'] < 200 || $response['status'] >= 300) {
        throw new RuntimeException('The selected image could not be downloaded from Google Drive.');
    }
    return ['file' => $file, 'bytes' => $response['body']];
}

function cms_google_drive_thumbnail(string $fileId): array
{
    $file = cms_google_drive_get_file($fileId);
    $thumbnailLink = (string) ($file['thumbnailLink'] ?? '');
    if ($thumbnailLink === '') {
        $download = cms_google_drive_download($fileId);
        return ['body' => $download['bytes'], 'content_type' => (string) ($file['mimeType'] ?? 'image/jpeg')];
    }
    $response = cms_google_drive_http(
        $thumbnailLink,
        ['Authorization: Bearer ' . cms_google_drive_access_token(), 'Accept: image/*']
    );
    if ($response['status'] < 200 || $response['status'] >= 300) {
        throw new RuntimeException('The Drive thumbnail could not be loaded.');
    }
    return ['body' => $response['body'], 'content_type' => $response['content_type'] ?: 'image/jpeg'];
}
