<?php
// app/Controllers/Admin/AdminManagerController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'managers';
$message = '';
$error = '';

// Handle GET requests
$pageTitle = 'Manager Management';
$data = [];

if ($action === 'view') {
    $userId = (int)$_GET['id'];
    $data['manager'] = getManagerById($userId);
    $pageTitle = 'View Manager';
} else {
    $data['managers'] = getAllManagers();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'manager_updated') {
        $message = 'Manager updated successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/ManagerListView.php';
