<?php
/**
 * Integration test for the lightweight Declaration CMS.
 * Run: php tests/CmsTest.php
 */

$databasePath = sys_get_temp_dir() . '/declaration-cms-test-' . bin2hex(random_bytes(6)) . '.sqlite';
define('CMS_DSN', 'sqlite:' . $databasePath);
require_once __DIR__ . '/../includes/cms.php';
cms_start_session();

$passed = 0;
$failed = 0;

function cms_test_assert(bool $condition, string $message): void
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

echo "=== Declaration CMS ===\n";

try {
    cms_test_assert(!cms_has_admin(), 'Fresh CMS has no admin');
    cms_create_admin('admin', 'declaration-test-password');
    cms_test_assert(cms_has_admin(), 'Single admin can be created');
    cms_test_assert(cms_login('admin', 'declaration-test-password'), 'Admin can sign in');
    cms_test_assert(!cms_login('admin', 'incorrect-password'), 'Incorrect password is rejected');

    $import = cms_import_planning_center_events([
        [
            'id' => 'pc-101',
            'name' => 'Declaration Test Gathering',
            'description' => '<p>A test event imported from Planning Center.</p>',
            'starts_at' => '2030-08-05T18:30:00-05:00',
            'ends_at' => '2030-08-05T20:00:00-05:00',
            'logo_url' => 'https://example.com/event.jpg',
            'public_url' => 'https://example.com/register',
        ],
    ]);
    cms_test_assert($import['inserted'] === 1, 'Planning Center event imports as visible');

    $events = cms_get_events_for_admin();
    cms_test_assert(count($events) === 1 && $events[0]['status'] === 'published', 'Imported event appears automatically');

    $id = (int) $events[0]['id'];
    cms_save_event([
        'title' => 'Declaration Website Gathering',
        'slug' => 'declaration-test-gathering',
        'summary' => 'A local event summary.',
        'body' => 'Full local event details.',
        'starts_at' => '2030-08-05T19:00:00-05:00',
        'ends_at' => '2030-08-05T20:00:00-05:00',
        'location_name' => 'Snyder Elementary',
        'location_address' => '28601 Birnham Woods Drive',
        'image_url' => 'https://example.com/event.jpg',
        'registration_url' => 'https://example.com/register',
        'registration_label' => 'Save my place',
        'status' => 'published',
        'is_featured' => '1',
    ], $id);

    $publicEvents = cms_get_published_events(3);
    cms_test_assert(count($publicEvents) === 1, 'Published event appears in public query');
    cms_test_assert($publicEvents[0]['public_url'] === '/events/declaration-test-gathering/', 'Public event uses local detail URL');

    $secondImport = cms_import_planning_center_events([
        [
            'id' => 'pc-101',
            'name' => 'Planning Center Changed Title',
            'description' => 'Changed remotely',
            'starts_at' => '2030-08-05T18:30:00-05:00',
            'ends_at' => '2030-08-05T20:00:00-05:00',
        ],
    ]);
    $preserved = cms_get_event($id);
    cms_test_assert($secondImport['updated'] === 1, 'Re-import updates fields that still belong to Planning Center');
    cms_test_assert($preserved['title'] === 'Declaration Website Gathering', 'Local title remains authoritative');
    cms_test_assert($preserved['body'] === cms_sanitize_rich_text('Full local event details.'), 'Local description remains authoritative');

    cms_save_event([
        'title' => $preserved['title'],
        'slug' => $preserved['slug'],
        'summary' => $preserved['summary'],
        'body' => $preserved['body'],
        'starts_at' => $preserved['starts_at'],
        'ends_at' => $preserved['ends_at'],
        'location_name' => $preserved['location_name'],
        'location_address' => $preserved['location_address'],
        'image_url' => $preserved['image_url'],
        'registration_url' => $preserved['registration_url'],
        'registration_label' => $preserved['registration_label'],
        'status' => 'draft',
        'is_featured' => '1',
    ], $id);
    cms_import_planning_center_events([[
        'id' => 'pc-101',
        'name' => 'Planning Center Changed Again',
        'description' => 'Changed remotely again',
        'starts_at' => '2030-08-06T18:30:00-05:00',
        'ends_at' => '2030-08-06T20:00:00-05:00',
    ]]);
    $hidden = cms_get_event($id);
    cms_test_assert($hidden['status'] === 'draft', 'Hidden Planning Center event stays hidden after sync');
    cms_test_assert($hidden['starts_at'] === '2030-08-05T19:00:00-05:00', 'Locally changed date stays overridden');

    cms_reset_planning_center_overrides($id);
    $reset = cms_get_event($id);
    cms_test_assert($reset['status'] === 'published', 'Reset makes a Planning Center event visible');
    cms_test_assert($reset['title'] === 'Planning Center Changed Again', 'Reset restores Planning Center content');

    $safeBody = cms_sanitize_rich_text(
        '<p><strong>Formatted</strong> description.</p><script>alert(1)</script>'
        . '<p><a href="javascript:alert(1)" onclick="alert(1)">Unsafe link</a></p>'
        . '<ul><li>Useful list</li></ul>'
    );
    cms_test_assert(str_contains($safeBody, '<strong>Formatted</strong>'), 'Rich text formatting is preserved');
    cms_test_assert(str_contains($safeBody, '<ul><li>Useful list</li></ul>'), 'Rich text lists are preserved');
    cms_test_assert(!str_contains($safeBody, '<script'), 'Unsafe rich text elements are removed');
    cms_test_assert(!str_contains($safeBody, 'javascript:'), 'Unsafe rich text links are removed');
    cms_test_assert(!str_contains($safeBody, 'onclick'), 'Rich text event attributes are removed');

    cms_delete_event($id);
    cms_test_assert(count(cms_get_events_for_admin()) === 0, 'Event can be deleted');
} finally {
    if (is_file($databasePath)) {
        unlink($databasePath);
    }
}

echo "\nResults: {$passed} passed, {$failed} failed\n";
exit($failed > 0 ? 1 : 0);
