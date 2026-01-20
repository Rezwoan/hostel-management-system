<?php
/**
 * AJAX: Get floors for a specific hostel
 * Usage: GET get_floors.php?hostel_id=1
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

$sql = "SELECT id, hostel_id, floor_no, label 
        FROM floors 
        WHERE hostel_id = $hostelId 
        ORDER BY floor_no ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    mysqli_close($conn);
    exit;
}

$floors = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

// Rename floor_no to floor_number for frontend compatibility
foreach ($floors as &$floor) {
    $floor['floor_number'] = $floor['floor_no'];
    $floor['name'] = $floor['label'];
}

echo json_encode(["success" => true, "data" => $floors]);
?>
