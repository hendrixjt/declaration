<?php
/**
 * Smoke test for the Declaration About page.
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

$_SERVER['REQUEST_URI'] = '/about/';
ob_start();
include __DIR__ . '/../about/index.php';
$html = ob_get_clean();

echo "=== About Page ===\n";
assert_contains($html, '<title>About | Declaration Church</title>', 'Page title is brand-specific');
assert_contains($html, 'class="about-page declaration-interior"', 'Interior page classes are present');
assert_contains($html, '<base href="/">', 'Base URL is correct');
assert_contains($html, '<link rel="canonical" href="https://www.declaration.org/about/">', 'Canonical URL is correct');
assert_contains($html, 'class="active">About</a>', 'About navigation state is active');
assert_contains($html, 'Jesus at the center.', 'Editorial hero content is present');
assert_contains($html, 'We want the real thing.', 'Declaration manifesto is present');
assert_contains($html, 'Scripture', 'Scripture devotion is present');
assert_contains($html, 'Prayer', 'Prayer devotion is present');
assert_contains($html, 'Communion', 'Communion devotion is present');
assert_contains($html, 'Generosity', 'Generosity devotion is present');
assert_contains($html, 'John and Kelly Sherrill', 'Lead pastor content is present');
assert_contains($html, 'application/ld+json', 'Church structured data is present');
assert_contains($html, '<footer id="footer"', 'Shared footer is present');
assert_not_contains($html, '<title>About - Eventia', 'Template branding is absent from the page title');
assert_not_contains($html, 'Lorem ipsum', 'Placeholder copy is absent');

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
