<?php
// app/Controllers/Auth/LoginController.php

require_once __DIR__ . '/../../Models/AuthModel.php';

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

    if (empty($email) || empty($password)) {
        $error_msg = "Please enter both email and password.";
    } else {
        $result = loginUser($email, $password);

        if ($result['success']) {
            // Set Session Data
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['name']    = $result['user']['name'];
            $_SESSION['email']   = $result['user']['email'];
            
            // STRICT ROLE CHECKING
            $roles = $result['user']['roles']; 

            if (in_array('ADMIN', $roles)) {
                $_SESSION['role'] = 'ADMIN';
                header("Location: index.php?page=admin_dashboard");
            } elseif (in_array('MANAGER', $roles)) {
                $_SESSION['role'] = 'MANAGER';
                header("Location: index.php?page=manager_dashboard");
            } elseif (in_array('STUDENT', $roles)) {
                $_SESSION['role'] = 'STUDENT';
                header("Location: index.php?page=student_dashboard");
            } 
            else {
                $error_msg = "Login failed: No valid role assigned to this account.";
                session_destroy(); 
            }
            exit;

        } else {
            $error_msg = $result['message'];
        }
    }
}

require_once __DIR__ . '/../../Views/Auth/LoginView.php';
?>