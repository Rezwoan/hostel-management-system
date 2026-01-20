<?php
// app/Controllers/Student/StudentDashboardController.php

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}

if ($_SESSION['role'] !== 'STUDENT') {
    if ($_SESSION['role'] === 'ADMIN') {
        header("Location: index.php?page=admin_dashboard");
    } elseif ($_SESSION['role'] === 'MANAGER') {
        header("Location: index.php?page=manager_dashboard");
    }
    exit;
}

require_once __DIR__ . '/../../Models/StudentModel.php';

$studentUserId = $_SESSION['user_id'];
$pageTitle = 'Student Dashboard';

// Get dashboard statistics
$stats = student_get_dashboard_stats($studentUserId);

// Get recent notices
$conn = dbConnect();
$sql = "SELECT n.*, u.name as created_by_name, h.name as hostel_name 
        FROM notices n 
        LEFT JOIN users u ON n.created_by_user_id = u.id 
        LEFT JOIN hostels h ON n.hostel_id = h.id 
        WHERE n.status = 'PUBLISHED' 
        AND (n.scope = 'GLOBAL' OR (n.scope = 'HOSTEL' AND n.hostel_id IN (
            SELECT a.hostel_id FROM allocations a WHERE a.student_user_id = $studentUserId AND a.status = 'ACTIVE'
        )))
        ORDER BY n.created_at DESC 
        LIMIT 5";
$result = mysqli_query($conn, $sql);
$recentNotices = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

// Get current allocation details
$allocation = student_get_allocation($studentUserId);

// Get current application status
$application = student_get_current_application($studentUserId);

// Prepare data for view
$data = [
    'stats' => $stats,
    'notices' => $recentNotices,
    'allocation' => $allocation,
    'application' => $application
];

require_once __DIR__ . '/../../Views/Student/StudentDashboardView.php';
