<?php
// app/Controllers/Student/StudentController.php

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}

if ($_SESSION['role'] !== 'STUDENT') {
    if ($_SESSION['role'] === 'ADMIN') {
        header("Location: index.php?page=admin_dashboard");
    } elseif ($_SESSION['role'] === 'MANAGER') {
        header("Location: index.php?page=manager_dashboard");
    }
    exit;
}

require_once __DIR__ . '/../../Views/Student/StudentDashboardView.php';