<?php
// app/Controllers/Admin/AuditLogController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'logs';
$message = '';
$error = '';

// Handle POST requests (Undo actions)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'undo_role_change') {
        $userId = (int)$_POST['user_id'];
        $oldRoleId = (int)$_POST['old_role_id'];
        
        $result = changeUserRole($userId, $oldRoleId, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_audit_logs&msg=undo_success');
            exit;
        } else {
            $error = 'Failed to undo the action.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Audit Logs';
$data = [];

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'undo_success') {
        $message = 'Action undone successfully. A new audit log entry has been created.';
    }
}

if ($action === 'view') {
    $logId = (int)$_GET['id'];
    $data['log'] = getAuditLogById($logId);
    $pageTitle = 'View Audit Log';
} elseif ($action === 'by_user') {
    $userId = (int)$_GET['user_id'];
    $data['logs'] = getAuditLogsByUser($userId);
    $data['user'] = getUserById($userId);
    $pageTitle = 'User Audit History';
} elseif ($action === 'by_entity') {
    $entityType = $_GET['entity_type'];
    $entityId = (int)$_GET['entity_id'];
    $data['logs'] = getAuditLogsByEntity($entityType, $entityId);
    $pageTitle = 'Entity Audit History';
} else {
    // Build filters from GET parameters
    $filters = [];
    
    // Filter by action type (but not if it's 'logs' which is the default action)
    if (!empty($_GET['filter_action'])) {
        $filters['action'] = $_GET['filter_action'];
    }
    
    if (!empty($_GET['entity_type'])) {
        $filters['entity_type'] = $_GET['entity_type'];
    }
    
    if (!empty($_GET['from_date'])) {
        $filters['from_date'] = $_GET['from_date'];
    }
    
    if (!empty($_GET['to_date'])) {
        $filters['to_date'] = $_GET['to_date'];
    }
    
    $data['logs'] = getAllAuditLogs($filters);
    $data['filters'] = $filters;
}

require_once __DIR__ . '/../../Views/Admin/AuditLogView.php';
