<?php
// ==========================================
// 1. CONFIGURATION
// ==========================================

// The Shared IP Address from your screenshot
$host = "167.235.11.154"; 

// Your cPanel Database Credentials
$user = "rezwoanm_hostelManagementSystem";
$password = "hostel-management-system";
$dbName = "rezwoanm_hostel-management-system";
$port = 3306;

// ==========================================
// 2. CONNECTION TEST
// ==========================================
echo "<h2>Database Connection Test</h2>";
echo "Attempting to connect to: <strong>$host</strong>...<br><br>";

// Enable strict error reporting to catch every issue
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_connect($host, $user, $password, $dbName, $port);
    
    if (!$conn) {
        throw new Exception(mysqli_connect_error());
    }

    mysqli_set_charset($conn, "utf8mb4");
    echo "<div style='color:green; border:1px solid green; padding:10px; background:#eaffea;'>";
    echo "✅ <strong>SUCCESS!</strong> Connected to database: <code>$dbName</code>";
    echo "</div><br>";

} catch (Exception $e) {
    echo "<div style='color:red; border:1px solid red; padding:10px; background:#ffeaea;'>";
    echo "❌ <strong>CONNECTION FAILED</strong><br><br>";
    echo "<strong>Error Message:</strong> " . $e->getMessage() . "<br><br>";
    echo "<strong>Troubleshooting:</strong><br>";
    echo "1. Check if the password is correct.<br>";
    echo "2. Since you used '%', ensure your firewall isn't blocking port 3306.<br>";
    echo "3. Double check the database user has 'All Privileges' in cPanel.";
    echo "</div>";
    exit(); // Stop script here if connection fails
}

// ==========================================
// 3. DATA RETRIEVAL TEST
// ==========================================
echo "<h3>Testing Data Retrieval...</h3>";

// We will try to fetch 1 row from the 'hostels' table we saw in your database dump
$sql = "SELECT id, name, code, status FROM hostels LIMIT 3";

try {
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1' cellpadding='8' style='border-collapse:collapse; width:100%;'>";
        echo "<tr style='background:#f2f2f2; text-align:left;'><th>ID</th><th>Hostel Name</th><th>Code</th><th>Status</th></tr>";
        
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["code"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br><span style='color:green;'>✅ Data retrieved successfully.</span>";
    } else {
        echo "<span style='color:orange;'>⚠️ Connected, but the 'hostels' table is empty.</span>";
    }

} catch (Exception $e) {
    echo "<div style='color:red; border:1px solid red; padding:10px;'>";
    echo "❌ <strong>QUERY FAILED</strong><br>";
    echo "<strong>SQL Error:</strong> " . $e->getMessage();
    echo "</div>";
}

// Close connection
mysqli_close($conn);
?>