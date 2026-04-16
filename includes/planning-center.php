<?php
/**
 * Planning Center Registrations API helper.
 *
 * Fetches upcoming events and caches them locally to avoid rate limits.
 */

$GLOBALS['pc_last_error'] = $GLOBALS['pc_last_error'] ?? '';
$GLOBALS['pc_cache_version'] = 3;

if (!defined('PC_APP_ID') || !defined('PC_SECRET')) {
    $config_path = __DIR__ . '/config.php';
    if (file_exists($config_path)) {
        require_once $config_path;
    }
}

/**
 * Fetch upcoming events from Planning Center Registrations.
 *
 * @param int $limit Max number of events to return.
 * @return array Array of event objects, or empty array on failure.
 */
function pc_get_upcoming_events(int $limit = 6): array
{
    $cache_file = __DIR__ . '/../cache/events.json';
    $cache_ttl = defined('PC_CACHE_TTL') ? (int) PC_CACHE_TTL : 1800;

    if (!defined('PC_APP_ID') || !defined('PC_SECRET')) {
        pc_set_last_error('Planning Center credentials are not configured.');
        return pc_read_cached_events($cache_file, $limit, false, $cache_ttl);
    }

    // Return cached data if still fresh
    $cached_events = pc_read_cached_events($cache_file, $limit, true, $cache_ttl);
    if (!empty($cached_events)) {
        return $cached_events;
    }

    $events = pc_fetch_events_from_api();

    // Write cache (create directory if needed)
    $cache_dir = dirname($cache_file);
    if (!is_dir($cache_dir)) {
        @mkdir($cache_dir, 0755, true);
    }
    if (!empty($events)) {
        @file_put_contents($cache_file, json_encode([
            'version' => $GLOBALS['pc_cache_version'],
            'fetched_at' => gmdate(DATE_ATOM),
            'events' => $events,
        ], JSON_PRETTY_PRINT));
    }

    if (empty($events)) {
        return pc_read_cached_events($cache_file, $limit, false, $cache_ttl);
    }

    return array_slice($events, 0, $limit);
}

/**
 * Call the Planning Center Registrations API.
 *
 * @return array Parsed event data.
 */
function pc_fetch_events_from_api(): array
{
    $url = 'https://api.planningcenteronline.com/registrations/v2/signups'
         . '?include=signup_times&per_page=100';

    $events = [];
    $page_count = 0;
    $max_pages = 12;

    while (!empty($url) && $page_count < $max_pages) {
        $response = pc_request_json($url);
        if (!$response['ok']) {
            return [];
        }

        $data = $response['data'];
        if (!isset($data['data']) || !is_array($data['data'])) {
            pc_set_last_error('Planning Center returned an unexpected response.');
            return [];
        }

        $signup_times_by_id = [];
        foreach (($data['included'] ?? []) as $included) {
            if (($included['type'] ?? '') !== 'SignupTime') {
                continue;
            }
            $signup_times_by_id[$included['id'] ?? ''] = $included['attributes'] ?? [];
        }

        foreach ($data['data'] as $item) {
            $attrs = $item['attributes'] ?? [];
            $signup_time_ids = $item['relationships']['signup_times']['data'] ?? [];
            $primary_signup_time = pc_select_primary_signup_time($signup_time_ids, $signup_times_by_id);
            $registration_url = (string) ($attrs['new_registration_url'] ?? '');
            $event = [
                'id'               => $item['id'] ?? '',
                'name'             => trim((string) ($attrs['name'] ?? '')),
                'description'      => trim((string) ($attrs['description'] ?? '')),
                'starts_at'        => (string) ($primary_signup_time['starts_at'] ?? $attrs['open_at'] ?? ''),
                'ends_at'          => (string) ($primary_signup_time['ends_at'] ?? $attrs['close_at'] ?? ''),
                'logo_url'         => (string) ($attrs['logo_url'] ?? ''),
                'registration_url' => $registration_url,
                'public_url'       => pc_build_public_signup_url((string) ($item['id'] ?? ''), $registration_url),
                'open_at'          => (string) ($attrs['open_at'] ?? ''),
                'close_at'         => (string) ($attrs['close_at'] ?? ''),
            ];

            if (pc_event_is_upcoming($event)) {
                $events[] = $event;
            }
        }

        $url = isset($data['links']['next']) && is_string($data['links']['next']) ? $data['links']['next'] : '';
        $page_count++;
    }

    usort($events, static function (array $a, array $b): int {
        return strtotime($a['starts_at'] ?? '') <=> strtotime($b['starts_at'] ?? '');
    });

    return $events;
}

/**
 * Read cached events when available.
 */
function pc_read_cached_events(string $cache_file, int $limit, bool $require_fresh, int $cache_ttl): array
{
    if (!file_exists($cache_file)) {
        return [];
    }

    $cache_age = time() - (filemtime($cache_file) ?: time());
    if ($require_fresh && $cache_age >= $cache_ttl) {
        return [];
    }

    $cached = json_decode((string) file_get_contents($cache_file), true);
    $expected_version = (int) ($GLOBALS['pc_cache_version'] ?? 1);
    $cached_version = (int) ($cached['version'] ?? 1);
    if ($cached_version !== $expected_version) {
        return [];
    }

    $events = pc_extract_cached_events($cached);

    return array_slice($events, 0, $limit);
}

/**
 * Support both legacy cache payloads and versioned cache objects.
 */
function pc_extract_cached_events($cached): array
{
    if (!is_array($cached)) {
        return [];
    }

    if (isset($cached['events']) && is_array($cached['events'])) {
        return $cached['events'];
    }

    return pc_is_list_array($cached) ? $cached : [];
}

/**
 * Determine whether an event is still upcoming/current.
 */
function pc_event_is_upcoming(array $event): bool
{
    $starts_at = $event['starts_at'] ?? '';
    $ends_at = $event['ends_at'] ?? '';
    $open_at = $event['open_at'] ?? '';
    $close_at = $event['close_at'] ?? '';

    $start_ts = $starts_at ? strtotime($starts_at) : false;
    $end_ts = $ends_at ? strtotime($ends_at) : false;
    $open_ts = $open_at ? strtotime($open_at) : false;
    $close_ts = $close_at ? strtotime($close_at) : false;
    $now = time();

    if ($end_ts !== false) {
        return $end_ts >= $now;
    }

    if ($start_ts !== false) {
        return $start_ts >= $now;
    }

    if ($close_ts !== false) {
        return $close_ts >= $now;
    }

    if ($open_ts !== false) {
        return $open_ts >= $now;
    }

    return false;
}

/**
 * Issue a JSON request to the Planning Center API.
 *
 * @return array{ok: bool, status: int, data: array|null}
 */
function pc_request_json(string $url): array
{
    $auth = base64_encode(PC_APP_ID . ':' . PC_SECRET);
    $headers = [
        'Authorization: Basic ' . $auth,
        'Accept: application/json',
        'User-Agent: DeclarationChurchPrototype/1.0',
    ];

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 12,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FAILONERROR => false,
        ]);

        $body = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $curl_error = curl_error($ch);

        if ($body === false) {
            pc_set_last_error('Planning Center request failed: ' . $curl_error);
            return ['ok' => false, 'status' => $status, 'data' => null];
        }

        $data = json_decode($body, true);
        if ($status < 200 || $status >= 300) {
            pc_set_last_error(pc_extract_api_error_message($data, $status));
            return ['ok' => false, 'status' => $status, 'data' => is_array($data) ? $data : null];
        }

        return ['ok' => is_array($data), 'status' => $status, 'data' => is_array($data) ? $data : null];
    }

    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => implode("\r\n", $headers) . "\r\n",
            'timeout' => 12,
            'ignore_errors' => true,
        ],
    ]);

    $body = @file_get_contents($url, false, $context);
    if ($body === false) {
        pc_set_last_error('Planning Center request failed.');
        return ['ok' => false, 'status' => 0, 'data' => null];
    }

    $status = 0;
    foreach (($http_response_header ?? []) as $header_line) {
        if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $header_line, $matches)) {
            $status = (int) $matches[1];
            break;
        }
    }

    $data = json_decode($body, true);
    if ($status < 200 || $status >= 300) {
        pc_set_last_error(pc_extract_api_error_message($data, $status));
        return ['ok' => false, 'status' => $status, 'data' => is_array($data) ? $data : null];
    }

    return ['ok' => is_array($data), 'status' => $status, 'data' => is_array($data) ? $data : null];
}

function pc_extract_api_error_message($data, int $status): string
{
    if (is_array($data) && !empty($data['errors'][0]['detail'])) {
        return 'Planning Center API error (' . $status . '): ' . $data['errors'][0]['detail'];
    }

    return 'Planning Center API returned status ' . $status . '.';
}

function pc_set_last_error(string $message): void
{
    $GLOBALS['pc_last_error'] = $message;
}

function pc_get_last_error(): string
{
    return (string) ($GLOBALS['pc_last_error'] ?? '');
}

function pc_is_list_array(array $value): bool
{
    $expected_key = 0;
    foreach ($value as $key => $_) {
        if ($key !== $expected_key) {
            return false;
        }
        $expected_key++;
    }

    return true;
}

function pc_select_primary_signup_time(array $signup_time_refs, array $signup_times_by_id): array
{
    $future_times = [];
    $past_times = [];
    $now = time();

    foreach ($signup_time_refs as $ref) {
        $time_id = $ref['id'] ?? '';
        if ($time_id === '' || !isset($signup_times_by_id[$time_id])) {
            continue;
        }

        $signup_time = $signup_times_by_id[$time_id];
        $start_ts = !empty($signup_time['starts_at']) ? strtotime($signup_time['starts_at']) : false;
        $end_ts = !empty($signup_time['ends_at']) ? strtotime($signup_time['ends_at']) : false;

        if ($end_ts !== false && $end_ts >= $now) {
            $future_times[] = $signup_time;
            continue;
        }

        if ($start_ts !== false && $start_ts >= $now) {
            $future_times[] = $signup_time;
            continue;
        }

        $past_times[] = $signup_time;
    }

    if (!empty($future_times)) {
        usort($future_times, static function (array $a, array $b): int {
            return strtotime($a['starts_at'] ?? '') <=> strtotime($b['starts_at'] ?? '');
        });
        return $future_times[0];
    }

    if (!empty($past_times)) {
        usort($past_times, static function (array $a, array $b): int {
            return strtotime($b['starts_at'] ?? '') <=> strtotime($a['starts_at'] ?? '');
        });
        return $past_times[0];
    }

    return [];
}

function pc_build_public_signup_url(string $event_id, string $registration_url): string
{
    if ($registration_url !== '') {
        return preg_replace('#/reservations/new/?$#', '', $registration_url) ?? $registration_url;
    }

    if ($event_id === '') {
        return '';
    }

    return 'https://declarationchurch.churchcenter.com/registrations/events/' . rawurlencode($event_id);
}

/**
 * Format an ISO 8601 date string for display.
 *
 * @param string $iso_date
 * @return string e.g. "Apr 22, 2026"
 */
function pc_format_date(string $iso_date): string
{
    if (empty($iso_date)) {
        return '';
    }
    $ts = strtotime($iso_date);
    if ($ts === false) {
        return '';
    }
    return date('M j, Y', $ts);
}

/**
 * Format an ISO 8601 date string for display in the church timezone.
 *
 * @param string $iso_date
 * @return string e.g. "6:30 PM"
 */
function pc_format_time(string $iso_date): string
{
    if (empty($iso_date)) {
        return '';
    }

    try {
        $dt = new DateTime($iso_date);
        $dt->setTimezone(new DateTimeZone('America/Chicago'));
        return $dt->format('g:i A');
    } catch (Exception $e) {
        return '';
    }
}

/**
 * Build a date range string from start/end dates.
 *
 * @param string $start ISO date
 * @param string $end   ISO date
 * @return string e.g. "Apr 22 - 24, 2026" or "Apr 22, 2026"
 */
function pc_date_range(string $start, string $end): string
{
    if (empty($start)) {
        return '';
    }

    $start_ts = strtotime($start);
    if ($start_ts === false) {
        return '';
    }

    if (empty($end)) {
        return date('M j, Y', $start_ts);
    }

    $end_ts = strtotime($end);
    if ($end_ts === false) {
        return date('M j, Y', $start_ts);
    }

    // Same day
    if (date('Y-m-d', $start_ts) === date('Y-m-d', $end_ts)) {
        return date('M j, Y', $start_ts);
    }

    // Same month & year
    if (date('Y-m', $start_ts) === date('Y-m', $end_ts)) {
        return date('M j', $start_ts) . ' - ' . date('j, Y', $end_ts);
    }

    // Different months
    return date('M j', $start_ts) . ' - ' . date('M j, Y', $end_ts);
}
