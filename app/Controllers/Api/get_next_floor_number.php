<?php
/**
 * AJAX: Get next available floor number for a specific hostel
 * Usage: GET get_next_floor_number.php?hostel_id=1
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$hostelId = isset($_GET['hostel_id']) ? (int)$_GET['hostel_id'] : 0;

if ($hostelId <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid hostel ID"]);
    exit;
}

$conn = dbConnect();

// Get the maximum floor number for this hostel
$sql = "SELECT MAX(floor_no) as max_floor_no 
        FROM floors 
        WHERE hostel_id = $hostelId";

$result = mysqli_query($conn, $sql);

if (!$result) {
    mysqli_close($conn);
    echo json_encode(["success" => false, "error" => "Database query failed"]);
    exit;
}

$row = mysqli_fetch_assoc($result);
$maxFloorNo = $row['max_floor_no'];

// If no floors exist, start with 0, otherwise increment by 1
$nextFloorNo = ($maxFloorNo === null) ? 0 : ($maxFloorNo + 1);

// Generate label based on floor number
$label = "Floor " . $nextFloorNo;

mysqli_close($conn);

echo json_encode([
    "success" => true,
    "next_floor_no" => $nextFloorNo,
    "suggested_label" => $label
]);
