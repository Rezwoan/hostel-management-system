<?php
// app/Controllers/Admin/RoomTypeController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'room_types';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'create_room_type') {
        $name = trim($_POST['name']);
        $defaultCapacity = (int)$_POST['default_capacity'];
        $defaultFee = (float)$_POST['default_fee'];
        $description = trim($_POST['description']);
        
        if (empty($name)) {
            $error = 'Room type name is required.';
        } else {
            $result = createRoomType($name, $defaultCapacity, $defaultFee, $description, $actorUserId);
            if ($result) {
                header('Location: index.php?page=admin_room_types&msg=room_type_created');
                exit;
            } else {
                $error = 'Failed to create room type.';
            }
        }
    } elseif ($formAction === 'update_room_type') {
        $id = (int)$_POST['id'];
        $name = trim($_POST['name']);
        $defaultCapacity = (int)$_POST['default_capacity'];
        $defaultFee = (float)$_POST['default_fee'];
        $description = trim($_POST['description']);
        
        $result = updateRoomType($id, $name, $defaultCapacity, $defaultFee, $description, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_room_types&msg=room_type_updated');
            exit;
        } else {
            $error = 'Failed to update room type.';
        }
    } elseif ($formAction === 'delete_room_type') {
        $id = (int)$_POST['id'];
        $result = deleteRoomType($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_room_types&msg=room_type_deleted');
            exit;
        } else {
            $error = 'Failed to delete room type. It may be in use.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Room Type Management';
$data = [];

if ($action === 'edit') {
    $id = (int)$_GET['id'];
    $data['room_type'] = getRoomTypeById($id);
    $pageTitle = 'Edit Room Type';
} elseif ($action === 'add') {
    $pageTitle = 'Add New Room Type';
} else {
    $data['room_types'] = getAllRoomTypes();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'room_type_created') {
        $message = 'Room type created successfully.';
    } elseif ($_GET['msg'] === 'room_type_updated') {
        $message = 'Room type updated successfully.';
    } elseif ($_GET['msg'] === 'room_type_deleted') {
        $message = 'Room type deleted successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/RoomTypeView.php';