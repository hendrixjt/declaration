<?php
/**
 * Public rendering test for locally published CMS events.
 * Run: php tests/PublicEventsTest.php
 */

$databasePath = sys_get_temp_dir() . '/declaration-public-events-test-' . bin2hex(random_bytes(6)) . '.sqlite';
define('CMS_DSN', 'sqlite:' . $databasePath);
require_once __DIR__ . '/../includes/cms.php';

$passed = 0;
$failed = 0;

function public_event_assert(bool $condition, string $message): void
{
    global $passed, $failed;
    if ($condition) {
        $passed++;
        echo "  PASS: {$message}\n";
        return;
    }
    $failed++;
    echo "  FAIL: {$message}\n";
}

try {
    $eventId = cms_save_event([
        'title' => 'CMS Preview Night',
        'slug' => 'cms-preview-night',
        'summary' => 'See the Declaration event system in action.',
        'body' => 'A complete event detail page managed from the new CMS.',
        'starts_at' => '2030-09-12T19:00:00-05:00',
        'ends_at' => '2030-09-12T20:30:00-05:00',
        'location_name' => 'Snyder Elementary',
        'location_address' => '28601 Birnham Woods Drive',
        'image_url' => 'https://example.com/preview.jpg',
        'registration_url' => 'https://example.com/register',
        'registration_label' => 'Register',
        'status' => 'published',
        'is_featured' => '1',
    ]);

    echo "=== Public CMS Events ===\n";

    $_SERVER['REQUEST_URI'] = '/';
    ob_start();
    include __DIR__ . '/../index.php';
    $homepage = ob_get_clean();
    public_event_assert(str_contains($homepage, 'CMS Preview Night'), 'Homepage reads the published CMS event');
    public_event_assert(str_contains($homepage, '/events/cms-preview-night/'), 'Homepage links to the local event detail page');

    $_GET['slug'] = 'cms-preview-night';
    $_SERVER['REQUEST_URI'] = '/events/cms-preview-night/';
    ob_start();
    include __DIR__ . '/../event/index.php';
    $detail = ob_get_clean();
    public_event_assert(str_contains($detail, '<title>CMS Preview Night | Declaration Church</title>'), 'Event detail has the correct title');
    public_event_assert(str_contains($detail, 'See the Declaration event system in action.'), 'Event detail renders the summary');
    public_event_assert(str_contains($detail, 'Snyder Elementary'), 'Event detail renders the location');

    cms_delete_event($eventId);
} finally {
    if (is_file($databasePath)) {
        unlink($databasePath);
    }
}

echo "\nResults: {$passed} passed, {$failed} failed\n";
exit($failed > 0 ? 1 : 0);
