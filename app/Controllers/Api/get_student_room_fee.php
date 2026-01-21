<?php
/**
 * AJAX: Get room fee for a specific student based on their current allocation
 * Usage: GET get_student_room_fee.php?student_user_id=3
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$studentUserId = isset($_GET['student_user_id']) ? (int)$_GET['student_user_id'] : 0;

if ($studentUserId <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid student ID"]);
    exit;
}

$conn = dbConnect();

// Get the room type's default fee for the student's current active allocation
$sql = "SELECT rt.default_fee, rt.name as room_type_name, 
               r.room_no, h.name as hostel_name
        FROM allocations a
        JOIN seats s ON a.seat_id = s.id
        JOIN rooms r ON s.room_id = r.id
        JOIN room_types rt ON r.room_type_id = rt.id
        JOIN floors f ON r.floor_id = f.id
        JOIN hostels h ON f.hostel_id = h.id
        WHERE a.student_user_id = $studentUserId 
        AND a.status = 'ACTIVE'
        LIMIT 1";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    mysqli_close($conn);
    exit;
}

$data = mysqli_fetch_assoc($result);
mysqli_close($conn);

if (!$data) {
    echo json_encode(["success" => false, "error" => "No active allocation found for this student"]);
    exit;
}

echo json_encode([
    "success" => true, 
    "data" => [
        "default_fee" => (float)$data['default_fee'],
        "room_type_name" => $data['room_type_name'],
        "room_no" => $data['room_no'],
        "hostel_name" => $data['hostel_name']
    ]
]);
?>
