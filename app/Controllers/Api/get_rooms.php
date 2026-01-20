<?php
/**
 * AJAX: Get rooms for a specific floor
 * Usage: GET get_rooms.php?floor_id=1
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

$sql = "SELECT r.id, r.floor_id, r.room_no, r.capacity, r.status, 
               rt.name as room_type_name
        FROM rooms r
        LEFT JOIN room_types rt ON r.room_type_id = rt.id
        WHERE r.floor_id = $floorId AND r.status = 'ACTIVE'
        ORDER BY r.room_no ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    mysqli_close($conn);
    exit;
}

$rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

// Rename room_no to room_number for frontend compatibility
foreach ($rooms as &$room) {
    $room['room_number'] = $room['room_no'];
    $room['room_type'] = $room['room_type_name'];
}

echo json_encode(["success" => true, "data" => $rooms]);
?>
