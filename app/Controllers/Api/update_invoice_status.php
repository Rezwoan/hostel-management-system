<?php
/**
 * AJAX: Update invoice status
 * Usage: POST update_invoice_status.php
 * Body: id=1&status=PAID
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

$validStatuses = ['DUE', 'PARTIAL', 'PAID', 'OVERDUE', 'CANCELLED', 'WAIVED'];

if ($id <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid invoice ID"]);
    exit;
}

if (!in_array($status, $validStatuses)) {
    echo json_encode(["success" => false, "error" => "Invalid status"]);
    exit;
}

$conn = dbConnect();
$status = mysqli_real_escape_string($conn, $status);

$sql = "UPDATE student_invoices SET status = '$status' WHERE id = $id";
$result = mysqli_query($conn, $sql);
mysqli_close($conn);

if ($result) {
    createAuditLog($actorUserId, 'UPDATE', 'student_invoices', $id, ['status' => $status]);
    echo json_encode(["success" => true, "message" => "Invoice status updated"]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to update status"]);
}
?>
