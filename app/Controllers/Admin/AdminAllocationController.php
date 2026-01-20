<?php
// app/Controllers/Admin/AllocationController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'allocations';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'create_allocation') {
        $studentUserId = isset($_POST['student_id']) ? (int)$_POST['student_id'] : (int)$_POST['student_user_id'];
        $seatId = (int)$_POST['seat_id'];
        $hostelId = isset($_POST['hostel_id']) ? (int)$_POST['hostel_id'] : 0;
        $startDate = $_POST['start_date'];
        
        // If hostel_id is not provided or is 0, get it from the seat selection
        if ($hostelId <= 0 && $seatId > 0) {
            $conn = dbConnect();
            $sql = "SELECT h.id as hostel_id 
                    FROM seats s 
                    JOIN rooms r ON s.room_id = r.id 
                    JOIN floors f ON r.floor_id = f.id 
                    JOIN hostels h ON f.hostel_id = h.id 
                    WHERE s.id = $seatId";
            $result = mysqli_query($conn, $sql);
            if ($row = mysqli_fetch_assoc($result)) {
                $hostelId = (int)$row['hostel_id'];
            }
            mysqli_close($conn);
        }
        
        if ($hostelId <= 0) {
            $error = 'Failed to determine hostel. Please ensure seat selection is valid.';
        } else {
            $result = createAllocation($studentUserId, $seatId, $hostelId, $startDate, $actorUserId);
            if ($result) {
                header('Location: index.php?page=admin_allocations&msg=allocation_created');
                exit;
            } else {
                $error = 'Failed to create allocation.';
            }
        }
    } elseif ($formAction === 'end_allocation') {
        $id = (int)$_POST['id'];
        $result = endAllocation($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_allocations&msg=allocation_ended');
            exit;
        } else {
            $error = 'Failed to end allocation.';
        }
    } elseif ($formAction === 'delete_allocation') {
        $id = (int)$_POST['id'];
        $result = deleteAllocation($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_allocations&msg=allocation_deleted');
            exit;
        } else {
            $error = 'Failed to delete allocation.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Seat Allocations';
$data = [];

if ($action === 'add') {
    $pageTitle = 'Create Allocation';
    $data['students'] = getAllStudents();
    $data['hostels'] = getAllHostels();
    $data['available_seats'] = getAvailableSeats();
} elseif ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['allocation'] = getAllocationById($id);
    $pageTitle = 'View Allocation';
} else {
    $data['allocations'] = getAllAllocations();
    $data['active_allocations'] = getActiveAllocations();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'allocation_created') {
        $message = 'Allocation created successfully.';
    } elseif ($_GET['msg'] === 'allocation_ended') {
        $message = 'Allocation ended successfully.';
    } elseif ($_GET['msg'] === 'allocation_deleted') {
        $message = 'Allocation deleted successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/AllocationView.php';
