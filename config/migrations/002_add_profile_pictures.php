<?php
// Run migration to add profile_picture column to student_profiles
require_once __DIR__ . '/../../app/Models/Database.php';

$conn = dbConnect();

// Add profile_picture column to student_profiles table
$sql = "ALTER TABLE `student_profiles` 
        ADD COLUMN `profile_picture` VARCHAR(255) 
        DEFAULT 'uploads/profile_pictures/default.png' 
        AFTER `address`";

if (mysqli_query($conn, $sql)) {
    echo "SUCCESS: profile_picture column added to student_profiles table.\n";
    
    // Update existing records to use default profile picture
    $updateSql = "UPDATE `student_profiles` 
                  SET profile_picture = 'uploads/profile_pictures/default.png' 
                  WHERE profile_picture IS NULL OR profile_picture = ''";
    
    if (mysqli_query($conn, $updateSql)) {
        echo "SUCCESS: Existing records updated with default profile picture.\n";
    } else {
        echo "ERROR updating existing records: " . mysqli_error($conn) . "\n";
    }
    
} else {
    // Column might already exist
    if (mysqli_errno($conn) == 1060) {
        echo "INFO: profile_picture column already exists.\n";
    } else {
        echo "ERROR: " . mysqli_error($conn) . "\n";
    }
}

mysqli_close($conn);
