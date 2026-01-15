<?php
// app/Controllers/Auth/signupController.php

// 1. Remove session_start() (Index handles it)
// session_start();

// 2. Load Model
require_once __DIR__ . '/../../Models/student_functions.php';

$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. EXTRACT DATA
    $fullName   = trim($_POST['full_name']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $phone      = trim($_POST['phone']);
    $studentId  = trim($_POST['student_id']);
    $dept       = trim($_POST['department']);
    $session    = trim($_POST['session']);
    $dob        = trim($_POST['dob']);
    $address    = trim($_POST['address']);

    // 4. STRICT VALIDATION CHECKS

    // A. Check for Empty Fields
    if (empty($fullName) || empty($email) || empty($password) || empty($phone) || 
        empty($studentId) || empty($dept) || empty($session) || empty($dob) || empty($address)) {
        
        $error_msg = "❌ Error: All fields are mandatory. Please fill in everything.";
    
    } else {
        // B. Validate Student ID Format (XX-XXXXX-X) without Regex
        // Expected Length: 10 chars (2 digits + '-' + 5 digits + '-' + 1 digit)
        $idValid = true;

        if (strlen($studentId) !== 10) {
            $idValid = false;
        } else {
            // Split by hyphen
            $parts = explode('-', $studentId);
            
            // Must have exactly 3 parts
            if (count($parts) !== 3) {
                $idValid = false;
            } else {
                // Check Part 1: XX (2 digits)
                if (!is_numeric($parts[0]) || strlen($parts[0]) !== 2) $idValid = false;
                
                // Check Part 2: XXXXX (5 digits)
                if (!is_numeric($parts[1]) || strlen($parts[1]) !== 5) $idValid = false;
                
                // Check Part 3: X (1 digit)
                if (!is_numeric($parts[2]) || strlen($parts[2]) !== 1) $idValid = false;
            }
        }

        if (!$idValid) {
            $error_msg = "❌ Invalid Student ID Format. Use XX-XXXXX-X (e.g., 23-12345-1).";
        } else {
            // C. All Checks Passed -> Call Model
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

            if ($result === true) {
                $success_msg = "✅ Account created successfully! <a href='index.php?page=login'>Login Here</a>";
            } else {
                $error_msg = "❌ " . $result;
            }
        }
    }
}

// 5. LOAD VIEW
require_once __DIR__ . '/../../Views/Auth/signup_view.php';
?>