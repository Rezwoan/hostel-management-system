<?php
// app/Controllers/Student/StudentNoticesController.php

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'STUDENT') {
    header("Location: index.php?page=login");
    exit;
}

require_once __DIR__ . '/../../Models/StudentModel.php';

$studentUserId = $_SESSION['user_id'];
$pageTitle = 'Notices';

// Get all notices (global + hostel-specific)
$conn = dbConnect();
$sql = "SELECT n.*, u.name as created_by_name, h.name as hostel_name 
        FROM notices n 
        LEFT JOIN users u ON n.created_by_user_id = u.id 
        LEFT JOIN hostels h ON n.hostel_id = h.id 
        WHERE n.status = 'PUBLISHED' 
        AND (n.scope = 'GLOBAL' OR (n.scope = 'HOSTEL' AND n.hostel_id IN (
            SELECT a.hostel_id FROM allocations a WHERE a.student_user_id = $studentUserId AND a.status = 'ACTIVE'
        )))
        ORDER BY n.created_at DESC";
$result = mysqli_query($conn, $sql);
$notices = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

$data = [
    'notices' => $notices
];

require_once __DIR__ . '/../../Views/Student/StudentNoticesView.php';
