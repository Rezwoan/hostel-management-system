<?php
// app/Models/AdminModel.php

require_once __DIR__ . '/Database.php';

// ============================================================
// AUDIT LOG HELPER
// ============================================================

function createAuditLog($actorUserId, $action, $entityType, $entityId, $metaJson = null) {
    $conn = dbConnect();
    $entityIdSql = $entityId ? $entityId : "NULL";
    $metaJsonSql = $metaJson ? "'" . $metaJson . "'" : "NULL";
    $sql = "INSERT INTO audit_logs (actor_user_id, action, entity_type, entity_id, meta_json) 
            VALUES ($actorUserId, '$action', '$entityType', $entityIdSql, $metaJsonSql)";
    mysqli_query($conn, $sql);
    mysqli_close($conn);
}

// ============================================================
// USER MANAGEMENT
// ============================================================

function getAllUsers() {
    $conn = dbConnect();
    $sql = "SELECT u.*, GROUP_CONCAT(r.name) as roles 
            FROM users u 
            LEFT JOIN user_roles ur ON u.id = ur.user_id 
            LEFT JOIN roles r ON ur.role_id = r.id 
            GROUP BY u.id 
            ORDER BY u.id DESC";
    $result = mysqli_query($conn, $sql);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $users;
}

function getUserById($id) {
    $conn = dbConnect();
    $sql = "SELECT u.*, GROUP_CONCAT(r.name) as roles, GROUP_CONCAT(r.id) as role_ids,
                   sp.student_id, sp.department, sp.session_year, sp.dob, sp.address
            FROM users u 
            LEFT JOIN user_roles ur ON u.id = ur.user_id 
            LEFT JOIN roles r ON ur.role_id = r.id 
            LEFT JOIN student_profiles sp ON u.id = sp.user_id
            WHERE u.id = $id
            GROUP BY u.id";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $user;
}

function createUser($name, $email, $phone, $password, $status, $roleId, $actorUserId) {
    $conn = dbConnect();
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    mysqli_begin_transaction($conn);
    try {
        $sql = "INSERT INTO users (name, email, phone, password_hash, status) 
                VALUES ('$name', '$email', '$phone', '$passwordHash', '$status')";
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error creating user: " . $conn->error);
        }
        $userId = mysqli_insert_id($conn);
        
        $sql = "INSERT INTO user_roles (user_id, role_id) VALUES ($userId, $roleId)";
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error assigning role: " . $conn->error);
        }
        
        mysqli_commit($conn);
        mysqli_close($conn);
        
        $meta = json_encode(['email' => $email, 'role_id' => $roleId]);
        createAuditLog($actorUserId, 'CREATE', 'users', $userId, $meta);
        
        return $userId;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        mysqli_close($conn);
        return false;
    }
}

function updateUser($id, $name, $email, $phone, $status, $actorUserId) {
    $conn = dbConnect();
    
    // Get old data for audit
    $oldData = getUserById($id);
    
    $sql = "UPDATE users SET name = '$name', email = '$email', phone = '$phone', status = '$status' WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode([
            'old' => ['name' => $oldData['name'], 'email' => $oldData['email'], 'status' => $oldData['status']],
            'new' => ['name' => $name, 'email' => $email, 'status' => $status]
        ]);
        createAuditLog($actorUserId, 'UPDATE', 'users', $id, $meta);
    }
    
    return $result;
}

function updateUserPassword($id, $newPassword, $actorUserId) {
    $conn = dbConnect();
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password_hash = '$passwordHash' WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        createAuditLog($actorUserId, 'PASSWORD_CHANGE', 'users', $id, null);
    }
    
    return $result;
}

function deleteUser($id, $actorUserId) {
    $conn = dbConnect();
    
    // Get user data before deletion for audit
    $userData = getUserById($id);
    
    $sql = "DELETE FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode(['deleted_email' => $userData['email'], 'deleted_name' => $userData['name']]);
        createAuditLog($actorUserId, 'DELETE', 'users', $id, $meta);
    }
    
    return $result;
}

function changeUserRole($userId, $newRoleId, $actorUserId) {
    $conn = dbConnect();
    
    // Get old role
    $result = mysqli_query($conn, "SELECT role_id FROM user_roles WHERE user_id = $userId");
    $oldRole = mysqli_fetch_assoc($result);
    $oldRoleId = $oldRole ? $oldRole['role_id'] : null;
    
    // Delete existing roles and insert new one (ensures clean state)
    mysqli_query($conn, "DELETE FROM user_roles WHERE user_id = $userId");
    $sql = "INSERT INTO user_roles (user_id, role_id) VALUES ($userId, $newRoleId)";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode(['old_role_id' => $oldRoleId, 'new_role_id' => $newRoleId]);
        createAuditLog($actorUserId, 'ROLE_CHANGE', 'users', $userId, $meta);
    }
    
    return $result;
}

// ============================================================
// STUDENT PROFILES
// ============================================================

function getAllStudents() {
    $conn = dbConnect();
    $sql = "SELECT u.*, sp.student_id, sp.department, sp.session_year, sp.dob, sp.address 
            FROM users u 
            JOIN user_roles ur ON u.id = ur.user_id 
            JOIN roles r ON ur.role_id = r.id
            JOIN student_profiles sp ON u.id = sp.user_id 
            WHERE r.name = 'STUDENT' 
            ORDER BY u.id DESC";
    $result = mysqli_query($conn, $sql);
    $students = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $students;
}

function getStudentById($userId) {
    $conn = dbConnect();
    $sql = "SELECT u.*, sp.student_id, sp.department, sp.session_year, sp.dob, sp.address 
            FROM users u 
            JOIN student_profiles sp ON u.id = sp.user_id 
            WHERE u.id = $userId";
    $result = mysqli_query($conn, $sql);
    $student = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $student;
}

function updateStudentProfile($userId, $studentId, $department, $sessionYear, $dob, $address, $actorUserId) {
    $conn = dbConnect();
    
    // Get old data for audit
    $oldData = getStudentById($userId);
    
    $sql = "UPDATE student_profiles 
            SET student_id = '$studentId', department = '$department', session_year = '$sessionYear', dob = '$dob', address = '$address' 
            WHERE user_id = $userId";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode([
            'old' => ['student_id' => $oldData['student_id'], 'department' => $oldData['department']],
            'new' => ['student_id' => $studentId, 'department' => $department]
        ]);
        createAuditLog($actorUserId, 'UPDATE', 'student_profiles', $userId, $meta);
    }
    
    return $result;
}

// ============================================================
// HOSTEL MANAGEMENT
// ============================================================

function getAllHostels() {
    $conn = dbConnect();
    $sql = "SELECT * FROM hostels ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $hostels = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $hostels;
}

function getHostelById($id) {
    $conn = dbConnect();
    $sql = "SELECT * FROM hostels WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $hostel = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $hostel;
}

function createHostel($name, $code, $address, $status, $actorUserId) {
    $conn = dbConnect();
    $sql = "INSERT INTO hostels (name, code, address, status) VALUES ('$name', '$code', '$address', '$status')";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['code' => $code, 'name' => $name]);
        createAuditLog($actorUserId, 'CREATE', 'hostels', $insertId, $meta);
    }
    
    return $insertId;
}

function updateHostel($id, $name, $code, $address, $status, $actorUserId) {
    $conn = dbConnect();
    
    $oldData = getHostelById($id);
    
    $sql = "UPDATE hostels SET name = '$name', code = '$code', address = '$address', status = '$status' WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode([
            'old' => ['name' => $oldData['name'], 'status' => $oldData['status']],
            'new' => ['name' => $name, 'status' => $status]
        ]);
        createAuditLog($actorUserId, 'UPDATE', 'hostels', $id, $meta);
    }
    
    return $result;
}

function deleteHostel($id, $actorUserId) {
    $conn = dbConnect();
    
    $hostelData = getHostelById($id);
    
    $sql = "DELETE FROM hostels WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode(['deleted_code' => $hostelData['code'], 'deleted_name' => $hostelData['name']]);
        createAuditLog($actorUserId, 'DELETE', 'hostels', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// HOSTEL MANAGERS
// ============================================================

function getAllHostelManagers() {
    $conn = dbConnect();
    $sql = "SELECT hm.*, h.name as hostel_name, u.name as manager_name, u.email as manager_email 
            FROM hostel_managers hm 
            JOIN hostels h ON hm.hostel_id = h.id 
            JOIN users u ON hm.manager_user_id = u.id 
            ORDER BY hm.id DESC";
    $result = mysqli_query($conn, $sql);
    $managers = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $managers;
}

function assignManagerToHostel($hostelId, $managerUserId, $actorUserId) {
    $conn = dbConnect();
    $sql = "INSERT INTO hostel_managers (hostel_id, manager_user_id) VALUES ($hostelId, $managerUserId)";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['hostel_id' => $hostelId, 'manager_user_id' => $managerUserId]);
        createAuditLog($actorUserId, 'ASSIGN_MANAGER', 'hostel_managers', $insertId, $meta);
    }
    
    return $result;
}

function removeManagerFromHostel($id, $actorUserId) {
    $conn = dbConnect();
    
    // Get data before deletion
    $result = mysqli_query($conn, "SELECT * FROM hostel_managers WHERE id = $id");
    $data = mysqli_fetch_assoc($result);
    
    $sql = "DELETE FROM hostel_managers WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $data) {
        $meta = json_encode(['hostel_id' => $data['hostel_id'], 'manager_user_id' => $data['manager_user_id']]);
        createAuditLog($actorUserId, 'REMOVE_MANAGER', 'hostel_managers', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// FLOORS
// ============================================================

function getAllFloors() {
    $conn = dbConnect();
    $sql = "SELECT f.*, h.name as hostel_name 
            FROM floors f 
            JOIN hostels h ON f.hostel_id = h.id 
            ORDER BY h.name, f.floor_no";
    $result = mysqli_query($conn, $sql);
    $floors = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $floors;
}

function getFloorsByHostel($hostelId) {
    $conn = dbConnect();
    $sql = "SELECT * FROM floors WHERE hostel_id = $hostelId ORDER BY floor_no";
    $result = mysqli_query($conn, $sql);
    $floors = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $floors;
}

function getFloorById($id) {
    $conn = dbConnect();
    $sql = "SELECT * FROM floors WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $floor = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $floor;
}

function createFloor($hostelId, $floorNo, $label, $actorUserId) {
    $conn = dbConnect();
    $sql = "INSERT INTO floors (hostel_id, floor_no, label) VALUES ($hostelId, $floorNo, '$label')";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['hostel_id' => $hostelId, 'floor_no' => $floorNo, 'label' => $label]);
        createAuditLog($actorUserId, 'CREATE', 'floors', $insertId, $meta);
    }
    
    return $insertId;
}

function updateFloor($id, $floorNo, $label, $actorUserId) {
    $conn = dbConnect();
    
    $oldData = getFloorById($id);
    
    $sql = "UPDATE floors SET floor_no = $floorNo, label = '$label' WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode([
            'old' => ['floor_no' => $oldData['floor_no'], 'label' => $oldData['label']],
            'new' => ['floor_no' => $floorNo, 'label' => $label]
        ]);
        createAuditLog($actorUserId, 'UPDATE', 'floors', $id, $meta);
    }
    
    return $result;
}

function deleteFloor($id, $actorUserId) {
    $conn = dbConnect();
    
    $floorData = getFloorById($id);
    
    $sql = "DELETE FROM floors WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $floorData) {
        $meta = json_encode(['hostel_id' => $floorData['hostel_id'], 'floor_no' => $floorData['floor_no']]);
        createAuditLog($actorUserId, 'DELETE', 'floors', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// ROOM TYPES
// ============================================================

function getAllRoomTypes() {
    $conn = dbConnect();
    $sql = "SELECT * FROM room_types ORDER BY name";
    $result = mysqli_query($conn, $sql);
    $roomTypes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $roomTypes;
}

function getRoomTypeById($id) {
    $conn = dbConnect();
    $sql = "SELECT * FROM room_types WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $roomType = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $roomType;
}

function createRoomType($name, $defaultCapacity, $defaultFee, $description, $actorUserId) {
    $conn = dbConnect();
    $sql = "INSERT INTO room_types (name, default_capacity, default_fee, description) 
            VALUES ('$name', $defaultCapacity, $defaultFee, '$description')";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['name' => $name, 'default_fee' => $defaultFee]);
        createAuditLog($actorUserId, 'CREATE', 'room_types', $insertId, $meta);
    }
    
    return $insertId;
}

function updateRoomType($id, $name, $defaultCapacity, $defaultFee, $description, $actorUserId) {
    $conn = dbConnect();
    
    $oldData = getRoomTypeById($id);
    
    $sql = "UPDATE room_types 
            SET name = '$name', default_capacity = $defaultCapacity, default_fee = $defaultFee, description = '$description' 
            WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode([
            'old' => ['name' => $oldData['name'], 'default_fee' => $oldData['default_fee']],
            'new' => ['name' => $name, 'default_fee' => $defaultFee]
        ]);
        createAuditLog($actorUserId, 'UPDATE', 'room_types', $id, $meta);
    }
    
    return $result;
}

function deleteRoomType($id, $actorUserId) {
    $conn = dbConnect();
    
    $data = getRoomTypeById($id);
    
    $sql = "DELETE FROM room_types WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $data) {
        $meta = json_encode(['deleted_name' => $data['name']]);
        createAuditLog($actorUserId, 'DELETE', 'room_types', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// ROOMS
// ============================================================

function getAllRooms() {
    $conn = dbConnect();
    $sql = "SELECT r.*, f.floor_no, f.label as floor_label, h.name as hostel_name, rt.name as room_type_name 
            FROM rooms r 
            JOIN floors f ON r.floor_id = f.id 
            JOIN hostels h ON f.hostel_id = h.id 
            JOIN room_types rt ON r.room_type_id = rt.id 
            ORDER BY h.name, f.floor_no, r.room_no";
    $result = mysqli_query($conn, $sql);
    $rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $rooms;
}

function getRoomsByHostel($hostelId) {
    $conn = dbConnect();
    $sql = "SELECT r.*, f.floor_no, rt.name as room_type_name 
            FROM rooms r 
            JOIN floors f ON r.floor_id = f.id 
            JOIN room_types rt ON r.room_type_id = rt.id 
            WHERE f.hostel_id = $hostelId 
            ORDER BY f.floor_no, r.room_no";
    $result = mysqli_query($conn, $sql);
    $rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $rooms;
}

function getRoomById($id) {
    $conn = dbConnect();
    $sql = "SELECT r.*, f.floor_no, f.hostel_id, rt.name as room_type_name 
            FROM rooms r 
            JOIN floors f ON r.floor_id = f.id 
            JOIN room_types rt ON r.room_type_id = rt.id 
            WHERE r.id = $id";
    $result = mysqli_query($conn, $sql);
    $room = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $room;
}

function createRoom($floorId, $roomTypeId, $roomNo, $capacity, $status, $actorUserId) {
    $conn = dbConnect();
    $sql = "INSERT INTO rooms (floor_id, room_type_id, room_no, capacity, status) 
            VALUES ($floorId, $roomTypeId, '$roomNo', $capacity, '$status')";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['floor_id' => $floorId, 'room_no' => $roomNo, 'capacity' => $capacity]);
        createAuditLog($actorUserId, 'CREATE', 'rooms', $insertId, $meta);
    }
    
    return $insertId;
}

function updateRoom($id, $floorId, $roomTypeId, $roomNo, $capacity, $status, $actorUserId) {
    $conn = dbConnect();
    
    $oldData = getRoomById($id);
    
    $sql = "UPDATE rooms 
            SET floor_id = $floorId, room_type_id = $roomTypeId, room_no = '$roomNo', capacity = $capacity, status = '$status' 
            WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode([
            'old' => ['room_no' => $oldData['room_no'], 'status' => $oldData['status']],
            'new' => ['room_no' => $roomNo, 'status' => $status]
        ]);
        createAuditLog($actorUserId, 'UPDATE', 'rooms', $id, $meta);
    }
    
    return $result;
}

function deleteRoom($id, $actorUserId) {
    $conn = dbConnect();
    
    $data = getRoomById($id);
    
    $sql = "DELETE FROM rooms WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $data) {
        $meta = json_encode(['room_no' => $data['room_no'], 'floor_id' => $data['floor_id']]);
        createAuditLog($actorUserId, 'DELETE', 'rooms', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// SEATS
// ============================================================

function getAllSeats() {
    $conn = dbConnect();
    $sql = "SELECT s.*, r.room_no, f.floor_no, h.name as hostel_name 
            FROM seats s 
            JOIN rooms r ON s.room_id = r.id 
            JOIN floors f ON r.floor_id = f.id 
            JOIN hostels h ON f.hostel_id = h.id 
            ORDER BY h.name, f.floor_no, r.room_no, s.seat_label";
    $result = mysqli_query($conn, $sql);
    $seats = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $seats;
}

function getSeatsByRoom($roomId) {
    $conn = dbConnect();
    $sql = "SELECT * FROM seats WHERE room_id = $roomId ORDER BY seat_label";
    $result = mysqli_query($conn, $sql);
    $seats = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $seats;
}

function getSeatById($id) {
    $conn = dbConnect();
    $sql = "SELECT * FROM seats WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $seat = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $seat;
}

function createSeat($roomId, $seatLabel, $status, $actorUserId) {
    $conn = dbConnect();
    $sql = "INSERT INTO seats (room_id, seat_label, status) VALUES ($roomId, '$seatLabel', '$status')";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['room_id' => $roomId, 'seat_label' => $seatLabel]);
        createAuditLog($actorUserId, 'CREATE', 'seats', $insertId, $meta);
    }
    
    return $insertId;
}

function updateSeat($id, $seatLabel, $status, $actorUserId) {
    $conn = dbConnect();
    
    $oldData = getSeatById($id);
    
    $sql = "UPDATE seats SET seat_label = '$seatLabel', status = '$status' WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode([
            'old' => ['seat_label' => $oldData['seat_label'], 'status' => $oldData['status']],
            'new' => ['seat_label' => $seatLabel, 'status' => $status]
        ]);
        createAuditLog($actorUserId, 'UPDATE', 'seats', $id, $meta);
    }
    
    return $result;
}

function deleteSeat($id, $actorUserId) {
    $conn = dbConnect();
    
    $data = getSeatById($id);
    
    $sql = "DELETE FROM seats WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $data) {
        $meta = json_encode(['room_id' => $data['room_id'], 'seat_label' => $data['seat_label']]);
        createAuditLog($actorUserId, 'DELETE', 'seats', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// ROOM APPLICATIONS
// ============================================================

function getAllRoomApplications() {
    $conn = dbConnect();
    $sql = "SELECT ra.*, u.name as student_name, u.email as student_email, h.name as hostel_name, rt.name as room_type_name, 
                   m.name as reviewer_name 
            FROM room_applications ra 
            JOIN users u ON ra.student_user_id = u.id 
            JOIN hostels h ON ra.hostel_id = h.id 
            JOIN room_types rt ON ra.preferred_room_type_id = rt.id 
            LEFT JOIN users m ON ra.reviewed_by_manager_user_id = m.id 
            ORDER BY ra.id DESC";
    $result = mysqli_query($conn, $sql);
    $applications = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $applications;
}

function getRoomApplicationById($id) {
    $conn = dbConnect();
    $sql = "SELECT ra.*, u.name as student_name, h.name as hostel_name, rt.name as room_type_name 
            FROM room_applications ra 
            JOIN users u ON ra.student_user_id = u.id 
            JOIN hostels h ON ra.hostel_id = h.id 
            JOIN room_types rt ON ra.preferred_room_type_id = rt.id 
            WHERE ra.id = $id";
    $result = mysqli_query($conn, $sql);
    $application = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $application;
}

function updateRoomApplicationStatus($id, $status, $rejectReason, $reviewedByUserId) {
    $conn = dbConnect();
    
    $oldData = getRoomApplicationById($id);
    
    $rejectReasonSql = $rejectReason ? "'$rejectReason'" : "NULL";
    $sql = "UPDATE room_applications 
            SET status = '$status', reject_reason = $rejectReasonSql, reviewed_at = NOW(), reviewed_by_manager_user_id = $reviewedByUserId 
            WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode([
            'old_status' => $oldData['status'],
            'new_status' => $status,
            'student_user_id' => $oldData['student_user_id'],
            'reject_reason' => $rejectReason
        ]);
        createAuditLog($reviewedByUserId, $status, 'room_applications', $id, $meta);
    }
    
    return $result;
}

function deleteRoomApplication($id, $actorUserId) {
    $conn = dbConnect();
    
    $data = getRoomApplicationById($id);
    
    $sql = "DELETE FROM room_applications WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $data) {
        $meta = json_encode(['student_user_id' => $data['student_user_id'], 'status' => $data['status']]);
        createAuditLog($actorUserId, 'DELETE', 'room_applications', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// ALLOCATIONS
// ============================================================

function getAllAllocations() {
    $conn = dbConnect();
    $sql = "SELECT a.*, u.name as student_name, u.email as student_email, s.seat_label, r.room_no, h.name as hostel_name, 
                   m.name as created_by_name 
            FROM allocations a 
            JOIN users u ON a.student_user_id = u.id 
            JOIN seats s ON a.seat_id = s.id 
            JOIN rooms r ON s.room_id = r.id 
            JOIN hostels h ON a.hostel_id = h.id 
            JOIN users m ON a.created_by_manager_user_id = m.id 
            ORDER BY a.id DESC";
    $result = mysqli_query($conn, $sql);
    $allocations = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $allocations;
}

function getActiveAllocations() {
    $conn = dbConnect();
    $sql = "SELECT a.*, u.name as student_name, s.seat_label, r.room_no, h.name as hostel_name 
            FROM allocations a 
            JOIN users u ON a.student_user_id = u.id 
            JOIN seats s ON a.seat_id = s.id 
            JOIN rooms r ON s.room_id = r.id 
            JOIN hostels h ON a.hostel_id = h.id 
            WHERE a.status = 'ACTIVE' 
            ORDER BY a.id DESC";
    $result = mysqli_query($conn, $sql);
    $allocations = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $allocations;
}

function getAllocationById($id) {
    $conn = dbConnect();
    $sql = "SELECT * FROM allocations WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $allocation = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $allocation;
}

function createAllocation($studentUserId, $seatId, $hostelId, $startDate, $createdByManagerUserId) {
    $conn = dbConnect();
    $sql = "INSERT INTO allocations (student_user_id, seat_id, hostel_id, start_date, created_by_manager_user_id) 
            VALUES ($studentUserId, $seatId, $hostelId, '$startDate', $createdByManagerUserId)";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['student_user_id' => $studentUserId, 'seat_id' => $seatId, 'hostel_id' => $hostelId]);
        createAuditLog($createdByManagerUserId, 'ASSIGN', 'allocations', $insertId, $meta);
    }
    
    return $insertId;
}

function endAllocation($id, $actorUserId) {
    $conn = dbConnect();
    
    $data = getAllocationById($id);
    
    $sql = "UPDATE allocations SET status = 'ENDED', end_date = NOW() WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode(['student_user_id' => $data['student_user_id'], 'seat_id' => $data['seat_id']]);
        createAuditLog($actorUserId, 'END_ALLOCATION', 'allocations', $id, $meta);
    }
    
    return $result;
}

function deleteAllocation($id, $actorUserId) {
    $conn = dbConnect();
    
    $data = getAllocationById($id);
    
    $sql = "DELETE FROM allocations WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $data) {
        $meta = json_encode(['student_user_id' => $data['student_user_id'], 'seat_id' => $data['seat_id']]);
        createAuditLog($actorUserId, 'DELETE', 'allocations', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// FEE PERIODS
// ============================================================

function getAllFeePeriods() {
    $conn = dbConnect();
    $sql = "SELECT * FROM fee_periods ORDER BY start_date DESC";
    $result = mysqli_query($conn, $sql);
    $periods = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $periods;
}

function getFeePeriodById($id) {
    $conn = dbConnect();
    $sql = "SELECT * FROM fee_periods WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $period = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $period;
}

function createFeePeriod($name, $startDate, $endDate, $actorUserId) {
    $conn = dbConnect();
    $sql = "INSERT INTO fee_periods (name, start_date, end_date) VALUES ('$name', '$startDate', '$endDate')";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['name' => $name, 'start_date' => $startDate, 'end_date' => $endDate]);
        createAuditLog($actorUserId, 'CREATE', 'fee_periods', $insertId, $meta);
    }
    
    return $insertId;
}

function updateFeePeriod($id, $name, $startDate, $endDate, $actorUserId) {
    $conn = dbConnect();
    
    $oldData = getFeePeriodById($id);
    
    $sql = "UPDATE fee_periods SET name = '$name', start_date = '$startDate', end_date = '$endDate' WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode([
            'old' => ['name' => $oldData['name']],
            'new' => ['name' => $name]
        ]);
        createAuditLog($actorUserId, 'UPDATE', 'fee_periods', $id, $meta);
    }
    
    return $result;
}

function deleteFeePeriod($id, $actorUserId) {
    $conn = dbConnect();
    
    $data = getFeePeriodById($id);
    
    $sql = "DELETE FROM fee_periods WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $data) {
        $meta = json_encode(['deleted_name' => $data['name']]);
        createAuditLog($actorUserId, 'DELETE', 'fee_periods', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// STUDENT INVOICES
// ============================================================

function getAllInvoices() {
    $conn = dbConnect();
    $sql = "SELECT si.*, u.name as student_name, u.email as student_email, 
                   sp.student_id as student_id_number, h.name as hostel_name, fp.name as period_name 
            FROM student_invoices si 
            JOIN users u ON si.student_user_id = u.id 
            LEFT JOIN student_profiles sp ON u.id = sp.user_id
            JOIN hostels h ON si.hostel_id = h.id 
            JOIN fee_periods fp ON si.period_id = fp.id 
            ORDER BY si.id DESC";
    $result = mysqli_query($conn, $sql);
    $invoices = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $invoices;
}

function getInvoiceById($id) {
    $conn = dbConnect();
    $sql = "SELECT si.*, u.name as student_name, u.email as student_email,
                   sp.student_id as student_id_number, h.name as hostel_name, fp.name as period_name 
            FROM student_invoices si 
            JOIN users u ON si.student_user_id = u.id 
            LEFT JOIN student_profiles sp ON u.id = sp.user_id
            JOIN hostels h ON si.hostel_id = h.id 
            JOIN fee_periods fp ON si.period_id = fp.id 
            WHERE si.id = $id";
    $result = mysqli_query($conn, $sql);
    $invoice = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $invoice;
}

function createInvoice($studentUserId, $hostelId, $periodId, $amountDue, $actorUserId) {
    $conn = dbConnect();
    $sql = "INSERT INTO student_invoices (student_user_id, hostel_id, period_id, amount_due) 
            VALUES ($studentUserId, $hostelId, $periodId, $amountDue)";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['student_user_id' => $studentUserId, 'amount_due' => $amountDue, 'period_id' => $periodId]);
        createAuditLog($actorUserId, 'CREATE', 'student_invoices', $insertId, $meta);
    }
    
    return $insertId;
}

function updateInvoiceStatus($id, $status, $actorUserId) {
    $conn = dbConnect();
    
    $oldData = getInvoiceById($id);
    
    $sql = "UPDATE student_invoices SET status = '$status' WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode([
            'old_status' => $oldData['status'],
            'new_status' => $status,
            'student_user_id' => $oldData['student_user_id']
        ]);
        createAuditLog($actorUserId, 'UPDATE_STATUS', 'student_invoices', $id, $meta);
    }
    
    return $result;
}

function deleteInvoice($id, $actorUserId) {
    $conn = dbConnect();
    
    $data = getInvoiceById($id);
    
    $sql = "DELETE FROM student_invoices WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $data) {
        $meta = json_encode(['student_user_id' => $data['student_user_id'], 'amount_due' => $data['amount_due']]);
        createAuditLog($actorUserId, 'DELETE', 'student_invoices', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// PAYMENTS
// ============================================================

function getAllPayments() {
    $conn = dbConnect();
    $sql = "SELECT p.*, si.amount_due, u.name as student_name, r.name as recorder_name 
            FROM payments p 
            JOIN student_invoices si ON p.invoice_id = si.id 
            JOIN users u ON si.student_user_id = u.id 
            JOIN users r ON p.recorded_by_user_id = r.id 
            ORDER BY p.id DESC";
    $result = mysqli_query($conn, $sql);
    $payments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $payments;
}

function getPaymentsByInvoice($invoiceId) {
    $conn = dbConnect();
    $sql = "SELECT p.*, r.name as recorder_name 
            FROM payments p 
            JOIN users r ON p.recorded_by_user_id = r.id 
            WHERE p.invoice_id = $invoiceId 
            ORDER BY p.paid_at DESC";
    $result = mysqli_query($conn, $sql);
    $payments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $payments;
}

function getPaymentById($id) {
    $conn = dbConnect();
    $sql = "SELECT p.*, si.student_user_id FROM payments p JOIN student_invoices si ON p.invoice_id = si.id WHERE p.id = $id";
    $result = mysqli_query($conn, $sql);
    $payment = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $payment;
}

function recordPayment($invoiceId, $amountPaid, $method, $referenceNo, $recordedByUserId) {
    $conn = dbConnect();
    $refSql = $referenceNo ? "'$referenceNo'" : "NULL";
    $sql = "INSERT INTO payments (invoice_id, amount_paid, method, reference_no, recorded_by_user_id) 
            VALUES ($invoiceId, $amountPaid, '$method', $refSql, $recordedByUserId)";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['invoice_id' => $invoiceId, 'amount_paid' => $amountPaid, 'method' => $method, 'reference_no' => $referenceNo]);
        createAuditLog($recordedByUserId, 'RECORD_PAYMENT', 'payments', $insertId, $meta);
    }
    
    return $insertId;
}

function deletePayment($id, $actorUserId) {
    $conn = dbConnect();
    
    $data = getPaymentById($id);
    
    $sql = "DELETE FROM payments WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $data) {
        $meta = json_encode(['invoice_id' => $data['invoice_id'], 'amount_paid' => $data['amount_paid']]);
        createAuditLog($actorUserId, 'DELETE', 'payments', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// COMPLAINT CATEGORIES
// ============================================================

function getAllComplaintCategories() {
    $conn = dbConnect();
    $sql = "SELECT * FROM complaint_categories ORDER BY name";
    $result = mysqli_query($conn, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $categories;
}

function getComplaintCategoryById($id) {
    $conn = dbConnect();
    $sql = "SELECT * FROM complaint_categories WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $category = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $category;
}

function createComplaintCategory($name, $actorUserId) {
    $conn = dbConnect();
    $sql = "INSERT INTO complaint_categories (name) VALUES ('$name')";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['name' => $name]);
        createAuditLog($actorUserId, 'CREATE', 'complaint_categories', $insertId, $meta);
    }
    
    return $insertId;
}

function updateComplaintCategory($id, $name, $actorUserId) {
    $conn = dbConnect();
    
    $oldData = getComplaintCategoryById($id);
    
    $sql = "UPDATE complaint_categories SET name = '$name' WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode(['old_name' => $oldData['name'], 'new_name' => $name]);
        createAuditLog($actorUserId, 'UPDATE', 'complaint_categories', $id, $meta);
    }
    
    return $result;
}

function deleteComplaintCategory($id, $actorUserId) {
    $conn = dbConnect();
    
    $data = getComplaintCategoryById($id);
    
    $sql = "DELETE FROM complaint_categories WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $data) {
        $meta = json_encode(['deleted_name' => $data['name']]);
        createAuditLog($actorUserId, 'DELETE', 'complaint_categories', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// COMPLAINTS
// ============================================================

function getAllComplaints() {
    $conn = dbConnect();
    $sql = "SELECT c.*, u.name as student_name, u.email as student_email, h.name as hostel_name, cc.name as category_name 
            FROM complaints c 
            JOIN users u ON c.student_user_id = u.id 
            JOIN hostels h ON c.hostel_id = h.id 
            JOIN complaint_categories cc ON c.category_id = cc.id 
            ORDER BY c.id DESC";
    $result = mysqli_query($conn, $sql);
    $complaints = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $complaints;
}

function getComplaintById($id) {
    $conn = dbConnect();
    $sql = "SELECT c.*, u.name as student_name, h.name as hostel_name, cc.name as category_name 
            FROM complaints c 
            JOIN users u ON c.student_user_id = u.id 
            JOIN hostels h ON c.hostel_id = h.id 
            JOIN complaint_categories cc ON c.category_id = cc.id 
            WHERE c.id = $id";
    $result = mysqli_query($conn, $sql);
    $complaint = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $complaint;
}

function updateComplaintStatus($id, $status, $actorUserId) {
    $conn = dbConnect();
    
    $oldData = getComplaintById($id);
    
    $sql = "UPDATE complaints SET status = '$status' WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode([
            'old_status' => $oldData['status'],
            'new_status' => $status,
            'student_user_id' => $oldData['student_user_id']
        ]);
        createAuditLog($actorUserId, 'UPDATE_STATUS', 'complaints', $id, $meta);
    }
    
    return $result;
}

function deleteComplaint($id, $actorUserId) {
    $conn = dbConnect();
    
    $data = getComplaintById($id);
    
    $sql = "DELETE FROM complaints WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $data) {
        $meta = json_encode(['student_user_id' => $data['student_user_id'], 'subject' => $data['subject']]);
        createAuditLog($actorUserId, 'DELETE', 'complaints', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// COMPLAINT MESSAGES
// ============================================================

function getComplaintMessages($complaintId) {
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

function addComplaintMessage($complaintId, $senderUserId, $message) {
    $conn = dbConnect();
    $sql = "INSERT INTO complaint_messages (complaint_id, sender_user_id, message) 
            VALUES ($complaintId, $senderUserId, '$message')";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['complaint_id' => $complaintId]);
        createAuditLog($senderUserId, 'ADD_MESSAGE', 'complaint_messages', $insertId, $meta);
    }
    
    return $insertId;
}

function deleteComplaintMessage($id, $actorUserId) {
    $conn = dbConnect();
    
    $result = mysqli_query($conn, "SELECT * FROM complaint_messages WHERE id = $id");
    $data = mysqli_fetch_assoc($result);
    
    $sql = "DELETE FROM complaint_messages WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $data) {
        $meta = json_encode(['complaint_id' => $data['complaint_id']]);
        createAuditLog($actorUserId, 'DELETE', 'complaint_messages', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// NOTICES
// ============================================================

function getAllNotices() {
    $conn = dbConnect();
    $sql = "SELECT n.*, h.name as hostel_name, u.name as created_by_name 
            FROM notices n 
            LEFT JOIN hostels h ON n.hostel_id = h.id 
            JOIN users u ON n.created_by_user_id = u.id 
            ORDER BY n.id DESC";
    $result = mysqli_query($conn, $sql);
    $notices = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $notices;
}

function getNoticeById($id) {
    $conn = dbConnect();
    $sql = "SELECT n.*, h.name as hostel_name, u.name as created_by_name 
            FROM notices n 
            LEFT JOIN hostels h ON n.hostel_id = h.id 
            JOIN users u ON n.created_by_user_id = u.id 
            WHERE n.id = $id";
    $result = mysqli_query($conn, $sql);
    $notice = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $notice;
}

function createGlobalNotice($title, $body, $status, $publishAt, $expireAt, $createdByUserId) {
    $conn = dbConnect();
    $publishAtSql = $publishAt ? "'$publishAt'" : "NULL";
    $expireAtSql = $expireAt ? "'$expireAt'" : "NULL";
    $sql = "INSERT INTO notices (scope, hostel_id, title, body, status, publish_at, expire_at, created_by_user_id) 
            VALUES ('GLOBAL', NULL, '$title', '$body', '$status', $publishAtSql, $expireAtSql, $createdByUserId)";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['scope' => 'GLOBAL', 'title' => $title]);
        createAuditLog($createdByUserId, 'CREATE', 'notices', $insertId, $meta);
    }
    
    return $insertId;
}

function createHostelNotice($hostelId, $title, $body, $status, $publishAt, $expireAt, $createdByUserId) {
    $conn = dbConnect();
    $publishAtSql = $publishAt ? "'$publishAt'" : "NULL";
    $expireAtSql = $expireAt ? "'$expireAt'" : "NULL";
    $sql = "INSERT INTO notices (scope, hostel_id, title, body, status, publish_at, expire_at, created_by_user_id) 
            VALUES ('HOSTEL', $hostelId, '$title', '$body', '$status', $publishAtSql, $expireAtSql, $createdByUserId)";
    $result = mysqli_query($conn, $sql);
    $insertId = $result ? mysqli_insert_id($conn) : false;
    mysqli_close($conn);
    
    if ($insertId) {
        $meta = json_encode(['scope' => 'HOSTEL', 'hostel_id' => $hostelId, 'title' => $title]);
        createAuditLog($createdByUserId, 'CREATE', 'notices', $insertId, $meta);
    }
    
    return $insertId;
}

function updateNotice($id, $title, $body, $status, $publishAt, $expireAt, $actorUserId) {
    $conn = dbConnect();
    
    $oldData = getNoticeById($id);
    
    $publishAtSql = $publishAt ? "'$publishAt'" : "NULL";
    $expireAtSql = $expireAt ? "'$expireAt'" : "NULL";
    $sql = "UPDATE notices 
            SET title = '$title', body = '$body', status = '$status', publish_at = $publishAtSql, expire_at = $expireAtSql 
            WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result) {
        $meta = json_encode([
            'old' => ['title' => $oldData['title'], 'status' => $oldData['status']],
            'new' => ['title' => $title, 'status' => $status]
        ]);
        createAuditLog($actorUserId, 'UPDATE', 'notices', $id, $meta);
    }
    
    return $result;
}

function deleteNotice($id, $actorUserId) {
    $conn = dbConnect();
    
    $data = getNoticeById($id);
    
    $sql = "DELETE FROM notices WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    if ($result && $data) {
        $meta = json_encode(['deleted_title' => $data['title'], 'scope' => $data['scope']]);
        createAuditLog($actorUserId, 'DELETE', 'notices', $id, $meta);
    }
    
    return $result;
}

// ============================================================
// AUDIT LOGS (View Only)
// ============================================================

function getAllAuditLogs() {
    $conn = dbConnect();
    $sql = "SELECT al.*, u.name as actor_name 
            FROM audit_logs al 
            LEFT JOIN users u ON al.actor_user_id = u.id 
            ORDER BY al.id DESC";
    $result = mysqli_query($conn, $sql);
    $logs = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $logs;
}

function getAuditLogById($id) {
    $conn = dbConnect();
    $sql = "SELECT al.*, u.name as user_name, u.email as user_email 
            FROM audit_logs al 
            LEFT JOIN users u ON al.actor_user_id = u.id 
            WHERE al.id = $id";
    $result = mysqli_query($conn, $sql);
    $log = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $log;
}

function getAuditLogsByEntity($entityType, $entityId) {
    $conn = dbConnect();
    $sql = "SELECT al.*, u.name as actor_name 
            FROM audit_logs al 
            JOIN users u ON al.actor_user_id = u.id 
            WHERE al.entity_type = '$entityType' AND al.entity_id = $entityId 
            ORDER BY al.id DESC";
    $result = mysqli_query($conn, $sql);
    $logs = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $logs;
}

function getAuditLogsByUser($userId) {
    $conn = dbConnect();
    $sql = "SELECT al.*, u.name as actor_name 
            FROM audit_logs al 
            JOIN users u ON al.actor_user_id = u.id 
            WHERE al.actor_user_id = $userId 
            ORDER BY al.id DESC";
    $result = mysqli_query($conn, $sql);
    $logs = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $logs;
}

function getRecentAuditLogs($limit = 50) {
    $conn = dbConnect();
    $sql = "SELECT al.*, u.name as actor_name 
            FROM audit_logs al 
            JOIN users u ON al.actor_user_id = u.id 
            ORDER BY al.id DESC 
            LIMIT $limit";
    $result = mysqli_query($conn, $sql);
    $logs = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $logs;
}

// ============================================================
// ROLES
// ============================================================

// Get all roles
function getAllRoles() {
    $conn = dbConnect();
    $sql = "SELECT * FROM roles ORDER BY id";
    $result = mysqli_query($conn, $sql);
    $roles = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $roles;
}

// ============================================================
// DASHBOARD STATISTICS
// ============================================================

// Get dashboard stats
function getDashboardStats() {
    $conn = dbConnect();
    $stats = [];
    
    // Total users
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
    $stats['total_users'] = mysqli_fetch_assoc($result)['count'];
    
    // Total students
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE r.name = 'STUDENT'");
    $stats['total_students'] = mysqli_fetch_assoc($result)['count'];
    
    // Total hostels
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM hostels");
    $stats['total_hostels'] = mysqli_fetch_assoc($result)['count'];
    
    // Active hostels
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM hostels WHERE status = 'ACTIVE'");
    $stats['active_hostels'] = mysqli_fetch_assoc($result)['count'];
    
    // Total rooms
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM rooms");
    $stats['total_rooms'] = mysqli_fetch_assoc($result)['count'];
    
    // Total seats
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM seats");
    $stats['total_seats'] = mysqli_fetch_assoc($result)['count'];
    
    // Active allocations
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM allocations WHERE status = 'ACTIVE'");
    $stats['active_allocations'] = mysqli_fetch_assoc($result)['count'];
    
    // Pending applications
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM room_applications WHERE status = 'SUBMITTED'");
    $stats['pending_applications'] = mysqli_fetch_assoc($result)['count'];
    
    // Open complaints
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM complaints WHERE status IN ('OPEN', 'IN_PROGRESS')");
    $stats['open_complaints'] = mysqli_fetch_assoc($result)['count'];
    
    // Due invoices
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM student_invoices WHERE status IN ('DUE', 'PARTIAL')");
    $stats['due_invoices'] = mysqli_fetch_assoc($result)['count'];
    
    // Total revenue
    $result = mysqli_query($conn, "SELECT COALESCE(SUM(amount_paid), 0) as total FROM payments");
    $stats['total_revenue'] = mysqli_fetch_assoc($result)['total'];
    
    mysqli_close($conn);
    return $stats;
}

// Get available seats (not allocated)
function getAvailableSeats() {
    $conn = dbConnect();
    $sql = "SELECT s.*, r.room_no, f.floor_no, h.name as hostel_name, h.id as hostel_id 
            FROM seats s 
            JOIN rooms r ON s.room_id = r.id 
            JOIN floors f ON r.floor_id = f.id 
            JOIN hostels h ON f.hostel_id = h.id 
            WHERE s.status = 'ACTIVE' 
            AND s.id NOT IN (SELECT seat_id FROM allocations WHERE status = 'ACTIVE') 
            AND r.status = 'ACTIVE' 
            AND h.status = 'ACTIVE' 
            ORDER BY h.name, f.floor_no, r.room_no, s.seat_label";
    $result = mysqli_query($conn, $sql);
    $seats = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $seats;
}

// Search users
function searchUsers($keyword) {
    $conn = dbConnect();
    $sql = "SELECT * FROM users WHERE name LIKE '%$keyword%' OR email LIKE '%$keyword%' OR phone LIKE '%$keyword%' ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $users;
}

// Get users by role
function getUsersByRole($roleId) {
    $conn = dbConnect();
    $sql = "SELECT u.* FROM users u JOIN user_roles ur ON u.id = ur.user_id WHERE ur.role_id = $roleId ORDER BY u.id DESC";
    $result = mysqli_query($conn, $sql);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($conn);
    return $users;
}
