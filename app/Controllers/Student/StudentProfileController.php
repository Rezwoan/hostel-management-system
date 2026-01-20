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
    } elseif ($formAction === 'update_profile_picture') {
        // Validate profile picture upload
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['profile_picture'];
            $fileSize = $file['size'];
            $fileTmpName = $file['tmp_name'];
            
            // Validate file size (2MB max)
            if ($fileSize > 2 * 1024 * 1024) {
                $error = "Profile picture must be less than 2MB.";
            } else {
                // Validate file type using getimagesize
                $imageInfo = getimagesize($fileTmpName);
                if ($imageInfo === false) {
                    $error = "Invalid image file.";
                } else {
                    $mimeType = $imageInfo['mime'];
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    
                    if (!in_array($mimeType, $allowedTypes)) {
                        $error = "Only JPG, PNG, and WEBP images are allowed.";
                    } else {
                        // Get old picture path to delete later
                        $oldPicture = student_get_profile_picture($studentUserId);
                        
                        // Generate unique filename
                        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                        $newFileName = 'student_' . $studentUserId . '_' . time() . '.' . $fileExtension;
                        $uploadDir = 'public/uploads/profile_pictures/';
                        
                        // Create directory if it doesn't exist
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        
                        $uploadPath = $uploadDir . $newFileName;
                        
                        if (move_uploaded_file($fileTmpName, $uploadPath)) {
                            $profilePicturePath = 'uploads/profile_pictures/' . $newFileName;
                            
                            // Update database
                            if (student_update_profile_picture($studentUserId, $profilePicturePath)) {
                                // Delete old picture if not default
                                if ($oldPicture !== 'uploads/profile_pictures/default.png' && file_exists('public/' . $oldPicture)) {
                                    unlink('public/' . $oldPicture);
                                }
                                
                                $message = 'Profile picture updated successfully!';
                            } else {
                                $error = 'Failed to update profile picture in database.';
                            }
                        } else {
                            $error = 'Failed to upload profile picture.';
                        }
                    }
                }
            }
        } else {
            $error = 'Please select an image file to upload.';
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
