<?php
$page_title = 'Registration';
$body_class = 'buy-tickets-page declaration-interior legacy-page';
$current_page = 'buy-tickets';
$base_url = '/'; include __DIR__ . '/../includes/header.php';
?>

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/events/showcase-5.webp);">
      <div class="container position-relative">
        <h1>Buy Tickets</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Buy Tickets</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Buy Tickets Section -->
    <section id="buy-tickets" class="buy-tickets section">

      <div class="container">

        <div class="row justify-content-center">
          <div class="col-lg-8">

            <div class="ticket-form-wrapper">
              <div class="event-info mb-5">
                <h3>Tech Innovation Conference 2026</h3>
                <div class="event-details">
                  <div class="event-meta">
                    <span><i class="bi bi-calendar-event"></i> March 15-17, 2026</span>
                    <span><i class="bi bi-geo-alt"></i> San Francisco Convention Center</span>
                    <span><i class="bi bi-clock"></i> 9:00 AM - 6:00 PM</span>
                  </div>
                </div>
              </div>

              <div class="ticket-types">
                <h4>Select Ticket Type</h4>

                <div class="ticket-option">
                  <input type="radio" id="early-bird" name="ticket_type" value="early-bird" checked="">
                  <label for="early-bird" class="ticket-label">
                    <div class="ticket-info">
                      <div class="ticket-name">Early Bird Pass</div>
                      <div class="ticket-description">Full access to all sessions and networking events</div>
                      <div class="ticket-benefits">
                        <span>✓ All conference sessions</span>
                        <span>✓ Networking lunch</span>
                        <span>✓ Welcome kit</span>
                      </div>
                    </div>
                    <div class="ticket-price">
                      <span class="original-price">$299</span>
                      <span class="current-price">$199</span>
                    </div>
                  </label>
                </div>

                <div class="ticket-option">
                  <input type="radio" id="standard" name="ticket_type" value="standard">
                  <label for="standard" class="ticket-label">
                    <div class="ticket-info">
                      <div class="ticket-name">Standard Pass</div>
                      <div class="ticket-description">Access to main conference sessions</div>
                      <div class="ticket-benefits">
                        <span>✓ All conference sessions</span>
                        <span>✓ Welcome kit</span>
                      </div>
                    </div>
                    <div class="ticket-price">
                      <span class="current-price">$249</span>
                    </div>
                  </label>
                </div>

                <div class="ticket-option">
                  <input type="radio" id="vip" name="ticket_type" value="vip">
                  <label for="vip" class="ticket-label">
                    <div class="ticket-info">
                      <div class="ticket-name">VIP Pass</div>
                      <div class="ticket-description">Premium experience with exclusive access</div>
                      <div class="ticket-benefits">
                        <span>✓ All conference sessions</span>
                        <span>✓ VIP networking dinner</span>
                        <span>✓ Premium welcome kit</span>
                        <span>✓ Priority seating</span>
                        <span>✓ Meet &amp; greet with speakers</span>
                      </div>
                    </div>
                    <div class="ticket-price">
                      <span class="current-price">$399</span>
                    </div>
                  </label>
                </div>

                <div class="ticket-option">
                  <input type="radio" id="student" name="ticket_type" value="student">
                  <label for="student" class="ticket-label">
                    <div class="ticket-info">
                      <div class="ticket-name">Student Pass</div>
                      <div class="ticket-description">Special pricing for students with valid ID</div>
                      <div class="ticket-benefits">
                        <span>✓ All conference sessions</span>
                        <span>✓ Student networking session</span>
                        <span>✓ Digital resources</span>
                      </div>
                    </div>
                    <div class="ticket-price">
                      <span class="current-price">$99</span>
                    </div>
                  </label>
                </div>
              </div>

              <form action="forms/buy-tickets.php" method="post" class="php-email-form ticket-form">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="first_name">First Name *</label>
                      <input type="text" name="first_name" id="first_name" class="form-control" required="">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="last_name">Last Name *</label>
                      <input type="text" name="last_name" id="last_name" class="form-control" required="">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="email">Email Address *</label>
                      <input type="email" name="email" id="email" class="form-control" required="">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="phone">Phone Number</label>
                      <input type="tel" name="phone" id="phone" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="company">Company/Organization</label>
                      <input type="text" name="company" id="company" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="job_title">Job Title</label>
                      <input type="text" name="job_title" id="job_title" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="quantity">Number of Tickets</label>
                  <select name="quantity" id="quantity" class="form-control" required="">
                    <option value="1">1 Ticket</option>
                    <option value="2">2 Tickets</option>
                    <option value="3">3 Tickets</option>
                    <option value="4">4 Tickets</option>
                    <option value="5">5 Tickets</option>
                    <option value="more">More than 5 (Contact us)</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="dietary">Dietary Restrictions (Optional)</label>
                  <textarea name="dietary" id="dietary" rows="3" class="form-control" placeholder="Please specify any dietary restrictions or food allergies"></textarea>
                </div>

                <div class="form-group">
                  <label for="special_requests">Special Requests (Optional)</label>
                  <textarea name="special_requests" id="special_requests" rows="3" class="form-control" placeholder="Any special accommodation requests or additional information"></textarea>
                </div>

                <div class="pricing-summary">
                  <h5>Order Summary</h5>
                  <div class="summary-row">
                    <span>Selected Ticket:</span>
                    <span class="ticket-name-display">Early Bird Pass</span>
                  </div>
                  <div class="summary-row">
                    <span>Quantity:</span>
                    <span class="quantity-display">1</span>
                  </div>
                  <div class="summary-row">
                    <span>Unit Price:</span>
                    <span class="unit-price-display">$199</span>
                  </div>
                  <div class="summary-row total">
                    <span>Total Amount:</span>
                    <span class="total-price">$199</span>
                  </div>
                  <div class="tax-note">*All prices include applicable taxes and fees</div>
                </div>

                <div class="terms-checkbox">
                  <input type="checkbox" id="terms" name="terms" required="">
                  <label for="terms">I agree to the <a href="#" target="_blank">Terms and Conditions</a> and <a href="#" target="_blank">Privacy Policy</a> *</label>
                </div>

                <div class="newsletter-checkbox">
                  <input type="checkbox" id="newsletter" name="newsletter">
                  <label for="newsletter">Subscribe to our newsletter for event updates and future conferences</label>
                </div>

                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your ticket booking request has been submitted. You will receive a confirmation email shortly!</div>

                <div class="form-submit">
                  <button type="submit" class="btn-submit">
                    <i class="bi bi-credit-card me-2"></i>
                    Proceed to Payment
                  </button>
                </div>

              </form>

              <div class="security-info">
                <div class="security-badges">
                  <div class="badge-item">
                    <i class="bi bi-shield-check"></i>
                    <span>Secure Payment</span>
                  </div>
                  <div class="badge-item">
                    <i class="bi bi-lock"></i>
                    <span>SSL Encrypted</span>
                  </div>
                  <div class="badge-item">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Full Refund Available</span>
                  </div>
                </div>
                <div class="payment-methods">
                  <span class="payment-label">We Accept:</span>
                  <div class="payment-icons">
                    <i class="bi bi-credit-card"></i>
                    <i class="bi bi-paypal"></i>
                    <span class="payment-text">Visa, MasterCard, AmEx, PayPal</span>
                  </div>
                </div>
              </div>

            </div>

          </div>
        </div>

      </div>

    </section><!-- /Buy Tickets Section -->

<?php include __DIR__ . '/../includes/footer.php'; ?>
