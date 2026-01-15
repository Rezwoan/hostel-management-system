<?php
declare(strict_types=1);

class AdminController
{
    public function handle(): void
    {
        $pageTitle = 'Admin Dashboard';
        $message = 'Hello Admin, this is a demo controller.';
        include __DIR__ . '/../../Views/Admin/AdminDashboardView.php';
    }
}
