<?php
// app/Models/StudentModel.php

require_once __DIR__ . '/Database.php';

/**
 * Create a new student account
 * Inserts data into: users, user_roles, student_profiles tables
 */
function createStudentAccount($name, $email, $password, $phone, $studentId, $department, $sessionYear, $dob, $address) {
    
    // Connect to database
    $conn = dbConnect();
    
    // Hash the password for security
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Start transaction (all queries succeed or all fail)
    mysqli_begin_transaction($conn);

    try {
        // Step 1: Insert into USERS table
        $sqlUser = "INSERT INTO users (name, email, phone, password_hash, status) 
                    VALUES ('$name', '$email', '$phone', '$passwordHash', 'ACTIVE')";
        
        if (!mysqli_query($conn, $sqlUser)) {
            if ($conn->errno === 1062) {
                throw new Exception("That email address is already registered.");
            }
            throw new Exception("Error creating user: " . $conn->error);
        }
        
        // Get the ID of the newly created user
        $newUserId = mysqli_insert_id($conn);

        // Step 2: Get the STUDENT role ID and assign it
        $roleResult = mysqli_query($conn, "SELECT id FROM roles WHERE name = 'STUDENT' LIMIT 1");
        if (!$roleResult || mysqli_num_rows($roleResult) === 0) {
            throw new Exception("STUDENT role not found in database");
        }
        $studentRoleId = mysqli_fetch_assoc($roleResult)['id'];
        
        $sqlRole = "INSERT INTO user_roles (user_id, role_id) VALUES ($newUserId, $studentRoleId)";
        
        if (!mysqli_query($conn, $sqlRole)) {
            throw new Exception("Error assigning role: " . $conn->error);
        }

        // Step 3: Create student profile
        $sqlProfile = "INSERT INTO student_profiles (user_id, student_id, department, session_year, dob, address) 
                       VALUES ($newUserId, '$studentId', '$department', '$sessionYear', '$dob', '$address')";
        
        if (!mysqli_query($conn, $sqlProfile)) {
            if ($conn->errno === 1062) {
                throw new Exception("That Student ID is already registered.");
            }
            throw new Exception("Error creating profile: " . $conn->error);
        }

        // All queries successful - save changes
        mysqli_commit($conn);
        mysqli_close($conn);
        return $newUserId; // Return the new user ID for tracking

    } catch (Exception $e) {
        // Something went wrong - undo all changes
        mysqli_rollback($conn);
        mysqli_close($conn);
        return $e->getMessage();
    }
}