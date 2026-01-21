<?php
// app/Controllers/Manager/ManagerStudentController.php

require_once __DIR__ . '/../../Models/ManagerModel.php';

$managerUserId = $_SESSION['user_id'];
$action = $_GET['action'] ?? 'list';
$pageTitle = 'Students';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['student'] = manager_get_student_details($id);
    $data['allocation'] = manager_get_student_allocation($id);
    
    // Get student's invoices
    $conn = dbConnect();
    $sql = "SELECT i.*, fp.name as period_name, h.name as hostel_name,
                   (SELECT COALESCE(SUM(p.amount_paid), 0) FROM payments p WHERE p.invoice_id = i.id) as paid_amount
            FROM student_invoices i
            JOIN fee_periods fp ON i.period_id = fp.id
            JOIN hostels h ON i.hostel_id = h.id
            WHERE i.student_user_id = $id
            ORDER BY i.generated_at DESC";
    $result = mysqli_query($conn, $sql);
    $data['invoices'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    
    $pageTitle = 'Student Details';
} else {
    $data['students'] = manager_get_students($managerUserId);
}

require_once __DIR__ . '/../../Views/Manager/ManagerStudentView.php';
