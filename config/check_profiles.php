<?php
// Quick check script
require_once __DIR__ . '/../app/Models/Database.php';
$conn = dbConnect();

echo "=== Audit Logs Check ===\n\n";

$r = $conn->query('SELECT COUNT(*) as count FROM audit_logs');
$count = $r->fetch_assoc()['count'];
echo "Total audit log entries: $count\n\n";

if ($count > 0) {
    echo "Last 5 entries:\n";
    $r = $conn->query('SELECT al.*, u.name as actor_name FROM audit_logs al LEFT JOIN users u ON al.actor_user_id = u.id ORDER BY al.id DESC LIMIT 5');
    while ($row = $r->fetch_assoc()) {
        echo "  - ID: {$row['id']}, Action: {$row['action']}, Entity: {$row['entity_type']}, Actor: {$row['actor_name']}\n";
    }
}

mysqli_close($conn);
