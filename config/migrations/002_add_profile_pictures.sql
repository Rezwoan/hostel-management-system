-- Migration: Add profile_picture column for student profile pictures
-- Run this SQL to add profile_picture to student_profiles table

ALTER TABLE `student_profiles` 
ADD COLUMN `profile_picture` VARCHAR(255) 
DEFAULT 'uploads/profile_pictures/default.png' 
AFTER `address`;

-- Update existing records to use default profile picture
UPDATE `student_profiles` 
SET profile_picture = 'uploads/profile_pictures/default.png' 
WHERE profile_picture IS NULL OR profile_picture = '';
