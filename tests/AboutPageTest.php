<?php
/**
 * Test for about/index.php
 *
 * Run: php tests/AboutPageTest.php
 */

$passed = 0;
$failed = 0;
$errors = [];

function assert_contains(string $haystack, string $needle, string $message): void {
    global $passed, $failed, $errors;
    if (str_contains($haystack, $needle)) {
        $passed++;
        echo "  PASS: {$message}\n";
    } else {
        $failed++;
        $errors[] = $message;
        echo "  FAIL: {$message}\n";
    }
}

function assert_not_contains(string $haystack, string $needle, string $message): void {
    global $passed, $failed, $errors;
    if (!str_contains($haystack, $needle)) {
        $passed++;
        echo "  PASS: {$message}\n";
    } else {
        $failed++;
        $errors[] = $message;
        echo "  FAIL: {$message}\n";
    }
}

function assert_matches(string $haystack, string $pattern, string $message): void {
    global $passed, $failed, $errors;
    if (preg_match($pattern, $haystack)) {
        $passed++;
        echo "  PASS: {$message}\n";
    } else {
        $failed++;
        $errors[] = $message;
        echo "  FAIL: {$message}\n";
    }
}

// ---- Setup ----
$_SERVER['REQUEST_URI'] = '/about/';

// Capture the rendered output
ob_start();
include __DIR__ . '/../about/index.php';
$html = ob_get_clean();

// ---- Tests ----

echo "=== Page Variables ===\n";
assert_contains($html, '<title>About - Eventia Bootstrap Template | Declaration Church</title>', 'Page title is set correctly');
assert_contains($html, 'class="about-page"', 'Body class is set to about-page');
assert_contains($html, '<base href="/">', 'Base URL defaults to /');

echo "\n=== SEO Meta Tags ===\n";
assert_matches($html, '/<meta name="description" content="[^"]+">/', 'Meta description is present');
assert_matches($html, '/<meta name="keywords" content="[^"]+">/', 'Meta keywords are present');
assert_contains($html, '<link rel="canonical" href="https://declarationchurch.com/about/">', 'Canonical URL includes /about/');
assert_contains($html, '<meta property="og:title"', 'Open Graph title is present');
assert_contains($html, '<meta property="og:description"', 'Open Graph description is present');
assert_contains($html, '<meta name="twitter:card" content="summary_large_image">', 'Twitter card meta is present');

echo "\n=== Navigation ===\n";
assert_matches($html, '/<a href="about\/"[^>]*class="active"/', 'About nav link is marked active');
assert_not_contains($html, '<a href="index.php" class="active"', 'Home nav link is NOT active');

echo "\n=== Page Title Section ===\n";
assert_matches($html, '/<h1>About(?: Us)?<\/h1>/', 'Page heading is About or About Us');
assert_contains($html, '<li><a href="index.php">Home</a></li>', 'Breadcrumb has Home link');
assert_contains($html, '<li class="current">About</li>', 'Breadcrumb shows current page');

echo "\n=== About Section Content ===\n";
assert_contains($html, 'id="about"', 'About section exists');
assert_contains($html, 'Annual Summit 2025', 'Intro banner has event name');
assert_contains($html, 'Shaping Tomorrow\'s Innovation', 'Intro banner has tagline');

echo "\n=== Stats ===\n";
assert_contains($html, '<span class="stat-number">4</span>', 'Days stat is present');
assert_contains($html, '<span class="stat-number">3,200+</span>', 'Guests stat is present');
assert_contains($html, '<span class="stat-number">95+</span>', 'Experts stat is present');
assert_contains($html, '<span class="stat-number">12</span>', 'Tracks stat is present');

echo "\n=== Feature Blocks ===\n";
assert_contains($html, 'Innovative Sessions', 'Feature: Innovative Sessions');
assert_contains($html, 'Global Networking', 'Feature: Global Networking');
assert_contains($html, 'Industry Recognition', 'Feature: Industry Recognition');

echo "\n=== Audience Cards ===\n";
$audiences = ['Engineers', 'Founders', 'Creatives', 'Analysts', 'Funders', 'Learners'];
foreach ($audiences as $audience) {
    assert_contains($html, "<h5>{$audience}</h5>", "Audience card: {$audience}");
}

echo "\n=== Internal Links ===\n";
assert_contains($html, 'href="schedule/"', 'Links to schedule page');
assert_contains($html, 'href="speakers/"', 'Links to speakers page');

echo "\n=== Page Structure ===\n";
assert_contains($html, '<!DOCTYPE html>', 'Starts with DOCTYPE');
assert_contains($html, '</html>', 'Ends with closing html tag');
assert_contains($html, '<header id="header"', 'Header is included');
assert_contains($html, '<footer id="footer"', 'Footer is included');
assert_contains($html, '<main class="main">', 'Main content wrapper exists');

echo "\n=== Structured Data ===\n";
assert_contains($html, 'application/ld+json', 'JSON-LD structured data is present');
assert_contains($html, '"@type": "Church"', 'Schema type is Church');

// ---- Summary ----
echo "\n" . str_repeat('=', 40) . "\n";
echo "Results: {$passed} passed, {$failed} failed\n";

if ($failed > 0) {
    echo "\nFailed tests:\n";
    foreach ($errors as $error) {
        echo "  - {$error}\n";
    }
    exit(1);
}

echo "\nAll tests passed!\n";
exit(0);
