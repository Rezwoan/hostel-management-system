<?php
// app/Controllers/Auth/AuthController.php

require_once __DIR__ . '/../../Models/user.php';

function auth_show_login(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // If already logged in, redirect to dashboard (guarded routes)
    if (!empty($_SESSION['user_id'])) {
        auth_redirect_dashboard($_SESSION['roles'] ?? []);
        exit;
    }

    require __DIR__ . '/../../Views/Auth/login.php';
    exit;
}

function auth_login_post(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
    $pass  = isset($_POST['password']) ? (string)$_POST['password'] : '';

    if ($email === '' || $pass === '') {
        header('Location: index.php?page=login&err=1');
        exit;
    }

    $user = user_find_by_email($email);

    // Generic error so attacker can't guess email
    if (!$user) {
        header('Location: index.php?page=login&err=1');
        exit;
    }

    // Optional: block inactive/locked users
    if (!empty($user['status']) && $user['status'] !== 'ACTIVE') {
        header('Location: index.php?page=login&err=1');
        exit;
    }

    $hash = $user['password_hash'] ?? '';
    if (!is_string($hash) || $hash === '' || !password_verify($pass, $hash)) {
        header('Location: index.php?page=login&err=1');
        exit;
    }

    // Login success
    session_regenerate_id(true);

    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['name']    = (string)$user['name'];
    $_SESSION['email']   = (string)$user['email'];
    $_SESSION['roles']   = user_get_roles((int)$user['id']); // ['ADMIN','MANAGER','STUDENT']

    // Redirect to guarded dashboard routes (index.php?page=...)
    auth_redirect_dashboard($_SESSION['roles']);
    exit;
}

function auth_logout(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'] ?? '/',
            $params['domain'] ?? '',
            (bool)($params['secure'] ?? false),
            (bool)($params['httponly'] ?? true)
        );
    }

    session_destroy();

    header('Location: index.php?page=login');
    exit;
}

function auth_redirect_dashboard(array $roles): void
{
    $roles = array_map('strtoupper', $roles);

    // Priority: ADMIN -> MANAGER -> STUDENT
    if (in_array('ADMIN', $roles, true)) {
        header('Location: index.php?page=admin_dashboard');
        exit;
    }

    if (in_array('MANAGER', $roles, true)) {
        header('Location: index.php?page=manager_dashboard');
        exit;
    }

    header('Location: index.php?page=student_dashboard');
    exit;
}
