<?php
declare(strict_types=1);

class ManagerController
{
    public function handle(): void
    {
        $pageTitle = 'Manager Portal';
        $message = 'Hello Manager, this is a demo controller.';
        include __DIR__ . '/../../Views/Manager/ManagerDashboardView.php';
    }
}
