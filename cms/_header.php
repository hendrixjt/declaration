<?php
cms_require_auth();
$cmsPageTitle = $cmsPageTitle ?? 'Events';
$cmsCurrent = $cmsCurrent ?? 'events';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title><?= cms_escape($cmsPageTitle) ?> | Declaration CMS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@500;600;700;800&family=Inter+Tight:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/cms/admin.css?v=<?= cms_escape((string) @filemtime(__DIR__ . '/admin.css')) ?>">
</head>
<body class="cms-body">
  <div class="cms-shell">
    <aside class="cms-sidebar">
      <a class="cms-brand" href="/cms/">
        <span class="cms-brand__mark">D</span>
        <span>Declaration <small>CMS</small></span>
      </a>
      <nav class="cms-nav" aria-label="CMS navigation">
        <a href="/cms/"<?= $cmsCurrent === 'events' ? ' class="is-active"' : '' ?>>
          <span>Events</span><span aria-hidden="true">›</span>
        </a>
        <a href="/" target="_blank" rel="noopener">
          <span>View website</span><span aria-hidden="true">↗</span>
        </a>
      </nav>
      <div class="cms-sidebar__footer">
        <span>Signed in as</span>
        <strong><?= cms_escape($_SESSION['cms_admin_username'] ?? 'Admin') ?></strong>
        <a href="/cms/logout.php">Sign out</a>
      </div>
    </aside>
    <main class="cms-main">
