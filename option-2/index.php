<?php
$o2_title = 'Declaration Church — Spacious Concept';
$o2_description = 'A spacious, movement-led concept for Declaration Church in Spring, Texas.';
$o2_current = 'home';
require __DIR__ . '/includes/data.php';
require __DIR__ . '/includes/header.php';
?>

  <main id="main">
    <section class="o2-hero" aria-labelledby="o2-hero-title">
      <div class="o2-hero__sticky">
        <figure class="o2-hero__image o2-hero__image--main" data-parallax="-0.055">
          <img src="/uploads/media/20260814-90faa7525f6158be-1917w.webp" alt="Declaration Church gathered on a Sunday morning" width="1917" height="1278">
        </figure>
        <figure class="o2-hero__image o2-hero__image--small" data-parallax="0.09">
          <img src="/assets/img/declaration/team/john-kelly.jpg" alt="Pastors John and Kelly Sherrill" width="806" height="668">
          <figcaption>Led by John + Kelly Sherrill</figcaption>
        </figure>

        <div class="o2-hero__title" data-parallax="-0.025">
          <p>Declaration Church</p>
          <h1 id="o2-hero-title">For Jesus.<br><em>For people.</em></h1>
        </div>

        <div class="o2-hero__service">
          <p><span>Sundays</span> 9:00 + 11:00 AM</p>
          <a href="https://www.google.com/maps/search/?api=1&amp;query=28601%20Birnham%20Woods%20Drive%2C%20Spring%2C%20TX%2077386" target="_blank" rel="noopener">Snyder Elementary <span aria-hidden="true">↗</span></a>
        </div>
        <p class="o2-hero__side" aria-hidden="true">Encounter Jesus · Follow Jesus</p>
      </div>
    </section>

    <section id="welcome" class="o2-welcome">
      <div class="o2-welcome__orbit" aria-hidden="true">You belong here · You belong here ·</div>
      <figure class="o2-welcome__small" data-parallax="0.08" data-reveal>
        <img src="/assets/img/declaration/team/rachael-santos.jpg" alt="A member of the Declaration team" loading="lazy" width="806" height="668">
      </figure>
      <div class="o2-welcome__copy" data-reveal>
        <p class="o2-index">01 / Welcome home</p>
        <h2>A church family<br>with room for <em>your story.</em></h2>
        <div>
          <p>Declaration is a multi-denominational church in Spring, Texas. We are helping people encounter and follow Jesus—and creating a place where every generation can belong, grow, and live with purpose.</p>
          <a class="o2-link" href="/about/">Meet Declaration <span aria-hidden="true">→</span></a>
        </div>
      </div>
    </section>

    <section id="pathway" class="o2-pathway" aria-labelledby="o2-pathway-title">
      <div class="o2-pathway__intro">
        <p class="o2-index">02 / Your next step</p>
        <h2 id="o2-pathway-title">Keep<br><em>moving.</em></h2>
        <p>Start where you are. Take one clear step toward the people, purpose, and life God has for you.</p>
      </div>

      <div class="o2-pathway__stack">
<?php foreach ($o2_steps as $step): ?>
        <article class="o2-step <?= htmlspecialchars($step['class']) ?>" data-stack-card>
          <div class="o2-step__number"><?= htmlspecialchars($step['number']) ?></div>
          <div class="o2-step__copy">
            <p><?= htmlspecialchars($step['label']) ?></p>
            <h3><?= htmlspecialchars($step['title']) ?></h3>
            <p><?= htmlspecialchars($step['copy']) ?></p>
            <a class="o2-link" href="<?= htmlspecialchars($step['url']) ?>"<?= str_starts_with($step['url'], 'http') ? ' target="_blank" rel="noopener"' : '' ?>><?= htmlspecialchars($step['cta']) ?> <span aria-hidden="true">↗</span></a>
          </div>
          <figure>
            <img src="<?= htmlspecialchars($step['image']) ?>" alt="" loading="lazy" width="1400" height="900">
          </figure>
        </article>
<?php endforeach; ?>
      </div>
    </section>

    <section id="family" class="o2-family">
      <div class="o2-family__heading" data-reveal>
        <p class="o2-index">03 / Life together</p>
        <h2>Known.<br><em>Not anonymous.</em></h2>
      </div>
      <figure class="o2-family__image o2-family__image--wide" data-parallax="-0.045">
        <img src="/assets/img/declaration/next-groups.jpg" alt="People building community at Declaration" loading="lazy" width="1400" height="900">
      </figure>
      <figure class="o2-family__image o2-family__image--portrait" data-parallax="0.075">
        <img src="/assets/img/declaration/kids-classroom.jpg" alt="Children learning together at Declaration Kids" loading="lazy" width="1600" height="1066">
      </figure>
      <div class="o2-family__copy" data-reveal>
        <p>Faith grows in community. Find meaningful relationships, a place for your family, and people who will walk with you beyond the weekend.</p>
        <div>
          <a href="/kids/">Kids <span>↗</span></a>
          <a href="/next-steps/">Groups <span>↗</span></a>
          <a href="/next-steps/#path">Serve Teams <span>↗</span></a>
        </div>
      </div>
    </section>

    <section id="sunday" class="o2-sunday">
      <div class="o2-sunday__marquee" aria-hidden="true">
        <div>THIS SUNDAY · COME AS YOU ARE · THIS SUNDAY · COME AS YOU ARE ·</div>
      </div>
      <div class="o2-sunday__inner">
        <p class="o2-index">Your first visit</p>
        <h2>Sunday can<br><em>feel simple.</em></h2>
        <p>Friendly faces will help you find your way, get your family settled, and make room for you to experience a Sunday at Declaration.</p>
        <a class="o2-button" href="/visit/">Plan your visit <span aria-hidden="true">↗</span></a>
      </div>
    </section>
  </main>

<?php require __DIR__ . '/includes/footer.php'; ?>
