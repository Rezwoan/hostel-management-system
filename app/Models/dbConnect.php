<?php

$host = "localhost";
$user = "root";
$password = "";
$dbName = "smartHostel_db";
$port = 3306;

function dbConnect()
{
    global $host, $user, $password, $dbName, $port;

    $conn = mysqli_connect($host, $user, $password, $dbName, $port);

    if (!$conn) {
        die("DB connection failed: " . mysqli_connect_error());
    }

    mysqli_set_charset($conn, "utf8mb4");
    return $conn;
}