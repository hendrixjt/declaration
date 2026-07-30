<?php
require_once __DIR__ . '/../includes/cms.php';
require_once __DIR__ . '/../includes/planning-center.php';
cms_require_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}
cms_verify_csrf();

$events = pc_fetch_events_from_api();
if (!$events) {
    $message = pc_get_last_error() ?: 'Planning Center returned no upcoming events.';
    header('Location: /cms/?notice=import-error&message=' . rawurlencode($message));
    exit;
}

$result = cms_import_planning_center_events($events);
$query = http_build_query([
    'notice' => 'imported',
    'added' => $result['inserted'],
    'updated' => $result['updated'],
    'skipped' => $result['skipped'],
]);
header('Location: /cms/?' . $query);
exit;
