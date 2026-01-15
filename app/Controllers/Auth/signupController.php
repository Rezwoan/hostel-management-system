<?php
// app/Controllers/Auth/signupController.php

// 1. Remove session_start() because index.php already handles it.
// session_start(); 

// 2. Load Model (Up 2 levels from Controllers/Auth -> app -> Models)
require_once __DIR__ . '/../../Models/student_functions.php';

$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. EXTRACT & CLEAN DATA
    $fullName   = trim($_POST['full_name']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $phone      = trim($_POST['phone']);
    
    $studentId  = trim($_POST['student_id']);
    $dept       = trim($_POST['department']);
    $session    = trim($_POST['session']);

    // 4. VALIDATION
    if (empty($fullName) || empty($email) || empty($password) || empty($studentId)) {
        $error_msg = "Please fill in all required fields (Name, Email, Password, Student ID).";
    } else {
        
        // 5. CALL MODEL
        $result = createStudentAccount(
            $fullName,
            $email,
            $password,
            $phone,
            $studentId,
            $dept,
            $session
        );

        // 6. HANDLE RESULT
        if ($result === true) {
            // FIX: Link uses the router (index.php?page=login)
            $success_msg = "✅ Account created successfully! <a href='index.php?page=login'>Login Here</a>";
        } else {
            $error_msg = "❌ " . $result;
        }
    }
}

// 7. LOAD VIEW
// FIX: Path goes up 2 levels (../../) to 'app', then down to 'Views/Auth/'
require_once __DIR__ . '/../../Views/Auth/signup_view.php';
?>