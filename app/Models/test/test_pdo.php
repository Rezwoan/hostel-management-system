<?php
$host = "167.235.11.154"; 
$user = "rezwoanm_hostelManagementSystem";
$password = "hostel-management-system";
$dbName = "rezwoanm_hostel-management-system";

try {
    // Attempt connection
    $dsn = "mysql:host=$host;dbname=$dbName;port=3306;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password);
    
    echo "✅ SUCCESS! Connected via PDO.";
    
} catch (PDOException $e) {
    echo "❌ ERROR: " . $e->getMessage();
}
?>