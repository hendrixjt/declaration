<?php
/**
 * Planning Center API credentials.
 *
 * Copy this file to config.php and fill in your real credentials.
 * Generate a token at: https://api.planningcenteronline.com/oauth/applications
 */

define('PC_APP_ID', 'your-planning-center-app-id');
define('PC_SECRET', 'your-planning-center-secret');

// Optional: override the cache time-to-live in seconds. Default is 1800 (30 minutes).
define('PC_CACHE_TTL', 1800);

/**
 * Optional CMS database settings.
 *
 * The prototype uses storage/declaration-cms.sqlite when CMS_DSN is omitted.
 * For SiteGround MySQL, use a DSN similar to:
 * mysql:host=localhost;dbname=database_name;charset=utf8mb4
 */
// define('CMS_DSN', 'mysql:host=localhost;dbname=database_name;charset=utf8mb4');
// define('CMS_DB_USER', 'database_user');
// define('CMS_DB_PASSWORD', 'database_password');

/**
 * Optional CMS media settings.
 *
 * Uploaded and imported images are optimized into responsive WebP files.
 * The defaults below are already correct when /uploads/media is writable.
 */
// define('CMS_MEDIA_STORAGE_PATH', __DIR__ . '/../uploads/media');
// define('CMS_MEDIA_PUBLIC_BASE', '/uploads/media');
// define('CMS_MEDIA_MAX_BYTES', 20 * 1024 * 1024);

/**
 * Optional read-only Google Drive media source.
 *
 * Create a service account in a Declaration-owned Google Cloud project,
 * enable the Drive API, and share the image folder with the service-account
 * email. Preserve the escaped newlines when storing the private key here.
 */
// define('GOOGLE_DRIVE_SERVICE_ACCOUNT_EMAIL', 'declaration-media@example-project.iam.gserviceaccount.com');
// define('GOOGLE_DRIVE_PRIVATE_KEY', "-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n");
// define('GOOGLE_DRIVE_FOLDER_ID', 'google-drive-folder-id');
// define('GOOGLE_DRIVE_SHARED_DRIVE_ID', 'optional-shared-drive-id');
