<?php
// app/Controllers/Manager/ManagerAllocationController.php

require_once __DIR__ . '/../../Models/ManagerModel.php';

$managerUserId = $_SESSION['user_id'];
$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';
    
    if ($formAction === 'create_allocation') {
        $studentUserId = (int)$_POST['student_user_id'];
        $seatId = (int)$_POST['seat_id'];
        $hostelId = (int)$_POST['hostel_id'];
        
        $result = manager_create_allocation($studentUserId, $seatId, $hostelId, $managerUserId);
        if ($result) {
            header('Location: index.php?page=manager_allocations&msg=allocation_created');
            exit;
        } else {
            $error = 'Failed to create allocation. The student may already have an active allocation.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Room Allocations';
$data = [];

if ($action === 'add') {
    $pageTitle = 'Create Allocation';
    $hostels = manager_get_assigned_hostels($managerUserId);
    $data['hostels'] = $hostels;
    
    // Get available seats if hostel is selected
    if (isset($_GET['hostel_id'])) {
        $hostelId = (int)$_GET['hostel_id'];
        $data['selected_hostel_id'] = $hostelId;
        $data['available_seats'] = manager_get_available_seats($hostelId);
    }
    
    // Get students with approved applications but no allocation
    $conn = dbConnect();
    $hostelIds = array_column($hostels, 'id');
    if (!empty($hostelIds)) {
        $hostelIdsStr = implode(',', $hostelIds);
        $sql = "SELECT DISTINCT u.id, u.name, u.email, sp.student_id, sp.department,
                       ra.hostel_id, h.name as hostel_name
                FROM users u
                JOIN student_profiles sp ON u.id = sp.user_id
                JOIN room_applications ra ON u.id = ra.student_user_id
                JOIN hostels h ON ra.hostel_id = h.id
                WHERE ra.status = 'APPROVED'
                AND ra.hostel_id IN ($hostelIdsStr)
                AND NOT EXISTS (
                    SELECT 1 FROM allocations a 
                    WHERE a.student_user_id = u.id AND a.status = 'ACTIVE'
                )
                ORDER BY u.name";
        $result = mysqli_query($conn, $sql);
        $data['approved_students'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_close($conn);
    } else {
        $data['approved_students'] = [];
    }
} else {
    $data['allocations'] = manager_get_allocations($managerUserId);
    $data['hostels'] = manager_get_assigned_hostels($managerUserId);
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'allocation_created') {
        $message = 'Allocation created successfully.';
    }
}

require_once __DIR__ . '/../../Views/Manager/ManagerAllocationView.php';
