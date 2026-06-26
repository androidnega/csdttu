<?php
declare(strict_types=1);

// ── Bootstrap ─────────────────────────────────────────────────
define('ROOT',     __DIR__);
define('DATA',     ROOT . '/data');
define('INCLUDES', ROOT . '/includes');
define('VIEWS',    ROOT . '/views');

session_start();

require INCLUDES . '/functions.php';
require INCLUDES . '/auth.php';
require INCLUDES . '/cards.php';

// ── Parse URI (supporting subdirectories) ────────────────────
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$scriptDir   = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$scriptDir   = str_replace('\\', '/', $scriptDir); // normalize windows paths

if ($scriptDir !== '/' && strpos($requestPath, $scriptDir) === 0) {
    $requestPath = substr($requestPath, strlen($scriptDir));
}

$uri         = trim($requestPath ?? '/', '/');
$segments    = $uri !== '' ? array_values(array_filter(explode('/', $uri))) : [];

$seg0 = $segments[0] ?? '';
$seg1 = $segments[1] ?? '';
$seg2 = $segments[2] ?? '';

// ── Front-end routes ──────────────────────────────────────────
if ($seg0 === '') {
    require VIEWS . '/home.php';
    exit;
}

if ($seg0 === 'hub') {
    if (!$seg1) { redirect('/'); }
    $hubSlug = $seg1;   // available in hub.php
    require VIEWS . '/hub.php';
    exit;
}

if ($seg0 === 'about') {
    require VIEWS . '/about.php';
    exit;
}

// Admin routes disabled from public access
if ($seg0 === 'admin') {
    http_response_code(404);
    require VIEWS . '/404.php';
    exit;
}

// ── 404 ───────────────────────────────────────────────────────
http_response_code(404);
require VIEWS . '/404.php';
