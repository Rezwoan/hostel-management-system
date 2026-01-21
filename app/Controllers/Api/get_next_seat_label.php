<?php
/**
 * AJAX: Get next available seat label for a specific room
 * Usage: GET get_next_seat_label.php?room_id=1
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$roomId = isset($_GET['room_id']) ? (int)$_GET['room_id'] : 0;

if ($roomId <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid room ID"]);
    exit;
}

$conn = dbConnect();

// Get room details and current seat count
$roomSql = "SELECT r.capacity, r.room_no,
                   (SELECT COUNT(*) FROM seats WHERE room_id = r.id) as seat_count
            FROM rooms r
            WHERE r.id = $roomId";

$roomResult = mysqli_query($conn, $roomSql);

if (!$roomResult || mysqli_num_rows($roomResult) === 0) {
    mysqli_close($conn);
    echo json_encode(["success" => false, "error" => "Room not found"]);
    exit;
}

$room = mysqli_fetch_assoc($roomResult);
$capacity = (int)$room['capacity'];
$seatCount = (int)$room['seat_count'];

// Check if room is full
if ($seatCount >= $capacity) {
    mysqli_close($conn);
    echo json_encode([
        "success" => false, 
        "error" => "Room is at full capacity",
        "capacity" => $capacity,
        "seat_count" => $seatCount
    ]);
    exit;
}

// Get existing seat labels to find gaps or next available
$seatsSql = "SELECT seat_label FROM seats WHERE room_id = $roomId ORDER BY seat_label";
$seatsResult = mysqli_query($conn, $seatsSql);
$existingLabels = [];
while ($row = mysqli_fetch_assoc($seatsResult)) {
    $existingLabels[] = $row['seat_label'];
}

// Find the first available seat label (fills gaps)
$nextSeatLabel = null;

// First, try letters A-Z
for ($i = 1; $i <= 26; $i++) {
    $letter = chr(64 + $i); // A=65, B=66, etc.
    if (!in_array($letter, $existingLabels)) {
        $nextSeatLabel = $letter;
        break;
    }
}

// If all A-Z are taken, use S1, S2, S3, etc.
if ($nextSeatLabel === null) {
    $num = 1;
    while ($num <= 100) { // Reasonable limit
        $label = "S" . $num;
        if (!in_array($label, $existingLabels)) {
            $nextSeatLabel = $label;
            break;
        }
        $num++;
    }
}

// Fallback if still null
if ($nextSeatLabel === null) {
    $nextSeatLabel = "SEAT" . ($seatCount + 1);
}

mysqli_close($conn);

echo json_encode([
    "success" => true,
    "next_seat_label" => $nextSeatLabel,
    "seat_count" => $seatCount,
    "capacity" => $capacity,
    "available_seats" => $capacity - $seatCount
]);
