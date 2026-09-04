<?php
$o2_title = $o2_title ?? 'Declaration Church';
$o2_description = $o2_description ?? 'Declaration Church in Spring, Texas.';
$o2_current = $o2_current ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($o2_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($o2_description) ?>">
  <meta name="robots" content="noindex, nofollow">
  <link rel="icon" type="image/png" href="/assets/img/declaration/Declaration-Church_website.png?v=2">
  <link rel="apple-touch-icon" href="/assets/img/declaration/Declaration-Church_website.png?v=2">
  <link rel="stylesheet" href="/assets/css/option-2.css?v=<?= filemtime(__DIR__ . '/../../assets/css/option-2.css') ?>">
</head>
<body class="o2-page o2-page--<?= htmlspecialchars($o2_current ?: 'home') ?>">
  <a class="o2-skip" href="#main">Skip to content</a>

  <header class="o2-header" data-header>
    <a class="o2-brand" href="/option-2/" aria-label="Declaration Church home">
      <img src="/assets/img/declaration/Declaration-Logo-1080.png" alt="Declaration Church">
    </a>
    <p>Spring, Texas</p>
    <button class="o2-menu-toggle" type="button" aria-expanded="false" aria-controls="o2-nav" aria-label="Open navigation" data-menu-toggle>
      <span></span><span></span>
    </button>
    <nav id="o2-nav" aria-label="Primary navigation" data-menu>
      <a href="/option-2/about/"<?= $o2_current === 'about' ? ' aria-current="page"' : '' ?>>About</a>
      <a href="/option-2/next-steps/"<?= $o2_current === 'next-steps' ? ' aria-current="page"' : '' ?>>Next Steps</a>
      <a class="o2-header__visit" href="/option-2/visit/"<?= $o2_current === 'visit' ? ' aria-current="page"' : '' ?>>Plan a visit <span aria-hidden="true">↗</span></a>
    </nav>
  </header>
