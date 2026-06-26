<?php
declare(strict_types=1);

// ──────────────────────────────────────────────────────────────
//  Settings file (admin credentials)
// ──────────────────────────────────────────────────────────────
function getSettings(): array {
    $path = DATA . '/settings.json';
    if (!file_exists($path)) {
        $defaults = [
            'admin_username'      => 'admin',
            'admin_password_hash' => password_hash('Admin@TTU2026', PASSWORD_BCRYPT),
        ];
        file_put_contents($path, json_encode($defaults, JSON_PRETTY_PRINT));
        return $defaults;
    }
    return json_decode(file_get_contents($path), true) ?? [];
}

function saveSettings(array $settings): bool {
    return file_put_contents(
        DATA . '/settings.json',
        json_encode($settings, JSON_PRETTY_PRINT)
    ) !== false;
}

// ──────────────────────────────────────────────────────────────
//  Session auth
// ──────────────────────────────────────────────────────────────
function authCheck(): bool {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function authLogin(string $username, string $password): bool {
    $settings = getSettings();
    if (
        isset($settings['admin_username'], $settings['admin_password_hash']) &&
        hash_equals($settings['admin_username'], $username) &&
        password_verify($password, $settings['admin_password_hash'])
    ) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username']  = $username;
        return true;
    }
    return false;
}

function authLogout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function authUsername(): string {
    return $_SESSION['admin_username'] ?? 'admin';
}

function requireAuth(): void {
    if (!authCheck()) {
        redirect('/admin');
    }
}
