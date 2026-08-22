  </main>

  <footer id="footer" class="declaration-footer">
    <div class="container-fluid declaration-shell">
      <div class="declaration-footer__top">
        <a href="index.php" class="declaration-footer__brand">Declaration</a>
        <p>For Jesus.<br>For People.</p>
      </div>
      <div class="declaration-footer__grid">
        <div>
          <span>Gather with us</span>
          <p>Sundays at 9:00 + 11:00am<br>Snyder Elementary<br>28601 Birnham Woods Drive<br>Spring, TX 77386</p>
        </div>
        <nav aria-label="Footer navigation">
          <span>Explore</span>
          <a href="index.php#visit">Plan a Visit</a>
          <a href="index.php#next-steps">Next Steps</a>
          <a href="events/">Events</a>
          <a href="about/">About</a>
          <a href="dna/">DNA</a>
          <a href="contact/">Contact</a>
          <a href="https://www.declaration.org/groups" target="_blank" rel="noopener">Groups</a>
          <a href="https://www.declaration.org/give" target="_blank" rel="noopener">Give</a>
        </nav>
        <div>
          <span>Get in touch</span>
          <a href="mailto:hello@declaration.org">hello@declaration.org</a>
          <a href="tel:+12816614279">(281) 661-4279</a>
        </div>
      </div>
      <div class="declaration-footer__bottom">
        <span>&copy; <?= date('Y') ?> Declaration Church</span>
        <span>Spring, Texas</span>
      </div>
    </div>
  </footer>

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center" aria-label="Back to top"><i class="bi bi-arrow-up-short"></i></a>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <?php if (!empty($enable_background_event_sync)): ?>
  <span hidden data-event-sync-endpoint="/api/event-sync.php"></span>
  <?php endif; ?>
  <?php
  $main_js_path = __DIR__ . '/../assets/js/main.js';
  $main_js_version = is_file($main_js_path) ? (string) filemtime($main_js_path) : '1';
  ?>
  <script src="assets/js/main.js?v=<?php echo htmlspecialchars($main_js_version); ?>"></script>
</body>
</html>
