<?php
// app/Controllers/Manager/ManagerFeePeriodController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$managerUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'fee_periods';
$message = '';
$error = '';

// Managers can only view fee periods, not create/edit/delete
// This is read-only for managers

// Handle GET requests
$pageTitle = 'Fee Period Management';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['period'] = getFeePeriodById($id);
    $pageTitle = 'View Fee Period';
} else {
    $data['periods'] = getAllFeePeriods();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'period_created') {
        $message = 'Fee period created successfully.';
    } elseif ($_GET['msg'] === 'period_updated') {
        $message = 'Fee period updated successfully.';
    } elseif ($_GET['msg'] === 'period_deleted') {
        $message = 'Fee period deleted successfully.';
    }
}

require_once __DIR__ . '/../../Views/Manager/ManagerFeePeriodView.php';
