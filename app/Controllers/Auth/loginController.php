<?php
// app/Controllers/Auth/LoginController.php

require_once __DIR__ . '/../../Models/AuthModel.php';
require_once __DIR__ . '/../../Models/AdminModel.php';

// Cookie name for remember me
define('REMEMBER_COOKIE_NAME', 'hms_remember_token');
define('REMEMBER_COOKIE_EXPIRY', 30 * 24 * 60 * 60); // 30 days

// Check for remember me cookie and auto-login
if (!isset($_SESSION['user_id']) && isset($_COOKIE[REMEMBER_COOKIE_NAME])) {
    $token = $_COOKIE[REMEMBER_COOKIE_NAME];
    $user = validateRememberToken($token);
    
    if ($user) {
        // Set session data from remembered user
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        
        // Log the auto-login via remember me
        logLoginEvent($user['id'], $user['email'], true, $user['role'] . ' (Remember Me)');
        
        // Redirect to appropriate dashboard
        if ($user['role'] === 'ADMIN') {
            header("Location: index.php?page=admin_dashboard");
        } elseif ($user['role'] === 'MANAGER') {
            header("Location: index.php?page=manager_dashboard");
        } elseif ($user['role'] === 'STUDENT') {
            header("Location: index.php?page=student_dashboard");
        }
        exit;
    } else {
        // Invalid token, clear the cookie
        setcookie(REMEMBER_COOKIE_NAME, '', time() - 3600, '/', '', false, true);
    }
}

// 1. Redirect if ALREADY logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'ADMIN') {
        header("Location: index.php?page=admin_dashboard");
    } elseif ($_SESSION['role'] === 'MANAGER') {
        header("Location: index.php?page=manager_dashboard");
    } elseif ($_SESSION['role'] === 'STUDENT') {
        header("Location: index.php?page=student_dashboard");
    }
    exit;
}

$error_msg = "";

// 2. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    if (empty($email) || empty($password)) {
        $error_msg = "Please enter both email and password.";
    } else {
        $result = loginUser($email, $password);

        if ($result['success']) {
            // Set Session Data
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['user_name'] = $result['user']['name'];
            $_SESSION['name']    = $result['user']['name'];
            $_SESSION['email']   = $result['user']['email'];
            
            // STRICT ROLE CHECKING
            $roles = $result['user']['roles']; 
            $primaryRole = null;

            if (in_array('ADMIN', $roles)) {
                $primaryRole = 'ADMIN';
                $_SESSION['role'] = 'ADMIN';
            } elseif (in_array('MANAGER', $roles)) {
                $primaryRole = 'MANAGER';
                $_SESSION['role'] = 'MANAGER';
            } elseif (in_array('STUDENT', $roles)) {
                $primaryRole = 'STUDENT';
                $_SESSION['role'] = 'STUDENT';
            }
            
            if ($primaryRole) {
                // Log successful login
                logLoginEvent($result['user']['id'], $email, true, $primaryRole);
                
                // Handle Remember Me
                if ($remember) {
                    $token = createRememberToken($result['user']['id']);
                    if ($token) {
                        setcookie(
                            REMEMBER_COOKIE_NAME, 
                            $token, 
                            time() + REMEMBER_COOKIE_EXPIRY, 
                            '/',           // Path
                            '',            // Domain (empty = current domain)
                            false,         // Secure (set to true if using HTTPS)
                            true           // HttpOnly - prevents JavaScript access
                        );
                    }
                }
                
                // Redirect to appropriate dashboard
                if ($primaryRole === 'ADMIN') {
                    header("Location: index.php?page=admin_dashboard");
                } elseif ($primaryRole === 'MANAGER') {
                    header("Location: index.php?page=manager_dashboard");
                } elseif ($primaryRole === 'STUDENT') {
                    header("Location: index.php?page=student_dashboard");
                }
                exit;
            } else {
                $error_msg = "Login failed: No valid role assigned to this account.";
                logLoginEvent($result['user']['id'], $email, false);
                session_destroy(); 
            }

        } else {
            $error_msg = $result['message'];
            // Log failed login attempt
            logLoginEvent(0, $email, false);
        }
    }
}

require_once __DIR__ . '/../../Views/Auth/LoginView.php';
?>