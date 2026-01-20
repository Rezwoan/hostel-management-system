<?php
/**
 * AJAX: Get seats for a specific room
 * Usage: GET get_seats.php?room_id=1&available_only=1
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$roomId = isset($_GET['room_id']) ? (int)$_GET['room_id'] : 0;
$availableOnly = isset($_GET['available_only']) ? (int)$_GET['available_only'] : 0;

if ($roomId <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid room ID"]);
    exit;
}

$conn = dbConnect();

// Get seats that don't have ACTIVE allocations
if ($availableOnly) {
    $sql = "SELECT s.id, s.room_id, s.seat_label, s.status
            FROM seats s
            LEFT JOIN allocations a ON s.id = a.seat_id AND a.status = 'ACTIVE'
            WHERE s.room_id = $roomId AND s.status = 'ACTIVE' AND a.id IS NULL
            ORDER BY s.seat_label ASC";
} else {
    $sql = "SELECT s.id, s.room_id, s.seat_label, s.status,
                   a.id as allocation_id, a.status as allocation_status
            FROM seats s
            LEFT JOIN allocations a ON s.id = a.seat_id AND a.status = 'ACTIVE'
            WHERE s.room_id = $roomId AND s.status = 'ACTIVE'
            ORDER BY s.seat_label ASC";
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    mysqli_close($conn);
    exit;
}

$seats = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

echo json_encode(["success" => true, "data" => $seats]);
?>
