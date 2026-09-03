<?php
require_once __DIR__ . '/../includes/cms.php';
cms_start_session();

if (cms_has_admin()) {
    header('Location: /cms/login.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cms_verify_csrf();
    try {
        cms_create_admin((string) ($_POST['username'] ?? ''), (string) ($_POST['password'] ?? ''));
        cms_login((string) $_POST['username'], (string) $_POST['password']);
        header('Location: /cms/');
        exit;
    } catch (Throwable $exception) {
        $error = $exception->getMessage();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Set up Declaration CMS</title>
  <link rel="icon" type="image/png" href="/assets/img/declaration/Declaration-Church_website.png">
  <link rel="apple-touch-icon" href="/assets/img/declaration/Declaration-Church_website.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@500;600;700;800&family=Inter+Tight:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/cms/admin.css">
</head>
<body class="cms-auth">
  <main class="cms-auth__card">
    <div class="cms-auth__brand"><span>D</span> Declaration CMS</div>
    <p class="cms-eyebrow">First-time setup</p>
    <h1>Create the admin login.</h1>
    <p>This will be the only account used for the prototype.</p>
    <?php if ($error): ?><div class="cms-alert cms-alert--error"><?= cms_escape($error) ?></div><?php endif; ?>
    <form method="post" class="cms-form">
      <input type="hidden" name="csrf_token" value="<?= cms_escape(cms_csrf_token()) ?>">
      <label>Username
        <input type="text" name="username" autocomplete="username" required maxlength="100" value="<?= cms_escape($_POST['username'] ?? 'admin') ?>">
      </label>
      <label>Password
        <input type="password" name="password" autocomplete="new-password" required minlength="10">
        <small>Use at least 10 characters.</small>
      </label>
      <button class="cms-button cms-button--primary" type="submit">Create admin</button>
    </form>
  </main>
</body>
</html>
