<?php
if (!function_exists('csrf_get_token')) {
    function csrf_get_token(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_get_token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('csrf_validate_token')) {
    function csrf_validate_token($token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        if (!is_string($sessionToken) || $sessionToken === '' || !is_string($token)) {
            return false;
        }
        return hash_equals($sessionToken, $token);
    }
}

if (!function_exists('csrf_guard')) {
    function csrf_guard(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        $token = $_POST['csrf_token'] ?? '';
        if (!csrf_validate_token($token)) {
            http_response_code(400);
            exit('Invalid CSRF token');
        }
    }
}

if (!function_exists('csrf_require_token')) {
    function csrf_require_token($token): void
    {
        if (!csrf_validate_token($token)) {
            http_response_code(400);
            exit('Invalid CSRF token');
        }
    }
}
