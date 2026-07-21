<?php
$page_title = 'Contact';
$body_class = 'contact-page declaration-interior';
$current_page = 'contact';
$base_url = '/';
$meta_description = 'Contact Declaration Church, get directions, or ask a question before your Sunday visit.';
$canonical_url = 'https://www.declaration.org/contact/';
include __DIR__ . '/../includes/header.php';
?>

    <section class="interior-hero interior-hero--contact">
      <div class="interior-hero__media">
        <img src="assets/img/events/gallery-6.webp" alt="Declaration Church community gathering">
      </div>
      <div class="interior-hero__veil"></div>
      <div class="container-fluid declaration-shell interior-hero__content">
        <p class="section-kicker section-kicker--light">Let’s connect</p>
        <h1>We would love<br>to hear from you.</h1>
        <p>Ask a question, tell us how we can pray, or let us help make your first Sunday simple.</p>
      </div>
    </section>

    <section id="contact" class="contact-editorial interior-section">
      <div class="container-fluid declaration-shell">
        <div class="contact-editorial__grid">
          <aside class="contact-editorial__details">
            <p class="section-kicker">Start here</p>
            <h2>Reach out anytime.</h2>
            <div class="contact-detail-list">
              <div><span>Email</span><a href="mailto:hello@declaration.org">hello@declaration.org</a></div>
              <div><span>Phone</span><a href="tel:+12816614279">(281) 661-4279</a></div>
              <div><span>Sunday location</span><p>Snyder Elementary<br>28601 Birnham Woods Drive<br>Spring, TX 77386</p></div>
              <div><span>Mailing address</span><p>330 Rayford Road, Ste 369<br>Spring, TX 77386</p></div>
            </div>
          </aside>

          <div class="contact-editorial__form">
            <p class="section-kicker">Send a message</p>
            <h2>How can we help?</h2>
            <form action="forms/contact.php" method="post" class="php-email-form editorial-form">
              <div class="editorial-form__row">
                <label for="name">Name<input type="text" name="name" id="name" autocomplete="name" required></label>
                <label for="email">Email<input type="email" name="email" id="email" autocomplete="email" required></label>
              </div>
              <label for="subject">Subject<input type="text" name="subject" id="subject" required></label>
              <label for="message">Message<textarea name="message" id="message" rows="6" required></textarea></label>
              <div class="form-status">
                <div class="loading">Sending</div>
                <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div>
              </div>
              <button type="submit" class="button button--black">Send message</button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <section class="contact-map" aria-label="Map to Snyder Elementary">
      <iframe src="https://www.google.com/maps?q=28601+Birnham+Woods+Drive,+Spring,+TX+77386&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
