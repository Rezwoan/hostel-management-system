<?php
// app/Controllers/Admin/PaymentController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'payments';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'record_payment') {
        $invoiceId = (int)$_POST['invoice_id'];
        $amountPaid = (float)$_POST['amount_paid'];
        $method = $_POST['method'];
        $referenceNo = trim($_POST['reference_no']);
        
        // Server-side validation: Check if payment exceeds remaining balance
        $invoice = getInvoiceById($invoiceId);
        if ($invoice) {
            $amountDue = (float)$invoice['amount_due'];
            $paidAmount = (float)($invoice['paid_amount'] ?? 0);
            $balance = $amountDue - $paidAmount;
            
            if ($amountPaid <= 0) {
                $error = 'Payment amount must be greater than zero.';
            } elseif ($amountPaid > $balance) {
                $error = 'Payment amount ($' . number_format($amountPaid, 2) . ') exceeds remaining balance ($' . number_format($balance, 2) . ').';
            } else {
                $result = recordPayment($invoiceId, $amountPaid, $method, $referenceNo, $actorUserId);
                if ($result) {
                    header('Location: index.php?page=admin_payments&msg=payment_recorded');
                    exit;
                } else {
                    $error = 'Failed to record payment.';
                }
            }
        } else {
            $error = 'Invalid invoice selected.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Payment Records';
$data = [];

if ($action === 'add') {
    $pageTitle = 'Record Payment';
    $data['invoices'] = getAllInvoices();
} elseif ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['payment'] = getPaymentById($id);
    $pageTitle = 'View Payment';
} else {
    $data['payments'] = getAllPayments();
    $data['stats'] = getPaymentStats();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'payment_recorded') {
        $message = 'Payment recorded successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/PaymentView.php';
