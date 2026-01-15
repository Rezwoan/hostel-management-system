<?php

require_once __DIR__ . '/dbConnect.php';


function user_find_by_email(string $email): ?array
{
    $conn = dbConnect();

    $sql = "SELECT id, name, email, password_hash, status
            FROM users
            WHERE email = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = $result ? mysqli_fetch_assoc($result) : null;

    mysqli_stmt_close($stmt);

    return $row ?: null;
}


function user_get_roles(int $userId): array
{
    $conn = dbConnect();

    $sql = "SELECT r.name
            FROM user_roles ur
            INNER JOIN roles r ON r.id = ur.role_id
            WHERE ur.user_id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return [];
    }

    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $roles = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if (!empty($row['name'])) {
                $roles[] = (string)$row['name'];
            }
        }
    }

    mysqli_stmt_close($stmt);

    return $roles;
}


function user_create(string $name, string $email, string $plainPassword, string $status = 'ACTIVE'): int
{
    $conn = dbConnect();

    $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password_hash, status)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return 0;
    }

    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hash, $status);
    mysqli_stmt_execute($stmt);

    $newId = (int)mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);

    return $newId;
}
