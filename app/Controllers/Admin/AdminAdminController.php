<?php
// app/Controllers/Admin/AdminAdminController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'admins';
$message = '';
$error = '';

// Handle GET requests
$pageTitle = 'Admin Management';
$data = [];

if ($action === 'view') {
    $userId = (int)$_GET['id'];
    $data['admin'] = getAdminById($userId);
    $pageTitle = 'View Admin';
} else {
    $data['admins'] = getAllAdminUsers();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'admin_updated') {
        $message = 'Admin updated successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/AdminListView.php';
