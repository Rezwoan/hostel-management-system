<?php
/**
 * AJAX: Delete user
 * Usage: POST delete_user.php
 * Body: id=1&type=hard|soft
 * 
 * type=hard: Permanently delete from database (Admin only)
 * type=soft: Set status to INACTIVE (default)
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$deleteType = isset($_POST['type']) ? $_POST['type'] : 'soft';
$actorUserId = $_SESSION['user_id'];
$actorRole = $_SESSION['role'] ?? '';

if ($id <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid user ID"]);
    exit;
}

// Check if trying to delete own account
if ($id == $actorUserId) {
    echo json_encode(["success" => false, "error" => "Cannot delete your own account"]);
    exit;
}

$conn = dbConnect();

// Hard delete - permanently remove from database
if ($deleteType === 'hard') {
    // Only Admin can hard delete
    if ($actorRole !== 'ADMIN') {
        mysqli_close($conn);
        echo json_encode(["success" => false, "error" => "Only Admin can permanently delete users"]);
        exit;
    }
    
    // Delete related records first (foreign key constraints)
    mysqli_query($conn, "DELETE FROM user_roles WHERE user_id = $id");
    mysqli_query($conn, "DELETE FROM student_profiles WHERE user_id = $id");
    mysqli_query($conn, "DELETE FROM allocations WHERE student_user_id = $id");
    mysqli_query($conn, "DELETE FROM room_applications WHERE student_user_id = $id");
    mysqli_query($conn, "DELETE FROM complaints WHERE student_user_id = $id");
    mysqli_query($conn, "UPDATE student_invoices SET student_user_id = NULL WHERE student_user_id = $id");
    mysqli_query($conn, "UPDATE payments SET recorded_by = NULL WHERE recorded_by = $id");
    
    $sql = "DELETE FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $affected = mysqli_affected_rows($conn);
    mysqli_close($conn);
    
    if ($result && $affected > 0) {
        echo json_encode(["success" => true, "message" => "User permanently deleted"]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to delete user"]);
    }
} else {
    // Soft delete - set status to INACTIVE
    $sql = "UPDATE users SET status = 'INACTIVE' WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        echo json_encode(["success" => true, "message" => "User deactivated"]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to deactivate user"]);
    }
}
?>
