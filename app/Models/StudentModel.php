<?php
// app/Models/StudentModel.php

require_once __DIR__ . '/Database.php';

// ============================================================
// STUDENT AUTHENTICATION & ACCOUNT
// ============================================================

/**
 * Create a new student account
 * Inserts data into: users, user_roles, student_profiles tables
 */
function createStudentAccount($name, $email, $password, $phone, $studentId, $department, $sessionYear, $dob, $address) {
    $conn = dbConnect();
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    mysqli_begin_transaction($conn);
    
    try {
        // Insert into users table
        $sql = "INSERT INTO users (name, email, phone, password_hash, status) 
                VALUES ('$name', '$email', '$phone', '$passwordHash', 'ACTIVE')";
        
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error creating user");
        }
        
        $newUserId = mysqli_insert_id($conn);
        
        // Get STUDENT role ID
        $roleResult = mysqli_query($conn, "SELECT id FROM roles WHERE name = 'STUDENT' LIMIT 1");
        $studentRoleId = mysqli_fetch_assoc($roleResult)['id'];
        
        // Assign STUDENT role
        $sql = "INSERT INTO user_roles (user_id, role_id) VALUES ($newUserId, $studentRoleId)";
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error assigning role");
        }
        
        // Create student profile
        $sql = "INSERT INTO student_profiles (user_id, student_id, department, session_year, dob, address) 
                VALUES ($newUserId, '$studentId', '$department', '$sessionYear', '$dob', '$address')";
        
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error creating profile");
        }
        
        mysqli_commit($conn);
        mysqli_close($conn);
        return $newUserId;
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        mysqli_close($conn);
        return false;
    }
}

/**
 * Alias for compatibility
 */
function student_create($name, $email, $password, $phone, $studentId, $department, $sessionYear, $dob, $address) {
    return createStudentAccount($name, $email, $password, $phone, $studentId, $department, $sessionYear, $dob, $address);
}

/**
 * Find student by email
 */
function student_find_by_email($email) {
    $conn = dbConnect();
    $sql = "SELECT u.*, sp.student_id, sp.department, sp.session_year, sp.dob, sp.address 
            FROM users u 
            LEFT JOIN student_profiles sp ON u.id = sp.user_id 
            WHERE u.email = '$email' 
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $user;
}

/**
 * Find student by student ID (e.g. STU-2026-0001)
 */
function student_find_by_student_id($studentId) {
    $conn = dbConnect();
    $sql = "SELECT u.*, sp.student_id, sp.department, sp.session_year, sp.dob, sp.address 
            FROM users u 
            JOIN student_profiles sp ON u.id = sp.user_id 
            WHERE sp.student_id = '$studentId' 
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $user;
}

/**
 * Student login - verify email and password
 */
function student_login($email, $password) {
    $conn = dbConnect();
    $sql = "SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN user_roles ur ON u.id = ur.user_id 
            JOIN roles r ON ur.role_id = r.id 
            WHERE u.email = '$email' AND r.name = 'STUDENT' 
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        return $user;
    }
    return false;
}

/**
 * Update student password
 */
function student_update_password($userId, $newPassword) {
    $conn = dbConnect();
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password_hash = '$passwordHash' WHERE id = $userId";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    return $result;
}

// ============================================================
// STUDENT PROFILE
// ============================================================

/**
 * Get student profile by user ID
 */
function student_get_by_id($userId) {
    $conn = dbConnect();
    $sql = "SELECT u.*, sp.student_id, sp.department, sp.session_year, sp.dob, sp.address 
            FROM users u 
            LEFT JOIN student_profiles sp ON u.id = sp.user_id 
            WHERE u.id = $userId 
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $student = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $student;
}

/**
 * Update student profile information
 */
function student_update_profile($userId, $name, $email, $phone, $department, $sessionYear, $dob, $address) {
    $conn = dbConnect();
    
    mysqli_begin_transaction($conn);
    
    try {
        // Update users table
        $sql = "UPDATE users SET name = '$name', email = '$email', phone = '$phone' WHERE id = $userId";
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error updating user");
        }
        
        // Update student_profiles table
        $sql = "UPDATE student_profiles 
                SET department = '$department', session_year = '$sessionYear', dob = '$dob', address = '$address' 
                WHERE user_id = $userId";
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error updating profile");
        }
        
        mysqli_commit($conn);
        mysqli_close($conn);
        return true;
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        mysqli_close($conn);
        return false;
    }
}

// ============================================================
// ROOM APPLICATIONS
// ============================================================

/**
 * Create a new room application
 */
function student_create_application($studentUserId, $hostelId, $roomTypeId, $notes) {
    $conn = dbConnect();
    
    // Check if student already has a pending/approved application
    $checkSql = "SELECT id FROM room_applications 
                 WHERE student_user_id = $studentUserId 
                 AND status IN ('SUBMITTED', 'APPROVED') 
                 LIMIT 1";
    $checkResult = mysqli_query($conn, $checkSql);
    
    if (mysqli_num_rows($checkResult) > 0) {
        mysqli_close($conn);
        return false; // Already has active application
    }
    
    $sql = "INSERT INTO room_applications (student_user_id, hostel_id, preferred_room_type_id, status, notes, submitted_at) 
            VALUES ($studentUserId, $hostelId, $roomTypeId, 'SUBMITTED', '$notes', NOW())";
    $result = mysqli_query($conn, $sql);
    $applicationId = mysqli_insert_id($conn);
    mysqli_close($conn);
    
    return $result ? $applicationId : false;
}

/**
 * Get student's room application(s)
 */
function student_get_applications($studentUserId) {
    $conn = dbConnect();
    $sql = "SELECT ra.*, h.name as hostel_name, rt.name as room_type_name, rt.default_fee 
            FROM room_applications ra 
            JOIN hostels h ON ra.hostel_id = h.id 
            JOIN room_types rt ON ra.preferred_room_type_id = rt.id 
            WHERE ra.student_user_id = $studentUserId 
            ORDER BY ra.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $applications = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $applications;
}

/**
 * Get student's current/latest application
 */
function student_get_current_application($studentUserId) {
    $conn = dbConnect();
    $sql = "SELECT ra.*, h.name as hostel_name, rt.name as room_type_name, rt.default_fee 
            FROM room_applications ra 
            JOIN hostels h ON ra.hostel_id = h.id 
            JOIN room_types rt ON ra.preferred_room_type_id = rt.id 
            WHERE ra.student_user_id = $studentUserId 
            ORDER BY ra.created_at DESC 
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $application = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $application;
}

/**
 * Cancel a room application
 */
function student_cancel_application($applicationId, $studentUserId) {
    $conn = dbConnect();
    $sql = "UPDATE room_applications 
            SET status = 'CANCELLED' 
            WHERE id = $applicationId AND student_user_id = $studentUserId AND status = 'SUBMITTED'";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    return $result;
}

// ============================================================
// ALLOCATION & ACCOMMODATION
// ============================================================

/**
 * Get student's current allocation
 */
function student_get_allocation($studentUserId) {
    $conn = dbConnect();
    $sql = "SELECT a.*, h.name as hostel_name, h.code as hostel_code, 
                   f.floor_number, r.room_number, s.seat_label,
                   rt.name as room_type_name
            FROM allocations a 
            JOIN seats s ON a.seat_id = s.id 
            JOIN rooms r ON s.room_id = r.id 
            JOIN floors f ON r.floor_id = f.id 
            JOIN hostels h ON f.hostel_id = h.id
            JOIN room_types rt ON r.room_type_id = rt.id
            WHERE a.student_user_id = $studentUserId AND a.status = 'ACTIVE' 
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $allocation = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $allocation;
}

/**
 * Get student's allocation history
 */
function student_get_allocation_history($studentUserId) {
    $conn = dbConnect();
    $sql = "SELECT a.*, h.name as hostel_name, f.floor_number, r.room_number, s.seat_label 
            FROM allocations a 
            JOIN seats s ON a.seat_id = s.id 
            JOIN rooms r ON s.room_id = r.id 
            JOIN floors f ON r.floor_id = f.id 
            JOIN hostels h ON f.hostel_id = h.id 
            WHERE a.student_user_id = $studentUserId 
            ORDER BY a.start_date DESC";
    $result = mysqli_query($conn, $sql);
    $allocations = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $allocations;
}

// ============================================================
// INVOICES & PAYMENTS
// ============================================================

/**
 * Get student's invoices
 */
function student_get_invoices($studentUserId) {
    $conn = dbConnect();
    $sql = "SELECT i.*, fp.period_name, fp.fee_amount,
                   (SELECT COALESCE(SUM(p.amount), 0) FROM payments p WHERE p.invoice_id = i.id) as paid_amount
            FROM student_invoices i 
            JOIN fee_periods fp ON i.fee_period_id = fp.id 
            WHERE i.student_user_id = $studentUserId 
            ORDER BY i.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $invoices = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $invoices;
}

/**
 * Get student's pending invoices
 */
function student_get_pending_invoices($studentUserId) {
    $conn = dbConnect();
    $sql = "SELECT i.*, fp.period_name, fp.fee_amount,
                   (SELECT COALESCE(SUM(p.amount), 0) FROM payments p WHERE p.invoice_id = i.id) as paid_amount
            FROM student_invoices i 
            JOIN fee_periods fp ON i.fee_period_id = fp.id 
            WHERE i.student_user_id = $studentUserId AND i.status IN ('PENDING', 'PARTIAL') 
            ORDER BY i.due_date ASC";
    $result = mysqli_query($conn, $sql);
    $invoices = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $invoices;
}

/**
 * Get student's payment history
 */
function student_get_payments($studentUserId) {
    $conn = dbConnect();
    $sql = "SELECT p.*, i.invoice_number, fp.period_name 
            FROM payments p 
            JOIN student_invoices i ON p.invoice_id = i.id 
            JOIN fee_periods fp ON i.fee_period_id = fp.id 
            WHERE i.student_user_id = $studentUserId 
            ORDER BY p.payment_date DESC";
    $result = mysqli_query($conn, $sql);
    $payments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $payments;
}

// ============================================================
// COMPLAINTS
// ============================================================

/**
 * Create a new complaint
 */
function student_create_complaint($studentUserId, $categoryId, $subject, $description) {
    $conn = dbConnect();
    $sql = "INSERT INTO complaints (student_user_id, category_id, subject, description, status) 
            VALUES ($studentUserId, $categoryId, '$subject', '$description', 'OPEN')";
    $result = mysqli_query($conn, $sql);
    $complaintId = mysqli_insert_id($conn);
    mysqli_close($conn);
    return $result ? $complaintId : false;
}

/**
 * Get student's complaints
 */
function student_get_complaints($studentUserId) {
    $conn = dbConnect();
    $sql = "SELECT c.*, cc.name as category_name 
            FROM complaints c 
            JOIN complaint_categories cc ON c.category_id = cc.id 
            WHERE c.student_user_id = $studentUserId 
            ORDER BY c.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $complaints = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $complaints;
}

/**
 * Get complaint by ID (only if belongs to student)
 */
function student_get_complaint($complaintId, $studentUserId) {
    $conn = dbConnect();
    $sql = "SELECT c.*, cc.name as category_name 
            FROM complaints c 
            JOIN complaint_categories cc ON c.category_id = cc.id 
            WHERE c.id = $complaintId AND c.student_user_id = $studentUserId 
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $complaint = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $complaint;
}

/**
 * Get complaint messages
 */
function student_get_complaint_messages($complaintId) {
    $conn = dbConnect();
    $sql = "SELECT cm.*, u.name as sender_name 
            FROM complaint_messages cm 
            JOIN users u ON cm.sender_user_id = u.id 
            WHERE cm.complaint_id = $complaintId 
            ORDER BY cm.created_at ASC";
    $result = mysqli_query($conn, $sql);
    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $messages;
}

/**
 * Add message to complaint
 */
function student_add_complaint_message($complaintId, $senderUserId, $message) {
    $conn = dbConnect();
    $sql = "INSERT INTO complaint_messages (complaint_id, sender_user_id, message) 
            VALUES ($complaintId, $senderUserId, '$message')";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    return $result;
}

// ============================================================
// NOTICES
// ============================================================

/**
 * Get all published notices for students
 */
function student_get_notices() {
    $conn = dbConnect();
    $sql = "SELECT n.*, u.name as created_by_name 
            FROM notices n 
            LEFT JOIN users u ON n.created_by_user_id = u.id 
            WHERE n.status = 'PUBLISHED' AND (n.target_role = 'ALL' OR n.target_role = 'STUDENT')
            ORDER BY n.priority DESC, n.published_at DESC 
            LIMIT 20";
    $result = mysqli_query($conn, $sql);
    $notices = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $notices;
}

// ============================================================
// AVAILABLE ROOMS & HOSTELS
// ============================================================

/**
 * Get all available hostels
 */
function student_get_available_hostels() {
    $conn = dbConnect();
    $sql = "SELECT * FROM hostels WHERE status = 'ACTIVE' ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);
    $hostels = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $hostels;
}

/**
 * Get all room types
 */
function student_get_room_types() {
    $conn = dbConnect();
    $sql = "SELECT * FROM room_types ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);
    $roomTypes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $roomTypes;
}

/**
 * Get available seats count for a hostel
 */
function student_get_available_seats_count($hostelId) {
    $conn = dbConnect();
    $sql = "SELECT COUNT(*) as available_count 
            FROM seats s 
            JOIN rooms r ON s.room_id = r.id 
            JOIN floors f ON r.floor_id = f.id 
            WHERE f.hostel_id = $hostelId AND s.status = 'AVAILABLE'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $row['available_count'];
}

/**
 * Get complaint categories
 */
function student_get_complaint_categories() {
    $conn = dbConnect();
    $sql = "SELECT * FROM complaint_categories WHERE status = 'ACTIVE' ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $categories;
}

// ============================================================
// DASHBOARD STATISTICS
// ============================================================

/**
 * Get dashboard stats for student
 */
function student_get_dashboard_stats($studentUserId) {
    $conn = dbConnect();
    
    $stats = [];
    
    // Application status
    $sql = "SELECT status FROM room_applications 
            WHERE student_user_id = $studentUserId 
            ORDER BY created_at DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $app = mysqli_fetch_assoc($result);
    $stats['application_status'] = $app ? $app['status'] : 'NONE';
    
    // Active allocation
    $sql = "SELECT COUNT(*) as count FROM allocations 
            WHERE student_user_id = $studentUserId AND status = 'ACTIVE'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $stats['has_allocation'] = $row['count'] > 0;
    
    // Pending invoices
    $sql = "SELECT COUNT(*) as count, COALESCE(SUM(fp.fee_amount), 0) as total_due
            FROM student_invoices i 
            JOIN fee_periods fp ON i.fee_period_id = fp.id 
            WHERE i.student_user_id = $studentUserId AND i.status IN ('PENDING', 'PARTIAL')";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $stats['pending_invoices'] = $row['count'];
    $stats['total_due'] = $row['total_due'];
    
    // Open complaints
    $sql = "SELECT COUNT(*) as count FROM complaints 
            WHERE student_user_id = $studentUserId AND status IN ('OPEN', 'IN_PROGRESS')";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $stats['open_complaints'] = $row['count'];
    
    mysqli_close($conn);
    return $stats;
}
