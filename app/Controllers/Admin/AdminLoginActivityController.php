<?php
/**
 * Admin Login Activity Controller
 * Handles login/logout/signup activity tracking and display
 */

require_once __DIR__ . '/../../Models/AdminModel.php';

$action = $_GET['action'] ?? 'list';
$message = '';
$error = '';
$data = [];

switch ($action) {
    case 'view':
        $pageTitle = 'View Login Activity';
        $logId = (int)($_GET['id'] ?? 0);
        if ($logId > 0) {
            $log = getAuditLogById($logId);
            if ($log) {
                // Get user details
                $conn = dbConnect();
                $stmt = mysqli_prepare($conn, "SELECT name, email FROM users WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $log['actor_user_id']);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_assoc($result);
                mysqli_close($conn);
                
                $log['user_name'] = $user['name'] ?? 'Unknown';
                $log['user_email'] = $user['email'] ?? '';
                $data['log'] = $log;
            } else {
                $error = 'Log entry not found';
                $action = 'list';
            }
        } else {
            $error = 'Invalid log ID';
            $action = 'list';
        }
        break;
        
    case 'list':
    default:
        $pageTitle = 'Login Activity';
        $data['logs'] = getLoginActivityLogs(500);
        $action = 'list';
        break;
}

// Fallback to list if view failed
if ($action === 'list' && empty($data['logs'])) {
    $data['logs'] = getLoginActivityLogs(500);
}

// Load the view
include __DIR__ . '/../../Views/Admin/LoginActivityView.php';
