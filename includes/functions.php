<?php
declare(strict_types=1);

// ──────────────────────────────────────────────────────────────
//  Output escaping
// ──────────────────────────────────────────────────────────────
function esc(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// ──────────────────────────────────────────────────────────────
//  HTTP helpers
// ──────────────────────────────────────────────────────────────
function url(string $path): string {
    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    $scriptDir = str_replace('\\', '/', $scriptDir);
    $base = ($scriptDir === '/') ? '' : $scriptDir;
    return $base . '/' . ltrim($path, '/');
}

function siteLogoUrl(): string {
    return url('assets/images/csd-ttu-logo-research-innovate-build.png');
}

function siteFaviconUrl(): string {
    return url('assets/images/csd-ttu-logo-research-innovate-build.png');
}

function siteLogoAlt(): string {
    return 'CSD-TTU logo — Research, Innovate, Build. Department of Computer Science, Takoradi Technical University';
}

function heroIllustrationUrl(): string {
    return url('assets/images/itsu-academic-team.png');
}

function siteLogo(int $height = 48, string $class = 'site-logo'): string {
    $h = max(32, min(180, $height));
    return '<img class="' . esc($class) . '"'
         . ' src="' . esc(siteLogoUrl()) . '"'
         . ' alt="' . esc(siteLogoAlt()) . '"'
         . ' width="1024" height="1024"'
         . ' style="height:' . $h . 'px;width:auto;display:block"'
         . ' loading="eager" decoding="async" draggable="false">';
}

function redirect(string $url): void {
    if (strpos($url, 'http://') !== 0 && strpos($url, 'https://') !== 0) {
        $url = url($url);
    }
    header('Location: ' . $url);
    exit;
}

function jsonResponse(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

// ──────────────────────────────────────────────────────────────
//  CSRF protection
// ──────────────────────────────────────────────────────────────
function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' . esc(csrfToken()) . '">';
}

function csrfVerify(): void {
    $supplied = $_POST['csrf_token'] ?? '';
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $supplied)) {
        http_response_code(403);
        die('<p style="font-family:sans-serif;padding:2rem">Security check failed. <a href="javascript:history.back()">Go back</a></p>');
    }
}

// ──────────────────────────────────────────────────────────────
//  SVG icon library  (stroke-based, 24×24 viewBox)
// ──────────────────────────────────────────────────────────────
function iconSvg(string $name, int $size = 20, string $extraClass = ''): string {
    static $icons = [
        'circuit'    => '<rect x="9" y="9" width="6" height="6" rx="1"/>
                         <path d="M9 2v3M15 2v3M9 19v3M15 19v3M2 9h3M19 9h3M2 15h3M19 15h3"/>',
        'document'   => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                         <polyline points="14 2 14 8 20 8"/>
                         <line x1="16" y1="13" x2="8" y2="13"/>
                         <line x1="16" y1="17" x2="8" y2="17"/>
                         <line x1="10" y1="9" x2="8" y2="9"/>',
        'lightning'  => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
        'rocket'     => '<path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/>
                         <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/>
                         <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/>
                         <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>',
        'users'      => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                         <circle cx="9" cy="7" r="4"/>
                         <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                         <path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'graduation' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                         <path d="M6 12v5c3 3 9 3 12 0v-5"/>',
        'code'       => '<polyline points="16 18 22 12 16 6"/>
                         <polyline points="8 6 2 12 8 18"/>',
        'gear'       => '<circle cx="12" cy="12" r="3"/>
                         <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>',
        'chart'      => '<line x1="18" y1="20" x2="18" y2="10"/>
                         <line x1="12" y1="20" x2="12" y2="4"/>
                         <line x1="6" y1="20" x2="6" y2="14"/>
                         <line x1="2" y1="20" x2="22" y2="20"/>',
        'star'       => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
        'globe'      => '<circle cx="12" cy="12" r="10"/>
                         <line x1="2" y1="12" x2="22" y2="12"/>
                         <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
        'lock'       => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                         <path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'megaphone'  => '<path d="M3 11l19-9-9 19-2-8-8-2z"/>',
        'calendar'   => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                         <line x1="16" y1="2" x2="16" y2="6"/>
                         <line x1="8" y1="2" x2="8" y2="6"/>
                         <line x1="3" y1="10" x2="21" y2="10"/>',
    ];

    $paths = $icons[$name] ?? $icons['document'];
    $cls   = $extraClass ? ' class="' . esc($extraClass) . '"' : '';

    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24"'
        . ' fill="none" stroke="currentColor" stroke-width="1.9"'
        . ' stroke-linecap="round" stroke-linejoin="round"%s>%s</svg>',
        $size, $size, $cls, $paths
    );
}

// ──────────────────────────────────────────────────────────────
//  Color palette
// ──────────────────────────────────────────────────────────────
function colorConfig(string $color): array {
    static $palette = [
        'blue'   => ['accent' => '#1D4ED8', 'rgb' => '29,78,216'],
        'green'  => ['accent' => '#059669', 'rgb' => '5,150,105'],
        'purple' => ['accent' => '#7C3AED', 'rgb' => '124,58,237'],
        'orange' => ['accent' => '#D97706', 'rgb' => '217,119,6'],
        'pink'   => ['accent' => '#DB2777', 'rgb' => '219,39,119'],
        'cyan'   => ['accent' => '#0891B2', 'rgb' => '8,145,178'],
        'red'    => ['accent' => '#DC2626', 'rgb' => '220,38,38'],
        'yellow' => ['accent' => '#CA8A04', 'rgb' => '202,138,4'],
    ];
    return $palette[$color] ?? $palette['blue'];
}

function colorCssVars(string $color): string {
    $c = colorConfig($color);
    return '--ca:' . $c['accent'] . ';--cr:' . $c['rgb'];
}

// ──────────────────────────────────────────────────────────────
//  Status helpers
// ──────────────────────────────────────────────────────────────
function statusLabel(string $status): string {
    return ['live' => 'Live', 'beta' => 'Beta', 'coming_soon' => 'Soon'][$status] ?? ucfirst($status);
}

function statusChipClass(string $status): string {
    return ['live' => 'chip-live', 'beta' => 'chip-beta', 'coming_soon' => 'chip-soon'][$status] ?? 'chip-live';
}

// ──────────────────────────────────────────────────────────────
//  Available icon list for admin pickers
// ──────────────────────────────────────────────────────────────
function availableIcons(): array {
    return [
        'circuit', 'document', 'lightning', 'rocket', 'users',
        'graduation', 'code', 'gear', 'chart', 'star', 'globe',
        'lock', 'megaphone', 'calendar',
    ];
}

function availableColors(): array {
    return ['blue', 'green', 'purple', 'orange', 'pink', 'cyan', 'red', 'yellow'];
}
