<?php
$o2_title = 'Next Steps | Declaration Church — Concept 02';
$o2_description = 'Take your next step at Declaration through DNA, Groups, and Serve Teams.';
$o2_current = 'next-steps';
require __DIR__ . '/../includes/data.php';
require __DIR__ . '/../includes/header.php';
?>

  <main id="main">
    <section class="o2-interior-hero o2-next-hero">
      <div class="o2-interior-hero__title" data-reveal>
        <p class="o2-index">02 / Next Steps</p>
        <h1>Start where you are.<br><em>Keep moving.</em></h1>
        <p>Following Jesus is a journey. You do not need everything figured out to take one meaningful step.</p>
      </div>
      <figure class="o2-next-hero__image" data-parallax="-0.055">
        <img src="/assets/img/declaration/next-dna.jpg" alt="People connecting at Declaration" width="1400" height="900">
      </figure>
      <p class="o2-interior-hero__note">DNA · Groups · Teams</p>
    </section>

    <section class="o2-steps-detail">
<?php foreach ($o2_steps as $step): ?>
      <article class="o2-step-detail <?= htmlspecialchars($step['class']) ?>">
        <div class="o2-step-detail__heading">
          <p class="o2-index"><?= htmlspecialchars($step['number']) ?> / <?= htmlspecialchars($step['label']) ?></p>
          <h2><?= htmlspecialchars($step['title']) ?></h2>
        </div>
        <figure data-parallax="<?= $step['number'] === '02' ? '0.045' : '-0.035' ?>">
          <img src="<?= htmlspecialchars($step['image']) ?>" alt="" loading="lazy" width="1400" height="900">
        </figure>
        <div class="o2-step-detail__copy" data-reveal>
          <p><?= htmlspecialchars($step['copy']) ?></p>
          <p><?= htmlspecialchars($step['detail']) ?></p>
          <a class="o2-link" href="<?= htmlspecialchars($step['url']) ?>"<?= str_starts_with($step['url'], 'http') ? ' target="_blank" rel="noopener"' : '' ?>><?= htmlspecialchars($step['cta']) ?> <span aria-hidden="true">↗</span></a>
        </div>
      </article>
<?php endforeach; ?>
    </section>

    <section class="o2-page-close">
      <p class="o2-index">Not sure where to begin?</p>
      <h2>Your first step<br><em>can be a Sunday.</em></h2>
      <a class="o2-button" href="/option-2/visit/">Plan your visit <span aria-hidden="true">↗</span></a>
    </section>
  </main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
