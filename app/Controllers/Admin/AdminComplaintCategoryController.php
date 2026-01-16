<?php
// app/Controllers/Admin/ComplaintCategoryController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'categories';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'create_complaint_category') {
        $name = trim($_POST['name']);
        
        if (empty($name)) {
            $error = 'Category name is required.';
        } else {
            $result = createComplaintCategory($name, $actorUserId);
            if ($result) {
                header('Location: index.php?page=admin_complaint_categories&msg=category_created');
                exit;
            } else {
                $error = 'Failed to create category.';
            }
        }
    } elseif ($formAction === 'update_complaint_category') {
        $id = (int)$_POST['id'];
        $name = trim($_POST['name']);
        
        $result = updateComplaintCategory($id, $name, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_complaint_categories&msg=category_updated');
            exit;
        } else {
            $error = 'Failed to update category.';
        }
    } elseif ($formAction === 'delete_complaint_category') {
        $id = (int)$_POST['id'];
        $result = deleteComplaintCategory($id, $actorUserId);
        if ($result) {
            header('Location: index.php?page=admin_complaint_categories&msg=category_deleted');
            exit;
        } else {
            $error = 'Failed to delete category. It may have complaints.';
        }
    }
}

// Handle GET requests
$pageTitle = 'Complaint Categories';
$data = [];

if ($action === 'edit') {
    $id = (int)$_GET['id'];
    $data['category'] = getComplaintCategoryById($id);
    $pageTitle = 'Edit Category';
} elseif ($action === 'add') {
    $pageTitle = 'Add New Category';
} else {
    $data['categories'] = getAllComplaintCategories();
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'category_created') {
        $message = 'Category created successfully.';
    } elseif ($_GET['msg'] === 'category_updated') {
        $message = 'Category updated successfully.';
    } elseif ($_GET['msg'] === 'category_deleted') {
        $message = 'Category deleted successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/ComplaintCategoryView.php';