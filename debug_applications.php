<?php
/**
 * Debug script to check for duplicate applications
 */

session_start();
require_once __DIR__ . '/app/Models/Database.php';

if (!isset($_SESSION['user_id'])) {
    echo "Please login first: <a href='index.php?page=login'>Login</a>";
    exit;
}

$studentUserId = $_SESSION['user_id'];

echo "<h2>Debug: Application Records for Student ID: $studentUserId</h2>";

$conn = dbConnect();
$sql = "SELECT id, student_user_id, hostel_id, preferred_room_type_id, status, 
               notes, reject_reason, submitted_at, reviewed_at, created_at, active_flag
        FROM room_applications 
        WHERE student_user_id = $studentUserId 
        ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    mysqli_close($conn);
    exit;
}

echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr>";
echo "<th>ID</th>";
echo "<th>Hostel ID</th>";
echo "<th>Room Type ID</th>";
echo "<th>Status</th>";
echo "<th>Active Flag</th>";
echo "<th>Notes</th>";
echo "<th>Reject Reason</th>";
echo "<th>Submitted At</th>";
echo "<th>Created At</th>";
echo "</tr>";

$count = 0;
$activeCount = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $count++;
    if (in_array($row['status'], ['SUBMITTED', 'APPROVED'])) {
        $activeCount++;
        $rowColor = '#ffcccc'; // Red for active
    } elseif ($row['status'] === 'REJECTED') {
        $rowColor = '#ffffcc'; // Yellow for rejected
    } else {
        $rowColor = '#ffffff'; // White for others
    }
    
    echo "<tr style='background-color: $rowColor;'>";
    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['hostel_id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['preferred_room_type_id']) . "</td>";
    echo "<td><strong>" . htmlspecialchars($row['status']) . "</strong></td>";
    echo "<td>" . ($row['active_flag'] ? '1' : 'NULL') . "</td>";
    echo "<td>" . htmlspecialchars(substr($row['notes'] ?? '', 0, 30)) . "</td>";
    echo "<td>" . htmlspecialchars(substr($row['reject_reason'] ?? '', 0, 30)) . "</td>";
    echo "<td>" . htmlspecialchars($row['submitted_at'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<p><strong>Total applications: $count</strong></p>";
echo "<p><strong>Active applications (SUBMITTED or APPROVED): $activeCount</strong></p>";

if ($activeCount > 1) {
    echo "<p style='color: red; font-weight: bold;'>⚠️ WARNING: Multiple active applications detected! This should not be possible with the constraint.</p>";
} elseif ($activeCount === 1) {
    echo "<p style='color: green;'>✓ OK: Only one active application exists.</p>";
} else {
    echo "<p style='color: blue;'>ℹ️ No active applications. Student can submit a new one.</p>";
}

mysqli_close($conn);

echo "<br><a href='index.php?page=student_applications'>Back to Applications</a>";
?>
