<?php
// app/Controllers/Student/student_dashboard.php

// 1. Remove session_start() (Index.php handles this)
// session_start();

// 2. Check if User is Logged In
if (!isset($_SESSION['user_id'])) {
    // FIX: Use Router Link
    header("Location: index.php?page=login");
    exit;
}

// 3. Strict Role Check: Only STUDENTS allowed here
if ($_SESSION['role'] !== 'STUDENT') {
    // FIX: Use Router Links
    if ($_SESSION['role'] === 'ADMIN') {
        header("Location: index.php?page=admin_dashboard");
    } elseif ($_SESSION['role'] === 'MANAGER') {
        header("Location: index.php?page=manager_dashboard");
    }
    exit;
}

// 4. Load the Dashboard View
// FIX: Go up 2 levels (../../) to reach 'app', then 'Views/Student/'
require_once __DIR__ . '/../../Views/Student/student_dashboard_view.php';
?>