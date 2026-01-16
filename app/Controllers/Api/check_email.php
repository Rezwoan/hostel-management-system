<?php
/**
 * AJAX: Check if email already exists
 * Usage: GET check_email.php?email=test@example.com
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$email = isset($_GET['email']) ? trim($_GET['email']) : '';
$excludeId = isset($_GET['exclude_id']) ? (int)$_GET['exclude_id'] : 0;

if (empty($email)) {
    echo json_encode(["success" => false, "error" => "Email is required"]);
    exit;
}

$conn = dbConnect();
$email = mysqli_real_escape_string($conn, $email);

$sql = "SELECT COUNT(*) as count FROM users WHERE email = '$email'";
if ($excludeId > 0) {
    $sql .= " AND id != $excludeId";
}

$result = mysqli_query($conn, $sql);
$count = mysqli_fetch_assoc($result)['count'];
mysqli_close($conn);

echo json_encode(["success" => true, "exists" => $count > 0]);
?>
