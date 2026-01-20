<?php
// app/Controllers/Auth/SignupController.php

require_once __DIR__ . '/../../Models/StudentModel.php';
require_once __DIR__ . '/../../Models/AdminModel.php';

$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName   = trim($_POST['full_name']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $phone      = trim($_POST['phone']);
    $studentId  = trim($_POST['student_id']);
    $dept       = trim($_POST['department']);
    $session    = trim($_POST['session']);
    $dob        = trim($_POST['dob']);
    $address    = trim($_POST['address']);

    if (empty($fullName) || empty($email) || empty($password) || empty($phone) || 
        empty($studentId) || empty($dept) || empty($session) || empty($dob) || empty($address)) {
        
        $error_msg = "All fields are mandatory. Please fill in everything.";
    
    } else {
        $idValid = true;

        if (strlen($studentId) !== 10) {
            $idValid = false;
        } else {
            $parts = explode('-', $studentId);
            
            if (count($parts) !== 3) {
                $idValid = false;
            } else {
                if (!is_numeric($parts[0]) || strlen($parts[0]) !== 2) $idValid = false;
                if (!is_numeric($parts[1]) || strlen($parts[1]) !== 5) $idValid = false;
                if (!is_numeric($parts[2]) || strlen($parts[2]) !== 1) $idValid = false;
            }
        }

        if (!$idValid) {
            $error_msg = "Invalid Student ID Format. Use XX-XXXXX-X (e.g., 23-12345-1).";
        } else {
            // Validate profile picture if uploaded
            $profilePicturePath = 'uploads/profile_pictures/default.png';
            $uploadError = '';
            
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['profile_picture'];
                $fileSize = $file['size'];
                $fileTmpName = $file['tmp_name'];
                
                // Validate file size (2MB max)
                if ($fileSize > 2 * 1024 * 1024) {
                    $uploadError = "Profile picture must be less than 2MB.";
                } else {
                    // Validate file type using getimagesize
                    $imageInfo = getimagesize($fileTmpName);
                    if ($imageInfo === false) {
                        $uploadError = "Invalid image file.";
                    } else {
                        $mimeType = $imageInfo['mime'];
                        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                        
                        if (!in_array($mimeType, $allowedTypes)) {
                            $uploadError = "Only JPG, PNG, and WEBP images are allowed.";
                        }
                    }
                }
            }
            
            if (!empty($uploadError)) {
                $error_msg = $uploadError;
            } else {
                $result = createStudentAccount(
                    $fullName,
                    $email,
                    $password,
                    $phone,
                    $studentId,
                    $dept,
                    $session,
                    $dob,
                    $address
                );

                if (is_numeric($result)) {
                    // $result is the new user ID
                    
                    // Handle profile picture upload after account creation
                    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                        $file = $_FILES['profile_picture'];
                        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                        $newFileName = 'student_' . $result . '_' . time() . '.' . $fileExtension;
                        $uploadDir = 'public/uploads/profile_pictures/';
                        
                        // Create directory if it doesn't exist
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        
                        $uploadPath = $uploadDir . $newFileName;
                        
                        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                            $profilePicturePath = 'uploads/profile_pictures/' . $newFileName;
                            student_update_profile_picture($result, $profilePicturePath);
                        }
                    }
                    
                    logSignupEvent($result, $email, $fullName);
                    $success_msg = "Account created successfully! <a href='index.php?page=login'>Login Here</a>";
                } elseif ($result === true) {
                    // Legacy support if function returns true
                    $success_msg = "Account created successfully! <a href='index.php?page=login'>Login Here</a>";
                } else {
                    $error_msg = $result;
                }
            }
        }
    }
}

require_once __DIR__ . '/../../Views/Auth/SignupView.php';