<?php
$page_title = 'Registration';
$body_class = 'tickets-page declaration-interior legacy-page';
$current_page = 'tickets';
$base_url = '/'; include __DIR__ . '/../includes/header.php';
?>

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/events/showcase-5.webp);">
      <div class="container position-relative">
        <h1>Tickets</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Tickets</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Tickets Section -->
    <section id="tickets" class="tickets section">

      <div class="container">

        <div class="row justify-content-center">

          <div class="col-lg-3 col-md-6 mb-4">
            <div class="ticket-card">
              <div class="ticket-header">
                <div class="ticket-icon">
                  <i class="bi bi-calendar-event"></i>
                </div>
                <h4 class="ticket-title">Early Bird</h4>
                <div class="price-section">
                  <span class="currency">$</span>
                  <span class="amount">75</span>
                </div>
                <p class="ticket-subtitle">Limited time offer</p>
              </div>
              <div class="ticket-body">
                <ul class="benefits-list">
                  <li><i class="bi bi-check2"></i> Event entrance</li>
                  <li><i class="bi bi-check2"></i> Welcome kit</li>
                  <li><i class="bi bi-check2"></i> Light refreshments</li>
                  <li><i class="bi bi-x"></i> Premium seating</li>
                  <li><i class="bi bi-x"></i> Networking session</li>
                </ul>
                <a href="buy-tickets/" class="ticket-btn">Purchase Now</a>
              </div>
            </div>
          </div><!-- End Ticket Card -->

          <div class="col-lg-3 col-md-6 mb-4">
            <div class="ticket-card premium">
              <div class="ticket-header">
                <div class="ticket-icon">
                  <i class="bi bi-star-fill"></i>
                </div>
                <h4 class="ticket-title">Regular</h4>
                <div class="price-section">
                  <span class="currency">$</span>
                  <span class="amount">125</span>
                </div>
                <p class="ticket-subtitle">Best value package</p>
              </div>
              <div class="ticket-body">
                <ul class="benefits-list">
                  <li><i class="bi bi-check2"></i> Event entrance</li>
                  <li><i class="bi bi-check2"></i> Welcome kit</li>
                  <li><i class="bi bi-check2"></i> Light refreshments</li>
                  <li><i class="bi bi-check2"></i> Premium seating</li>
                  <li><i class="bi bi-x"></i> Networking session</li>
                </ul>
                <a href="buy-tickets/" class="ticket-btn">Purchase Now</a>
              </div>
            </div>
          </div><!-- End Ticket Card -->

          <div class="col-lg-3 col-md-6 mb-4">
            <div class="ticket-card">
              <div class="ticket-header">
                <div class="ticket-icon">
                  <i class="bi bi-gem"></i>
                </div>
                <h4 class="ticket-title">Premium</h4>
                <div class="price-section">
                  <span class="currency">$</span>
                  <span class="amount">195</span>
                </div>
                <p class="ticket-subtitle">Full experience access</p>
              </div>
              <div class="ticket-body">
                <ul class="benefits-list">
                  <li><i class="bi bi-check2"></i> Event entrance</li>
                  <li><i class="bi bi-check2"></i> Welcome kit</li>
                  <li><i class="bi bi-check2"></i> Light refreshments</li>
                  <li><i class="bi bi-check2"></i> Premium seating</li>
                  <li><i class="bi bi-check2"></i> Networking session</li>
                </ul>
                <a href="buy-tickets/" class="ticket-btn">Purchase Now</a>
              </div>
            </div>
          </div><!-- End Ticket Card -->

          <div class="col-lg-3 col-md-6 mb-4">
            <div class="ticket-card">
              <div class="ticket-header">
                <div class="ticket-icon">
                  <i class="bi bi-bank"></i>
                </div>
                <h4 class="ticket-title">VIP</h4>
                <div class="price-section">
                  <span class="currency">$</span>
                  <span class="amount">275</span>
                </div>
                <p class="ticket-subtitle">Exclusive access</p>
              </div>
              <div class="ticket-body">
                <ul class="benefits-list">
                  <li><i class="bi bi-check2"></i> All premium benefits</li>
                  <li><i class="bi bi-check2"></i> VIP lounge access</li>
                  <li><i class="bi bi-check2"></i> Meet &amp; greet</li>
                  <li><i class="bi bi-check2"></i> Exclusive merchandise</li>
                  <li><i class="bi bi-check2"></i> Priority support</li>
                </ul>
                <a href="buy-tickets/" class="ticket-btn">Purchase Now</a>
              </div>
            </div>
          </div><!-- End Ticket Card -->

        </div>

      </div>

    </section><!-- /Tickets Section -->

<?php include __DIR__ . '/../includes/footer.php'; ?>
