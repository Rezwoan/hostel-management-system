<?php
// app/Controllers/Admin/NoticeController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'notices';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'create_notice') {
        $scope = $_POST['scope'];
        $hostelId = isset($_POST['hostel_id']) ? (int)$_POST['hostel_id'] : null;
        $title = trim($_POST['title']);
        $body = trim($_POST['body']);
        $status = $_POST['status'];
        $publishAt = !empty($_POST['publish_at']) ? $_POST['publish_at'] : null;
        $expireAt = !empty($_POST['expire_at']) ? $_POST['expire_at'] : null;
        
        if (empty($title) || empty($body)) {
            $error = 'Title and body are required.';
        } else {
            if ($scope === 'GLOBAL') {
                $result = createGlobalNotice($title, $body, $status, $publishAt, $expireAt, $actorUserId);
            } else {
                $result = createHostelNotice($hostelId, $title, $body, $status, $publishAt, $expireAt, $actorUserId);
            }
            
            if ($result) {
                header('Location: index.php?page=admin_notices&msg=notice_created');
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
        
        $result = updateNotice($id, $title, $body, $status, $publishAt, $expireAt, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_notices&msg=notice_updated');
            exit;
        } else {
            $error = 'Failed to update notice.';
        }
    } elseif ($formAction === 'delete_notice') {
        $id = (int)$_POST['id'];
        $result = deleteNotice($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_notices&msg=notice_deleted');
            exit;
        } else {
            $error = 'Failed to delete notice.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Notice Management';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['notice'] = getNoticeById($id);
    $pageTitle = 'View Notice';
} elseif ($action === 'edit') {
    $id = (int)$_GET['id'];
    $data['notice'] = getNoticeById($id);
    $data['hostels'] = getAllHostels();
    $pageTitle = 'Edit Notice';
} elseif ($action === 'add') {
    $pageTitle = 'Create Notice';
    $data['hostels'] = getAllHostels();
} else {
    $data['notices'] = getAllNotices();
    $data['hostels'] = getAllHostels();
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

require_once __DIR__ . '/../../Views/Admin/NoticeView.php';