<?php
// app/Controllers/Student/StudentProfileController.php

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'STUDENT') {
    header("Location: index.php?page=login");
    exit;
}

require_once __DIR__ . '/../../Models/StudentModel.php';

$studentUserId = $_SESSION['user_id'];
$pageTitle = 'My Profile';
$action = $_GET['action'] ?? 'view';
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';
    
    if ($formAction === 'update_profile') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $department = $_POST['department'] ?? '';
        $sessionYear = $_POST['session_year'] ?? '';
        $dob = $_POST['dob'] ?? '';
        $address = $_POST['address'] ?? '';
        
        $result = student_update_profile($studentUserId, $name, $email, $phone, $department, $sessionYear, $dob, $address);
        
        if ($result) {
            $_SESSION['name'] = $name;
            $message = 'Profile updated successfully!';
            $action = 'view';
        } else {
            $error = 'Failed to update profile.';
        }
    } elseif ($formAction === 'change_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Verify current password
        $student = student_get_by_id($studentUserId);
        if (!password_verify($currentPassword, $student['password_hash'])) {
            $error = 'Current password is incorrect.';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'New passwords do not match.';
        } elseif (strlen($newPassword) < 8) {
            $error = 'Password must be at least 8 characters.';
        } else {
            $result = student_update_password($studentUserId, $newPassword);
            if ($result) {
                $message = 'Password changed successfully!';
            } else {
                $error = 'Failed to change password.';
            }
        }
    }
}

// Get student profile
$profile = student_get_by_id($studentUserId);

$data = [
    'profile' => $profile
];

require_once __DIR__ . '/../../Views/Student/StudentProfileView.php';
