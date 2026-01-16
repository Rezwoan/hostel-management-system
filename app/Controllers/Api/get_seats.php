<?php
/**
 * AJAX: Get seats by room ID
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
$availableOnly = isset($_GET['available_only']) && $_GET['available_only'] == '1';

if ($roomId <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid room ID"]);
    exit;
}

$conn = dbConnect();

// Get seats with their availability status
$sql = "SELECT s.id, s.seat_label,
        CASE WHEN a.id IS NOT NULL THEN 'occupied' ELSE 'available' END as status
        FROM seats s 
        LEFT JOIN allocations a ON s.id = a.seat_id AND a.end_date IS NULL
        WHERE s.room_id = $roomId";

if ($availableOnly) {
    $sql .= " HAVING status = 'available'";
}

$sql .= " ORDER BY s.seat_label";

$result = mysqli_query($conn, $sql);
$seats = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

echo json_encode(["success" => true, "data" => $seats]);
?>
