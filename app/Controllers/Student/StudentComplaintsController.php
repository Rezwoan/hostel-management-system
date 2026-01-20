<?php
// app/Controllers/Student/StudentComplaintsController.php

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'STUDENT') {
    header("Location: index.php?page=login");
    exit;
}

require_once __DIR__ . '/../../Models/StudentModel.php';

$studentUserId = $_SESSION['user_id'];
$pageTitle = 'Complaints';
$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';
    
    if ($formAction === 'create_complaint') {
        $categoryId = (int)$_POST['category_id'];
        $subject = $_POST['subject'] ?? '';
        $description = $_POST['description'] ?? '';
        
        // Get student's hostel from allocation
        $allocation = student_get_allocation($studentUserId);
        $hostelId = $allocation ? (int)$allocation['hostel_id'] : 0;
        
        if (!$hostelId) {
            $error = 'You must have a room allocation to file a complaint.';
        } else {
            $conn = dbConnect();
            $sql = "INSERT INTO complaints (student_user_id, hostel_id, category_id, subject, description, status) 
                    VALUES ($studentUserId, $hostelId, $categoryId, '$subject', '$description', 'OPEN')";
            $result = mysqli_query($conn, $sql);
            $complaintId = mysqli_insert_id($conn);
            mysqli_close($conn);
            
            if ($result) {
                $message = 'Complaint submitted successfully!';
                $action = 'list';
            } else {
                $error = 'Failed to submit complaint.';
            }
        }
    } elseif ($formAction === 'add_message') {
        $complaintId = (int)$_POST['complaint_id'];
        $messageText = $_POST['message'] ?? '';
        
        $result = student_add_complaint_message($complaintId, $studentUserId, $messageText);
        
        if ($result) {
            $message = 'Message added successfully.';
        } else {
            $error = 'Failed to add message.';
        }
    }
}

// Get complaints list
$complaints = student_get_complaints($studentUserId);

// Get complaint categories for form
$categories = [];
$conn = dbConnect();
$sql = "SELECT * FROM complaint_categories ORDER BY name ASC";
$result = mysqli_query($conn, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

// If viewing a specific complaint
$complaint = null;
$messages = [];
if ($action === 'view') {
    $complaintId = (int)($_GET['id'] ?? 0);
    if ($complaintId) {
        $complaint = student_get_complaint($complaintId, $studentUserId);
        if ($complaint) {
            $messages = student_get_complaint_messages($complaintId);
        }
    }
}

$data = [
    'complaints' => $complaints,
    'categories' => $categories,
    'complaint' => $complaint,
    'messages' => $messages
];

require_once __DIR__ . '/../../Views/Student/StudentComplaintsView.php';
