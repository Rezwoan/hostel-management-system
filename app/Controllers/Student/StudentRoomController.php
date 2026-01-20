<?php
// app/Controllers/Student/StudentRoomController.php

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'STUDENT') {
    header("Location: index.php?page=login");
    exit;
}

require_once __DIR__ . '/../../Models/StudentModel.php';

$studentUserId = $_SESSION['user_id'];
$pageTitle = 'My Room';

// Get current allocation
$allocation = student_get_allocation($studentUserId);

// Get roommates if allocated
$roommates = [];
if ($allocation) {
    $conn = dbConnect();
    $roomId = (int)$allocation['room_id'];
    $sql = "SELECT u.name, u.email, u.phone, sp.student_id, sp.department, s.seat_label 
            FROM allocations a 
            JOIN seats s ON a.seat_id = s.id 
            JOIN users u ON a.student_user_id = u.id 
            JOIN student_profiles sp ON u.id = sp.user_id 
            WHERE s.room_id = $roomId AND a.status = 'ACTIVE' AND a.student_user_id != $studentUserId";
    $result = mysqli_query($conn, $sql);
    $roommates = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
}

// Get allocation history
$history = student_get_allocation_history($studentUserId);

$data = [
    'allocation' => $allocation,
    'roommates' => $roommates,
    'history' => $history
];

require_once __DIR__ . '/../../Views/Student/StudentRoomView.php';
