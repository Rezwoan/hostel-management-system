<?php


if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$page  = isset($_GET['page']) ? (string)$_GET['page'] : 'login';
$roles = isset($_SESSION['roles']) && is_array($_SESSION['roles']) ? $_SESSION['roles'] : [];


function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function has_role(string $role): bool
{
    $roles = isset($_SESSION['roles']) && is_array($_SESSION['roles']) ? $_SESSION['roles'] : [];
    $roles = array_map('strtoupper', $roles);
    return in_array(strtoupper($role), $roles, true);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: index.php?page=login');
        exit;
    }
}

function require_role(string $role): void
{
    require_login();
    if (!has_role($role)) {
        http_response_code(403);
        echo "Forbidden";
        exit;
    }
}


if ($page === 'login') {
    require_once __DIR__ . '/app/Controllers/Auth/AuthController.php';
    auth_show_login();

} elseif ($page === 'login_post') {
    require_once __DIR__ . '/app/Controllers/Auth/AuthController.php';
    auth_login_post();

} elseif ($page === 'logout') {
    require_once __DIR__ . '/app/Controllers/Auth/AuthController.php';
    auth_logout();

} elseif ($page === 'admin_dashboard') {
    require_role('ADMIN');
    require_once __DIR__ . '/app/Views/Admin/dashboard.php';

} elseif ($page === 'manager_dashboard') {
    require_role('MANAGER');
    require_once __DIR__ . '/app/Views/Manager/dashboard.php';

} elseif ($page === 'student_dashboard') {
    require_role('STUDENT');
    require_once __DIR__ . '/app/Views/Student/dashboard.php';

} else {
    header('Location: index.php?page=login');
    exit;
}
