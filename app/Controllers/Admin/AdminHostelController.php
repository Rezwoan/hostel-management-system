<?php
// app/Controllers/Admin/HostelController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'hostels';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'create_hostel') {
        $name = trim($_POST['name']);
        $code = trim($_POST['code']);
        $address = trim($_POST['address']);
        $status = $_POST['status'];
        
        if (empty($name) || empty($code)) {
            $error = 'Hostel name and code are required.';
        } else {
            $result = createHostel($name, $code, $address, $status, $actorUserId);
            if ($result) {
                header('Location: index.php?page=admin_hostels&msg=hostel_created');
                exit;
            } else {
                $error = 'Failed to create hostel.';
            }
        }
    } elseif ($formAction === 'update_hostel') {
        $id = (int)$_POST['id'];
        $name = trim($_POST['name']);
        $code = trim($_POST['code']);
        $address = trim($_POST['address']);
        $status = $_POST['status'];
        
        $result = updateHostel($id, $name, $code, $address, $status, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_hostels&msg=hostel_updated');
            exit;
        } else {
            $error = 'Failed to update hostel.';
        }
    } elseif ($formAction === 'delete_hostel') {
        $id = (int)$_POST['id'];
        $result = deleteHostel($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_hostels&msg=hostel_deleted');
            exit;
        } else {
            $error = 'Failed to delete hostel. It may have associated data.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Hostel Management';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['hostel'] = getHostelById($id);
    $data['floors'] = getFloorsByHostel($id);
    $data['rooms'] = getRoomsByHostel($id);
    $pageTitle = 'View Hostel';
} elseif ($action === 'edit') {
    $id = (int)$_GET['id'];
    $data['hostel'] = getHostelById($id);
    $pageTitle = 'Edit Hostel';
} elseif ($action === 'add') {
    $pageTitle = 'Add New Hostel';
} else {
    $data['hostels'] = getAllHostels();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'hostel_created') {
        $message = 'Hostel created successfully.';
    } elseif ($_GET['msg'] === 'hostel_updated') {
        $message = 'Hostel updated successfully.';
    } elseif ($_GET['msg'] === 'hostel_deleted') {
        $message = 'Hostel deleted successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/HostelView.php';