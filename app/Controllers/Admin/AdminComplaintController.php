<?php
// app/Controllers/Admin/ComplaintController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'complaints';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'update_complaint_status') {
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        
        $result = updateComplaintStatus($id, $status, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_complaints&msg=complaint_updated');
            exit;
        } else {
            $error = 'Failed to update complaint.';
        }
    } elseif ($formAction === 'delete_complaint') {
        $id = (int)$_POST['id'];
        $result = deleteComplaint($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_complaints&msg=complaint_deleted');
            exit;
        } else {
            $error = 'Failed to delete complaint.';
        }
    } elseif ($formAction === 'add_complaint_message') {
        $complaintId = (int)$_POST['complaint_id'];
        $messageText = trim($_POST['message']);
        
        if (empty($messageText)) {
            $error = 'Message cannot be empty.';
        } else {
            $result = addComplaintMessage($complaintId, $actorUserId, $messageText);
            if ($result) {
                header('Location: index.php?page=admin_complaints&action=view&id=' . $complaintId . '&msg=message_added');
                exit;
            } else {
                $error = 'Failed to add message.';
            }
        }
    }
}

// Handle GET requests
$pageTitle = 'Complaint Management';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['complaint'] = getComplaintById($id);
    $data['messages'] = getComplaintMessages($id);
    $pageTitle = 'View Complaint';
} else {
    $data['complaints'] = getAllComplaints();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'complaint_updated') {
        $message = 'Complaint status updated successfully.';
    } elseif ($_GET['msg'] === 'complaint_deleted') {
        $message = 'Complaint deleted successfully.';
    } elseif ($_GET['msg'] === 'message_added') {
        $message = 'Message added successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/ComplaintView.php';
