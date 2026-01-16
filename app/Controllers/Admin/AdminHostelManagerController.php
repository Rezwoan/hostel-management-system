<?php
// app/Controllers/Admin/HostelManagerController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'assign_manager') {
        $hostelId = (int)$_POST['hostel_id'];
        $managerUserId = (int)$_POST['manager_user_id'];
        
        $result = assignManagerToHostel($hostelId, $managerUserId, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_hostel_managers&msg=manager_assigned');
            exit;
        } else {
            $error = 'Failed to assign manager.';
        }
    } elseif ($formAction === 'remove_manager') {
        $id = (int)$_POST['id'];
        $result = removeManagerFromHostel($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_hostel_managers&msg=manager_removed');
            exit;
        } else {
            $error = 'Failed to remove manager.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Hostel Manager Assignments';
$data = [];
$data['assignments'] = getAllHostelManagers();
$data['hostels'] = getAllHostels();
$data['managers'] = getUsersByRole(2);

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'manager_assigned') {
        $message = 'Manager assigned successfully.';
    } elseif ($_GET['msg'] === 'manager_removed') {
        $message = 'Manager removed successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/HostelManagerView.php';
