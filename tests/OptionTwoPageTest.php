<?php
/**
 * Smoke tests for the isolated Option 2 homepage concept.
 */

$failures = 0;
$passes = 0;

function option_two_assert_contains(string $haystack, string $needle, string $label): void
{
    global $failures, $passes;
    if (str_contains($haystack, $needle)) {
        echo "  PASS: {$label}\n";
        $passes++;
        return;
    }
    echo "  FAIL: {$label}\n";
    $failures++;
}

ob_start();
include __DIR__ . '/../option-2/index.php';
$html = ob_get_clean();
ob_start();
include __DIR__ . '/../option-2/about/index.php';
$about = ob_get_clean();
ob_start();
include __DIR__ . '/../option-2/next-steps/index.php';
$nextSteps = ob_get_clean();
ob_start();
include __DIR__ . '/../option-2/visit/index.php';
$visit = ob_get_clean();
$css = file_get_contents(__DIR__ . '/../assets/css/option-2.css');

echo "=== Option 2 Homepage ===\n";
option_two_assert_contains($html, '<meta name="robots" content="noindex, nofollow">', 'Prototype is excluded from search indexing');
option_two_assert_contains($html, '/assets/css/option-2.css', 'Option 2 uses isolated CSS');
option_two_assert_contains($html, '/assets/js/option-2.js', 'Option 2 uses isolated motion JavaScript');
option_two_assert_contains($html, 'For Jesus.', 'Declaration hero language is present');
option_two_assert_contains($html, 'Start with DNA.', 'DNA is the first pathway card');
option_two_assert_contains($html, 'Grow in community.', 'Groups pathway is present');
option_two_assert_contains($html, 'Bring what you carry.', 'Serve Teams pathway is present');
option_two_assert_contains($html, 'data-stack-card', 'Sticky stack cards are present');
option_two_assert_contains($html, 'data-parallax', 'Parallax hooks are present');
option_two_assert_contains($css, 'prefers-reduced-motion', 'Reduced-motion support is present in page assets');
option_two_assert_contains($html, '/option-2/about/', 'Homepage About navigation stays in Option 2');
option_two_assert_contains($html, '/option-2/next-steps/', 'Homepage Next Steps navigation stays in Option 2');
option_two_assert_contains($html, '/option-2/visit/', 'Homepage visit navigation stays in Option 2');
option_two_assert_contains($html, 'Declaration-Church_website.png', 'Declaration favicon replaces the template icon');

echo "\n=== Option 2 Interior Pages ===\n";
option_two_assert_contains($about, 'A house for Jesus.', 'About page has the Option 2 hero');
option_two_assert_contains($about, 'Meet the team', 'About page includes staff');
option_two_assert_contains($about, 'aria-current="page">About</a>', 'About navigation state is active');
option_two_assert_contains($nextSteps, 'Start where you are.', 'Next Steps page has the Option 2 hero');
option_two_assert_contains($nextSteps, 'DNA introduces Declaration', 'Next Steps page has expanded pathway content');
option_two_assert_contains($nextSteps, 'aria-current="page">Next Steps</a>', 'Next Steps navigation state is active');
option_two_assert_contains($visit, 'A morning', 'Visit page includes Sunday expectations');
option_two_assert_contains($visit, 'Declaration Kids', 'Visit page includes family information');
option_two_assert_contains($visit, 'aria-current="page">Plan a visit', 'Visit navigation state is active');

echo "\nResults: {$passes} passed, {$failures} failed\n";
exit($failures > 0 ? 1 : 0);
