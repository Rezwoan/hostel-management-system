<?php
// app/Models/Database.php

// 1. DEFINE CREDENTIALS AS CONSTANTS
// Constants are available globally automatically. No 'global $var' needed.
define("DB_USER", "rezwoanm_hostelManagementSystem");
define("DB_PASS", "hostel-management-system");
define("DB_NAME", "rezwoanm_hostel-management-system");
define("DB_PORT", 3306);

// 2. AUTO-DETECT HOST
// If Windows (XAMPP), use Remote IP. If Linux (cPanel), use Localhost.
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    define("DB_HOST", "167.235.11.154");
} else {
    define("DB_HOST", "localhost");
}

// 3. ENABLE ERROR REPORTING
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function dbConnect()
{
    try {
        // Use the Constants we defined above
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        
        // Set Charset
        mysqli_set_charset($conn, "utf8mb4");
        
        return $conn;

    } catch (mysqli_sql_exception $e) {
        // Clean Error Handling
        die("<strong>Database Connection Failed:</strong> " . $e->getMessage());
    }
}
?>