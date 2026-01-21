<?php
// app/Controllers/Manager/ManagerDashboardController.php

require_once __DIR__ . '/../../Models/ManagerModel.php';

$managerUserId = $_SESSION['user_id'];
$pageTitle = 'Manager Dashboard';

// Get dashboard stats
$data = [];
$data['stats'] = manager_get_dashboard_stats($managerUserId);
$data['hostels'] = manager_get_assigned_hostels($managerUserId);

// Get recent pending applications (top 5)
$allApplications = manager_get_pending_applications($managerUserId);
$data['recent_applications'] = array_slice($allApplications, 0, 5);

// Get recent open complaints (top 5)
$allComplaints = manager_get_complaints($managerUserId);
$openComplaints = array_filter($allComplaints, function($c) {
    return in_array($c['status'], ['OPEN', 'IN_PROGRESS']);
});
$data['recent_complaints'] = array_slice($openComplaints, 0, 5);

require_once __DIR__ . '/../../Views/Manager/ManagerDashboardView.php';
