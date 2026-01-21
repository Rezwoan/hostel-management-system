<?php
// app/Controllers/Admin/SeatController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'seats';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'create_seat') {
        $roomId = (int)$_POST['room_id'];
        $seatLabel = trim($_POST['seat_label']);
        $status = $_POST['status'] ?? 'ACTIVE';
        
        // Validate inputs
        if ($roomId <= 0) {
            $error = 'Please select a valid room.';
        } elseif (empty($seatLabel)) {
            $error = 'Seat label is required.';
        } elseif (!in_array($status, ['ACTIVE', 'INACTIVE'])) {
            $error = 'Invalid status value.';
        } else {
            // Check if room has available capacity before creating seat
            $room = getRoomById($roomId);
            if (!$room) {
                $error = 'Room not found.';
            } else {
                $seatCount = getSeatCountByRoom($roomId);
                if ($seatCount >= $room['capacity']) {
                    $error = 'Room is at full capacity. Cannot add more seats.';
                } else {
                    $result = createSeat($roomId, $seatLabel, $status, $actorUserId);
                    if ($result) {
                        header('Location: index.php?page=admin_seats&msg=seat_created');
                        exit;
                    } else {
                        $error = 'Failed to create seat. Seat label may already exist in this room.';
                    }
                }
            }
        }
    } elseif ($formAction === 'update_seat') {
        $id = (int)$_POST['id'];
        $seatLabel = trim($_POST['seat_label']);
        $status = $_POST['status'];
        
        $result = updateSeat($id, $seatLabel, $status, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_seats&msg=seat_updated');
            exit;
        } else {
            $error = 'Failed to update seat.';
        }
    } elseif ($formAction === 'delete_seat') {
        $id = (int)$_POST['id'];
        $result = deleteSeat($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_seats&msg=seat_deleted');
            exit;
        } else {
            $error = 'Failed to delete seat. It may be allocated.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Seat Management';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['seat'] = getSeatById($id);
    $pageTitle = 'View Seat';
} elseif ($action === 'edit') {
    $id = (int)$_GET['id'];
    $data['seat'] = getSeatById($id);
    $pageTitle = 'View Seat';
} elseif ($action === 'edit') {
    $id = (int)$_GET['id'];
    $data['seat'] = getSeatById($id);
    $pageTitle = 'Edit Seat';
} elseif ($action === 'add') {
    $pageTitle = 'Add New Seat';
    $data['hostels'] = getAllHostels();
    $data['floors'] = getAllFloors();
    $data['rooms'] = getAllRooms();
} else {
    $data['seats'] = getAllSeats();
    $data['rooms'] = getAllRooms();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'seat_created') {
        $message = 'Seat created successfully.';
    } elseif ($_GET['msg'] === 'seat_updated') {
        $message = 'Seat updated successfully.';
    } elseif ($_GET['msg'] === 'seat_deleted') {
        $message = 'Seat deleted successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/SeatView.php';