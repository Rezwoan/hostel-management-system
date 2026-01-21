<?php
// app/Controllers/Manager/ManagerComplaintController.php

require_once __DIR__ . '/../../Models/ManagerModel.php';

$managerUserId = $_SESSION['user_id'];
$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';
    
    if ($formAction === 'update_complaint_status') {
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        
        $result = manager_update_complaint_status($id, $status, $managerUserId);
        if ($result) {
            header('Location: index.php?page=manager_complaints&action=view&id=' . $id . '&msg=status_updated');
            exit;
        } else {
            $error = 'Failed to update complaint status.';
        }
    } elseif ($formAction === 'add_response') {
        $complaintId = (int)$_POST['complaint_id'];
        $messageText = trim($_POST['message'] ?? '');
        
        if (empty($messageText)) {
            $error = 'Response message cannot be empty.';
        } else {
            $result = manager_add_complaint_response($complaintId, $managerUserId, $messageText);
            if ($result) {
                header('Location: index.php?page=manager_complaints&action=view&id=' . $complaintId . '&msg=response_added');
                exit;
            } else {
                $error = 'Failed to add response.';
            }
        }
    }
}

// Handle GET requests
$pageTitle = 'Complaints';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['complaint'] = manager_get_complaint_by_id($id);
    $data['messages'] = manager_get_complaint_messages($id);
    $pageTitle = 'View Complaint';
} else {
    $data['complaints'] = manager_get_complaints($managerUserId);
    $data['stats'] = manager_get_complaint_stats($managerUserId);
    $data['categories'] = manager_get_complaint_categories();
    $data['open_count'] = count(array_filter($data['complaints'], function($c) {
        return in_array($c['status'], ['OPEN', 'IN_PROGRESS']);
    }));
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'status_updated') {
        $message = 'Complaint status updated successfully.';
    } elseif ($_GET['msg'] === 'response_added') {
        $message = 'Response added successfully.';
    }
}

require_once __DIR__ . '/../../Views/Manager/ManagerComplaintView.php';
