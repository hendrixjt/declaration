<?php
/**
 * Planning Center Registrations API helper.
 *
 * Fetches upcoming events and caches them locally to avoid rate limits.
 */

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
    if (!defined('PC_APP_ID') || !defined('PC_SECRET')) {
        return [];
    }

    $cache_file = __DIR__ . '/../cache/events.json';
    $cache_ttl = 1800; // 30 minutes

    // Return cached data if still fresh
    if (file_exists($cache_file)) {
        $cache_age = time() - filemtime($cache_file);
        if ($cache_age < $cache_ttl) {
            $cached = json_decode(file_get_contents($cache_file), true);
            if (is_array($cached)) {
                return array_slice($cached, 0, $limit);
            }
        }
    }

    $events = pc_fetch_events_from_api();

    // Write cache (create directory if needed)
    $cache_dir = dirname($cache_file);
    if (!is_dir($cache_dir)) {
        @mkdir($cache_dir, 0755, true);
    }
    @file_put_contents($cache_file, json_encode($events, JSON_PRETTY_PRINT));

    return array_slice($events, 0, $limit);
}

/**
 * Call the Planning Center Registrations API.
 *
 * @return array Parsed event data.
 */
function pc_fetch_events_from_api(): array
{
    $url = 'https://api.planningcenteronline.com/registrations/v2/events'
         . '?filter=unarchived,published&order=starts_at&per_page=20';

    $auth = base64_encode(PC_APP_ID . ':' . PC_SECRET);

    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "Authorization: Basic {$auth}\r\n"
                      . "Accept: application/json\r\n",
            'timeout' => 10,
            'ignore_errors' => true,
        ],
    ]);

    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        return [];
    }

    $data = json_decode($response, true);
    if (!isset($data['data']) || !is_array($data['data'])) {
        return [];
    }

    $events = [];
    foreach ($data['data'] as $item) {
        $attrs = $item['attributes'] ?? [];
        $events[] = [
            'id'               => $item['id'] ?? '',
            'name'             => $attrs['name'] ?? '',
            'description'      => $attrs['description'] ?? '',
            'starts_at'        => $attrs['starts_at'] ?? '',
            'ends_at'          => $attrs['ends_at'] ?? '',
            'logo_url'         => $attrs['logo_url'] ?? '',
            'registration_url' => $attrs['registration_url'] ?? '',
        ];
    }

    return $events;
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
