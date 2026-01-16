<?php
// app/Controllers/Admin/RoomController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'rooms';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'create_room') {
        $floorId = (int)$_POST['floor_id'];
        $roomTypeId = (int)$_POST['room_type_id'];
        $roomNo = trim($_POST['room_no']);
        $capacity = (int)$_POST['capacity'];
        $status = $_POST['status'];
        
        if (empty($roomNo)) {
            $error = 'Room number is required.';
        } else {
            $result = createRoom($floorId, $roomTypeId, $roomNo, $capacity, $status, $actorUserId);
            if ($result) {
                header('Location: index.php?page=admin_rooms&msg=room_created');
                exit;
            } else {
                $error = 'Failed to create room.';
            }
        }
    } elseif ($formAction === 'update_room') {
        $id = (int)$_POST['id'];
        $floorId = (int)$_POST['floor_id'];
        $roomTypeId = (int)$_POST['room_type_id'];
        $roomNo = trim($_POST['room_no']);
        $capacity = (int)$_POST['capacity'];
        $status = $_POST['status'];
        
        $result = updateRoom($id, $floorId, $roomTypeId, $roomNo, $capacity, $status, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_rooms&msg=room_updated');
            exit;
        } else {
            $error = 'Failed to update room.';
        }
    } elseif ($formAction === 'delete_room') {
        $id = (int)$_POST['id'];
        $result = deleteRoom($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_rooms&msg=room_deleted');
            exit;
        } else {
            $error = 'Failed to delete room. It may have seats or allocations.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Room Management';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['room'] = getRoomById($id);
    $data['seats'] = getSeatsByRoom($id);
    $pageTitle = 'View Room';
} elseif ($action === 'edit') {
    $id = (int)$_GET['id'];
    $data['room'] = getRoomById($id);
    $data['floors'] = getAllFloors();
    $data['room_types'] = getAllRoomTypes();
    $pageTitle = 'Edit Room';
} elseif ($action === 'add') {
    $pageTitle = 'Add New Room';
    $data['floors'] = getAllFloors();
    $data['room_types'] = getAllRoomTypes();
} else {
    $data['rooms'] = getAllRooms();
    $data['floors'] = getAllFloors();
    $data['room_types'] = getAllRoomTypes();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'room_created') {
        $message = 'Room created successfully.';
    } elseif ($_GET['msg'] === 'room_updated') {
        $message = 'Room updated successfully.';
    } elseif ($_GET['msg'] === 'room_deleted') {
        $message = 'Room deleted successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/RoomView.php';