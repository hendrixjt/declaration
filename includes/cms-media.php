<?php
/**
 * Media library services for the Declaration CMS.
 *
 * Public website copies live outside the database so they can be served by
 * Apache and backed up independently. Google Drive remains an optional source;
 * imported files are normalized and owned by the website.
 */

function cms_media_migrate(PDO $pdo): void
{
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    $idColumn = $driver === 'mysql'
        ? 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY'
        : 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $text = $driver === 'mysql' ? 'LONGTEXT' : 'TEXT';

    $assetIndexes = $driver === 'mysql'
        ? ', UNIQUE INDEX cms_media_source_idx (source_type, source_id), INDEX cms_media_status_idx (status, created_at)'
        : '';
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS cms_media_assets (
            id {$idColumn},
            source_type VARCHAR(20) NOT NULL DEFAULT 'upload',
            source_id VARCHAR(255) NULL,
            source_revision VARCHAR(255) NULL,
            original_name VARCHAR(500) NOT NULL,
            stored_name VARCHAR(500) NOT NULL,
            public_url TEXT NOT NULL,
            variants_json {$text} NULL,
            mime_type VARCHAR(100) NOT NULL,
            width INTEGER NOT NULL,
            height INTEGER NOT NULL,
            file_size INTEGER NOT NULL DEFAULT 0,
            orientation VARCHAR(20) NOT NULL,
            title VARCHAR(255) NOT NULL,
            alt_text VARCHAR(500) NULL,
            caption TEXT NULL,
            credit VARCHAR(255) NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'active',
            created_by VARCHAR(100) NULL,
            updated_at VARCHAR(40) NOT NULL,
            created_at VARCHAR(40) NOT NULL
            {$assetIndexes}
        )"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS cms_media_tags (
            id {$idColumn},
            name VARCHAR(100) NOT NULL UNIQUE,
            slug VARCHAR(100) NOT NULL UNIQUE
        )"
    );

    $junctionPrimary = $driver === 'mysql'
        ? ', PRIMARY KEY (asset_id, tag_id), INDEX cms_media_asset_tags_tag_idx (tag_id)'
        : ', PRIMARY KEY (asset_id, tag_id)';
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS cms_media_asset_tags (
            asset_id INTEGER NOT NULL,
            tag_id INTEGER NOT NULL,
            FOREIGN KEY (asset_id) REFERENCES cms_media_assets(id) ON DELETE CASCADE,
            FOREIGN KEY (tag_id) REFERENCES cms_media_tags(id) ON DELETE CASCADE
            {$junctionPrimary}
        )"
    );

    if ($driver === 'sqlite') {
        $pdo->exec('CREATE UNIQUE INDEX IF NOT EXISTS cms_media_source_idx ON cms_media_assets (source_type, source_id) WHERE source_id IS NOT NULL');
        $pdo->exec('CREATE INDEX IF NOT EXISTS cms_media_status_idx ON cms_media_assets (status, created_at)');
        $pdo->exec('CREATE INDEX IF NOT EXISTS cms_media_asset_tags_tag_idx ON cms_media_asset_tags (tag_id)');
    }
}

function cms_media_storage_path(): string
{
    return defined('CMS_MEDIA_STORAGE_PATH')
        ? rtrim((string) CMS_MEDIA_STORAGE_PATH, DIRECTORY_SEPARATOR)
        : dirname(__DIR__) . '/uploads/media';
}

function cms_media_public_base(): string
{
    return defined('CMS_MEDIA_PUBLIC_BASE')
        ? rtrim((string) CMS_MEDIA_PUBLIC_BASE, '/')
        : '/uploads/media';
}

function cms_media_max_upload_bytes(): int
{
    return defined('CMS_MEDIA_MAX_BYTES') ? max(1024, (int) CMS_MEDIA_MAX_BYTES) : 20 * 1024 * 1024;
}

function cms_media_allowed_types(): array
{
    return ['image/jpeg', 'image/png', 'image/webp'];
}

function cms_media_default_tags(): array
{
    return ['Baptism', 'Community', 'Events', 'Groups', 'Kids', 'Missions', 'Prayer', 'Serve', 'Staff', 'Sunday', 'Worship', 'Youth'];
}

function cms_media_seed_default_tags(): void
{
    foreach (cms_media_default_tags() as $tag) {
        cms_media_find_or_create_tag($tag);
    }
}

function cms_media_filename_title(string $filename): string
{
    $name = pathinfo($filename, PATHINFO_FILENAME);
    $name = preg_replace('/[_-]+/', ' ', $name) ?? $name;
    $name = trim(preg_replace('/\s+/', ' ', $name) ?? $name);
    return $name !== '' ? mb_convert_case($name, MB_CASE_TITLE, 'UTF-8') : 'Untitled image';
}

function cms_media_orientation(int $width, int $height): string
{
    if ($width === $height) {
        return 'square';
    }
    return $width > $height ? 'landscape' : 'portrait';
}

function cms_media_tags_from_input($value): array
{
    if (is_string($value)) {
        $value = preg_split('/[,\n]+/', $value) ?: [];
    }
    if (!is_array($value)) {
        return [];
    }

    $tags = [];
    foreach ($value as $tag) {
        $tag = trim(preg_replace('/\s+/', ' ', (string) $tag) ?? '');
        if ($tag === '') {
            continue;
        }
        if (mb_strlen($tag) > 100) {
            throw new InvalidArgumentException('Tags must be 100 characters or fewer.');
        }
        $tags[mb_strtolower($tag)] = $tag;
    }
    return array_values($tags);
}

function cms_media_tag_slug(string $name): string
{
    $slug = cms_slugify($name);
    return $slug === 'event' ? 'tag-' . substr(hash('sha256', $name), 0, 10) : $slug;
}

function cms_media_find_or_create_tag(string $name): int
{
    $name = trim($name);
    if ($name === '') {
        throw new InvalidArgumentException('Tag names cannot be empty.');
    }

    $pdo = cms_pdo();
    $lookup = $pdo->prepare('SELECT id FROM cms_media_tags WHERE LOWER(name) = LOWER(:name) LIMIT 1');
    $lookup->execute([':name' => $name]);
    $existing = $lookup->fetchColumn();
    if ($existing) {
        return (int) $existing;
    }

    $base = cms_media_tag_slug($name);
    $slug = $base;
    $suffix = 2;
    while (true) {
        $slugLookup = $pdo->prepare('SELECT id FROM cms_media_tags WHERE slug = :slug LIMIT 1');
        $slugLookup->execute([':slug' => $slug]);
        if (!$slugLookup->fetchColumn()) {
            break;
        }
        $slug = $base . '-' . $suffix++;
    }

    $statement = $pdo->prepare('INSERT INTO cms_media_tags (name, slug) VALUES (:name, :slug)');
    $statement->execute([':name' => $name, ':slug' => $slug]);
    return (int) $pdo->lastInsertId();
}

function cms_media_sync_tags(int $assetId, $tags): void
{
    $pdo = cms_pdo();
    $pdo->prepare('DELETE FROM cms_media_asset_tags WHERE asset_id = :asset_id')->execute([':asset_id' => $assetId]);
    $insert = $pdo->prepare('INSERT INTO cms_media_asset_tags (asset_id, tag_id) VALUES (:asset_id, :tag_id)');
    foreach (cms_media_tags_from_input($tags) as $name) {
        $insert->execute([':asset_id' => $assetId, ':tag_id' => cms_media_find_or_create_tag($name)]);
    }
}

function cms_media_get_tags(int $assetId): array
{
    $statement = cms_pdo()->prepare(
        'SELECT t.id, t.name, t.slug FROM cms_media_tags t
         JOIN cms_media_asset_tags mt ON mt.tag_id = t.id
         WHERE mt.asset_id = :asset_id ORDER BY t.name ASC'
    );
    $statement->execute([':asset_id' => $assetId]);
    return $statement->fetchAll();
}

function cms_media_all_tags(bool $onlyUsed = false): array
{
    cms_media_seed_default_tags();
    $sql = 'SELECT t.id, t.name, t.slug, COUNT(mt.asset_id) AS asset_count
            FROM cms_media_tags t LEFT JOIN cms_media_asset_tags mt ON mt.tag_id = t.id
            GROUP BY t.id, t.name, t.slug';
    if ($onlyUsed) {
        $sql .= ' HAVING COUNT(mt.asset_id) > 0';
    }
    $sql .= ' ORDER BY t.name ASC';
    return cms_pdo()->query($sql)->fetchAll();
}

function cms_media_get_asset(int $id): ?array
{
    $statement = cms_pdo()->prepare('SELECT * FROM cms_media_assets WHERE id = :id LIMIT 1');
    $statement->execute([':id' => $id]);
    $asset = $statement->fetch();
    if (!$asset) {
        return null;
    }
    $asset['tags'] = cms_media_get_tags((int) $asset['id']);
    $asset['variants'] = json_decode((string) ($asset['variants_json'] ?? ''), true) ?: [];
    return $asset;
}

function cms_media_get_asset_by_source(string $sourceType, string $sourceId): ?array
{
    $statement = cms_pdo()->prepare(
        'SELECT id FROM cms_media_assets WHERE source_type = :source_type AND source_id = :source_id LIMIT 1'
    );
    $statement->execute([':source_type' => $sourceType, ':source_id' => $sourceId]);
    $id = $statement->fetchColumn();
    return $id ? cms_media_get_asset((int) $id) : null;
}

function cms_media_source_map(string $sourceType, array $sourceIds): array
{
    $sourceIds = array_values(array_unique(array_filter(array_map('strval', $sourceIds))));
    if (!$sourceIds) {
        return [];
    }
    $placeholders = [];
    $params = [':source_type' => $sourceType];
    foreach ($sourceIds as $index => $sourceId) {
        $placeholder = ':source_' . $index;
        $placeholders[] = $placeholder;
        $params[$placeholder] = $sourceId;
    }
    $statement = cms_pdo()->prepare(
        'SELECT id, source_id FROM cms_media_assets WHERE source_type = :source_type
         AND source_id IN (' . implode(', ', $placeholders) . ')'
    );
    $statement->execute($params);
    $map = [];
    foreach ($statement->fetchAll() as $row) {
        $map[(string) $row['source_id']] = (int) $row['id'];
    }
    return $map;
}

function cms_media_search(array $filters = []): array
{
    $sql = "SELECT DISTINCT a.* FROM cms_media_assets a
            LEFT JOIN cms_media_asset_tags mt ON mt.asset_id = a.id
            LEFT JOIN cms_media_tags t ON t.id = mt.tag_id
            WHERE 1 = 1";
    $params = [];

    $status = (string) ($filters['status'] ?? 'active');
    if ($status !== 'all') {
        $sql .= ' AND a.status = :status';
        $params[':status'] = $status === 'archived' ? 'archived' : 'active';
    }

    $query = trim((string) ($filters['q'] ?? ''));
    if ($query !== '') {
        $sql .= ' AND (LOWER(a.title) LIKE :query OR LOWER(a.original_name) LIKE :query
                  OR LOWER(COALESCE(a.alt_text, \'\')) LIKE :query
                  OR LOWER(COALESCE(a.caption, \'\')) LIKE :query
                  OR LOWER(COALESCE(a.credit, \'\')) LIKE :query OR LOWER(t.name) LIKE :query)';
        $params[':query'] = '%' . mb_strtolower($query) . '%';
    }

    $orientation = (string) ($filters['orientation'] ?? '');
    if (in_array($orientation, ['landscape', 'portrait', 'square'], true)) {
        $sql .= ' AND a.orientation = :orientation';
        $params[':orientation'] = $orientation;
    }

    $source = (string) ($filters['source'] ?? '');
    if (in_array($source, ['upload', 'drive'], true)) {
        $sql .= ' AND a.source_type = :source_type';
        $params[':source_type'] = $source;
    }

    $tag = trim((string) ($filters['tag'] ?? ''));
    if ($tag !== '') {
        $sql .= ' AND EXISTS (
            SELECT 1 FROM cms_media_asset_tags mt2
            JOIN cms_media_tags t2 ON t2.id = mt2.tag_id
            WHERE mt2.asset_id = a.id AND t2.slug = :tag
        )';
        $params[':tag'] = $tag;
    }

    $sql .= ' ORDER BY a.updated_at DESC, a.id DESC';
    $statement = cms_pdo()->prepare($sql);
    $statement->execute($params);
    return array_map(static function (array $asset): array {
        $asset['tags'] = cms_media_get_tags((int) $asset['id']);
        $asset['variants'] = json_decode((string) ($asset['variants_json'] ?? ''), true) ?: [];
        return $asset;
    }, $statement->fetchAll());
}

function cms_media_counts(): array
{
    $row = cms_pdo()->query(
        "SELECT COUNT(*) AS total,
         SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS active,
         SUM(CASE WHEN orientation = 'landscape' AND status = 'active' THEN 1 ELSE 0 END) AS landscape,
         SUM(CASE WHEN orientation = 'portrait' AND status = 'active' THEN 1 ELSE 0 END) AS portrait,
         SUM(CASE WHEN orientation = 'square' AND status = 'active' THEN 1 ELSE 0 END) AS square
         FROM cms_media_assets"
    )->fetch() ?: [];
    return array_map('intval', $row + ['total' => 0, 'active' => 0, 'landscape' => 0, 'portrait' => 0, 'square' => 0]);
}

function cms_media_update_asset(int $id, array $input): void
{
    if (!cms_media_get_asset($id)) {
        throw new RuntimeException('Media asset not found.');
    }

    $title = trim((string) ($input['title'] ?? ''));
    if ($title === '') {
        throw new InvalidArgumentException('Enter a title for this image.');
    }
    $status = ($input['status'] ?? '') === 'archived' ? 'archived' : 'active';
    $statement = cms_pdo()->prepare(
        'UPDATE cms_media_assets SET title = :title, alt_text = :alt_text, caption = :caption,
         credit = :credit, status = :status, updated_at = :updated_at WHERE id = :id'
    );
    $statement->execute([
        ':title' => $title,
        ':alt_text' => trim((string) ($input['alt_text'] ?? '')) ?: null,
        ':caption' => trim((string) ($input['caption'] ?? '')) ?: null,
        ':credit' => trim((string) ($input['credit'] ?? '')) ?: null,
        ':status' => $status,
        ':updated_at' => gmdate(DATE_ATOM),
        ':id' => $id,
    ]);
    cms_media_sync_tags($id, $input['tags'] ?? []);
}

function cms_media_normalize_uploads(array $files): array
{
    if (!isset($files['name'])) {
        return [];
    }
    if (!is_array($files['name'])) {
        return [$files];
    }
    $normalized = [];
    foreach ($files['name'] as $index => $name) {
        $normalized[] = [
            'name' => $name,
            'type' => $files['type'][$index] ?? '',
            'tmp_name' => $files['tmp_name'][$index] ?? '',
            'error' => $files['error'][$index] ?? UPLOAD_ERR_NO_FILE,
            'size' => $files['size'][$index] ?? 0,
        ];
    }
    return $normalized;
}

function cms_media_store_upload(array $file, array $metadata = []): int
{
    $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($error !== UPLOAD_ERR_OK) {
        throw new RuntimeException(cms_media_upload_error_message($error));
    }
    $tmpName = (string) ($file['tmp_name'] ?? '');
    if ($tmpName === '' || !is_file($tmpName)) {
        throw new RuntimeException('The uploaded image could not be read.');
    }
    $bytes = (string) file_get_contents($tmpName);
    return cms_media_store_binary($bytes, (string) ($file['name'] ?? 'image'), $metadata + ['source_type' => 'upload']);
}

function cms_media_upload_error_message(int $error): string
{
    return match ($error) {
        UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'The image is larger than the upload limit.',
        UPLOAD_ERR_PARTIAL => 'The image upload was interrupted. Try again.',
        UPLOAD_ERR_NO_FILE => 'Choose at least one image to upload.',
        default => 'The image could not be uploaded.',
    };
}

function cms_media_store_binary(string $bytes, string $originalName, array $metadata = []): int
{
    if ($bytes === '') {
        throw new InvalidArgumentException('The image file is empty.');
    }
    if (strlen($bytes) > cms_media_max_upload_bytes()) {
        throw new InvalidArgumentException('The image exceeds the ' . round(cms_media_max_upload_bytes() / 1048576) . ' MB upload limit.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string) $finfo->buffer($bytes);
    if (!in_array($mime, cms_media_allowed_types(), true)) {
        throw new InvalidArgumentException('Upload a JPEG, PNG, or WebP image.');
    }

    $size = @getimagesizefromstring($bytes);
    if (!$size || empty($size[0]) || empty($size[1])) {
        throw new InvalidArgumentException('The selected file is not a valid image.');
    }
    $width = (int) $size[0];
    $height = (int) $size[1];
    if ($width * $height > 50000000) {
        throw new InvalidArgumentException('The image dimensions are too large to process safely.');
    }
    if (!function_exists('imagecreatefromstring') || !function_exists('imagewebp')) {
        throw new RuntimeException('The server needs PHP GD with WebP support to process media uploads.');
    }

    $image = @imagecreatefromstring($bytes);
    if (!$image instanceof GdImage) {
        throw new InvalidArgumentException('The selected image could not be decoded.');
    }

    $storage = cms_media_storage_path();
    if (!is_dir($storage) && !mkdir($storage, 0755, true) && !is_dir($storage)) {
        throw new RuntimeException('The website media directory could not be created.');
    }
    if (!is_writable($storage)) {
        throw new RuntimeException('The website media directory is not writable.');
    }

    $baseName = gmdate('Ymd') . '-' . bin2hex(random_bytes(8));
    $variants = [];
    $createdFiles = [];
    try {
        foreach ([480, 960, 1600, 2400] as $targetWidth) {
            if ($targetWidth > $width && $targetWidth !== 2400) {
                continue;
            }
            $outputWidth = min($targetWidth, $width);
            if ($targetWidth === 2400 && isset($variants[(string) $outputWidth])) {
                continue;
            }
            $outputHeight = max(1, (int) round($height * ($outputWidth / $width)));
            $resized = imagecreatetruecolor($outputWidth, $outputHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefill($resized, 0, 0, $transparent);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $outputWidth, $outputHeight, $width, $height);

            $filename = $baseName . '-' . $outputWidth . 'w.webp';
            $path = $storage . DIRECTORY_SEPARATOR . $filename;
            if (!imagewebp($resized, $path, 84)) {
                throw new RuntimeException('The optimized image could not be saved.');
            }
            $resized = null;
            @chmod($path, 0644);
            $createdFiles[] = $path;
            $variants[(string) $outputWidth] = [
                'url' => cms_media_public_base() . '/' . $filename,
                'width' => $outputWidth,
                'height' => $outputHeight,
                'size' => (int) filesize($path),
            ];
        }
    } catch (Throwable $exception) {
        foreach ($createdFiles as $createdFile) {
            @unlink($createdFile);
        }
        throw $exception;
    }
    $image = null;

    if (!$variants) {
        throw new RuntimeException('No optimized image variants were created.');
    }
    $primary = end($variants);
    $now = gmdate(DATE_ATOM);
    $sourceType = ($metadata['source_type'] ?? '') === 'drive' ? 'drive' : 'upload';
    $sourceId = trim((string) ($metadata['source_id'] ?? '')) ?: null;
    if ($sourceId) {
        $existing = cms_media_get_asset_by_source($sourceType, $sourceId);
        if ($existing) {
            foreach ($createdFiles as $createdFile) {
                @unlink($createdFile);
            }
            cms_media_update_asset((int) $existing['id'], $metadata + ['title' => $existing['title']]);
            return (int) $existing['id'];
        }
    }

    $title = trim((string) ($metadata['title'] ?? '')) ?: cms_media_filename_title($originalName);
    $statement = cms_pdo()->prepare(
        'INSERT INTO cms_media_assets (
            source_type, source_id, source_revision, original_name, stored_name,
            public_url, variants_json, mime_type, width, height, file_size,
            orientation, title, alt_text, caption, credit, status, created_by,
            updated_at, created_at
        ) VALUES (
            :source_type, :source_id, :source_revision, :original_name, :stored_name,
            :public_url, :variants_json, :mime_type, :width, :height, :file_size,
            :orientation, :title, :alt_text, :caption, :credit, :status, :created_by,
            :updated_at, :created_at
        )'
    );
    try {
        $statement->execute([
            ':source_type' => $sourceType,
            ':source_id' => $sourceId,
            ':source_revision' => trim((string) ($metadata['source_revision'] ?? '')) ?: null,
            ':original_name' => mb_substr($originalName, 0, 500),
            ':stored_name' => basename((string) $primary['url']),
            ':public_url' => $primary['url'],
            ':variants_json' => json_encode($variants, JSON_UNESCAPED_SLASHES),
            ':mime_type' => 'image/webp',
            ':width' => (int) $primary['width'],
            ':height' => (int) $primary['height'],
            ':file_size' => (int) $primary['size'],
            ':orientation' => cms_media_orientation($width, $height),
            ':title' => mb_substr($title, 0, 255),
            ':alt_text' => trim((string) ($metadata['alt_text'] ?? '')) ?: null,
            ':caption' => trim((string) ($metadata['caption'] ?? '')) ?: null,
            ':credit' => trim((string) ($metadata['credit'] ?? '')) ?: null,
            ':status' => 'active',
            ':created_by' => mb_substr((string) ($_SESSION['cms_admin_username'] ?? 'Admin'), 0, 100),
            ':updated_at' => $now,
            ':created_at' => $now,
        ]);
    } catch (Throwable $exception) {
        foreach ($createdFiles as $createdFile) {
            @unlink($createdFile);
        }
        throw $exception;
    }
    $id = (int) cms_pdo()->lastInsertId();
    cms_media_sync_tags($id, $metadata['tags'] ?? []);
    return $id;
}

function cms_media_srcset(array $asset): string
{
    $variants = $asset['variants'] ?? json_decode((string) ($asset['variants_json'] ?? ''), true);
    if (!is_array($variants)) {
        return '';
    }
    $parts = [];
    foreach ($variants as $variant) {
        if (!empty($variant['url']) && !empty($variant['width'])) {
            $parts[] = $variant['url'] . ' ' . (int) $variant['width'] . 'w';
        }
    }
    return implode(', ', $parts);
}
