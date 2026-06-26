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

// ── Admin routes ──────────────────────────────────────────────
if ($seg0 === 'admin') {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    // Logout
    if ($seg1 === 'logout') {
        authLogout();
        redirect('/admin');
    }

    // Login
    if ($seg1 === '' || $seg1 === 'login') {
        if (authCheck()) { redirect('/admin/dashboard'); }
        $loginError = '';
        if ($method === 'POST') {
            csrfVerify();
            if (authLogin($_POST['username'] ?? '', $_POST['password'] ?? '')) {
                redirect('/admin/dashboard');
            }
            $loginError = 'Invalid username or password.';
        }
        require VIEWS . '/admin/login.php';
        exit;
    }

    // All other admin routes require auth
    requireAuth();

    // Dashboard
    if ($seg1 === 'dashboard') {
        require VIEWS . '/admin/dashboard.php';
        exit;
    }

    // Cards management
    if ($seg1 === 'cards') {

        if ($method === 'POST') {
            csrfVerify();
            if ($seg2 === 'save') {
                $postId   = trim($_POST['id'] ?? '');
                $cardData = sanitizeCardPost();
                if ($cardData['title'] !== '') {
                    if ($postId && findCard($postId)) {
                        updateCard($postId, $cardData);
                    } else {
                        createCard($cardData);
                    }
                }
                redirect('/admin/cards');
            }
            if ($seg2 === 'delete') {
                $delId = trim($_POST['id'] ?? '');
                if ($delId) deleteCard($delId);
                redirect('/admin/cards');
            }
            if ($seg2 === 'toggle') {
                $togId = trim($_POST['id'] ?? '');
                $ok    = $togId ? toggleCardActive($togId) : false;
                $card  = $togId ? findCard($togId) : null;
                jsonResponse(['ok' => $ok, 'active' => $card['active'] ?? false]);
            }
            redirect('/admin/cards');
        }

        // GET
        if ($seg2 === 'new') {
            $editCard = null;
            require VIEWS . '/admin/card_form.php';
            exit;
        }
        if ($seg2 === 'edit') {
            $editId  = $_GET['id'] ?? '';
            $editCard = findCard($editId);
            if (!$editCard) { redirect('/admin/cards'); }
            require VIEWS . '/admin/card_form.php';
            exit;
        }
        require VIEWS . '/admin/cards.php';
        exit;
    }

    // Settings
    if ($seg1 === 'settings') {
        $settingsError   = '';
        $settingsSuccess = '';
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            csrfVerify();
            $settings = getSettings();
            $curPw    = $_POST['current_password'] ?? '';
            $newPw    = $_POST['new_password']     ?? '';
            $confPw   = $_POST['confirm_password'] ?? '';
            $newUser  = trim($_POST['new_username'] ?? '');
            if (!password_verify($curPw, $settings['admin_password_hash'])) {
                $settingsError = 'Current password is incorrect.';
            } elseif (strlen($newPw) < 8) {
                $settingsError = 'New password must be at least 8 characters.';
            } elseif ($newPw !== $confPw) {
                $settingsError = 'New passwords do not match.';
            } else {
                if ($newUser !== '') $settings['admin_username'] = $newUser;
                $settings['admin_password_hash'] = password_hash($newPw, PASSWORD_BCRYPT);
                saveSettings($settings);
                $settingsSuccess = 'Settings saved successfully.';
            }
        }
        require VIEWS . '/admin/settings.php';
        exit;
    }

    redirect('/admin/dashboard');
    exit;
}

// ── 404 ───────────────────────────────────────────────────────
http_response_code(404);
require VIEWS . '/404.php';
