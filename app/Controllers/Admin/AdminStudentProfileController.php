<?php
// app/Controllers/Admin/StudentProfileController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'students';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'update_student' || $formAction === 'update_student_profile') {
        $userId = (int)$_POST['user_id'];
        $studentId = trim($_POST['student_id']);
        $department = trim($_POST['department']);
        $sessionYear = trim($_POST['session_year']);
        $dob = $_POST['dob'];
        $address = trim($_POST['address']);
        
        $result = updateStudentProfile($userId, $studentId, $department, $sessionYear, $dob, $address, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_students&msg=student_updated');
            exit;
        } else {
            $error = 'Failed to update student profile.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Student Management';
$data = [];

if ($action === 'view') {
    $userId = (int)$_GET['id'];
    $data['student'] = getStudentById($userId);
    $pageTitle = 'View Student';
} elseif ($action === 'edit') {
    $userId = (int)$_GET['id'];
    $data['student'] = getStudentById($userId);
    $pageTitle = 'Edit Student';
} else {
    $data['students'] = getAllStudents();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'student_updated') {
        $message = 'Student profile updated successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/StudentProfileView.php';
