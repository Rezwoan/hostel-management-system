<?php
// app/Controllers/Admin/FloorController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'floors';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'create_floor') {
        $hostelId = (int)$_POST['hostel_id'];
        $floorNo = (int)$_POST['floor_no'];
        $label = trim($_POST['label']);
        
        $result = createFloor($hostelId, $floorNo, $label, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_floors&msg=floor_created');
            exit;
        } else {
            $error = 'Failed to create floor.';
        }
    } elseif ($formAction === 'update_floor') {
        $id = (int)$_POST['id'];
        $floorNo = (int)$_POST['floor_no'];
        $label = trim($_POST['label']);
        
        $result = updateFloor($id, $floorNo, $label, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_floors&msg=floor_updated');
            exit;
        } else {
            $error = 'Failed to update floor.';
        }
    } elseif ($formAction === 'delete_floor') {
        $id = (int)$_POST['id'];
        $result = deleteFloor($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_floors&msg=floor_deleted');
            exit;
        } else {
            $error = 'Failed to delete floor. It may have associated rooms.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Floor Management';
$data = [];

if ($action === 'edit') {
    $id = (int)$_GET['id'];
    $data['floor'] = getFloorById($id);
    $pageTitle = 'Edit Floor';
} elseif ($action === 'add') {
    $pageTitle = 'Add New Floor';
    $data['hostels'] = getAllHostels();
} else {
    $data['floors'] = getAllFloors();
    $data['hostels'] = getAllHostels();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'floor_created') {
        $message = 'Floor created successfully.';
    } elseif ($_GET['msg'] === 'floor_updated') {
        $message = 'Floor updated successfully.';
    } elseif ($_GET['msg'] === 'floor_deleted') {
        $message = 'Floor deleted successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/FloorView.php';
