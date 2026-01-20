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
        $hostelId = (int)$_POST['hostel_id'];
        $roomTypeId = (int)$_POST['room_type_id'];
        $notes = $_POST['notes'] ?? '';
        
        $result = student_create_application($studentUserId, $hostelId, $roomTypeId, $notes);
        
        if ($result) {
            $message = 'Application submitted successfully!';
        } else {
            $error = 'Failed to submit application. You may already have a pending application.';
        }
    } elseif ($formAction === 'cancel_application') {
        $applicationId = (int)$_POST['id'];
        $result = student_cancel_application($applicationId, $studentUserId);
        
        if ($result) {
            $message = 'Application cancelled successfully.';
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

$data = [
    'applications' => $applications,
    'hostels' => $hostels,
    'roomTypes' => $roomTypes
];

require_once __DIR__ . '/../../Views/Student/StudentApplicationsView.php';
