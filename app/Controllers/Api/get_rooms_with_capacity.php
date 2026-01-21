<?php
/**
 * AJAX: Get rooms with available seat capacity for a specific floor
 * Usage: GET get_rooms_with_capacity.php?floor_id=1
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

// Get rooms with their capacity and current seat count
$sql = "SELECT r.id, r.room_no, r.capacity, r.floor_id,
               (SELECT COUNT(*) FROM seats WHERE room_id = r.id) as seat_count
        FROM rooms r
        WHERE r.floor_id = $floorId AND r.status = 'ACTIVE'
        ORDER BY r.room_no ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    mysqli_close($conn);
    echo json_encode(["success" => false, "error" => "Database query failed"]);
    exit;
}

$rooms = [];
while ($row = mysqli_fetch_assoc($result)) {
    $availableSeats = (int)$row['capacity'] - (int)$row['seat_count'];
    
    // Only include rooms that have available capacity
    if ($availableSeats > 0) {
        $rooms[] = [
            'id' => (int)$row['id'],
            'room_no' => $row['room_no'],
            'capacity' => (int)$row['capacity'],
            'seat_count' => (int)$row['seat_count'],
            'available_seats' => $availableSeats
        ];
    }
}

mysqli_close($conn);

echo json_encode([
    "success" => true,
    "rooms" => $rooms
]);
