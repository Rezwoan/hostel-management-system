<?php
declare(strict_types=1);

class StudentController
{
    public function handle(): void
    {
        $pageTitle = 'Student Portal';
        $message = 'Hello Student, this is a demo controller.';
        include __DIR__ . '/../../Views/Student/dashboard.php';
    }
}
