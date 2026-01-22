<?php
// app/Models/AuthModel.php

require_once __DIR__ . '/Database.php';

/**
 * Attempt to login a user
 * @param string $email
 * @param string $password
 * @return array ['success' => bool, 'message' => string, 'user' => array|null]
 */
function loginUser($email, $password) {
    $conn = dbConnect();
    $response = ['success' => false, 'message' => '', 'user' => null];

    try {
        // 1. Fetch User by Email
        // We select id, password_hash, status, and name
        $sql = "SELECT id, name, email, password_hash, status, phone FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            
            // 2. Check Password
            if (password_verify($password, $row['password_hash'])) {
                
                // 3. Check Account Status
                if ($row['status'] !== 'ACTIVE') {
                    $response['message'] = "Account is " . strtolower($row['status']) . ". Please contact admin.";
                    return $response;
                }

                // 4. Fetch User Roles (Because you have a Many-to-Many user_roles table)
                $roleSql = "SELECT r.name, r.id FROM roles r 
                            JOIN user_roles ur ON r.id = ur.role_id 
                            WHERE ur.user_id = ?";
                $roleStmt = mysqli_prepare($conn, $roleSql);
                mysqli_stmt_bind_param($roleStmt, "i", $row['id']);
                mysqli_stmt_execute($roleStmt);
                $roleResult = mysqli_stmt_get_result($roleStmt);
                
                $roles = [];
                while ($roleRow = mysqli_fetch_assoc($roleResult)) {
                    $roles[] = $roleRow['name']; // e.g. ['STUDENT'] or ['MANAGER']
                }

                // 5. Update Last Login Time
                $updateSql = "UPDATE users SET last_login_at = NOW() WHERE id = ?";
                $updateStmt = mysqli_prepare($conn, $updateSql);
                mysqli_stmt_bind_param($updateStmt, "i", $row['id']);
                mysqli_stmt_execute($updateStmt);

                // 6. Success: Prepare Return Data (Exclude password hash)
                unset($row['password_hash']); // Security: Don't send hash back
                $row['roles'] = $roles; // Attach roles to user data

                $response['success'] = true;
                $response['user'] = $row;
                $response['message'] = "Login successful";

            } else {
                $response['message'] = "Invalid password.";
            }
        } else {
            $response['message'] = "No account found with this email.";
        }

    } catch (Exception $e) {
        $response['message'] = "System Error: " . $e->getMessage();
    } finally {
        mysqli_close($conn);
    }

    return $response;
}

/**
 * Logout User (Helper to keep logic in one place)
 */
function logoutUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_unset();
    session_destroy();
}

/**
 * Create a remember me token for a user
 * @param int $userId
 * @return string|false The token or false on failure
 */
function createRememberToken($userId) {
    $conn = dbConnect();
    
    try {
        // Generate a secure random token
        $selector = bin2hex(random_bytes(16)); // Used to look up the token
        $validator = bin2hex(random_bytes(32)); // Used to verify the token
        $token = $selector . ':' . $validator;
        $hashedValidator = hash('sha256', $validator);
        $expiry = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60)); // 30 days
        
        // Delete any existing tokens for this user (optional: allow multiple devices)
        $deleteSql = "DELETE FROM remember_tokens WHERE user_id = ?";
        $deleteStmt = mysqli_prepare($conn, $deleteSql);
        mysqli_stmt_bind_param($deleteStmt, "i", $userId);
        mysqli_stmt_execute($deleteStmt);
        
        // Insert new token
        $sql = "INSERT INTO remember_tokens (user_id, selector, hashed_validator, expires_at) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isss", $userId, $selector, $hashedValidator, $expiry);
        
        if (mysqli_stmt_execute($stmt)) {
            return $token;
        }
        
        return false;
    } catch (Exception $e) {
        error_log("Remember token creation failed: " . $e->getMessage());
        return false;
    } finally {
        mysqli_close($conn);
    }
}

/**
 * Validate a remember me token and return user data if valid
 * @param string $token
 * @return array|false User data or false if invalid
 */
function validateRememberToken($token) {
    $conn = dbConnect();
    
    try {
        // Split token into selector and validator
        $parts = explode(':', $token);
        if (count($parts) !== 2) {
            return false;
        }
        
        list($selector, $validator) = $parts;
        $hashedValidator = hash('sha256', $validator);
        
        // Look up token by selector
        $sql = "SELECT rt.user_id, rt.hashed_validator, rt.expires_at, 
                       u.id, u.name, u.email, u.status,
                       (SELECT r.name FROM roles r JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = u.id LIMIT 1) as role
                FROM remember_tokens rt
                JOIN users u ON rt.user_id = u.id
                WHERE rt.selector = ? AND rt.expires_at > NOW()";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $selector);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Verify the validator using timing-safe comparison
            if (hash_equals($row['hashed_validator'], $hashedValidator)) {
                // Check if user is still active
                if ($row['status'] !== 'ACTIVE') {
                    // User is no longer active, delete the token
                    deleteRememberToken($selector);
                    return false;
                }
                
                return [
                    'id' => $row['user_id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'role' => $row['role']
                ];
            }
        }
        
        return false;
    } catch (Exception $e) {
        error_log("Remember token validation failed: " . $e->getMessage());
        return false;
    } finally {
        mysqli_close($conn);
    }
}

/**
 * Delete a remember me token
 * @param string $selector
 */
function deleteRememberToken($selector) {
    $conn = dbConnect();
    
    try {
        $sql = "DELETE FROM remember_tokens WHERE selector = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $selector);
        mysqli_stmt_execute($stmt);
    } catch (Exception $e) {
        error_log("Remember token deletion failed: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
}

/**
 * Delete all remember me tokens for a user (call on logout or password change)
 * @param int $userId
 */
function deleteAllRememberTokens($userId) {
    $conn = dbConnect();
    
    try {
        $sql = "DELETE FROM remember_tokens WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
    } catch (Exception $e) {
        error_log("Remember tokens deletion failed: " . $e->getMessage());
    } finally {
        mysqli_close($conn);
    }
}

/**
 * Verify student identity for password reset
 * @param string $email
 * @param string $student_id
 * @param string $dob (format: YYYY-MM-DD)
 * @return array ['success' => bool, 'message' => string, 'user_id' => int|null]
 */
function verifyStudentIdentity($email, $student_id, $dob) {
    $conn = dbConnect();
    $response = ['success' => false, 'message' => '', 'user_id' => null];
    
    try {
        // Join users and student_profiles to verify all three fields
        $sql = "SELECT u.id, u.name, u.status 
                FROM users u 
                INNER JOIN student_profiles sp ON u.id = sp.user_id 
                WHERE u.email = ? AND sp.student_id = ? AND sp.dob = ?";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $email, $student_id, $dob);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // Check if account is active
            if ($row['status'] !== 'ACTIVE') {
                $response['message'] = "Account is " . strtolower($row['status']) . ". Please contact admin.";
                return $response;
            }
            
            $response['success'] = true;
            $response['user_id'] = $row['id'];
            $response['message'] = 'Identity verified successfully.';
        } else {
            $response['message'] = 'No matching account found. Please check your email, student ID, and date of birth.';
        }
        
    } catch (Exception $e) {
        $response['message'] = "System Error: " . $e->getMessage();
    } finally {
        mysqli_close($conn);
    }
    
    return $response;
}

/**
 * Reset user password
 * @param int $userId
 * @param string $newPassword
 * @return array ['success' => bool, 'message' => string]
 */
function resetUserPassword($userId, $newPassword) {
    $conn = dbConnect();
    $response = ['success' => false, 'message' => ''];
    
    try {
        // Hash the new password
        $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update password
        $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $password_hash, $userId);
        
        if (mysqli_stmt_execute($stmt)) {
            // Delete all remember tokens for this user (security measure)
            deleteAllRememberTokens($userId);
            
            $response['success'] = true;
            $response['message'] = 'Password reset successfully.';
        } else {
            $response['message'] = 'Failed to update password. Please try again.';
        }
        
    } catch (Exception $e) {
        $response['message'] = "System Error: " . $e->getMessage();
    } finally {
        mysqli_close($conn);
    }
    
    return $response;
}
?>