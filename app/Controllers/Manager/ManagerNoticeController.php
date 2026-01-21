<?php
// app/Controllers/Manager/ManagerNoticeController.php

require_once __DIR__ . '/../../Models/ManagerModel.php';

$managerUserId = $_SESSION['user_id'];
$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';
    
    if ($formAction === 'create_notice') {
        $hostelId = (int)$_POST['hostel_id'];
        $title = trim($_POST['title']);
        $body = trim($_POST['body']);
        $status = $_POST['status'];
        $publishAt = !empty($_POST['publish_at']) ? $_POST['publish_at'] : null;
        $expireAt = !empty($_POST['expire_at']) ? $_POST['expire_at'] : null;
        
        if (empty($title) || empty($body)) {
            $error = 'Title and body are required.';
        } else {
            $result = manager_create_hostel_notice($hostelId, $title, $body, $status, $publishAt, $expireAt, $managerUserId);
            if ($result) {
                header('Location: index.php?page=manager_notices&msg=notice_created');
                exit;
            } else {
                $error = 'Failed to create notice.';
            }
        }
    } elseif ($formAction === 'update_notice') {
        $id = (int)$_POST['id'];
        $title = trim($_POST['title']);
        $body = trim($_POST['body']);
        $status = $_POST['status'];
        $publishAt = !empty($_POST['publish_at']) ? $_POST['publish_at'] : null;
        $expireAt = !empty($_POST['expire_at']) ? $_POST['expire_at'] : null;
        
        if (empty($title) || empty($body)) {
            $error = 'Title and body are required.';
        } else {
            $result = manager_update_notice($id, $title, $body, $status, $publishAt, $expireAt, $managerUserId);
            if ($result) {
                header('Location: index.php?page=manager_notices&msg=notice_updated');
                exit;
            } else {
                $error = 'Failed to update notice.';
            }
        }
    } elseif ($formAction === 'delete_notice') {
        $id = (int)$_POST['id'];
        $result = manager_delete_notice($id, $managerUserId);
        if ($result) {
            header('Location: index.php?page=manager_notices&msg=notice_deleted');
            exit;
        } else {
            $error = 'Failed to delete notice.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Hostel Notices';
$data = [];

if ($action === 'add') {
    $hostels = manager_get_assigned_hostels($managerUserId);
    $data['hostels'] = $hostels;
    $pageTitle = 'Create Notice';
} elseif ($action === 'edit') {
    $id = (int)$_GET['id'];
    $data['notice'] = manager_get_notice_by_id($id);
    $hostels = manager_get_assigned_hostels($managerUserId);
    $data['hostels'] = $hostels;
    $pageTitle = 'Edit Notice';
} else {
    $data['notices'] = manager_get_hostel_notices($managerUserId);
    $data['hostels'] = manager_get_assigned_hostels($managerUserId);
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'notice_created') {
        $message = 'Notice created successfully.';
    } elseif ($_GET['msg'] === 'notice_updated') {
        $message = 'Notice updated successfully.';
    } elseif ($_GET['msg'] === 'notice_deleted') {
        $message = 'Notice deleted successfully.';
    }
}

require_once __DIR__ . '/../../Views/Manager/ManagerNoticeView.php';
