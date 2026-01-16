<?php
/**
 * AJAX: Get rooms by floor ID
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
$sql = "SELECT r.id, r.room_number, rt.name as room_type 
        FROM rooms r 
        LEFT JOIN room_types rt ON r.room_type_id = rt.id 
        WHERE r.floor_id = $floorId 
        ORDER BY r.room_number";
$result = mysqli_query($conn, $sql);
$rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

echo json_encode(["success" => true, "data" => $rooms]);
?>
