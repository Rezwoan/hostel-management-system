<?php
// app/Controllers/Manager/ManagerPaymentController.php

require_once __DIR__ . '/../../Models/ManagerModel.php';
require_once __DIR__ . '/../../Models/AdminModel.php';

$managerUserId = $_SESSION['user_id'];
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
        
        // Verify manager has access to this invoice's hostel
        $invoice = getInvoiceById($invoiceId);
        if ($invoice) {
            $hasAccess = manager_verify_hostel_access($managerUserId, $invoice['hostel_id']);
            if (!$hasAccess) {
                $error = 'Access denied. You do not have permission to record payments for this invoice.';
            } else {
                // Server-side validation: Check if payment exceeds remaining balance
                $amountDue = (float)$invoice['amount_due'];
                $paidAmount = (float)($invoice['paid_amount'] ?? 0);
                $balance = $amountDue - $paidAmount;
                
                if ($amountPaid <= 0) {
                    $error = 'Payment amount must be greater than zero.';
                } elseif ($amountPaid > $balance) {
                    $error = 'Payment amount ($' . number_format($amountPaid, 2) . ') exceeds remaining balance ($' . number_format($balance, 2) . ').';
                } else {
                    $result = recordPayment($invoiceId, $amountPaid, $method, $referenceNo, $managerUserId);
                    if ($result) {
                        header('Location: index.php?page=manager_payments&msg=payment_recorded');
                        exit;
                    } else {
                        $error = 'Failed to record payment.';
                    }
                }
            }
        } else {
            $error = 'Invalid invoice selected.';
        }
    } elseif ($formAction === 'delete_payment') {
        $paymentId = (int)$_POST['id'];
        
        // Verify manager has access to this payment's invoice's hostel
        $payment = getPaymentById($paymentId);
        if ($payment) {
            $invoice = getInvoiceById($payment['invoice_id']);
            if ($invoice) {
                $hasAccess = manager_verify_hostel_access($managerUserId, $invoice['hostel_id']);
                if (!$hasAccess) {
                    $error = 'Access denied. You do not have permission to delete this payment.';
                } else {
                    $result = deletePayment($paymentId, $managerUserId);
                    if ($result) {
                        header('Location: index.php?page=manager_payments&msg=payment_deleted');
                        exit;
                    } else {
                        $error = 'Failed to delete payment.';
                    }
                }
            }
        } else {
            $error = 'Invalid payment selected.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Payment Records';
$data = [];

if ($action === 'add') {
    $pageTitle = 'Record Payment';
    // Get invoices for manager's hostels only
    $data['invoices'] = manager_get_invoices($managerUserId);
    
    // Pre-select invoice if invoice_id is provided
    if (isset($_GET['invoice_id'])) {
        $data['selected_invoice_id'] = (int)$_GET['invoice_id'];
    }
} elseif ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['payment'] = getPaymentById($id);
    
    // Verify manager has access to this payment's invoice's hostel
    if ($data['payment']) {
        $invoice = getInvoiceById($data['payment']['invoice_id']);
        if ($invoice) {
            $hasAccess = manager_verify_hostel_access($managerUserId, $invoice['hostel_id']);
            if (!$hasAccess) {
                header('Location: index.php?page=manager_payments&error=access_denied');
                exit;
            }
        }
    }
    $pageTitle = 'View Payment';
} else {
    $data['payments'] = manager_get_payments($managerUserId);
    $data['stats'] = manager_get_payment_stats($managerUserId);
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'payment_recorded') {
        $message = 'Payment recorded successfully.';
    } elseif ($_GET['msg'] === 'payment_deleted') {
        $message = 'Payment deleted successfully.';
    }
}

// Handle error messages
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'access_denied') {
        $error = 'Access denied. You do not have permission to access this payment.';
    }
}

require_once __DIR__ . '/../../Views/Manager/ManagerPaymentView.php';
