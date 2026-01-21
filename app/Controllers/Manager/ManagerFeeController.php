<?php
// app/Controllers/Manager/ManagerFeeController.php

require_once __DIR__ . '/../../Models/ManagerModel.php';

$managerUserId = $_SESSION['user_id'];
$action = $_GET['action'] ?? 'list';
$pageTitle = 'Fee Management';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['invoice'] = manager_get_invoice_details($id);
    $data['payments'] = manager_get_payment_history($id);
    
    if ($data['invoice']) {
        $data['remaining_amount'] = $data['invoice']['amount_due'] - $data['invoice']['paid_amount'];
    }
    
    $pageTitle = 'Invoice Details';
} else {
    $data['invoices'] = manager_get_student_invoices($managerUserId);
    $data['stats'] = manager_get_invoice_stats($managerUserId);
}

require_once __DIR__ . '/../../Views/Manager/ManagerFeeView.php';
