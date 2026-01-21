-- Migration: Add constraint to prevent duplicate active applications per student
-- Date: 2026-01-21

-- Add a generated column to help enforce uniqueness for active applications
ALTER TABLE `room_applications` 
ADD COLUMN `active_flag` TINYINT GENERATED ALWAYS AS (
    IF(`status` IN ('SUBMITTED', 'APPROVED'), 1, NULL)
) STORED;

-- Add unique constraint: only one active (SUBMITTED or APPROVED) application per student
ALTER TABLE `room_applications`
ADD UNIQUE KEY `uk_one_active_app_per_student` (`student_user_id`, `active_flag`);

-- This constraint allows:
-- - Multiple REJECTED, CANCELLED, or DRAFT applications per student
-- - But only ONE SUBMITTED or APPROVED application at a time per student
