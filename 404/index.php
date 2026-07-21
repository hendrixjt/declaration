<?php
$page_title = 'Page Not Found';
$body_class = 'page-404 declaration-interior';
$current_page = '404';
$base_url = '/';
$meta_description = 'The page you requested could not be found.';
include __DIR__ . '/../includes/header.php';
?>

    <section class="not-found-editorial section-black">
      <div class="container-fluid declaration-shell">
        <p class="section-kicker section-kicker--light">Page not found</p>
        <div class="not-found-editorial__number">404</div>
        <div class="not-found-editorial__content">
          <h1>Looks like this path ends here.</h1>
          <p>The page may have moved, but there is still a clear next step.</p>
          <div>
            <a class="button button--white" href="index.php">Go home</a>
            <a class="arrow-link arrow-link--light" href="contact/">Contact us <span aria-hidden="true">&#8594;</span></a>
          </div>
        </div>
      </div>
    </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
