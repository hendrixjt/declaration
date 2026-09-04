<?php
$o2_title = 'About | Declaration Church — Concept 02';
$o2_description = 'Meet Declaration Church, explore our vision and beliefs, and get to know our pastors and staff.';
$o2_current = 'about';
require __DIR__ . '/../includes/data.php';
require __DIR__ . '/../includes/header.php';
?>

  <main id="main">
    <section class="o2-interior-hero o2-about-hero">
      <div class="o2-interior-hero__title" data-reveal>
        <p class="o2-index">01 / About Declaration</p>
        <h1>A house for Jesus.<br><em>A family for people.</em></h1>
      </div>
      <figure class="o2-about-hero__main" data-parallax="-0.05">
        <img src="/uploads/media/20260814-90faa7525f6158be-1917w.webp" alt="Declaration Church gathered together" width="1917" height="1278">
      </figure>
      <figure class="o2-about-hero__small" data-parallax="0.08">
        <img src="/assets/img/declaration/team/john-kelly.jpg" alt="Pastors John and Kelly Sherrill" width="806" height="668">
      </figure>
      <p class="o2-interior-hero__note">Spring, Texas · Multi-denominational · For Jesus and for people</p>
    </section>

    <section class="o2-about-vision">
      <p class="o2-index">Our vision</p>
      <div data-reveal>
        <h2>Encounter Jesus.<br>Follow Jesus.<br><em>Help others do the same.</em></h2>
        <p>We believe church should be a spiritual family where people can bring their whole story, experience the presence of God, and discover the freedom and purpose found in Jesus.</p>
      </div>
    </section>

    <section class="o2-beliefs">
      <div class="o2-beliefs__heading">
        <p class="o2-index">What shapes us</p>
        <h2>Belief<br><em>becomes life.</em></h2>
        <p>Unity on the essentials. Liberty on the non-essentials. Love in everything.</p>
      </div>
      <div class="o2-beliefs__lists">
        <div>
          <p class="o2-index">We are devoted to</p>
          <ol>
            <li><span>01</span><strong>Scripture</strong><em>&amp; the Holy Spirit</em></li>
            <li><span>02</span><strong>Prayer</strong><em>&amp; His Presence</em></li>
            <li><span>03</span><strong>Communion</strong><em>&amp; Community</em></li>
            <li><span>04</span><strong>Remembrance</strong><em>&amp; Redemption</em></li>
            <li><span>05</span><strong>Generosity</strong><em>&amp; Grace</em></li>
          </ol>
        </div>
        <div>
          <p class="o2-index">We desire</p>
          <ol>
            <li><span>01</span><strong>Authenticity</strong><em>over appearance</em></li>
            <li><span>02</span><strong>Intimacy</strong><em>over intellect</em></li>
            <li><span>03</span><strong>Passion</strong><em>over performance</em></li>
            <li><span>04</span><strong>Kingdom</strong><em>over consumerism</em></li>
            <li><span>05</span><strong>Service</strong><em>over selfishness</em></li>
          </ol>
        </div>
      </div>
    </section>

    <section class="o2-pastors">
      <figure data-parallax="-0.04">
        <img src="/assets/img/declaration/team/john-kelly.jpg" alt="John and Kelly Sherrill with their family" loading="lazy" width="806" height="668">
      </figure>
      <div data-reveal>
        <p class="o2-index">Lead pastors</p>
        <h2>John + Kelly<br><em>Sherrill</em></h2>
        <p>John and Kelly lead Declaration with a heart for Jesus, people, prayer, and the presence of God. They believe the local church should be a spiritual family where lives are changed and every generation is invited into God’s purpose.</p>
        <blockquote>“God is just getting started. Our best days are yet to come.”</blockquote>
      </div>
    </section>

    <section class="o2-team">
      <div class="o2-team__heading">
        <p class="o2-index">Meet the team</p>
        <h2>People<br><em>serving people.</em></h2>
      </div>
      <div class="o2-team__grid">
<?php foreach ($o2_staff as $index => $person): ?>
        <article class="o2-team-card<?= $index % 3 === 1 ? ' o2-team-card--lower' : '' ?>" data-reveal>
          <img src="/assets/img/declaration/team/<?= htmlspecialchars($person['image']) ?>" alt="<?= htmlspecialchars($person['name']) ?>" loading="lazy" width="806" height="668">
          <h3><?= htmlspecialchars($person['name']) ?></h3>
          <p><?= htmlspecialchars($person['role']) ?></p>
        </article>
<?php endforeach; ?>
      </div>
    </section>

    <section class="o2-page-close o2-page-close--blue">
      <p class="o2-index">Come meet us</p>
      <h2>There is room<br><em>for you here.</em></h2>
      <a class="o2-button" href="/option-2/visit/">Plan your visit <span aria-hidden="true">↗</span></a>
    </section>
  </main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
