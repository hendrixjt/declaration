<?php
// Prevent server-side page caching (e.g. SG Optimizer) - remove when site is live
if (!headers_sent()) {
  header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
  header('Pragma: no-cache');
  header('Expires: 0');
}
$base_url = $base_url ?? '/';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <base href="<?php echo $base_url; ?>">
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <!-- SEO Title -->
  <title><?php echo htmlspecialchars($page_title ?? 'Declaration Church'); ?><?php if (isset($page_title)) echo ' | Declaration Church'; ?></title>

  <!-- Meta Description & Keywords -->
  <meta name="description" content="<?php echo htmlspecialchars($meta_description ?? 'Join Declaration Church for inspiring worship, biblical teaching, and community fellowship.'); ?>">
  <meta name="keywords" content="<?php echo htmlspecialchars($meta_keywords ?? 'church, worship, faith, community, Declaration Church'); ?>">

  <!-- Canonical URL -->
  <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url ?? 'https://declarationchurch.com' . $_SERVER['REQUEST_URI']); ?>">

  <!-- Open Graph Meta Tags -->
  <meta property="og:title" content="<?php echo htmlspecialchars($page_title ?? 'Declaration Church'); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($meta_description ?? 'Join Declaration Church for inspiring worship, biblical teaching, and community fellowship.'); ?>">
  <meta property="og:image" content="<?php echo htmlspecialchars($og_image ?? $base_url . 'assets/img/declaration/Declaration-Church_website.png'); ?>">
  <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url ?? 'https://declarationchurch.com' . $_SERVER['REQUEST_URI']); ?>">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Declaration Church">

  <!-- Twitter Card Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title ?? 'Declaration Church'); ?>">
  <meta name="twitter:description" content="<?php echo htmlspecialchars($meta_description ?? 'Join Declaration Church for inspiring worship, biblical teaching, and community fellowship.'); ?>">
  <meta name="twitter:image" content="<?php echo htmlspecialchars($og_image ?? $base_url . 'assets/img/declaration/Declaration-Church_website.png'); ?>">

  <!-- Geo Tags for Local SEO (update with actual location) -->
  <meta name="geo.region" content="<?php echo htmlspecialchars($geo_region ?? 'US'); ?>">
  <meta name="geo.placename" content="<?php echo htmlspecialchars($geo_placename ?? ''); ?>">
  <?php if (!empty($geo_position)): ?>
  <meta name="geo.position" content="<?php echo htmlspecialchars($geo_position); ?>">
  <?php endif; ?>

  <!-- Favicons -->
  <link href="assets/img/declaration/Declaration-Church_website.png" rel="icon" type="image/png">
  <link href="assets/img/declaration/Declaration-Church_website.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Eventia
  * Template URL: https://bootstrapmade.com/Eventia-bootstrap-events-template/
  * Updated: Jan 10 2026 with Bootstrap v5.3.8
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

  <!-- Structured Data (JSON-LD) for SEO -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Church",
    "name": "Declaration Church",
    "url": "<?php echo htmlspecialchars($base_url); ?>",
    "logo": "<?php echo htmlspecialchars($base_url . 'assets/img/declaration/Declaration-Logo-1080.png'); ?>",
    "image": "<?php echo htmlspecialchars($og_image ?? $base_url . 'assets/img/declaration/Declaration-Church_website.png'); ?>",
    "description": "<?php echo htmlspecialchars($meta_description ?? 'Join Declaration Church for inspiring worship, biblical teaching, and community fellowship.'); ?>"<?php if (!empty($church_address)): ?>,
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "<?php echo htmlspecialchars($church_address['street'] ?? ''); ?>",
      "addressLocality": "<?php echo htmlspecialchars($church_address['city'] ?? ''); ?>",
      "addressRegion": "<?php echo htmlspecialchars($church_address['state'] ?? ''); ?>",
      "postalCode": "<?php echo htmlspecialchars($church_address['zip'] ?? ''); ?>",
      "addressCountry": "<?php echo htmlspecialchars($church_address['country'] ?? 'US'); ?>"
    }<?php endif; ?><?php if (!empty($church_phone)): ?>,
    "telephone": "<?php echo htmlspecialchars($church_phone); ?>"<?php endif; ?><?php if (!empty($twitter_url) || !empty($facebook_url) || !empty($instagram_url) || !empty($linkedin_url)): ?>,
    "sameAs": [
      <?php
      $social_links = array_filter([
        $twitter_url ?? '',
        $facebook_url ?? '',
        $instagram_url ?? '',
        $linkedin_url ?? ''
      ]);
      echo '"' . implode('","', array_map('htmlspecialchars', $social_links)) . '"';
      ?>
    ]<?php endif; ?>
  }
  </script>
</head>

<body class="<?php echo htmlspecialchars($body_class ?? 'index-page'); ?>">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <img src="assets/img/declaration/Declaration-Expanded-White.png" alt="Declaration Church Logo">
        <span class="sitename visually-hidden">Declaration Church</span>
      </a>

      <nav id="navmenu" class="navmenu" aria-label="Main navigation">
        <ul>
          <li><a href="index.php"<?php if (($current_page ?? '') === 'index') echo ' class="active"'; ?>>Home</a></li>
          <li><a href="index.php#visit">Visit</a></li>
          <li><a href="index.php#upcoming-events">Events</a></li>
          <li><a href="index.php#rhythms">Life at Declaration</a></li>
          <li><a href="index.php#next-steps">Next Steps</a></li>
          <li><a href="index.php#contact">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <div class="header-actions">
        <a href="index.php#visit" class="header-cta">Plan Your Visit</a>
      </div>

    </div>
  </header>

  <main class="main">
