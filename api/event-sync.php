<?php
/**
 * Refresh the local event calendar without delaying a public page response.
 * Freshness checks and the non-blocking lock live in cms_sync_planning_center_events().
 */

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, max-age=0');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['ok' => false, 'message' => 'Method not allowed.']);
    exit;
}

ignore_user_abort(true);
@set_time_limit(180);

try {
    require_once __DIR__ . '/../includes/planning-center.php';
    require_once __DIR__ . '/../includes/cms.php';
    $result = cms_sync_planning_center_events();
    echo json_encode(['ok' => true, 'result' => $result]);
} catch (Throwable $exception) {
    http_response_code(503);
    echo json_encode(['ok' => false, 'message' => 'Event refresh is temporarily unavailable.']);
}
