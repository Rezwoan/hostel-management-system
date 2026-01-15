<?php
// app/Models/student_functions.php

// Ensure this path matches your file structure (Same folder)
require_once __DIR__ . '/DB_Connect.php';

/**
 * Create a new student account (User + Role + Profile)
 * @param string $name
 * @param string $email
 * @param string $password
 * @param string $phone
 * @param string $studentId
 * @param string $department
 * @param string $sessionYear
 * @param string $dob      (New Argument)
 * @param string $address  (New Argument)
 * @return bool|string Returns TRUE on success, Error Message string on failure
 */
function createStudentAccount($name, $email, $password, $phone, $studentId, $department, $sessionYear, $dob, $address) {
    
    // 1. Setup Connection
    $conn = dbConnect();
    
    // Hash password immediately
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // 2. Start Transaction
    mysqli_begin_transaction($conn);

    try {
        // --- A. Insert into USERS table ---
        $sqlUser = "INSERT INTO users (name, email, phone, password_hash, status) VALUES (?, ?, ?, ?, 'ACTIVE')";
        $stmtUser = mysqli_prepare($conn, $sqlUser);
        mysqli_stmt_bind_param($stmtUser, "ssss", $name, $email, $phone, $passwordHash);
        
        if (!mysqli_stmt_execute($stmtUser)) {
            // MySQL Error 1062 = Duplicate Entry (Email already exists)
            if ($conn->errno === 1062) throw new Exception("That email address is already registered.");
            throw new Exception("System Error (User): " . $conn->error);
        }
        
        $newUserId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmtUser);

        // --- B. Assign Role (ID 3 = STUDENT) ---
        $roleId = 3; 
        $sqlRole = "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)";
        $stmtRole = mysqli_prepare($conn, $sqlRole);
        mysqli_stmt_bind_param($stmtRole, "ii", $newUserId, $roleId);
        
        if (!mysqli_stmt_execute($stmtRole)) {
            throw new Exception("System Error (Role): " . $conn->error);
        }
        mysqli_stmt_close($stmtRole);

        // --- C. Create Student Profile (Updated with DOB and Address) ---
        $sqlProfile = "INSERT INTO student_profiles (user_id, student_id, department, session_year, dob, address) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtProfile = mysqli_prepare($conn, $sqlProfile);
        
        // Bind Params: i (int) + s (string) * 5
        mysqli_stmt_bind_param($stmtProfile, "isssss", $newUserId, $studentId, $department, $sessionYear, $dob, $address);
        
        if (!mysqli_stmt_execute($stmtProfile)) {
            if ($conn->errno === 1062) throw new Exception("That Student ID is already registered.");
            throw new Exception("System Error (Profile): " . $conn->error);
        }
        mysqli_stmt_close($stmtProfile);

        // 3. Commit Transaction
        mysqli_commit($conn);
        mysqli_close($conn);
        return true;

    } catch (Exception $e) {
        // 4. Rollback on any error
        mysqli_rollback($conn);
        mysqli_close($conn);
        return $e->getMessage();
    }
}
?>