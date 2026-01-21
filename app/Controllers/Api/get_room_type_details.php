<?php
/**
 * AJAX: Get room type details including capacity
 * Usage: GET get_room_type_details.php?room_type_id=1
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$roomTypeId = isset($_GET['room_type_id']) ? (int)$_GET['room_type_id'] : 0;

if ($roomTypeId <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid room type ID"]);
    exit;
}

$conn = dbConnect();

$sql = "SELECT id, name, default_capacity, default_fee, description 
        FROM room_types 
        WHERE id = $roomTypeId";

$result = mysqli_query($conn, $sql);

if (!$result) {
    mysqli_close($conn);
    echo json_encode(["success" => false, "error" => "Database query failed"]);
    exit;
}

$roomType = mysqli_fetch_assoc($result);
mysqli_close($conn);

if (!$roomType) {
    echo json_encode(["success" => false, "error" => "Room type not found"]);
    exit;
}

echo json_encode([
    "success" => true,
    "room_type" => $roomType,
    "capacity" => (int)$roomType['default_capacity'],
    "fee" => (float)$roomType['default_fee']
]);
