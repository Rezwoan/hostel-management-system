<?php
// app/Controllers/Student/StudentFeesController.php

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'STUDENT') {
    header("Location: index.php?page=login");
    exit;
}

require_once __DIR__ . '/../../Models/StudentModel.php';

$studentUserId = $_SESSION['user_id'];
$pageTitle = 'Fee Status';

// Get invoices and payments
$conn = dbConnect();

// Get invoices with fee details
$sql = "SELECT i.*, fp.name as period_name, h.name as hostel_name,
               (SELECT COALESCE(SUM(p.amount_paid), 0) FROM payments p WHERE p.invoice_id = i.id) as paid_amount
        FROM student_invoices i 
        JOIN fee_periods fp ON i.period_id = fp.id 
        JOIN hostels h ON i.hostel_id = h.id 
        WHERE i.student_user_id = $studentUserId 
        ORDER BY i.generated_at DESC";
$result = mysqli_query($conn, $sql);
$invoices = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get all payments
$sql = "SELECT p.*, i.period_id, fp.name as period_name, u.name as recorded_by_name 
        FROM payments p 
        JOIN student_invoices i ON p.invoice_id = i.id 
        JOIN fee_periods fp ON i.period_id = fp.id 
        LEFT JOIN users u ON p.recorded_by_user_id = u.id 
        WHERE i.student_user_id = $studentUserId 
        ORDER BY p.paid_at DESC";
$result = mysqli_query($conn, $sql);
$payments = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($conn);

$data = [
    'invoices' => $invoices,
    'payments' => $payments
];

require_once __DIR__ . '/../../Views/Student/StudentFeesView.php';
