<?php
/**
 * AJAX: Get floors by hostel ID
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
$sql = "SELECT id, floor_number, name FROM floors WHERE hostel_id = $hostelId ORDER BY floor_number";
$result = mysqli_query($conn, $sql);
$floors = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

echo json_encode(["success" => true, "data" => $floors]);
?>
