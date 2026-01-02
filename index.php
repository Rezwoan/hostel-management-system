<?php
declare(strict_types=1);

// Minimal front controller to route by role for the demo controllers/views
$role = isset($_GET['role']) ? strtolower($_GET['role']) : 'student';

$controllerMap = [
	'admin' => [
		'file' => __DIR__ . '/app/Controllers/Admin/AdminController.php',
		'class' => 'AdminController',
	],
	'manager' => [
		'file' => __DIR__ . '/app/Controllers/Manager/ManagerController.php',
		'class' => 'ManagerController',
	],
	'student' => [
		'file' => __DIR__ . '/app/Controllers/Student/StudentController.php',
		'class' => 'StudentController',
	],
];

if (!isset($controllerMap[$role])) {
	http_response_code(404);
	echo 'Role not found.';
	exit;
}

$controllerInfo = $controllerMap[$role];
require_once $controllerInfo['file'];

$controller = new $controllerInfo['class']();
$controller->handle();
?>