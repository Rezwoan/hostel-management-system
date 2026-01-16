<?php
// app/Controllers/Admin/InvoiceController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'invoices';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'create_invoice') {
        $studentUserId = (int)$_POST['student_user_id'];
        $hostelId = (int)$_POST['hostel_id'];
        $periodId = (int)$_POST['period_id'];
        $amountDue = (float)$_POST['amount_due'];
        
        $result = createInvoice($studentUserId, $hostelId, $periodId, $amountDue, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_invoices&msg=invoice_created');
            exit;
        } else {
            $error = 'Failed to create invoice.';
        }
    } elseif ($formAction === 'update_invoice') {
        $id = (int)$_POST['id'];
        $studentUserId = (int)$_POST['student_user_id'];
        $hostelId = (int)$_POST['hostel_id'];
        $periodId = (int)$_POST['period_id'];
        $amountDue = (float)$_POST['amount_due'];
        $status = $_POST['status'];
        
        $result = updateInvoice($id, $studentUserId, $hostelId, $periodId, $amountDue, $status, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_invoices&msg=invoice_updated');
            exit;
        } else {
            $error = 'Failed to update invoice.';
        }
    } elseif ($formAction === 'update_invoice_status') {
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        
        $result = updateInvoiceStatus($id, $status, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_invoices&msg=invoice_updated');
            exit;
        } else {
            $error = 'Failed to update invoice status.';
        }
    } elseif ($formAction === 'delete_invoice') {
        $id = (int)$_POST['id'];
        $result = deleteInvoice($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_invoices&msg=invoice_deleted');
            exit;
        } else {
            $error = 'Failed to delete invoice. It may have payments.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Invoice Management';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['invoice'] = getInvoiceById($id);
    $data['payments'] = getPaymentsByInvoice($id);
    $pageTitle = 'View Invoice';
} elseif ($action === 'edit') {
    $id = (int)$_GET['id'];
    $data['invoice'] = getInvoiceById($id);
    $data['students'] = getAllStudents();
    $data['hostels'] = getAllHostels();
    $data['periods'] = getAllFeePeriods();
    $pageTitle = 'Edit Invoice';
} elseif ($action === 'add') {
    $pageTitle = 'Create Invoice';
    $data['students'] = getAllStudents();
    $data['hostels'] = getAllHostels();
    $data['periods'] = getAllFeePeriods();
} else {
    $data['invoices'] = getAllInvoices();
    $data['stats'] = getInvoiceStats();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'invoice_created') {
        $message = 'Invoice created successfully.';
    } elseif ($_GET['msg'] === 'invoice_updated') {
        $message = 'Invoice status updated successfully.';
    } elseif ($_GET['msg'] === 'invoice_deleted') {
        $message = 'Invoice deleted successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/InvoiceView.php';