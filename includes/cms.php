<?php
/**
 * Lightweight Declaration CMS services.
 *
 * The prototype defaults to a protected SQLite file so it can run without
 * additional hosting setup. Define CMS_DSN, CMS_DB_USER, and CMS_DB_PASSWORD
 * in includes/config.php to use MySQL instead.
 */

function cms_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_name('declaration_cms');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/cms/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function cms_pdo(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    if (!defined('CMS_DSN')) {
        $configPath = __DIR__ . '/config.php';
        if (is_file($configPath)) {
            require_once $configPath;
        }
    }

    $dsn = defined('CMS_DSN')
        ? CMS_DSN
        : 'sqlite:' . __DIR__ . '/../storage/declaration-cms.sqlite';
    $user = defined('CMS_DB_USER') ? CMS_DB_USER : null;
    $password = defined('CMS_DB_PASSWORD') ? CMS_DB_PASSWORD : null;

    if (str_starts_with($dsn, 'sqlite:')) {
        $databasePath = substr($dsn, 7);
        $directory = dirname($databasePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0750, true);
        }
    }

    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    if ($pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
        $pdo->exec('PRAGMA foreign_keys = ON');
    }

    cms_migrate($pdo);
    return $pdo;
}

function cms_migrate(PDO $pdo): void
{
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    $idColumn = $driver === 'mysql'
        ? 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY'
        : 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $boolean = $driver === 'mysql' ? 'TINYINT(1)' : 'INTEGER';
    $text = $driver === 'mysql' ? 'LONGTEXT' : 'TEXT';

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS cms_admin (
            id {$idColumn},
            username VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at VARCHAR(40) NOT NULL
        )"
    );

    $eventIndex = $driver === 'mysql'
        ? ', INDEX cms_events_status_start_idx (status, starts_at)'
        : '';
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS cms_events (
            id {$idColumn},
            planning_center_id VARCHAR(100) NULL UNIQUE,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            summary TEXT NULL,
            body {$text} NULL,
            starts_at VARCHAR(40) NOT NULL,
            ends_at VARCHAR(40) NULL,
            location_name VARCHAR(255) NULL,
            location_address VARCHAR(500) NULL,
            image_url TEXT NULL,
            registration_url TEXT NULL,
            registration_label VARCHAR(100) NOT NULL DEFAULT 'Register',
            status VARCHAR(20) NOT NULL DEFAULT 'draft',
            is_featured {$boolean} NOT NULL DEFAULT 0,
            local_override {$boolean} NOT NULL DEFAULT 0,
            source_data {$text} NULL,
            override_fields TEXT NULL,
            imported_at VARCHAR(40) NULL,
            updated_at VARCHAR(40) NOT NULL,
            created_at VARCHAR(40) NOT NULL
            {$eventIndex}
        )"
    );

    if ($driver === 'sqlite') {
        $pdo->exec('CREATE INDEX IF NOT EXISTS cms_events_status_start_idx ON cms_events (status, starts_at)');
    }

    cms_add_column_if_missing($pdo, 'cms_events', 'source_data', "{$text} NULL");
    cms_add_column_if_missing($pdo, 'cms_events', 'override_fields', 'TEXT NULL');

    if (function_exists('cms_media_migrate')) {
        cms_media_migrate($pdo);
    }
}

function cms_add_column_if_missing(PDO $pdo, string $table, string $column, string $definition): void
{
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    if ($driver === 'sqlite') {
        $columns = $pdo->query("PRAGMA table_info({$table})")->fetchAll();
        foreach ($columns as $existing) {
            if (($existing['name'] ?? '') === $column) {
                return;
            }
        }
    } else {
        $statement = $pdo->prepare(
            'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table AND COLUMN_NAME = :column'
        );
        $statement->execute([':table' => $table, ':column' => $column]);
        if ($statement->fetch()) {
            return;
        }
    }

    $pdo->exec("ALTER TABLE {$table} ADD COLUMN {$column} {$definition}");
}

function cms_has_admin(): bool
{
    return (int) cms_pdo()->query('SELECT COUNT(*) FROM cms_admin')->fetchColumn() > 0;
}

function cms_create_admin(string $username, string $password): void
{
    $username = trim($username);
    if ($username === '' || strlen($username) > 100) {
        throw new InvalidArgumentException('Enter a username no longer than 100 characters.');
    }
    if (strlen($password) < 10) {
        throw new InvalidArgumentException('Use a password with at least 10 characters.');
    }
    if (cms_has_admin()) {
        throw new RuntimeException('The admin login has already been created.');
    }

    $statement = cms_pdo()->prepare(
        'INSERT INTO cms_admin (username, password_hash, created_at) VALUES (:username, :password_hash, :created_at)'
    );
    $statement->execute([
        ':username' => $username,
        ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ':created_at' => gmdate(DATE_ATOM),
    ]);
}

function cms_login(string $username, string $password): bool
{
    $statement = cms_pdo()->prepare('SELECT id, username, password_hash FROM cms_admin WHERE username = :username LIMIT 1');
    $statement->execute([':username' => trim($username)]);
    $admin = $statement->fetch();
    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        return false;
    }

    cms_start_session();
    if (!headers_sent()) {
        session_regenerate_id(true);
    }
    $_SESSION['cms_admin_id'] = (int) $admin['id'];
    $_SESSION['cms_admin_username'] = $admin['username'];
    $_SESSION['cms_authenticated_at'] = time();
    return true;
}

function cms_logout(): void
{
    cms_start_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], '', $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function cms_is_authenticated(): bool
{
    cms_start_session();
    return !empty($_SESSION['cms_admin_id']);
}

function cms_require_auth(): void
{
    if (cms_is_authenticated()) {
        return;
    }
    header('Location: /cms/login.php');
    exit;
}

function cms_csrf_token(): string
{
    cms_start_session();
    if (empty($_SESSION['cms_csrf'])) {
        $_SESSION['cms_csrf'] = bin2hex(random_bytes(24));
    }
    return $_SESSION['cms_csrf'];
}

function cms_verify_csrf(): void
{
    cms_start_session();
    $submitted = (string) ($_POST['csrf_token'] ?? '');
    if ($submitted === '' || !hash_equals((string) ($_SESSION['cms_csrf'] ?? ''), $submitted)) {
        http_response_code(403);
        exit('Your session expired. Go back, refresh the page, and try again.');
    }
}

function cms_slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    return trim($value, '-') ?: 'event';
}

function cms_unique_slug(string $value, ?int $excludeId = null): string
{
    $pdo = cms_pdo();
    $base = cms_slugify($value);
    $slug = $base;
    $suffix = 2;

    while (true) {
        $sql = 'SELECT id FROM cms_events WHERE slug = :slug';
        $params = [':slug' => $slug];
        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params[':id'] = $excludeId;
        }
        $statement = $pdo->prepare($sql . ' LIMIT 1');
        $statement->execute($params);
        if (!$statement->fetch()) {
            return $slug;
        }
        $slug = $base . '-' . $suffix++;
    }
}

function cms_import_planning_center_events(array $events): array
{
    $pdo = cms_pdo();
    $inserted = 0;
    $updated = 0;
    $skipped = 0;
    $now = gmdate(DATE_ATOM);

    $lookup = $pdo->prepare(
        'SELECT id, local_override, override_fields FROM cms_events
         WHERE planning_center_id = :planning_center_id LIMIT 1'
    );
    $insert = $pdo->prepare(
        "INSERT INTO cms_events (
            planning_center_id, title, slug, summary, body, starts_at, ends_at,
            image_url, registration_url, registration_label, status, is_featured,
            local_override, source_data, override_fields, imported_at, updated_at, created_at
        ) VALUES (
            :planning_center_id, :title, :slug, :summary, :body, :starts_at, :ends_at,
            :image_url, :registration_url, 'Register', 'published', 0, 0,
            :source_data, '[]', :imported_at, :updated_at, :created_at
        )"
    );

    foreach ($events as $event) {
        $externalId = trim((string) ($event['id'] ?? ''));
        $title = trim((string) ($event['name'] ?? ''));
        $startsAt = trim((string) ($event['starts_at'] ?? ''));
        if ($externalId === '' || $title === '' || $startsAt === '') {
            $skipped++;
            continue;
        }

        $lookup->execute([':planning_center_id' => $externalId]);
        $existing = $lookup->fetch();
        $description = trim((string) ($event['description'] ?? ''));
        $source = [
            'title' => $title,
            'summary' => cms_plain_summary($description),
            'body' => cms_sanitize_rich_text($description),
            'starts_at' => $startsAt,
            'ends_at' => trim((string) ($event['ends_at'] ?? '')) ?: null,
            'image_url' => trim((string) ($event['logo_url'] ?? '')) ?: null,
            'registration_url' => trim((string) (($event['public_url'] ?? '') ?: ($event['registration_url'] ?? ''))) ?: null,
        ];
        $values = [
            ':title' => $source['title'],
            ':summary' => $source['summary'],
            ':body' => $source['body'],
            ':starts_at' => $source['starts_at'],
            ':ends_at' => $source['ends_at'],
            ':image_url' => $source['image_url'],
            ':registration_url' => $source['registration_url'],
            ':source_data' => json_encode($source, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            ':imported_at' => $now,
            ':updated_at' => $now,
        ];

        if ($existing) {
            $overrideFields = cms_decode_override_fields($existing['override_fields'] ?? null);
            if (!$overrideFields && (int) $existing['local_override'] === 1) {
                $overrideFields = array_merge(array_keys($source), ['status']);
            }

            $assignments = [
                'source_data = :source_data',
                'override_fields = :override_fields',
                'local_override = :local_override',
                'imported_at = :imported_at',
                'updated_at = :updated_at',
            ];
            $updateValues = [
                ':source_data' => $values[':source_data'],
                ':override_fields' => json_encode(array_values($overrideFields)),
                ':local_override' => $overrideFields ? 1 : 0,
                ':imported_at' => $now,
                ':updated_at' => $now,
                ':id' => (int) $existing['id'],
            ];

            foreach ($source as $field => $value) {
                if (!in_array($field, $overrideFields, true)) {
                    $assignments[] = "{$field} = :{$field}";
                    $updateValues[":{$field}"] = $value;
                }
            }
            if (!in_array('status', $overrideFields, true)) {
                $assignments[] = "status = 'published'";
            }

            $update = $pdo->prepare(
                'UPDATE cms_events SET ' . implode(', ', $assignments) . ' WHERE id = :id'
            );
            $update->execute($updateValues);
            $updated++;
            continue;
        }

        $insert->execute($values + [
            ':planning_center_id' => $externalId,
            ':slug' => cms_unique_slug($title),
            ':created_at' => $now,
        ]);
        $inserted++;
    }

    return ['inserted' => $inserted, 'updated' => $updated, 'skipped' => $skipped];
}

function cms_decode_override_fields(?string $json): array
{
    $fields = json_decode((string) $json, true);
    if (!is_array($fields)) {
        return [];
    }
    return array_values(array_unique(array_filter($fields, 'is_string')));
}

function cms_sync_planning_center_events(bool $force = false): array
{
    if ((defined('CMS_DISABLE_AUTO_SYNC') && CMS_DISABLE_AUTO_SYNC) || !function_exists('pc_get_upcoming_events')) {
        return ['inserted' => 0, 'updated' => 0, 'skipped' => 0];
    }

    $cacheDirectory = __DIR__ . '/../cache';
    if (!is_dir($cacheDirectory)) {
        @mkdir($cacheDirectory, 0755, true);
    }

    $lockHandle = @fopen($cacheDirectory . '/planning-center-sync.lock', 'c');
    if ($lockHandle === false || !flock($lockHandle, LOCK_EX | LOCK_NB)) {
        if (is_resource($lockHandle)) {
            fclose($lockHandle);
        }
        return ['inserted' => 0, 'updated' => 0, 'skipped' => 0];
    }

    try {
        // Recheck freshness after taking the lock so concurrent visitors cannot
        // start duplicate Planning Center requests when the cache expires.
        if (!$force) {
            $lastImportedAt = cms_pdo()->query(
                'SELECT MAX(imported_at) FROM cms_events WHERE planning_center_id IS NOT NULL'
            )->fetchColumn();
            $syncTtl = defined('PC_CACHE_TTL') ? max(60, (int) PC_CACHE_TTL) : 1800;
            if ($lastImportedAt && strtotime((string) $lastImportedAt) >= time() - $syncTtl) {
                return ['inserted' => 0, 'updated' => 0, 'skipped' => 0];
            }
        }

        $events = pc_get_upcoming_events(100);
        if (!$events) {
            return ['inserted' => 0, 'updated' => 0, 'skipped' => 0];
        }

        return cms_import_planning_center_events($events);
    } finally {
        flock($lockHandle, LOCK_UN);
        fclose($lockHandle);
    }
}

function cms_plain_summary(string $html, int $limit = 220): string
{
    $plain = trim(preg_replace('/\s+/', ' ', strip_tags($html)) ?? '');
    if (mb_strlen($plain) <= $limit) {
        return $plain;
    }
    return rtrim(mb_substr($plain, 0, $limit - 1)) . '…';
}

function cms_get_events_for_admin(): array
{
    return cms_pdo()->query(
        "SELECT * FROM cms_events
         ORDER BY CASE WHEN status = 'draft' THEN 0 ELSE 1 END, starts_at ASC"
    )->fetchAll();
}

function cms_get_event(int $id): ?array
{
    $statement = cms_pdo()->prepare('SELECT * FROM cms_events WHERE id = :id LIMIT 1');
    $statement->execute([':id' => $id]);
    return $statement->fetch() ?: null;
}

function cms_get_event_by_slug(string $slug): ?array
{
    $statement = cms_pdo()->prepare(
        "SELECT * FROM cms_events WHERE slug = :slug AND status = 'published' LIMIT 1"
    );
    $statement->execute([':slug' => $slug]);
    return $statement->fetch() ?: null;
}

function cms_get_published_events(int $limit = 12): array
{
    $statement = cms_pdo()->prepare(
        "SELECT * FROM cms_events
         WHERE status = 'published'
           AND COALESCE(NULLIF(ends_at, ''), starts_at) >= :cutoff
         ORDER BY starts_at ASC
         LIMIT :limit"
    );
    $statement->bindValue(':cutoff', gmdate('Y-m-d\TH:i:sP'));
    $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
    $statement->execute();
    return array_map('cms_public_event_shape', $statement->fetchAll());
}

function cms_get_website_events(int $limit = 12): array
{
    return cms_get_published_events($limit);
}

function cms_public_event_shape(array $event): array
{
    return [
        'id' => (string) $event['id'],
        'name' => $event['title'],
        'description' => $event['body'] ?: $event['summary'],
        'summary' => $event['summary'],
        'starts_at' => $event['starts_at'],
        'ends_at' => $event['ends_at'] ?: '',
        'logo_url' => $event['image_url'] ?: '',
        'registration_url' => $event['registration_url'] ?: '',
        'public_url' => '/events/' . rawurlencode($event['slug']) . '/',
        'location_name' => $event['location_name'] ?: '',
        'location_address' => $event['location_address'] ?: '',
        'registration_label' => $event['registration_label'] ?: 'Register',
        'is_featured' => (int) $event['is_featured'] === 1,
    ];
}

function cms_save_event(array $input, ?int $id = null): int
{
    $title = trim((string) ($input['title'] ?? ''));
    $startsAt = trim((string) ($input['starts_at'] ?? ''));
    if ($title === '' || $startsAt === '') {
        throw new InvalidArgumentException('Title and start date are required.');
    }

    $status = ($input['status'] ?? '') === 'published' ? 'published' : 'draft';
    $slugInput = trim((string) ($input['slug'] ?? '')) ?: $title;
    $slug = cms_unique_slug($slugInput, $id);
    $now = gmdate(DATE_ATOM);
    $values = [
        ':title' => $title,
        ':slug' => $slug,
        ':summary' => trim((string) ($input['summary'] ?? '')) ?: null,
        ':body' => cms_sanitize_rich_text((string) ($input['body'] ?? '')) ?: null,
        ':starts_at' => $startsAt,
        ':ends_at' => trim((string) ($input['ends_at'] ?? '')) ?: null,
        ':location_name' => trim((string) ($input['location_name'] ?? '')) ?: null,
        ':location_address' => trim((string) ($input['location_address'] ?? '')) ?: null,
        ':image_url' => trim((string) ($input['image_url'] ?? '')) ?: null,
        ':registration_url' => trim((string) ($input['registration_url'] ?? '')) ?: null,
        ':registration_label' => trim((string) ($input['registration_label'] ?? '')) ?: 'Register',
        ':status' => $status,
        ':is_featured' => !empty($input['is_featured']) ? 1 : 0,
        ':updated_at' => $now,
    ];

    $pdo = cms_pdo();
    if ($id !== null) {
        $existing = cms_get_event($id);
        if (!$existing) {
            throw new RuntimeException('Event not found.');
        }

        $overrideFields = [];
        if (!empty($existing['planning_center_id'])) {
            $source = json_decode((string) ($existing['source_data'] ?? ''), true);
            if (is_array($source)) {
                foreach (array_keys($source) as $field) {
                    $postedValue = $values[":{$field}"] ?? null;
                    $sourceValue = $source[$field] ?? null;
                    if (!cms_event_values_match($field, $postedValue, $sourceValue)) {
                        $overrideFields[] = $field;
                    }
                }
            } elseif ((int) ($existing['local_override'] ?? 0) === 1) {
                $overrideFields = ['title', 'summary', 'body', 'starts_at', 'ends_at', 'image_url', 'registration_url'];
            }
            if ($status !== 'published') {
                $overrideFields[] = 'status';
            }
        }

        $overrideFields = array_values(array_unique($overrideFields));
        $values[':override_fields'] = json_encode($overrideFields);
        $values[':local_override'] = $overrideFields ? 1 : 0;
        $statement = $pdo->prepare(
            'UPDATE cms_events SET title = :title, slug = :slug, summary = :summary,
             body = :body, starts_at = :starts_at, ends_at = :ends_at,
             location_name = :location_name, location_address = :location_address,
             image_url = :image_url, registration_url = :registration_url,
             registration_label = :registration_label, status = :status,
             is_featured = :is_featured, local_override = :local_override,
             override_fields = :override_fields, updated_at = :updated_at
             WHERE id = :id'
        );
        $statement->execute($values + [':id' => $id]);
        return $id;
    }

    $statement = $pdo->prepare(
        'INSERT INTO cms_events (
            title, slug, summary, body, starts_at, ends_at, location_name,
            location_address, image_url, registration_url, registration_label,
            status, is_featured, local_override, updated_at, created_at
         ) VALUES (
            :title, :slug, :summary, :body, :starts_at, :ends_at, :location_name,
            :location_address, :image_url, :registration_url, :registration_label,
            :status, :is_featured, 1, :updated_at, :created_at
         )'
    );
    $statement->execute($values + [':created_at' => $now]);
    return (int) $pdo->lastInsertId();
}

function cms_event_values_match(string $field, $first, $second): bool
{
    if (in_array($field, ['starts_at', 'ends_at'], true)) {
        if (($first === null || $first === '') && ($second === null || $second === '')) {
            return true;
        }
        $firstTime = strtotime((string) $first);
        $secondTime = strtotime((string) $second);
        return $firstTime !== false && $secondTime !== false && $firstTime === $secondTime;
    }

    return (string) ($first ?? '') === (string) ($second ?? '');
}

function cms_delete_event(int $id): void
{
    $statement = cms_pdo()->prepare('DELETE FROM cms_events WHERE id = :id');
    $statement->execute([':id' => $id]);
}

function cms_reset_planning_center_overrides(int $id): void
{
    $event = cms_get_event($id);
    if (!$event || empty($event['planning_center_id'])) {
        throw new InvalidArgumentException('Only Planning Center events can be reset.');
    }

    $source = json_decode((string) ($event['source_data'] ?? ''), true);
    if (!is_array($source) || !$source) {
        throw new RuntimeException('Sync this event with Planning Center before resetting it.');
    }

    $assignments = [];
    $values = [
        ':id' => $id,
        ':updated_at' => gmdate(DATE_ATOM),
    ];
    foreach ($source as $field => $value) {
        if (!in_array($field, ['title', 'summary', 'body', 'starts_at', 'ends_at', 'image_url', 'registration_url'], true)) {
            continue;
        }
        $assignments[] = "{$field} = :{$field}";
        $values[":{$field}"] = $value;
    }

    $assignments[] = "status = 'published'";
    $assignments[] = 'local_override = 0';
    $assignments[] = "override_fields = '[]'";
    $assignments[] = 'updated_at = :updated_at';
    cms_pdo()->prepare(
        'UPDATE cms_events SET ' . implode(', ', $assignments) . ' WHERE id = :id'
    )->execute($values);
}

function cms_datetime_local_value(?string $value): string
{
    if (!$value) {
        return '';
    }
    try {
        $date = new DateTime($value);
        $date->setTimezone(new DateTimeZone('America/Chicago'));
        return $date->format('Y-m-d\TH:i');
    } catch (Exception $exception) {
        return '';
    }
}

function cms_datetime_storage_value(string $value): string
{
    if ($value === '') {
        return '';
    }
    $date = DateTime::createFromFormat('Y-m-d\TH:i', $value, new DateTimeZone('America/Chicago'));
    if (!$date) {
        throw new InvalidArgumentException('Enter a valid date and time.');
    }
    return $date->format(DATE_ATOM);
}

function cms_escape(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function cms_safe_url(?string $value, string $fallback = ''): string
{
    $value = trim((string) $value);
    if ($value === '') {
        return $fallback;
    }
    if (str_starts_with($value, '/')) {
        return $value;
    }
    $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));
    return in_array($scheme, ['http', 'https'], true) ? $value : $fallback;
}

function cms_sanitize_rich_text(string $html): string
{
    $html = trim($html);
    if ($html === '') {
        return '';
    }

    if ($html === strip_tags($html)) {
        return nl2br(cms_escape($html));
    }

    $html = preg_replace('#<(script|style|iframe|object|embed)\b[^>]*>.*?</\1>#is', '', $html) ?? '';
    if (!class_exists('DOMDocument')) {
        return strip_tags($html, '<p><br><strong><b><em><i><ul><ol><li><a><h2><h3><blockquote><div>');
    }

    $document = new DOMDocument('1.0', 'UTF-8');
    $previousErrors = libxml_use_internal_errors(true);
    $document->loadHTML(
        '<?xml encoding="UTF-8"><div id="cms-rich-root">' . $html . '</div>',
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );
    libxml_clear_errors();
    libxml_use_internal_errors($previousErrors);

    $root = $document->getElementById('cms-rich-root');
    if (!$root) {
        return '';
    }

    $allowedTags = ['p', 'br', 'strong', 'b', 'em', 'i', 'ul', 'ol', 'li', 'a', 'h2', 'h3', 'blockquote', 'div'];
    $cleanNode = function (DOMNode $node) use (&$cleanNode, $allowedTags): void {
        foreach (iterator_to_array($node->childNodes) as $child) {
            $cleanNode($child);
        }

        if (!$node instanceof DOMElement || $node->getAttribute('id') === 'cms-rich-root') {
            return;
        }

        $tag = strtolower($node->tagName);
        if (!in_array($tag, $allowedTags, true)) {
            $parent = $node->parentNode;
            if ($parent) {
                while ($node->firstChild) {
                    $parent->insertBefore($node->firstChild, $node);
                }
                $parent->removeChild($node);
            }
            return;
        }

        $href = $tag === 'a' ? $node->getAttribute('href') : '';
        while ($node->attributes->length > 0) {
            $node->removeAttributeNode($node->attributes->item(0));
        }

        if ($tag === 'a') {
            $safeHref = cms_safe_url($href);
            if ($safeHref !== '') {
                $node->setAttribute('href', $safeHref);
                if (!str_starts_with($safeHref, '/')) {
                    $node->setAttribute('target', '_blank');
                    $node->setAttribute('rel', 'noopener');
                }
            }
        }
    };
    $cleanNode($root);

    $result = '';
    foreach ($root->childNodes as $child) {
        $result .= $document->saveHTML($child);
    }
    return trim($result);
}

require_once __DIR__ . '/cms-media.php';
