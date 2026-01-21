<?php
// app/Controllers/Manager/ManagerHostelController.php

require_once __DIR__ . '/../../Models/ManagerModel.php';

$managerUserId = $_SESSION['user_id'];
$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';

// Handle GET requests
$pageTitle = 'My Hostels';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['hostel'] = manager_get_hostel_details($id);
    
    // Verify manager has access to this hostel
    $assignedHostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($assignedHostels, 'id');
    
    if (!in_array($id, $hostelIds)) {
        header('Location: index.php?page=manager_hostels&error=access_denied');
        exit;
    }
    
    // Get hostel statistics
    $conn = dbConnect();
    
    // Total floors
    $sql = "SELECT COUNT(*) as count FROM floors WHERE hostel_id = $id";
    $result = mysqli_query($conn, $sql);
    $data['total_floors'] = (int)mysqli_fetch_assoc($result)['count'];
    
    // Total rooms
    $sql = "SELECT COUNT(*) as count FROM rooms r JOIN floors f ON r.floor_id = f.id WHERE f.hostel_id = $id";
    $result = mysqli_query($conn, $sql);
    $data['total_rooms'] = (int)mysqli_fetch_assoc($result)['count'];
    
    // Total seats
    $sql = "SELECT COUNT(*) as count FROM seats s JOIN rooms r ON s.room_id = r.id JOIN floors f ON r.floor_id = f.id WHERE f.hostel_id = $id";
    $result = mysqli_query($conn, $sql);
    $data['total_seats'] = (int)mysqli_fetch_assoc($result)['count'];
    
    // Occupied seats
    $sql = "SELECT COUNT(*) as count FROM allocations WHERE hostel_id = $id AND status = 'ACTIVE'";
    $result = mysqli_query($conn, $sql);
    $data['occupied_seats'] = (int)mysqli_fetch_assoc($result)['count'];
    
    // Available seats
    $data['available_seats'] = $data['total_seats'] - $data['occupied_seats'];
    
    // Pending applications
    $sql = "SELECT COUNT(*) as count FROM room_applications WHERE hostel_id = $id AND status = 'SUBMITTED'";
    $result = mysqli_query($conn, $sql);
    $data['pending_applications'] = (int)mysqli_fetch_assoc($result)['count'];
    
    // Get floors with rooms
    $sql = "SELECT f.*, 
                   (SELECT COUNT(*) FROM rooms WHERE floor_id = f.id) as room_count
            FROM floors f 
            WHERE f.hostel_id = $id 
            ORDER BY f.floor_no";
    $result = mysqli_query($conn, $sql);
    $data['floors'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    mysqli_close($conn);
    
    $pageTitle = 'Hostel Details';
} else {
    $data['hostels'] = manager_get_assigned_hostels($managerUserId);
}

// Handle messages
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'access_denied') {
        $error = 'Access denied. You do not have permission to view this hostel.';
    }
}

require_once __DIR__ . '/../../Views/Manager/ManagerHostelView.php';
