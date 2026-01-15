<?php
// app/Controllers/Auth/SignupController.php

require_once __DIR__ . '/../../Models/StudentModel.php';

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
                $success_msg = "Account created successfully! <a href='index.php?page=login'>Login Here</a>";
            } else {
                $error_msg = $result;
            }
        }
    }
}

require_once __DIR__ . '/../../Views/Auth/SignupView.php';