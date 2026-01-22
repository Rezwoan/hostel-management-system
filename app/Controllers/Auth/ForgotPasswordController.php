<?php
// app/Controllers/Auth/ForgotPasswordController.php

require_once __DIR__ . '/../../Models/AuthModel.php';

$error_msg = '';
$success_msg = '';
$step = 'verify'; // 'verify' or 'reset'
$verified_user_id = null;

// Check if user came from successful verification (stored in session)
if (isset($_SESSION['reset_user_id']) && isset($_SESSION['reset_token'])) {
    $step = 'reset';
    $verified_user_id = $_SESSION['reset_user_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Step 1: Verify identity (email + student_id + dob)
    if (isset($_POST['verify_identity'])) {
        $email = trim($_POST['email'] ?? '');
        $student_id = trim($_POST['student_id'] ?? '');
        $dob = trim($_POST['dob'] ?? '');
        
        // Validate inputs
        if (empty($email) || empty($student_id) || empty($dob)) {
            $error_msg = 'All fields are required.';
        } else {
            // Verify student identity
            $result = verifyStudentIdentity($email, $student_id, $dob);
            
            if ($result['success']) {
                // Store verified user in session with a token
                $_SESSION['reset_user_id'] = $result['user_id'];
                $_SESSION['reset_token'] = bin2hex(random_bytes(16));
                $_SESSION['reset_expires'] = time() + 600; // 10 minutes
                
                $step = 'reset';
                $verified_user_id = $result['user_id'];
                $success_msg = 'Identity verified! Please set your new password.';
            } else {
                $error_msg = $result['message'];
            }
        }
    }
    
    // Step 2: Reset password
    if (isset($_POST['reset_password'])) {
        // Verify session is still valid
        if (!isset($_SESSION['reset_user_id']) || !isset($_SESSION['reset_token']) || !isset($_SESSION['reset_expires'])) {
            $error_msg = 'Session expired. Please verify your identity again.';
            $step = 'verify';
        } elseif (time() > $_SESSION['reset_expires']) {
            // Session expired
            unset($_SESSION['reset_user_id'], $_SESSION['reset_token'], $_SESSION['reset_expires']);
            $error_msg = 'Session expired. Please verify your identity again.';
            $step = 'verify';
        } else {
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validate passwords
            if (empty($new_password) || empty($confirm_password)) {
                $error_msg = 'Both password fields are required.';
                $step = 'reset';
            } elseif (strlen($new_password) < 6) {
                $error_msg = 'Password must be at least 6 characters long.';
                $step = 'reset';
            } elseif ($new_password !== $confirm_password) {
                $error_msg = 'Passwords do not match.';
                $step = 'reset';
            } else {
                // Update password
                $result = resetUserPassword($_SESSION['reset_user_id'], $new_password);
                
                if ($result['success']) {
                    // Clear session data
                    unset($_SESSION['reset_user_id'], $_SESSION['reset_token'], $_SESSION['reset_expires']);
                    
                    // Redirect to login with success message
                    $_SESSION['login_success_msg'] = 'Password reset successful! Please login with your new password.';
                    header('Location: index.php?page=login');
                    exit;
                } else {
                    $error_msg = $result['message'];
                    $step = 'reset';
                }
            }
        }
    }
}

// Load the view
require_once __DIR__ . '/../../Views/Auth/ForgotPasswordView.php';
