<?php
// app/Controllers/Admin/ApplicationController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
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
        
        $result = updateRoomApplicationStatus($id, $status, $rejectReason, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_applications&msg=application_reviewed');
            exit;
        } else {
            $error = 'Failed to update application.';
        }
    } elseif ($formAction === 'revert_application') {
        $id = (int)$_POST['id'];
        $result = revertRoomApplicationStatus($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_applications&action=view&id=' . $id . '&msg=application_reverted');
            exit;
        } else {
            $error = 'Failed to revert application.';
        }
    } elseif ($formAction === 'delete_application') {
        $id = (int)$_POST['id'];
        $result = deleteRoomApplication($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_applications&msg=application_deleted');
            exit;
        } else {
            $error = 'Failed to delete application.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Room Applications';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['application'] = getRoomApplicationById($id);
    $pageTitle = 'View Application';
} else {
    $data['applications'] = getAllRoomApplications();
    $data['stats'] = getApplicationStats();
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

require_once __DIR__ . '/../../Views/Admin/ApplicationView.php';
