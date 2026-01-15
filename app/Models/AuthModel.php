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
?>