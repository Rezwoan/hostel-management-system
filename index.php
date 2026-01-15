<?php

// 1. Start Session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// 2. Get Requested Page (Default to login)
$page = isset($_GET['page']) ? (string)$_GET['page'] : 'login';


// ==========================================
// HELPER FUNCTIONS
// ==========================================

function is_logged_in(): bool
{
    // Checks if the user_id exists in session (set by loginController)
    return !empty($_SESSION['user_id']);
}

function has_role(string $required_role): bool
{
    // Our login logic sets a single primary 'role' in the session
    $user_role = $_SESSION['role'] ?? '';
    return strtoupper($user_role) === strtoupper($required_role);
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
    
    // Check if the current user has the required role
    if (!has_role($role)) {
        http_response_code(403);
        echo "<h1 style='color:red; text-align:center; margin-top:20%;'>403 Forbidden</h1>";
        echo "<p style='text-align:center;'>You do not have permission to access this page.</p>";
        echo "<p style='text-align:center;'><a href='index.php?page=login'>Go Back</a></p>";
        exit;
    }
}


// ==========================================
// ROUTING LOGIC
// ==========================================

switch ($page) {

    // --- AUTHENTICATION ---
    case 'login':
        // If already logged in, redirect to correct dashboard
        if (is_logged_in() && isset($_SESSION['role'])) {
             $role = strtoupper($_SESSION['role']);
             if ($role === 'ADMIN') header('Location: index.php?page=admin_dashboard');
             elseif ($role === 'MANAGER') header('Location: index.php?page=manager_dashboard');
             elseif ($role === 'STUDENT') header('Location: index.php?page=student_dashboard');
             exit;
        }
        // Load the Controller (which loads the View)
        require_once __DIR__ . '/app/Controllers/Auth/LoginController.php';
        break;

    case 'signup':
        require_once __DIR__ . '/app/Controllers/Auth/SignupController.php';
        break;

    case 'logout':
        // Destroy session and redirect to login
        session_unset();
        session_destroy();
        header('Location: index.php?page=login');
        exit;


    // --- DASHBOARDS ---
    case 'admin_dashboard':
        require_role('ADMIN');
        // Matches: app/Controllers/Admin/AdminController.php
        require_once __DIR__ . '/app/Controllers/Admin/AdminController.php';
        break;

    case 'manager_dashboard':
        require_role('MANAGER');
        // Matches: app/Controllers/Manager/ManagerController.php
        require_once __DIR__ . '/app/Controllers/Manager/ManagerController.php';
        break;

    case 'student_dashboard':
        require_role('STUDENT');
        require_once __DIR__ . '/app/Controllers/Student/StudentController.php';
        break;


    // --- 404 NOT FOUND ---
    default:
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The page '$page' does not exist.</p>";
        break;
}