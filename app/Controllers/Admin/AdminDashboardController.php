<?php
// app/Controllers/Admin/DashboardController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$pageTitle = 'Dashboard';
$data = [];
$data['stats'] = getDashboardStats();
$data['recent_logs'] = getRecentAuditLogs(10);

require_once __DIR__ . '/../../Views/Admin/AdminDashboardView.php';
