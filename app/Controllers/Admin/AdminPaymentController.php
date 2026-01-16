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
        
        $result = recordPayment($invoiceId, $amountPaid, $method, $referenceNo, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_payments&msg=payment_recorded');
            exit;
        } else {
            $error = 'Failed to record payment.';
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
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'payment_recorded') {
        $message = 'Payment recorded successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/PaymentView.php';
