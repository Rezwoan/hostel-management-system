<?php
/**
 * Run Migration: Add unique constraint for active applications
 */

require_once __DIR__ . '/../../app/Models/Database.php';

echo "Running migration: Add unique constraint for active applications...\n\n";

$conn = dbConnect();

// Step 1: First, delete any duplicate active applications if they exist
echo "Step 1: Checking for duplicate active applications...\n";
$checkSql = "SELECT student_user_id, COUNT(*) as count 
             FROM room_applications 
             WHERE status IN ('SUBMITTED', 'APPROVED') 
             GROUP BY student_user_id 
             HAVING count > 1";
$result = mysqli_query($conn, $checkSql);

if (mysqli_num_rows($result) > 0) {
    echo "Found students with duplicate active applications. Keeping only the most recent one...\n";
    while ($row = mysqli_fetch_assoc($result)) {
        $studentUserId = $row['student_user_id'];
        
        // Keep the most recent, delete older ones
        $deleteSql = "DELETE FROM room_applications 
                     WHERE student_user_id = $studentUserId 
                     AND status IN ('SUBMITTED', 'APPROVED')
                     AND id NOT IN (
                         SELECT * FROM (
                             SELECT id FROM room_applications 
                             WHERE student_user_id = $studentUserId 
                             AND status IN ('SUBMITTED', 'APPROVED')
                             ORDER BY created_at DESC 
                             LIMIT 1
                         ) as temp
                     )";
        if (mysqli_query($conn, $deleteSql)) {
            echo "  - Cleaned up duplicates for student ID: $studentUserId\n";
        }
    }
} else {
    echo "No duplicate active applications found.\n";
}

// Step 2: Add the generated column
echo "\nStep 2: Adding active_flag column...\n";
$sql1 = "ALTER TABLE `room_applications` 
         ADD COLUMN `active_flag` TINYINT GENERATED ALWAYS AS (
             IF(`status` IN ('SUBMITTED', 'APPROVED'), 1, NULL)
         ) STORED";

if (mysqli_query($conn, $sql1)) {
    echo "✓ Active flag column added successfully.\n";
} else {
    if (mysqli_errno($conn) == 1060) { // Duplicate column name
        echo "✓ Active flag column already exists.\n";
    } else {
        echo "✗ Error adding column: " . mysqli_error($conn) . "\n";
        mysqli_close($conn);
        exit(1);
    }
}

// Step 3: Add the unique constraint
echo "\nStep 3: Adding unique constraint...\n";
$sql2 = "ALTER TABLE `room_applications`
         ADD UNIQUE KEY `uk_one_active_app_per_student` (`student_user_id`, `active_flag`)";

if (mysqli_query($conn, $sql2)) {
    echo "✓ Unique constraint added successfully.\n";
} else {
    if (mysqli_errno($conn) == 1061) { // Duplicate key name
        echo "✓ Unique constraint already exists.\n";
    } else {
        echo "✗ Error adding constraint: " . mysqli_error($conn) . "\n";
        mysqli_close($conn);
        exit(1);
    }
}

mysqli_close($conn);

echo "\n✓ Migration completed successfully!\n";
echo "\nThis constraint ensures that:\n";
echo "- Students can have multiple REJECTED or CANCELLED applications\n";
echo "- But only ONE SUBMITTED or APPROVED application at a time\n";
echo "- Duplicate active applications will be automatically prevented\n";
?>
