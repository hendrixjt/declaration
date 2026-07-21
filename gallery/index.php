<?php
$page_title = 'Gallery';
$body_class = 'gallery-page declaration-interior';
$current_page = 'gallery';
$base_url = '/';
$meta_description = 'A glimpse of worship, community, and life together at Declaration Church.';
$canonical_url = 'https://www.declaration.org/gallery/';
$gallery_images = [
    ['file' => 'gallery-1.webp', 'alt' => 'Declaration church community'],
    ['file' => 'gallery-2.webp', 'alt' => 'Life together at Declaration'],
    ['file' => 'gallery-3.webp', 'alt' => 'People gathering at church'],
    ['file' => 'gallery-4.webp', 'alt' => 'Worship and prayer gathering'],
    ['file' => 'gallery-5.webp', 'alt' => 'Declaration church family'],
    ['file' => 'gallery-6.webp', 'alt' => 'People connecting at Declaration'],
    ['file' => 'gallery-7.webp', 'alt' => 'Community at Declaration Church'],
    ['file' => 'gallery-8.webp', 'alt' => 'A Declaration gathering'],
];
include __DIR__ . '/../includes/header.php';
?>

    <section class="interior-hero interior-hero--gallery">
      <div class="interior-hero__media">
        <img src="assets/img/events/gallery-1.webp" alt="Declaration Church gathering">
      </div>
      <div class="interior-hero__veil"></div>
      <div class="container-fluid declaration-shell interior-hero__content">
        <p class="section-kicker section-kicker--light">A glimpse inside</p>
        <h1>Life at<br>Declaration.</h1>
        <p>Worship, prayer, friendship, joy, and the ordinary moments that become church family.</p>
      </div>
    </section>

    <section class="gallery-editorial interior-section section-white">
      <div class="container-fluid declaration-shell">
        <div class="gallery-editorial__intro">
          <p class="section-kicker">Together is better</p>
          <h2>More than a place we go. A people we belong to.</h2>
        </div>
        <div class="gallery-editorial__grid">
<?php foreach ($gallery_images as $index => $image): ?>
          <a href="assets/img/events/<?= htmlspecialchars($image['file']) ?>" class="gallery-editorial__item glightbox" title="Life at Declaration">
            <img src="assets/img/events/<?= htmlspecialchars($image['file']) ?>" alt="<?= htmlspecialchars($image['alt']) ?>" loading="lazy">
            <span><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?> <i class="bi bi-arrows-angle-expand"></i></span>
          </a>
<?php endforeach; ?>
        </div>
      </div>
    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
