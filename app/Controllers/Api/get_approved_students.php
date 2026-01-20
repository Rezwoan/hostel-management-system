<?php
/**
 * AJAX: Get all students with APPROVED applications who don't have active allocations
 * Usage: GET get_approved_students.php
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$conn = dbConnect();

// Get students with APPROVED applications who don't have ACTIVE allocations
$sql = "SELECT DISTINCT u.id, u.name, u.email, sp.student_id, 
               ra.id as application_id, ra.hostel_id, h.name as hostel_name, 
               ra.preferred_room_type_id, rt.name as room_type_name
        FROM users u 
        JOIN user_roles ur ON u.id = ur.user_id 
        JOIN roles r ON ur.role_id = r.id 
        LEFT JOIN student_profiles sp ON u.id = sp.user_id
        JOIN room_applications ra ON u.id = ra.student_user_id AND ra.status = 'APPROVED'
        JOIN hostels h ON ra.hostel_id = h.id
        JOIN room_types rt ON ra.preferred_room_type_id = rt.id
        LEFT JOIN allocations a ON u.id = a.student_user_id AND a.status = 'ACTIVE'
        WHERE r.name = 'STUDENT' 
        AND u.status = 'ACTIVE'
        AND a.id IS NULL
        ORDER BY u.name ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    mysqli_close($conn);
    exit;
}

$students = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

echo json_encode(["success" => true, "data" => $students]);
?>
