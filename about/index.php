<?php
$page_title = 'About - Eventia Bootstrap Template';
$body_class = 'about-page';
$current_page = 'about';
$base_url = '/';
include __DIR__ . '/../includes/header.php';
?>

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/events/showcase-5.webp);">
      <div class="container position-relative">
        <h1>About Us</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">About</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="intro-banner">
          <div class="banner-content">
            <span class="badge-label">Annual Summit 2025</span>
            <h3>Shaping Tomorrow's Innovation</h3>
            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit.</p>
            <div class="banner-stats">
              <div class="single-stat">
                <span class="stat-number">4</span>
                <span class="stat-label">Days</span>
              </div>
              <div class="single-stat">
                <span class="stat-number">3,200+</span>
                <span class="stat-label">Guests</span>
              </div>
              <div class="single-stat">
                <span class="stat-number">95+</span>
                <span class="stat-label">Experts</span>
              </div>
              <div class="single-stat">
                <span class="stat-number">12</span>
                <span class="stat-label">Tracks</span>
              </div>
            </div>
          </div>
        </div><!-- End Intro Banner -->

        <div class="features-row">
          <div class="row gy-4">

            <div class="col-lg-4">
              <div class="feature-block">
                <div class="feature-icon">
                  <i class="bi bi-lightbulb"></i>
                </div>
                <h4>Innovative Sessions</h4>
                <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum dolore.</p>
              </div>
            </div><!-- End Feature Block -->

            <div class="col-lg-4">
              <div class="feature-block featured">
                <div class="feature-icon">
                  <i class="bi bi-people-fill"></i>
                </div>
                <h4>Global Networking</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore magna aliqua veniam.</p>
              </div>
            </div><!-- End Feature Block -->

            <div class="col-lg-4">
              <div class="feature-block">
                <div class="feature-icon">
                  <i class="bi bi-trophy"></i>
                </div>
                <h4>Industry Recognition</h4>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur occaecat.</p>
              </div>
            </div><!-- End Feature Block -->

          </div>
        </div><!-- End Features Row -->

        <div class="audience-wrapper">
          <div class="row align-items-center">
            <div class="col-lg-5">
              <div class="audience-intro">
                <h3>Who Should Join Us?</h3>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium totam rem aperiam eaque ipsa.</p>
                <blockquote>
                  <p>"Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit sed quia consequuntur magni."</p>
                  <cite>— Michael Torres, Program Lead</cite>
                </blockquote>
                <div class="action-links">
                  <a href="schedule/" class="primary-btn">Explore Schedule</a>
                  <a href="speakers/" class="text-link">View Speakers <i class="bi bi-arrow-right"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-7">
              <div class="audience-grid">

                <div class="audience-card">
                  <i class="bi bi-terminal"></i>
                  <h5>Engineers</h5>
                  <p>Backend and frontend specialists</p>
                </div><!-- End Audience Card -->

                <div class="audience-card">
                  <i class="bi bi-rocket-takeoff"></i>
                  <h5>Founders</h5>
                  <p>Visionary startup creators</p>
                </div><!-- End Audience Card -->

                <div class="audience-card">
                  <i class="bi bi-brush"></i>
                  <h5>Creatives</h5>
                  <p>Visual and experience designers</p>
                </div><!-- End Audience Card -->

                <div class="audience-card">
                  <i class="bi bi-bar-chart-line"></i>
                  <h5>Analysts</h5>
                  <p>Data and strategy professionals</p>
                </div><!-- End Audience Card -->

                <div class="audience-card">
                  <i class="bi bi-currency-dollar"></i>
                  <h5>Funders</h5>
                  <p>Angel investors and VC partners</p>
                </div><!-- End Audience Card -->

                <div class="audience-card">
                  <i class="bi bi-book"></i>
                  <h5>Learners</h5>
                  <p>Future industry innovators</p>
                </div><!-- End Audience Card -->

              </div><!-- End Audience Grid -->
            </div>
          </div>
        </div><!-- End Audience Wrapper -->

      </div>

    </section><!-- /About Section -->

<?php include __DIR__ . '/../includes/footer.php'; ?>
