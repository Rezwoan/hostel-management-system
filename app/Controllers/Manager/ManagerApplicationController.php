<?php
// app/Controllers/Manager/ManagerApplicationController.php

require_once __DIR__ . '/../../Models/ManagerModel.php';

$managerUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'applications';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'review_application' || $formAction === 'update_application_status') {
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        $rejectReason = isset($_POST['reject_reason']) ? trim($_POST['reject_reason']) : '';
        
        // Use manager-specific functions
        if ($status === 'APPROVED') {
            $result = manager_approve_application($id, $managerUserId);
        } elseif ($status === 'REJECTED') {
            if (empty($rejectReason)) {
                $error = 'Rejection reason is required.';
                $result = false;
            } else {
                $result = manager_reject_application($id, $rejectReason, $managerUserId);
            }
        } else {
            $result = false;
        }
        
        if ($result) {
            header('Location: index.php?page=manager_applications&msg=application_reviewed');
            exit;
        } else {
            if (empty($error)) {
                $error = 'Failed to update application.';
            }
        }
    } elseif ($formAction === 'delete_application') {
        $id = (int)$_POST['id'];
        // Managers cannot delete applications - redirect with error
        header('Location: index.php?page=manager_applications&error=permission_denied');
        exit;
    }
}

// Handle GET requests
$pageTitle = 'Room Applications';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['application'] = manager_get_application_by_id($id);
    
    // Verify manager has access to this application's hostel
    if ($data['application']) {
        $assignedHostels = manager_get_assigned_hostels($managerUserId);
        $hostelIds = array_column($assignedHostels, 'id');
        
        if (!in_array($data['application']['hostel_id'], $hostelIds)) {
            header('Location: index.php?page=manager_applications&error=access_denied');
            exit;
        }
    }
    
    $pageTitle = 'View Application';
} else {
    $data['applications'] = manager_get_all_applications($managerUserId);
    $data['stats'] = manager_get_application_stats($managerUserId);
    $data['hostels'] = manager_get_assigned_hostels($managerUserId);
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'application_reviewed') {
        $message = 'Application reviewed successfully.';
    } elseif ($_GET['msg'] === 'application_deleted') {
        $message = 'Application deleted successfully.';
    } elseif ($_GET['msg'] === 'application_reverted') {
        $message = 'Application status has been reverted to SUBMITTED. You can now review it again.';
    }
}

// Handle error messages
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'access_denied') {
        $error = 'Access denied. You do not have permission to access this application.';
    } elseif ($_GET['error'] === 'permission_denied') {
        $error = 'Permission denied. Managers cannot delete applications.';
    }
}

require_once __DIR__ . '/../../Views/Manager/ManagerApplicationView.php';
