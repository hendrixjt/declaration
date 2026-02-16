<?php
$page_title = '404 - Eventia Bootstrap Template';
$body_class = 'page-404';
$current_page = '404';
$base_url = '/';
include __DIR__ . '/../includes/header.php';
?>

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/events/showcase-5.webp);">
      <div class="container position-relative">
        <h1>404</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">404</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Error 404 Section -->
    <section id="error-404" class="error-404 section">

      <div class="container">

        <div class="error-404-content text-center">
          <h1 class="display-1 fw-bold">404</h1>
          <h2>Oops! Page Not Found</h2>
          <p class="lead">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
          <div class="d-flex justify-content-center mt-4">
            <a href="index.php" class="btn btn-primary me-3">Go to Homepage</a>
            <a href="contact/" class="btn btn-outline-primary">Contact Support</a>
          </div>
        </div>

      </div>

    </section><!-- /Error 404 Section -->

<?php include __DIR__ . '/../includes/footer.php'; ?>
