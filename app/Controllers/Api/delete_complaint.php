<?php
/**
 * AJAX: Delete complaint
 * Usage: POST delete_complaint.php
 * Body: id=1
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid complaint ID"]);
    exit;
}

$conn = dbConnect();
$sql = "DELETE FROM complaints WHERE id = $id";
$result = mysqli_query($conn, $sql);
mysqli_close($conn);

if ($result) {
    echo json_encode(["success" => true, "message" => "Complaint deleted"]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to delete complaint"]);
}
?>
