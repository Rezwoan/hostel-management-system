<?php
/**
 * AJAX: Get next available room number for a specific floor
 * Usage: GET get_next_room_number.php?floor_id=1
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$floorId = isset($_GET['floor_id']) ? (int)$_GET['floor_id'] : 0;

if ($floorId <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid floor ID"]);
    exit;
}

$conn = dbConnect();

// Get floor details
$floorSql = "SELECT f.floor_no, f.label, h.code as hostel_code 
             FROM floors f 
             JOIN hostels h ON f.hostel_id = h.id 
             WHERE f.id = $floorId";
$floorResult = mysqli_query($conn, $floorSql);

if (!$floorResult || mysqli_num_rows($floorResult) === 0) {
    mysqli_close($conn);
    echo json_encode(["success" => false, "error" => "Floor not found"]);
    exit;
}

$floor = mysqli_fetch_assoc($floorResult);
$floorNo = $floor['floor_no'];

// Get the count of existing rooms on this floor to generate sequential number
$countSql = "SELECT COUNT(*) as room_count FROM rooms WHERE floor_id = $floorId";
$countResult = mysqli_query($conn, $countSql);
$countRow = mysqli_fetch_assoc($countResult);
$roomCount = $countRow['room_count'] ?? 0;

// Generate next room number
// Format: FloorNumber + Sequential (e.g., Floor 1 -> 101, 102, 103)
// If floor is 0 (ground), use 001, 002, 003
$nextSequential = $roomCount + 1;

if ($floorNo == 0) {
    $nextRoomNo = str_pad($nextSequential, 3, '0', STR_PAD_LEFT);
} else {
    $nextRoomNo = $floorNo . str_pad($nextSequential, 2, '0', STR_PAD_LEFT);
}

mysqli_close($conn);

echo json_encode([
    "success" => true,
    "next_room_no" => $nextRoomNo,
    "floor_no" => $floorNo,
    "room_count" => $roomCount
]);
