<?php
/**
 * AJAX: Search students for allocation autocomplete
 * Only returns students with APPROVED room applications who don't have active allocations
 * Usage: GET search_students.php?q=john
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode(["success" => true, "data" => []]);
    exit;
}

$conn = dbConnect();
$query = mysqli_real_escape_string($conn, $query);

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
        AND (u.name LIKE '%$query%' OR u.email LIKE '%$query%' OR sp.student_id LIKE '%$query%')
        LIMIT 10";

$result = mysqli_query($conn, $sql);
$students = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

echo json_encode(["success" => true, "data" => $students]);
?>
