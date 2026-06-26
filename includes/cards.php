<?php
declare(strict_types=1);

// ──────────────────────────────────────────────────────────────
//  Low-level JSON persistence
// ──────────────────────────────────────────────────────────────
function readCards(): array {
    $path = DATA . '/cards.json';
    if (!file_exists($path)) return [];
    $raw = file_get_contents($path);
    return is_array($decoded = json_decode($raw, true)) ? $decoded : [];
}

function writeCards(array $cards): bool {
    return file_put_contents(
        DATA . '/cards.json',
        json_encode($cards, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    ) !== false;
}

// ──────────────────────────────────────────────────────────────
//  Queries
// ──────────────────────────────────────────────────────────────

/** Active top-level hub cards, sorted by order */
function getHubs(): array {
    $hubs = array_filter(readCards(), fn($c) => $c['parent_id'] === null && ($c['active'] ?? true));
    usort($hubs, fn($a, $b) => ($a['order'] ?? 99) <=> ($b['order'] ?? 99));
    return array_values($hubs);
}

/** All top-level hub cards (including inactive) for admin */
function getAllHubs(): array {
    $hubs = array_filter(readCards(), fn($c) => $c['parent_id'] === null);
    usort($hubs, fn($a, $b) => ($a['order'] ?? 99) <=> ($b['order'] ?? 99));
    return array_values($hubs);
}

/** All cards (for admin list) */
function getAllCards(): array {
    $cards = readCards();
    usort($cards, function($a, $b) {
        // Hubs first, then apps; within same type sort by order
        $aTop = $a['parent_id'] === null ? 0 : 1;
        $bTop = $b['parent_id'] === null ? 0 : 1;
        if ($aTop !== $bTop) return $aTop <=> $bTop;
        return ($a['order'] ?? 99) <=> ($b['order'] ?? 99);
    });
    return $cards;
}

/** Active children of a hub, sorted */
function getChildCards(string $parentId, bool $activeOnly = true): array {
    $children = array_filter(readCards(), function($c) use ($parentId, $activeOnly) {
        if ($c['parent_id'] !== $parentId) return false;
        if ($activeOnly && !($c['active'] ?? true)) return false;
        return true;
    });
    usort($children, fn($a, $b) => ($a['order'] ?? 99) <=> ($b['order'] ?? 99));
    return array_values($children);
}

function findCard(string $id): ?array {
    foreach (readCards() as $card) {
        if ($card['id'] === $id) return $card;
    }
    return null;
}

// ──────────────────────────────────────────────────────────────
//  Mutations
// ──────────────────────────────────────────────────────────────
function generateId(string $title): string {
    $id   = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($title)));
    $id   = trim($id, '-') ?: 'card';
    $base = $id;
    $existing = array_column(readCards(), 'id');
    for ($n = 2; in_array($id, $existing); $n++) {
        $id = $base . '-' . $n;
    }
    return $id;
}

function createCard(array $data): string {
    $cards = readCards();
    $id    = generateId($data['title'] ?? 'card');
    $card  = array_merge([
        'id'          => $id,
        'parent_id'   => null,
        'type'        => 'app',
        'title'       => '',
        'full_name'   => '',
        'description' => '',
        'icon'        => 'document',
        'color'       => 'blue',
        'url'         => '',
        'badge'       => '',
        'features'    => [],
        'status'      => 'live',
        'active'      => true,
        'order'       => 99,
        'created_at'  => date('c'),
    ], $data, ['id' => $id, 'created_at' => date('c')]);
    $cards[] = $card;
    writeCards($cards);
    return $id;
}

function updateCard(string $id, array $data): bool {
    $cards = readCards();
    foreach ($cards as &$card) {
        if ($card['id'] === $id) {
            $card = array_merge($card, $data, [
                'id'         => $id,
                'created_at' => $card['created_at'] ?? date('c'),
            ]);
            return writeCards($cards);
        }
    }
    return false;
}

function deleteCard(string $id): bool {
    $cards = readCards();
    // Remove card and all its children
    $cards = array_values(array_filter(
        $cards,
        fn($c) => $c['id'] !== $id && $c['parent_id'] !== $id
    ));
    return writeCards($cards);
}

function toggleCardActive(string $id): bool {
    $cards = readCards();
    foreach ($cards as &$card) {
        if ($card['id'] === $id) {
            $card['active'] = !($card['active'] ?? true);
            return writeCards($cards);
        }
    }
    return false;
}

/** Sanitise raw POST into a safe card data array */
function sanitizeCardPost(): array {
    $rawFeatures = $_POST['features'] ?? '';
    $features = array_values(array_filter(
        array_map('trim', preg_split('/\r?\n/', $rawFeatures)),
        fn($f) => $f !== ''
    ));

    $type     = in_array($_POST['type'] ?? '', ['hub', 'app'], true) ? $_POST['type'] : 'app';
    $parentId = ($type === 'app' && !empty($_POST['parent_id'])) ? trim($_POST['parent_id']) : null;

    return [
        'title'       => trim($_POST['title']       ?? ''),
        'full_name'   => trim($_POST['full_name']    ?? ''),
        'description' => trim($_POST['description']  ?? ''),
        'icon'        => trim($_POST['icon']         ?? 'document'),
        'color'       => trim($_POST['color']        ?? 'blue'),
        'url'         => trim($_POST['url']          ?? ''),
        'badge'       => trim($_POST['badge']        ?? ''),
        'status'      => in_array($_POST['status'] ?? '', ['live','beta','coming_soon'], true)
                           ? $_POST['status'] : 'live',
        'type'        => $type,
        'parent_id'   => $parentId,
        'features'    => $features,
        'active'      => true,
        'order'       => (int)($_POST['order'] ?? 99),
    ];
}
