<?php
// app/Controllers/Admin/UserController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'users';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'create_user') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $password = $_POST['password'];
        $status = $_POST['status'];
        $roleId = (int)$_POST['role_id'];
        
        if (empty($name) || empty($email) || empty($password)) {
            $error = 'Name, email and password are required.';
        } else {
            $result = createUser($name, $email, $phone, $password, $status, $roleId, $actorUserId);
            if ($result) {
                header('Location: index.php?page=admin_users&msg=user_created');
                exit;
            } else {
                $error = 'Failed to create user. Email may already exist.';
            }
        }
    } elseif ($formAction === 'update_user') {
        $id = (int)$_POST['id'];
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $status = $_POST['status'];
        
        $result = updateUser($id, $name, $email, $phone, $status, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_users&msg=user_updated');
            exit;
        } else {
            $error = 'Failed to update user.';
        }
    } elseif ($formAction === 'update_user_password') {
        $id = (int)$_POST['id'];
        $newPassword = $_POST['new_password'];
        
        if (empty($newPassword) || strlen($newPassword) < 6) {
            $error = 'Password must be at least 6 characters.';
        } else {
            $result = updateUserPassword($id, $newPassword, $actorUserId);
            if ($result) {
                header('Location: index.php?page=admin_users&msg=password_updated');
                exit;
            } else {
                $error = 'Failed to update password.';
            }
        }
    } elseif ($formAction === 'delete_user') {
        $id = (int)$_POST['id'];
        $result = deleteUser($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_users&msg=user_deleted');
            exit;
        } else {
            $error = 'Failed to delete user.';
        }
    } elseif ($formAction === 'change_user_role') {
        $userId = (int)$_POST['user_id'];
        $newRoleId = (int)$_POST['new_role_id'];
        $result = changeUserRole($userId, $newRoleId, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_users&msg=role_changed');
            exit;
        } else {
            $error = 'Failed to change role.';
        }
    }
}

// Handle GET requests
$pageTitle = 'User Management';
$data = [];

if ($action === 'view') {
    $id = (int)$_GET['id'];
    $data['user'] = getUserById($id);
    $data['roles'] = getAllRoles();
    $pageTitle = 'View User: ' . ($data['user']['name'] ?? 'Unknown');
} elseif ($action === 'edit') {
    $id = (int)$_GET['id'];
    $data['user'] = getUserById($id);
    $data['roles'] = getAllRoles();
    $pageTitle = 'Edit User';
} elseif ($action === 'add') {
    $pageTitle = 'Add New User';
    $data['roles'] = getAllRoles();
} elseif ($action === 'search') {
    $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
    $pageTitle = 'Search Users';
    $data['users'] = !empty($keyword) ? searchUsers($keyword) : [];
    $data['keyword'] = $keyword;
} else {
    $data['users'] = getAllUsers();
    $data['roles'] = getAllRoles();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'user_created') {
        $message = 'User created successfully.';
    } elseif ($_GET['msg'] === 'user_updated') {
        $message = 'User updated successfully.';
    } elseif ($_GET['msg'] === 'user_deleted') {
        $message = 'User deleted successfully.';
    } elseif ($_GET['msg'] === 'password_updated') {
        $message = 'Password updated successfully.';
    } elseif ($_GET['msg'] === 'role_changed') {
        $message = 'User role changed successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/UserView.php';
