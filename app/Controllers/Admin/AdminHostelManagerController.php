<?php
// app/Controllers/Admin/HostelManagerController.php

require_once __DIR__ . '/../../Models/AdminModel.php';

$actorUserId = $_SESSION['user_id'];
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$message = '';
$error = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = isset($_POST['form_action']) ? $_POST['form_action'] : '';
    
    if ($formAction === 'assign_manager') {
        // Validate required fields
        if (empty($_POST['hostel_id']) || empty($_POST['user_id'])) {
            $error = 'Please select both a hostel and a manager.';
        } else {
            $hostelId = (int)$_POST['hostel_id'];
            $managerUserId = (int)$_POST['user_id'];
            
            // Additional validation - ensure IDs are positive
            if ($hostelId <= 0 || $managerUserId <= 0) {
                $error = 'Invalid hostel or manager selection.';
            } else {
                $result = assignManagerToHostel($hostelId, $managerUserId, $actorUserId);
                if ($result) {
                    header('Location: index.php?page=admin_hostel_managers&msg=manager_assigned');
                    exit;
                } else {
                    $error = 'Failed to assign manager. The manager may already be assigned to this hostel.';
                }
            }
        }
    } elseif ($formAction === 'update_assignment') {
        // Validate required fields
        if (empty($_POST['id']) || empty($_POST['hostel_id']) || empty($_POST['user_id'])) {
            $error = 'Please provide all required information.';
        } else {
            $id = (int)$_POST['id'];
            $hostelId = (int)$_POST['hostel_id'];
            $managerUserId = (int)$_POST['user_id'];
            
            // Additional validation
            if ($id <= 0 || $hostelId <= 0 || $managerUserId <= 0) {
                $error = 'Invalid assignment, hostel, or manager selection.';
            } else {
                $result = updateManagerAssignment($id, $hostelId, $managerUserId, $actorUserId);
                if ($result) {
                    header('Location: index.php?page=admin_hostel_managers&msg=assignment_updated');
                    exit;
                } else {
                    $error = 'Failed to update assignment.';
                }
            }
        }
    } elseif ($formAction === 'remove_manager') {
        if (empty($_POST['id'])) {
            $error = 'Invalid assignment ID.';
        } else {
            $id = (int)$_POST['id'];
            if ($id <= 0) {
                $error = 'Invalid assignment ID.';
            } else {
                $result = removeManagerFromHostel($id, $actorUserId);
                if ($result) {
                    header('Location: index.php?page=admin_hostel_managers&msg=manager_removed');
                    exit;
                } else {
                    $error = 'Failed to remove manager.';
                }
            }
        }
    }
}

// Handle GET requests
$pageTitle = 'Hostel Manager Assignments';
$data = [];
$data['assignments'] = getAllHostelManagers();
$data['hostels'] = getAllHostels();
$data['managers'] = getUsersByRole(2);

// Handle view and edit actions
if (($action === 'view' || $action === 'edit') && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($id > 0) {
        $data['assignment'] = getHostelManagerById($id);
        if (!$data['assignment']) {
            $error = 'Assignment not found.';
            $action = 'list'; // Revert to list view if assignment not found
        }
    } else {
        $error = 'Invalid assignment ID.';
        $action = 'list';
    }
}

// Handle success messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'manager_assigned') {
        $message = 'Manager assigned successfully.';
    } elseif ($_GET['msg'] === 'manager_removed') {
        $message = 'Manager removed successfully.';
    } elseif ($_GET['msg'] === 'assignment_updated') {
        $message = 'Assignment updated successfully.';
    }
}

require_once __DIR__ . '/../../Views/Admin/HostelManagerView.php';
