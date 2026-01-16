<?php
/**
 * AJAX: Delete payment
 * Usage: POST delete_payment.php
 * Body: id=1
 */
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../../Models/Database.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid payment ID"]);
    exit;
}

$conn = dbConnect();

// Get invoice ID for status update
$sql = "SELECT invoice_id FROM payments WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$invoiceId = $row ? (int)$row['invoice_id'] : 0;

$sql = "DELETE FROM payments WHERE id = $id";
$result = mysqli_query($conn, $sql);

if ($result && $invoiceId > 0) {
    // Recalculate invoice status
    $sql = "SELECT si.amount_due, COALESCE(SUM(p.amount_paid), 0) as total_paid 
            FROM student_invoices si 
            LEFT JOIN payments p ON si.id = p.invoice_id 
            WHERE si.id = $invoiceId 
            GROUP BY si.id";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    
    if ($row) {
        $amountDue = (float)$row['amount_due'];
        $totalPaid = (float)$row['total_paid'];
        
        if ($totalPaid >= $amountDue) {
            $newStatus = 'PAID';
        } elseif ($totalPaid > 0) {
            $newStatus = 'PARTIAL';
        } else {
            $newStatus = 'DUE';
        }
        
        mysqli_query($conn, "UPDATE student_invoices SET status = '$newStatus' WHERE id = $invoiceId");
    }
}

mysqli_close($conn);

if ($result) {
    echo json_encode(["success" => true, "message" => "Payment deleted"]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to delete payment"]);
}
?>
