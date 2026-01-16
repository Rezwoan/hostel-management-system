<?php
/**
 * AJAX: Get dashboard statistics
 * Usage: GET get_dashboard_stats.php
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$conn = dbConnect();

// Total students
$sql = "SELECT COUNT(DISTINCT u.id) as count FROM users u 
        JOIN user_roles ur ON u.id = ur.user_id 
        JOIN roles r ON ur.role_id = r.id 
        WHERE r.name = 'STUDENT'";
$result = mysqli_query($conn, $sql);
$totalStudents = mysqli_fetch_assoc($result)['count'] ?? 0;

// Total hostels
$sql = "SELECT COUNT(*) as count FROM hostels WHERE status = 'ACTIVE'";
$result = mysqli_query($conn, $sql);
$totalHostels = mysqli_fetch_assoc($result)['count'] ?? 0;

// Total rooms
$sql = "SELECT COUNT(*) as count FROM rooms";
$result = mysqli_query($conn, $sql);
$totalRooms = mysqli_fetch_assoc($result)['count'] ?? 0;

// Total seats
$sql = "SELECT COUNT(*) as count FROM seats";
$result = mysqli_query($conn, $sql);
$totalSeats = mysqli_fetch_assoc($result)['count'] ?? 0;

// Occupied seats
$sql = "SELECT COUNT(DISTINCT seat_id) as count FROM allocations WHERE end_date IS NULL";
$result = mysqli_query($conn, $sql);
$occupiedSeats = mysqli_fetch_assoc($result)['count'] ?? 0;

$availableSeats = $totalSeats - $occupiedSeats;
$occupancyRate = $totalSeats > 0 ? round(($occupiedSeats / $totalSeats) * 100, 1) : 0;

// Pending applications (room_applications table)
$sql = "SELECT COUNT(*) as count FROM room_applications WHERE status = 'PENDING'";
$result = mysqli_query($conn, $sql);
$pendingApplications = $result ? (mysqli_fetch_assoc($result)['count'] ?? 0) : 0;

// Open complaints
$sql = "SELECT COUNT(*) as count FROM complaints WHERE status IN ('OPEN', 'IN_PROGRESS')";
$result = mysqli_query($conn, $sql);
$openComplaints = $result ? (mysqli_fetch_assoc($result)['count'] ?? 0) : 0;

// Financial stats
$sql = "SELECT 
        COALESCE(SUM(amount_due), 0) as total_due,
        COALESCE(SUM(CASE WHEN status = 'PAID' THEN amount_due ELSE 0 END), 0) as total_paid
        FROM student_invoices";
$result = mysqli_query($conn, $sql);
$financial = mysqli_fetch_assoc($result);

// Today's collection
$sql = "SELECT COALESCE(SUM(amount_paid), 0) as today FROM payments WHERE DATE(paid_at) = CURDATE()";
$result = mysqli_query($conn, $sql);
$todayCollection = mysqli_fetch_assoc($result)['today'] ?? 0;

mysqli_close($conn);

$stats = [
    "total_students" => (int)$totalStudents,
    "total_hostels" => (int)$totalHostels,
    "total_rooms" => (int)$totalRooms,
    "total_seats" => (int)$totalSeats,
    "occupied_seats" => (int)$occupiedSeats,
    "available_seats" => (int)$availableSeats,
    "occupancy_rate" => $occupancyRate,
    "pending_applications" => (int)$pendingApplications,
    "open_complaints" => (int)$openComplaints,
    "total_due" => (float)($financial['total_due'] ?? 0),
    "total_collected" => (float)($financial['total_paid'] ?? 0),
    "today_collection" => (float)$todayCollection,
    "last_updated" => date('H:i:s')
];

echo json_encode(["success" => true, "data" => $stats]);
?>
