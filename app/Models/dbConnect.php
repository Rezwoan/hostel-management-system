<?php

// 1. CREDENTIALS
$user = "rezwoanm_hostelManagementSystem";
$password = "hostel-management-system";
$dbName = "rezwoanm_hostel-management-system";
$port = 3306;

// 2. AUTO-DETECT ENVIRONMENT
// If the OS is Windows (your laptop), use the Remote IP.
// If the OS is Linux (the cPanel server), use 'localhost'.
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    // Running on Local XAMPP -> Connect to Online DB remotely
    $host = "167.235.11.154"; 
} else {
    // Running on Online Server -> Connect internally
    $host = "localhost"; 
}

function dbConnect()
{
    global $host, $user, $password, $dbName, $port;

    // Connect
    $conn = mysqli_connect($host, $user, $password, $dbName, $port);

    if (!$conn) {
        die("DB connection failed: " . mysqli_connect_error());
    }

    // Set Charset
    mysqli_set_charset($conn, "utf8mb4");
    
    return $conn;
}

?>