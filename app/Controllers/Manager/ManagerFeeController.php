<?php
// app/Controllers/Manager/ManagerFeeController.php

require_once __DIR__ . '/../../Models/ManagerModel.php';
require_once __DIR__ . '/../../Models/AdminModel.php';

$managerUserId = $_SESSION['user_id'];
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
        
        // Verify manager has access to this hostel
        $hostelAccess = manager_verify_hostel_access($managerUserId, $hostelId);
        if (!$hostelAccess) {
            $error = 'Access denied. You do not have permission to manage this hostel.';
        } else {
            $result = createInvoice($studentUserId, $hostelId, $periodId, $amountDue, $managerUserId);
            if ($result) {
                header('Location: index.php?page=manager_fees&msg=invoice_created');
                exit;
            } else {
                $error = 'Failed to create invoice.';
            }
        }
    } elseif ($formAction === 'update_invoice') {
        $id = (int)$_POST['id'];
        $studentUserId = (int)$_POST['student_user_id'];
        $hostelId = (int)$_POST['hostel_id'];
        $periodId = (int)$_POST['period_id'];
        $amountDue = (float)$_POST['amount_due'];
        $status = $_POST['status'];
        
        // Verify manager has access to this hostel
        $hostelAccess = manager_verify_hostel_access($managerUserId, $hostelId);
        if (!$hostelAccess) {
            $error = 'Access denied. You do not have permission to manage this hostel.';
        } else {
            $result = updateInvoice($id, $studentUserId, $hostelId, $periodId, $amountDue, $status, $managerUserId);
            if ($result) {
                header('Location: index.php?page=manager_fees&msg=invoice_updated');
                exit;
            } else {
                $error = 'Failed to update invoice.';
            }
        }
    } elseif ($formAction === 'update_invoice_status') {
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        
        // Verify manager has access to this invoice's hostel
        $invoice = getInvoiceById($id);
        if ($invoice) {
            $hostelAccess = manager_verify_hostel_access($managerUserId, $invoice['hostel_id']);
            if (!$hostelAccess) {
                $error = 'Access denied.';
            } else {
                $result = updateInvoiceStatus($id, $status, $managerUserId);
                if ($result) {
                    header('Location: index.php?page=manager_fees&msg=invoice_updated');
                    exit;
                } else {
                    $error = 'Failed to update invoice status.';
                }
            }
        } else {
            $error = 'Invoice not found.';
        }
    } elseif ($formAction === 'delete_invoice') {
        $id = (int)$_POST['id'];
        
        // Verify manager has access to this invoice's hostel
        $invoice = getInvoiceById($id);
        if ($invoice) {
            $hostelAccess = manager_verify_hostel_access($managerUserId, $invoice['hostel_id']);
            if (!$hostelAccess) {
                $error = 'Access denied.';
            } else {
                $result = deleteInvoice($id, $managerUserId);
                if ($result) {
                    header('Location: index.php?page=manager_fees&msg=invoice_deleted');
                    exit;
                } else {
                    $error = 'Failed to delete invoice. It may have payments.';
                }
            }
        } else {
            $error = 'Invoice not found.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Fee Management';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['invoice'] = getInvoiceById($id);
    
    // Verify manager has access to this invoice's hostel
    if ($data['invoice']) {
        $hostelAccess = manager_verify_hostel_access($managerUserId, $data['invoice']['hostel_id']);
        if (!$hostelAccess) {
            header('Location: index.php?page=manager_fees&error=access_denied');
            exit;
        }
        $data['payments'] = getPaymentsByInvoice($id);
    }
    $pageTitle = 'View Invoice';
} elseif ($action === 'edit') {
    $id = (int)$_GET['id'];
    $data['invoice'] = getInvoiceById($id);
    
    // Verify manager has access to this invoice's hostel
    if ($data['invoice']) {
        $hostelAccess = manager_verify_hostel_access($managerUserId, $data['invoice']['hostel_id']);
        if (!$hostelAccess) {
            header('Location: index.php?page=manager_fees&error=access_denied');
            exit;
        }
    }
    
    $data['students'] = manager_get_students_in_hostels($managerUserId);
    $data['hostels'] = manager_get_assigned_hostels($managerUserId);
    $data['periods'] = getAllFeePeriods();
    $pageTitle = 'Edit Invoice';
} elseif ($action === 'add') {
    $pageTitle = 'Create Invoice';
    $data['students'] = manager_get_students_with_allocations($managerUserId);
    $data['hostels'] = manager_get_assigned_hostels($managerUserId);
    $data['periods'] = getAllFeePeriods();
} else {
    $data['invoices'] = manager_get_invoices($managerUserId);
    $data['stats'] = manager_get_invoice_stats($managerUserId);
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

// Handle error messages
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'access_denied') {
        $error = 'Access denied. You do not have permission to access this invoice.';
    }
}

require_once __DIR__ . '/../../Views/Manager/ManagerFeeView.php';
