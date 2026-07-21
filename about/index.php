<?php
$page_title = 'About';
$body_class = 'about-page declaration-interior';
$current_page = 'about';
$base_url = '/';
$meta_description = 'Learn the heart, beliefs, and values of Declaration Church in Spring, Texas.';
$canonical_url = 'https://www.declaration.org/about/';
include __DIR__ . '/../includes/header.php';
?>

    <section class="interior-hero interior-hero--about">
      <div class="interior-hero__media">
        <img src="assets/img/events/gallery-3.webp" alt="People gathered together at church">
      </div>
      <div class="interior-hero__veil"></div>
      <div class="container-fluid declaration-shell interior-hero__content">
        <p class="section-kicker section-kicker--light">This is Declaration</p>
        <h1>Jesus at the center.<br>People in the family.</h1>
        <p>We are a multi-denominational church in Spring, Texas helping people encounter and follow Jesus.</p>
      </div>
    </section>

    <section class="about-manifesto interior-section">
      <div class="container-fluid declaration-shell">
        <div class="interior-intro-grid">
          <p class="section-kicker">Our heart</p>
          <div>
            <h2>We want the real thing.</h2>
            <p class="lead-copy">A life with Jesus that is honest, rooted in Scripture, alive to the Holy Spirit, and shared with people who become family.</p>
            <div class="two-column-copy">
              <p>Declaration exists to help people encounter and follow Jesus. We believe church should be a place where people can bring their whole story, experience the presence of God, grow in freedom, and discover meaningful purpose.</p>
              <p>We hold unity on the essentials, liberty on the non-essentials, and love in everything. Whatever your background or season, there is room to take a next step here.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="belief-editorial section-black">
      <div class="container-fluid declaration-shell">
        <p class="section-kicker section-kicker--light">What shapes us</p>
        <div class="belief-editorial__header">
          <h2>Our devotions.</h2>
          <p>These are the practices and convictions that keep our attention on Jesus and shape the life we build together.</p>
        </div>
        <div class="belief-lines">
          <div><span>01</span><h3>Scripture</h3><p>&amp; the Holy Spirit</p></div>
          <div><span>02</span><h3>Prayer</h3><p>&amp; His Presence</p></div>
          <div><span>03</span><h3>Communion</h3><p>&amp; Community</p></div>
          <div><span>04</span><h3>Remembrance</h3><p>&amp; Redemption</p></div>
          <div><span>05</span><h3>Generosity</h3><p>&amp; Grace</p></div>
        </div>
        <a class="arrow-link arrow-link--light" href="https://www.declaration.org/what-we-believe" target="_blank" rel="noopener">Read what we believe <span aria-hidden="true">&#8599;</span></a>
      </div>
    </section>

    <section class="pastor-note">
      <div class="pastor-note__image">
        <img src="assets/img/declaration/holiding bread and cup_edited.jpg" alt="Bread and cup representing communion" loading="lazy">
      </div>
      <div class="pastor-note__copy">
        <p class="section-kicker">From our pastors</p>
        <h2>Welcome to the family.</h2>
        <blockquote>“Our desire is to build a church where people know Jesus deeply, find authentic community, and walk confidently in their God-given purpose.”</blockquote>
        <p>John and Kelly Sherrill lead Declaration with a heart for Jesus, people, prayer, and the presence of God.</p>
        <img class="pastor-signature" src="assets/img/declaration/John and Kelly-signature.svg" alt="John and Kelly Sherrill">
      </div>
    </section>

    <section class="interior-cta section-white">
      <div class="container-fluid declaration-shell">
        <p class="section-kicker">Come meet us</p>
        <h2>The best way to know Declaration is to experience a Sunday.</h2>
        <div class="interior-cta__actions">
          <a class="button button--black" href="index.php#visit">Plan your visit</a>
          <a class="arrow-link" href="index.php#next-steps">Explore next steps <span aria-hidden="true">&#8594;</span></a>
        </div>
      </div>
    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
