<?php
// app/Controllers/Admin/FeePeriodController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'fee_periods';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'create_fee_period') {
        $name = trim($_POST['name']);
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        
        if (empty($name)) {
            $error = 'Period name is required.';
        } else {
            $result = createFeePeriod($name, $startDate, $endDate, $actorUserId);
            if ($result) {
                header('Location: index.php?page=admin_fee_periods&msg=period_created');
                exit;
            } else {
                $error = 'Failed to create fee period.';
            }
        }
    } elseif ($formAction === 'update_fee_period') {
        $id = (int)$_POST['id'];
        $name = trim($_POST['name']);
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        
        $result = updateFeePeriod($id, $name, $startDate, $endDate, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_fee_periods&msg=period_updated');
            exit;
        } else {
            $error = 'Failed to update fee period.';
        }
    } elseif ($formAction === 'delete_fee_period') {
        $id = (int)$_POST['id'];
        $result = deleteFeePeriod($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_fee_periods&msg=period_deleted');
            exit;
        } else {
            $error = 'Failed to delete fee period. It may have invoices.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Fee Period Management';
$data = [];

if ($action === 'edit') {
    $id = (int)$_GET['id'];
    $data['period'] = getFeePeriodById($id);
    $pageTitle = 'Edit Fee Period';
} elseif ($action === 'add') {
    $pageTitle = 'Add New Fee Period';
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

require_once __DIR__ . '/../../Views/Admin/FeePeriodView.php';