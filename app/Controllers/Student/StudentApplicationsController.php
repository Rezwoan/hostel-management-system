<?php
// app/Controllers/Student/StudentApplicationsController.php

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'STUDENT') {
    header("Location: index.php?page=login");
    exit;
}

require_once __DIR__ . '/../../Models/StudentModel.php';

$studentUserId = $_SESSION['user_id'];
$pageTitle = 'Room Applications';
$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';
    
    if ($formAction === 'create_application') {
        // Check for duplicate submission token
        $submittedToken = $_POST['submit_token'] ?? '';
        $sessionToken = $_SESSION['last_submit_token'] ?? '';
        
        if ($submittedToken && $submittedToken === $sessionToken) {
            // Duplicate submission detected
            header('Location: index.php?page=student_applications&msg=duplicate');
            exit;
        }
        
        $hostelId = (int)$_POST['hostel_id'];
        $roomTypeId = (int)$_POST['room_type_id'];
        $notes = $_POST['notes'] ?? '';
        
        $result = student_create_application($studentUserId, $hostelId, $roomTypeId, $notes);
        
        if ($result) {
            // Save token to prevent resubmission
            $_SESSION['last_submit_token'] = $submittedToken;
            // Redirect to prevent form resubmission on page refresh
            header('Location: index.php?page=student_applications&msg=application_created');
            exit;
        } else {
            $error = 'Failed to submit application. You may already have a pending application.';
        }
    } elseif ($formAction === 'cancel_application') {
        $applicationId = (int)$_POST['id'];
        $result = student_cancel_application($applicationId, $studentUserId);
        
        if ($result) {
            // Redirect to prevent form resubmission
            header('Location: index.php?page=student_applications&msg=application_cancelled');
            exit;
        } else {
            $error = 'Failed to cancel application.';
        }
    }
}

// Get applications list
$applications = student_get_applications($studentUserId);

// Get available hostels and room types for form
$hostels = student_get_available_hostels();
$roomTypes = student_get_room_types();

// Generate unique form token to prevent duplicate submissions
$formToken = bin2hex(random_bytes(16));

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'application_created') {
        $message = 'Application submitted successfully!';
    } elseif ($_GET['msg'] === 'application_cancelled') {
        $message = 'Application cancelled successfully.';
    } elseif ($_GET['msg'] === 'duplicate') {
        $error = 'This form was already submitted. Please do not submit the form multiple times.';
    }
}

$data = [
    'applications' => $applications,
    'hostels' => $hostels,
    'roomTypes' => $roomTypes,
    'formToken' => $formToken
];

require_once __DIR__ . '/../../Views/Student/StudentApplicationsView.php';
