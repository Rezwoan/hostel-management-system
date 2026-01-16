<?php
/**
 * AJAX: Update room application status
 * Usage: POST update_application_status.php
 * Body: id=1&status=APPROVED or id=1&status=REJECTED&reject_reason=...
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';
$rejectReason = isset($_POST['reject_reason']) ? trim($_POST['reject_reason']) : '';
$reviewerId = $_SESSION['user_id'];

$validStatuses = ['DRAFT', 'SUBMITTED', 'APPROVED', 'REJECTED', 'CANCELLED'];

if ($id <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid application ID"]);
    exit;
}

if (!in_array($status, $validStatuses)) {
    echo json_encode(["success" => false, "error" => "Invalid status: " . $status]);
    exit;
}

// Require reason for rejection
if ($status === 'REJECTED' && empty($rejectReason)) {
    echo json_encode(["success" => false, "error" => "Rejection reason is required"]);
    exit;
}

$conn = dbConnect();
$status = mysqli_real_escape_string($conn, $status);
$rejectReason = mysqli_real_escape_string($conn, $rejectReason);

// Build update query
$rejectReasonSql = $status === 'REJECTED' ? "'$rejectReason'" : "NULL";
$sql = "UPDATE room_applications 
        SET status = '$status', 
            reject_reason = $rejectReasonSql, 
            reviewed_at = NOW(), 
            reviewed_by_manager_user_id = $reviewerId 
        WHERE id = $id";

$result = mysqli_query($conn, $sql);
mysqli_close($conn);

if ($result) {
    echo json_encode(["success" => true, "message" => "Application status updated to $status"]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to update status"]);
}
?>
