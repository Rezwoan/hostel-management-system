<?php
/**
 * AJAX: Update complaint status
 * Usage: POST update_complaint_status.php
 * Body: id=1&status=RESOLVED
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';
require_once __DIR__ . '/../../Models/AdminModel.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';
$actorUserId = $_SESSION['user_id'];

$validStatuses = ['OPEN', 'IN_PROGRESS', 'RESOLVED', 'CLOSED'];

if ($id <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid complaint ID"]);
    exit;
}

if (!in_array($status, $validStatuses)) {
    echo json_encode(["success" => false, "error" => "Invalid status"]);
    exit;
}

$conn = dbConnect();
$status = mysqli_real_escape_string($conn, $status);

// Add resolved_at if status is RESOLVED or CLOSED
$resolvedAt = in_array($status, ['RESOLVED', 'CLOSED']) ? ", resolved_at = NOW()" : "";

$sql = "UPDATE complaints SET status = '$status' $resolvedAt WHERE id = $id";
$result = mysqli_query($conn, $sql);
mysqli_close($conn);

if ($result) {
    createAuditLog($actorUserId, 'UPDATE', 'complaints', $id, ['status' => $status]);
    echo json_encode(["success" => true, "message" => "Complaint status updated"]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to update status"]);
}
?>
