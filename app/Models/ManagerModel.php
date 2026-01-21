<?php
// app/Models/ManagerModel.php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/AdminModel.php';

// ============================================================
// MANAGER INFO & HOSTEL
// ============================================================

function manager_get_assigned_hostels($managerUserId) {
    $conn = dbConnect();
    $sql = "SELECT h.*, hm.assigned_at 
            FROM hostel_managers hm 
            JOIN hostels h ON hm.hostel_id = h.id 
            WHERE hm.manager_user_id = $managerUserId 
            ORDER BY h.name";
    $result = mysqli_query($conn, $sql);
    $hostels = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $hostels;
}

function manager_get_hostel_details($hostelId) {
    $conn = dbConnect();
    $sql = "SELECT * FROM hostels WHERE id = $hostelId";
    $result = mysqli_query($conn, $sql);
    $hostel = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $hostel;
}

// ============================================================
// DASHBOARD STATS
// ============================================================

function manager_get_dashboard_stats($managerUserId) {
    $conn = dbConnect();
    $stats = [];
    
    // Get manager's hostels
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        mysqli_close($conn);
        return [
            'total_hostels' => 0,
            'total_seats' => 0,
            'occupied_seats' => 0,
            'available_seats' => 0,
            'occupancy_rate' => 0,
            'pending_applications' => 0,
            'open_complaints' => 0,
            'total_students' => 0,
            'unpaid_invoices' => 0
        ];
    }
    
    $hostelIdsStr = implode(',', $hostelIds);
    
    // Total hostels
    $stats['total_hostels'] = count($hostels);
    
    // Total seats in manager's hostels
    $sql = "SELECT COUNT(*) as count 
            FROM seats s 
            JOIN rooms r ON s.room_id = r.id 
            JOIN floors f ON r.floor_id = f.id 
            WHERE f.hostel_id IN ($hostelIdsStr) AND s.status = 'ACTIVE'";
    $result = mysqli_query($conn, $sql);
    $stats['total_seats'] = (int)mysqli_fetch_assoc($result)['count'];
    
    // Occupied seats
    $sql = "SELECT COUNT(DISTINCT a.seat_id) as count 
            FROM allocations a 
            WHERE a.hostel_id IN ($hostelIdsStr) AND a.status = 'ACTIVE'";
    $result = mysqli_query($conn, $sql);
    $stats['occupied_seats'] = (int)mysqli_fetch_assoc($result)['count'];
    
    // Available seats
    $stats['available_seats'] = $stats['total_seats'] - $stats['occupied_seats'];
    
    // Occupancy rate
    $stats['occupancy_rate'] = $stats['total_seats'] > 0 
        ? round(($stats['occupied_seats'] / $stats['total_seats']) * 100, 1) 
        : 0;
    
    // Pending applications
    $sql = "SELECT COUNT(*) as count 
            FROM room_applications 
            WHERE hostel_id IN ($hostelIdsStr) AND status = 'SUBMITTED'";
    $result = mysqli_query($conn, $sql);
    $stats['pending_applications'] = (int)mysqli_fetch_assoc($result)['count'];
    
    // Open complaints
    $sql = "SELECT COUNT(*) as count 
            FROM complaints 
            WHERE hostel_id IN ($hostelIdsStr) AND status IN ('OPEN', 'IN_PROGRESS')";
    $result = mysqli_query($conn, $sql);
    $stats['open_complaints'] = (int)mysqli_fetch_assoc($result)['count'];
    
    // Total students with active allocations
    $sql = "SELECT COUNT(DISTINCT a.student_user_id) as count 
            FROM allocations a 
            WHERE a.hostel_id IN ($hostelIdsStr) AND a.status = 'ACTIVE'";
    $result = mysqli_query($conn, $sql);
    $stats['total_students'] = (int)mysqli_fetch_assoc($result)['count'];
    
    // Unpaid invoices
    $sql = "SELECT COUNT(*) as count 
            FROM student_invoices 
            WHERE hostel_id IN ($hostelIdsStr) AND status IN ('DUE', 'PARTIAL')";
    $result = mysqli_query($conn, $sql);
    $stats['unpaid_invoices'] = (int)mysqli_fetch_assoc($result)['count'];
    
    mysqli_close($conn);
    return $stats;
}

// ============================================================
// ROOM APPLICATIONS
// ============================================================

function manager_get_pending_applications($managerUserId) {
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        return [];
    }
    
    $conn = dbConnect();
    $hostelIdsStr = implode(',', $hostelIds);
    $sql = "SELECT ra.*, 
                   u.name as student_name, u.email as student_email, u.phone as student_phone,
                   sp.student_id, sp.department,
                   h.name as hostel_name, h.code as hostel_code,
                   rt.name as room_type_name
            FROM room_applications ra 
            JOIN users u ON ra.student_user_id = u.id 
            JOIN student_profiles sp ON u.id = sp.user_id
            JOIN hostels h ON ra.hostel_id = h.id 
            JOIN room_types rt ON ra.preferred_room_type_id = rt.id 
            WHERE ra.hostel_id IN ($hostelIdsStr) AND ra.status = 'SUBMITTED' 
            ORDER BY ra.submitted_at ASC";
    $result = mysqli_query($conn, $sql);
    $applications = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $applications;
}

function manager_get_all_applications($managerUserId) {
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        return [];
    }
    
    $conn = dbConnect();
    $hostelIdsStr = implode(',', $hostelIds);
    $sql = "SELECT ra.*, 
                   u.name as student_name, u.email as student_email,
                   sp.student_id,
                   h.name as hostel_name,
                   rt.name as room_type_name,
                   reviewer.name as reviewer_name
            FROM room_applications ra 
            JOIN users u ON ra.student_user_id = u.id 
            JOIN student_profiles sp ON u.id = sp.user_id
            JOIN hostels h ON ra.hostel_id = h.id 
            JOIN room_types rt ON ra.preferred_room_type_id = rt.id 
            LEFT JOIN users reviewer ON ra.reviewed_by_manager_user_id = reviewer.id
            WHERE ra.hostel_id IN ($hostelIdsStr) 
            ORDER BY ra.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $applications = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $applications;
}

function manager_get_application_by_id($applicationId) {
    $conn = dbConnect();
    $sql = "SELECT ra.*, 
                   u.name as student_name, u.email as student_email, u.phone as student_phone,
                   sp.student_id, sp.department, sp.session_year, sp.dob, sp.address,
                   h.name as hostel_name, h.code as hostel_code,
                   rt.name as room_type_name, rt.default_fee,
                   reviewer.name as reviewer_name
            FROM room_applications ra 
            JOIN users u ON ra.student_user_id = u.id 
            JOIN student_profiles sp ON u.id = sp.user_id
            JOIN hostels h ON ra.hostel_id = h.id 
            JOIN room_types rt ON ra.preferred_room_type_id = rt.id 
            LEFT JOIN users reviewer ON ra.reviewed_by_manager_user_id = reviewer.id
            WHERE ra.id = $applicationId";
    $result = mysqli_query($conn, $sql);
    $application = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $application;
}

function manager_approve_application($applicationId, $managerUserId) {
    $conn = dbConnect();
    $now = date('Y-m-d H:i:s');
    $sql = "UPDATE room_applications 
            SET status = 'APPROVED', 
                reviewed_at = '$now', 
                reviewed_by_manager_user_id = $managerUserId 
            WHERE id = $applicationId AND status = 'SUBMITTED'";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        createAuditLog($managerUserId, 'APPROVE', 'room_applications', $applicationId, json_encode(['reviewed_by' => $managerUserId]));
    }
    
    return $result;
}

function manager_reject_application($applicationId, $rejectReason, $managerUserId) {
    $conn = dbConnect();
    $now = date('Y-m-d H:i:s');
    $sql = "UPDATE room_applications 
            SET status = 'REJECTED', 
                reject_reason = '$rejectReason', 
                reviewed_at = '$now', 
                reviewed_by_manager_user_id = $managerUserId 
            WHERE id = $applicationId AND status = 'SUBMITTED'";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        createAuditLog($managerUserId, 'REJECT', 'room_applications', $applicationId, json_encode(['reviewed_by' => $managerUserId, 'reason' => $rejectReason]));
    }
    
    return $result;
}

function manager_delete_application($applicationId, $managerUserId) {
    $conn = dbConnect();
    
    // First verify the manager has access to this application's hostel
    $checkSql = "SELECT ra.id 
                 FROM room_applications ra
                 JOIN hostel_managers hm ON ra.hostel_id = hm.hostel_id
                 WHERE ra.id = $applicationId AND hm.manager_user_id = $managerUserId";
    $checkResult = mysqli_query($conn, $checkSql);
    
    if (!$checkResult || mysqli_num_rows($checkResult) === 0) {
        mysqli_close($conn);
        return false; // No access to this application
    }
    
    $sql = "DELETE FROM room_applications WHERE id = $applicationId";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        createAuditLog($managerUserId, 'DELETE', 'room_applications', $applicationId, json_encode(['deleted_by' => $managerUserId]));
    }
    
    return $result;
}

// ============================================================
// ALLOCATIONS
// ============================================================

function manager_create_allocation($studentUserId, $seatId, $hostelId, $managerUserId) {
    $conn = dbConnect();
    $startDate = date('Y-m-d H:i:s');
    
    mysqli_begin_transaction($conn);
    try {
        // Create allocation
        $sql = "INSERT INTO allocations (student_user_id, seat_id, hostel_id, start_date, created_by_manager_user_id, status) 
                VALUES ($studentUserId, $seatId, $hostelId, '$startDate', $managerUserId, 'ACTIVE')";
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error creating allocation");
        }
        $allocationId = mysqli_insert_id($conn);
        
        mysqli_commit($conn);
        mysqli_close($conn);
        
        createAuditLog($managerUserId, 'ASSIGN', 'allocations', $allocationId, json_encode(['seat_id' => $seatId, 'student_user_id' => $studentUserId]));
        
        return $allocationId;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        mysqli_close($conn);
        return false;
    }
}

function manager_get_allocations($managerUserId) {
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        return [];
    }
    
    $conn = dbConnect();
    $hostelIdsStr = implode(',', $hostelIds);
    $sql = "SELECT a.*, 
                   u.name as student_name, u.email as student_email,
                   sp.student_id,
                   h.name as hostel_name,
                   f.floor_no, f.label as floor_label,
                   r.room_no,
                   s.seat_label,
                   creator.name as created_by_name
            FROM allocations a 
            JOIN users u ON a.student_user_id = u.id 
            JOIN student_profiles sp ON u.id = sp.user_id
            JOIN seats s ON a.seat_id = s.id 
            JOIN rooms r ON s.room_id = r.id 
            JOIN floors f ON r.floor_id = f.id 
            JOIN hostels h ON a.hostel_id = h.id 
            LEFT JOIN users creator ON a.created_by_manager_user_id = creator.id
            WHERE a.hostel_id IN ($hostelIdsStr) 
            ORDER BY a.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $allocations = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $allocations;
}

function manager_get_available_seats($hostelId) {
    $conn = dbConnect();
    $sql = "SELECT s.*, r.room_no, r.room_type_id, f.floor_no, f.label as floor_label,
                   rt.name as room_type_name
            FROM seats s 
            JOIN rooms r ON s.room_id = r.id 
            JOIN floors f ON r.floor_id = f.id 
            JOIN room_types rt ON r.room_type_id = rt.id
            WHERE f.hostel_id = $hostelId 
            AND s.status = 'ACTIVE' 
            AND r.status = 'ACTIVE'
            AND s.id NOT IN (SELECT seat_id FROM allocations WHERE status = 'ACTIVE') 
            ORDER BY f.floor_no, r.room_no, s.seat_label";
    $result = mysqli_query($conn, $sql);
    $seats = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $seats;
}

// ============================================================
// COMPLAINTS
// ============================================================

function manager_get_complaints($managerUserId) {
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        return [];
    }
    
    $conn = dbConnect();
    $hostelIdsStr = implode(',', $hostelIds);
    $sql = "SELECT c.*, 
                   u.name as student_name, u.email as student_email,
                   sp.student_id,
                   h.name as hostel_name,
                   cc.name as category_name
            FROM complaints c 
            JOIN users u ON c.student_user_id = u.id 
            JOIN student_profiles sp ON u.id = sp.user_id
            JOIN hostels h ON c.hostel_id = h.id 
            JOIN complaint_categories cc ON c.category_id = cc.id 
            WHERE c.hostel_id IN ($hostelIdsStr) 
            ORDER BY 
                CASE c.status 
                    WHEN 'OPEN' THEN 1 
                    WHEN 'IN_PROGRESS' THEN 2 
                    WHEN 'RESOLVED' THEN 3 
                    WHEN 'CLOSED' THEN 4 
                END,
                c.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $complaints = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $complaints;
}

function manager_get_complaint_by_id($complaintId) {
    $conn = dbConnect();
    $sql = "SELECT c.*, 
                   u.name as student_name, u.email as student_email, u.phone as student_phone,
                   sp.student_id, sp.department,
                   h.name as hostel_name,
                   cc.name as category_name
            FROM complaints c 
            JOIN users u ON c.student_user_id = u.id 
            JOIN student_profiles sp ON u.id = sp.user_id
            JOIN hostels h ON c.hostel_id = h.id 
            JOIN complaint_categories cc ON c.category_id = cc.id 
            WHERE c.id = $complaintId";
    $result = mysqli_query($conn, $sql);
    $complaint = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $complaint;
}

function manager_get_complaint_messages($complaintId) {
    $conn = dbConnect();
    $sql = "SELECT cm.*, u.name as sender_name, 
                   CASE 
                       WHEN EXISTS (SELECT 1 FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = cm.sender_user_id AND r.name IN ('ADMIN', 'MANAGER')) 
                       THEN 'staff' 
                       ELSE 'student' 
                   END as sender_type
            FROM complaint_messages cm 
            JOIN users u ON cm.sender_user_id = u.id 
            WHERE cm.complaint_id = $complaintId 
            ORDER BY cm.created_at ASC";
    $result = mysqli_query($conn, $sql);
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $messages;
}

function manager_update_complaint_status($complaintId, $status, $managerUserId) {
    $conn = dbConnect();
    $sql = "UPDATE complaints SET status = '$status' WHERE id = $complaintId";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        createAuditLog($managerUserId, 'UPDATE_STATUS', 'complaints', $complaintId, json_encode(['status' => $status]));
    }
    
    return $result;
}

function manager_add_complaint_response($complaintId, $managerUserId, $message) {
    $conn = dbConnect();
    $sql = "INSERT INTO complaint_messages (complaint_id, sender_user_id, message) 
            VALUES ($complaintId, $managerUserId, '$message')";
    $result = mysqli_query($conn, $sql);
    $messageId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    return $messageId;
}

// ============================================================
// STUDENTS
// ============================================================

function manager_get_students($managerUserId) {
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        return [];
    }
    
    $conn = dbConnect();
    $hostelIdsStr = implode(',', $hostelIds);
    $sql = "SELECT DISTINCT u.*, 
                   sp.student_id, sp.department, sp.session_year,
                   a.hostel_id,
                   h.name as hostel_name,
                   f.floor_no,
                   r.room_no,
                   s.seat_label
            FROM allocations a 
            JOIN users u ON a.student_user_id = u.id 
            JOIN student_profiles sp ON u.id = sp.user_id
            JOIN hostels h ON a.hostel_id = h.id
            JOIN seats s ON a.seat_id = s.id
            JOIN rooms r ON s.room_id = r.id
            JOIN floors f ON r.floor_id = f.id
            WHERE a.hostel_id IN ($hostelIdsStr) AND a.status = 'ACTIVE' 
            ORDER BY u.name";
    $result = mysqli_query($conn, $sql);
    $students = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $students;
}

function manager_get_student_details($studentUserId) {
    $conn = dbConnect();
    $sql = "SELECT u.*, 
                   sp.student_id, sp.department, sp.session_year, sp.dob, sp.address, sp.profile_picture
            FROM users u 
            JOIN student_profiles sp ON u.id = sp.user_id 
            WHERE u.id = $studentUserId";
    $result = mysqli_query($conn, $sql);
    $student = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $student;
}

function manager_get_student_allocation($studentUserId) {
    $conn = dbConnect();
    $sql = "SELECT a.*, 
                   h.name as hostel_name, h.code as hostel_code,
                   f.floor_no, f.label as floor_label,
                   r.room_no, r.room_type_id,
                   rt.name as room_type_name,
                   s.seat_label
            FROM allocations a 
            JOIN hostels h ON a.hostel_id = h.id 
            JOIN seats s ON a.seat_id = s.id 
            JOIN rooms r ON s.room_id = r.id 
            JOIN floors f ON r.floor_id = f.id 
            JOIN room_types rt ON r.room_type_id = rt.id
            WHERE a.student_user_id = $studentUserId AND a.status = 'ACTIVE' 
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $allocation = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $allocation;
}

// ============================================================
// FEES
// ============================================================

function manager_get_student_invoices($managerUserId) {
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        return [];
    }
    
    $conn = dbConnect();
    $hostelIdsStr = implode(',', $hostelIds);
    $sql = "SELECT i.*, 
                   u.name as student_name, u.email as student_email,
                   sp.student_id,
                   h.name as hostel_name,
                   fp.name as period_name,
                   (SELECT COALESCE(SUM(p.amount_paid), 0) FROM payments p WHERE p.invoice_id = i.id) as paid_amount
            FROM student_invoices i 
            JOIN users u ON i.student_user_id = u.id 
            JOIN student_profiles sp ON u.id = sp.user_id
            JOIN hostels h ON i.hostel_id = h.id 
            JOIN fee_periods fp ON i.period_id = fp.id 
            WHERE i.hostel_id IN ($hostelIdsStr) 
            ORDER BY i.generated_at DESC";
    $result = mysqli_query($conn, $sql);
    $invoices = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $invoices;
}

function manager_get_invoice_details($invoiceId) {
    $conn = dbConnect();
    $sql = "SELECT i.*, 
                   u.name as student_name, u.email as student_email, u.phone as student_phone,
                   sp.student_id, sp.department,
                   h.name as hostel_name,
                   fp.name as period_name, fp.start_date, fp.end_date,
                   (SELECT COALESCE(SUM(p.amount_paid), 0) FROM payments p WHERE p.invoice_id = i.id) as paid_amount
            FROM student_invoices i 
            JOIN users u ON i.student_user_id = u.id 
            JOIN student_profiles sp ON u.id = sp.user_id
            JOIN hostels h ON i.hostel_id = h.id 
            JOIN fee_periods fp ON i.period_id = fp.id 
            WHERE i.id = $invoiceId";
    $result = mysqli_query($conn, $sql);
    $invoice = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $invoice;
}

function manager_get_payment_history($invoiceId) {
    $conn = dbConnect();
    $sql = "SELECT p.*, u.name as recorded_by_name 
            FROM payments p 
            JOIN users u ON p.recorded_by_user_id = u.id 
            WHERE p.invoice_id = $invoiceId 
            ORDER BY p.paid_at DESC";
    $result = mysqli_query($conn, $sql);
    $payments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $payments;
}

function manager_get_invoice_stats($managerUserId) {
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        return [
            'total_amount' => 0,
            'paid_amount' => 0,
            'pending_amount' => 0,
            'overdue_count' => 0
        ];
    }
    
    $conn = dbConnect();
    $hostelIdsStr = implode(',', $hostelIds);
    
    // Total amount due across all invoices for manager's hostels
    $sql = "SELECT 
                COALESCE(SUM(amount_due), 0) as total_amount,
                COALESCE(SUM(CASE WHEN status = 'DUE' THEN 1 ELSE 0 END), 0) as due_count,
                COALESCE(SUM(CASE WHEN status = 'PAID' THEN 1 ELSE 0 END), 0) as paid_count
            FROM student_invoices
            WHERE hostel_id IN ($hostelIdsStr)";
    $result = mysqli_query($conn, $sql);
    $invoiceStats = mysqli_fetch_assoc($result);
    
    // Total amount collected from payments for manager's hostels
    $sql = "SELECT COALESCE(SUM(p.amount_paid), 0) as paid_amount 
            FROM payments p
            JOIN student_invoices i ON p.invoice_id = i.id
            WHERE i.hostel_id IN ($hostelIdsStr)";
    $result = mysqli_query($conn, $sql);
    $paymentStats = mysqli_fetch_assoc($result);
    
    mysqli_close($conn);
    
    return [
        'total_amount' => (float)$invoiceStats['total_amount'],
        'paid_amount' => (float)$paymentStats['paid_amount'],
        'pending_amount' => (float)$invoiceStats['total_amount'] - (float)$paymentStats['paid_amount'],
        'overdue_count' => (int)$invoiceStats['due_count']
    ];
}

// ============================================================
// STATS FUNCTIONS
// ============================================================

function manager_get_application_stats($managerUserId) {
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        return ['pending' => 0, 'approved' => 0, 'rejected' => 0, 'total' => 0];
    }
    
    $conn = dbConnect();
    $hostelIdsStr = implode(',', $hostelIds);
    
    $stats = ['pending' => 0, 'approved' => 0, 'rejected' => 0, 'total' => 0];
    
    // Count pending (DRAFT + SUBMITTED)
    $sql = "SELECT COUNT(*) as cnt FROM room_applications WHERE hostel_id IN ($hostelIdsStr) AND status IN ('DRAFT', 'SUBMITTED')";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $stats['pending'] = (int)$row['cnt'];
    }
    
    // Count approved
    $sql = "SELECT COUNT(*) as cnt FROM room_applications WHERE hostel_id IN ($hostelIdsStr) AND status = 'APPROVED'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $stats['approved'] = (int)$row['cnt'];
    }
    
    // Count rejected
    $sql = "SELECT COUNT(*) as cnt FROM room_applications WHERE hostel_id IN ($hostelIdsStr) AND status = 'REJECTED'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $stats['rejected'] = (int)$row['cnt'];
    }
    
    // Total
    $sql = "SELECT COUNT(*) as cnt FROM room_applications WHERE hostel_id IN ($hostelIdsStr)";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $stats['total'] = (int)$row['cnt'];
    }
    
    mysqli_close($conn);
    return $stats;
}

function manager_get_complaint_stats($managerUserId) {
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        return ['open' => 0, 'in_progress' => 0, 'resolved' => 0, 'total' => 0];
    }
    
    $conn = dbConnect();
    $hostelIdsStr = implode(',', $hostelIds);
    
    $stats = ['open' => 0, 'in_progress' => 0, 'resolved' => 0, 'total' => 0];
    
    $sql = "SELECT status, COUNT(*) as count FROM complaints WHERE hostel_id IN ($hostelIdsStr) GROUP BY status";
    $result = mysqli_query($conn, $sql);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $status = strtolower($row['status']);
        $count = (int)$row['count'];
        
        if ($status === 'open') {
            $stats['open'] = $count;
        } elseif ($status === 'in_progress') {
            $stats['in_progress'] = $count;
        } elseif ($status === 'resolved' || $status === 'closed') {
            $stats['resolved'] += $count;
        }
        $stats['total'] += $count;
    }
    
    mysqli_close($conn);
    return $stats;
}

function manager_get_complaint_categories() {
    $conn = dbConnect();
    $sql = "SELECT * FROM complaint_categories ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $categories;
}

// ============================================================
// NOTICES
// ============================================================

function manager_get_hostel_notices($managerUserId) {
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        return [];
    }
    
    $conn = dbConnect();
    $hostelIdsStr = implode(',', $hostelIds);
    $sql = "SELECT n.*, h.name as hostel_name, u.name as created_by_name 
            FROM notices n 
            JOIN hostels h ON n.hostel_id = h.id 
            JOIN users u ON n.created_by_user_id = u.id 
            WHERE n.scope = 'HOSTEL' AND n.hostel_id IN ($hostelIdsStr) 
            ORDER BY n.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $notices = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $notices;
}

function manager_create_hostel_notice($hostelId, $title, $body, $status, $publishAt, $expireAt, $managerUserId) {
    $conn = dbConnect();
    $publishAtStr = $publishAt ? "'$publishAt'" : 'NULL';
    $expireAtStr = $expireAt ? "'$expireAt'" : 'NULL';
    
    $sql = "INSERT INTO notices (scope, hostel_id, title, body, status, publish_at, expire_at, created_by_user_id) 
            VALUES ('HOSTEL', $hostelId, '$title', '$body', '$status', $publishAtStr, $expireAtStr, $managerUserId)";
    $result = mysqli_query($conn, $sql);
    $noticeId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($noticeId) {
        createAuditLog($managerUserId, 'CREATE', 'notices', $noticeId, json_encode(['hostel_id' => $hostelId, 'scope' => 'HOSTEL']));
    }
    
    return $noticeId;
}

function manager_update_notice($noticeId, $title, $body, $status, $publishAt, $expireAt, $managerUserId) {
    $conn = dbConnect();
    $publishAtStr = $publishAt ? "'$publishAt'" : 'NULL';
    $expireAtStr = $expireAt ? "'$expireAt'" : 'NULL';
    
    $sql = "UPDATE notices 
            SET title = '$title', body = '$body', status = '$status', 
                publish_at = $publishAtStr, expire_at = $expireAtStr 
            WHERE id = $noticeId AND scope = 'HOSTEL'";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        createAuditLog($managerUserId, 'UPDATE', 'notices', $noticeId, NULL);
    }
    
    return $result;
}

function manager_delete_notice($noticeId, $managerUserId) {
    $conn = dbConnect();
    $sql = "DELETE FROM notices WHERE id = $noticeId AND scope = 'HOSTEL'";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        createAuditLog($managerUserId, 'DELETE', 'notices', $noticeId, NULL);
    }
    
    return $result;
}

function manager_get_notice_by_id($noticeId) {
    $conn = dbConnect();
    $sql = "SELECT n.*, h.name as hostel_name 
            FROM notices n 
            LEFT JOIN hostels h ON n.hostel_id = h.id 
            WHERE n.id = $noticeId";
    $result = mysqli_query($conn, $sql);
    $notice = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $notice;
}

// ============================================================
// INVOICE & FEE MANAGEMENT
// ============================================================

function manager_verify_hostel_access($managerUserId, $hostelId) {
    $conn = dbConnect();
    $sql = "SELECT id FROM hostel_managers 
            WHERE manager_user_id = $managerUserId AND hostel_id = $hostelId";
    $result = mysqli_query($conn, $sql);
    $hasAccess = mysqli_num_rows($result) > 0;
    mysqli_close($conn);
    return $hasAccess;
}

function manager_get_students_with_allocations($managerUserId) {
    $conn = dbConnect();
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        mysqli_close($conn);
        return [];
    }
    
    $hostelIdsStr = implode(',', $hostelIds);
    $sql = "SELECT DISTINCT u.id, u.name, u.email, sp.student_id,
                   a.hostel_id, h.name as hostel_name, r.room_no as room_number
            FROM users u
            JOIN student_profiles sp ON u.id = sp.user_id
            JOIN allocations a ON u.id = a.student_user_id
            JOIN hostels h ON a.hostel_id = h.id
            JOIN seats s ON a.seat_id = s.id
            JOIN rooms r ON s.room_id = r.id
            WHERE a.status = 'ACTIVE' AND a.hostel_id IN ($hostelIdsStr)
            ORDER BY u.name";
    $result = mysqli_query($conn, $sql);
    $students = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $students;
}

function manager_get_students_in_hostels($managerUserId) {
    $conn = dbConnect();
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        mysqli_close($conn);
        return [];
    }
    
    $hostelIdsStr = implode(',', $hostelIds);
    $sql = "SELECT DISTINCT u.id, u.name, u.email, sp.student_id
            FROM users u
            JOIN student_profiles sp ON u.id = sp.user_id
            JOIN allocations a ON u.id = a.student_user_id
            WHERE a.hostel_id IN ($hostelIdsStr)
            ORDER BY u.name";
    $result = mysqli_query($conn, $sql);
    $students = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $students;
}

function manager_get_invoices($managerUserId) {
    $conn = dbConnect();
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        mysqli_close($conn);
        return [];
    }
    
    $hostelIdsStr = implode(',', $hostelIds);
    $sql = "SELECT i.*, 
                   u.name as student_name, 
                   sp.student_id as student_id_number,
                   fp.name as period_name,
                   COALESCE(SUM(p.amount_paid), 0) as paid_amount
            FROM student_invoices i
            JOIN users u ON i.student_user_id = u.id
            LEFT JOIN student_profiles sp ON u.id = sp.user_id
            JOIN fee_periods fp ON i.period_id = fp.id
            LEFT JOIN payments p ON i.id = p.invoice_id
            WHERE i.hostel_id IN ($hostelIdsStr)
            GROUP BY i.id
            ORDER BY i.generated_at DESC";
    $result = mysqli_query($conn, $sql);
    $invoices = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $invoices;
}

// Get all payments for manager's hostels
function manager_get_payments($managerUserId) {
    $conn = dbConnect();
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        mysqli_close($conn);
        return [];
    }
    
    $hostelIdsStr = implode(',', $hostelIds);
    $sql = "SELECT p.*, 
                   i.amount_due,
                   u.name as student_name,
                   sp.student_id as student_id_number,
                   r.name as recorder_name
            FROM payments p
            JOIN student_invoices i ON p.invoice_id = i.id
            JOIN users u ON i.student_user_id = u.id
            LEFT JOIN student_profiles sp ON u.id = sp.user_id
            JOIN users r ON p.recorded_by_user_id = r.id
            WHERE i.hostel_id IN ($hostelIdsStr)
            ORDER BY p.id DESC";
    $result = mysqli_query($conn, $sql);
    $payments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $payments;
}

// Get payment statistics for manager's hostels
function manager_get_payment_stats($managerUserId) {
    $conn = dbConnect();
    $hostels = manager_get_assigned_hostels($managerUserId);
    $hostelIds = array_column($hostels, 'id');
    
    if (empty($hostelIds)) {
        mysqli_close($conn);
        return [
            'total_collected' => 0,
            'total_payments' => 0,
            'today_collected' => 0,
            'this_month' => 0
        ];
    }
    
    $hostelIdsStr = implode(',', $hostelIds);
    
    // Total collected
    $sql = "SELECT COALESCE(SUM(p.amount_paid), 0) as total_collected,
                   COUNT(p.id) as total_payments
            FROM payments p
            JOIN student_invoices i ON p.invoice_id = i.id
            WHERE i.hostel_id IN ($hostelIdsStr)";
    $result = mysqli_query($conn, $sql);
    $stats = mysqli_fetch_assoc($result);
    
    // Today's collection
    $sql = "SELECT COALESCE(SUM(p.amount_paid), 0) as today_collected
            FROM payments p
            JOIN student_invoices i ON p.invoice_id = i.id
            WHERE i.hostel_id IN ($hostelIdsStr)
            AND DATE(p.paid_at) = CURDATE()";
    $result = mysqli_query($conn, $sql);
    $todayStats = mysqli_fetch_assoc($result);
    $stats['today_collected'] = $todayStats['today_collected'];
    
    // This month's collection
    $sql = "SELECT COALESCE(SUM(p.amount_paid), 0) as this_month
            FROM payments p
            JOIN student_invoices i ON p.invoice_id = i.id
            WHERE i.hostel_id IN ($hostelIdsStr)
            AND YEAR(p.paid_at) = YEAR(CURDATE())
            AND MONTH(p.paid_at) = MONTH(CURDATE())";
    $result = mysqli_query($conn, $sql);
    $monthStats = mysqli_fetch_assoc($result);
    $stats['this_month'] = $monthStats['this_month'];
    
    mysqli_close($conn);
    return $stats;
}
