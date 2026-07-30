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
