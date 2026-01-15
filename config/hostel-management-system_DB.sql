-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2026 at 05:40 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hostel-management-system_DB`
--

-- --------------------------------------------------------

--
-- Table structure for table `allocations`
--

CREATE TABLE `allocations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_user_id` bigint(20) UNSIGNED NOT NULL,
  `seat_id` bigint(20) UNSIGNED NOT NULL,
  `hostel_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('ACTIVE','ENDED') NOT NULL DEFAULT 'ACTIVE',
  `created_by_manager_user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `student_active_flag` tinyint(4) GENERATED ALWAYS AS (if(`status` = 'ACTIVE',1,NULL)) STORED,
  `seat_active_flag` tinyint(4) GENERATED ALWAYS AS (if(`status` = 'ACTIVE',1,NULL)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `allocations`
--

INSERT INTO `allocations` (`id`, `student_user_id`, `seat_id`, `hostel_id`, `start_date`, `end_date`, `status`, `created_by_manager_user_id`, `created_at`) VALUES
(1, 3, 1, 1, '2026-01-14 05:21:44', NULL, 'ACTIVE', 2, '2026-01-14 05:21:44'),
(2, 4, 3, 1, '2026-01-14 05:21:44', NULL, 'ACTIVE', 2, '2026-01-14 05:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `actor_user_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(120) NOT NULL,
  `entity_type` varchar(120) NOT NULL,
  `entity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `meta_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_json`)),
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `actor_user_id`, `action`, `entity_type`, `entity_id`, `meta_json`, `created_at`) VALUES
(1, 1, 'CREATE', 'hostels', 1, '{\"code\": \"PHA\"}', '2026-01-14 05:21:44'),
(2, 2, 'APPROVE', 'room_applications', 1, '{\"reviewed_by\": 2}', '2026-01-14 05:21:44'),
(3, 2, 'ASSIGN', 'allocations', 1, '{\"seat_id\": 1, \"student_user_id\": 3}', '2026-01-14 05:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_user_id` bigint(20) UNSIGNED NOT NULL,
  `hostel_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` enum('OPEN','IN_PROGRESS','RESOLVED','CLOSED') NOT NULL DEFAULT 'OPEN',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `student_user_id`, `hostel_id`, `category_id`, `subject`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, 'Fan not working', 'Ceiling fan is not functioning in room 101.', 'OPEN', '2026-01-14 05:21:44', '2026-01-14 05:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_categories`
--

CREATE TABLE `complaint_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaint_categories`
--

INSERT INTO `complaint_categories` (`id`, `name`) VALUES
(2, 'Cleanliness'),
(1, 'Maintenance'),
(3, 'Security');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_messages`
--

CREATE TABLE `complaint_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `complaint_id` bigint(20) UNSIGNED NOT NULL,
  `sender_user_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaint_messages`
--

INSERT INTO `complaint_messages` (`id`, `complaint_id`, `sender_user_id`, `message`, `created_at`) VALUES
(1, 1, 3, 'Reported the issue. Please resolve soon.', '2026-01-14 05:21:44'),
(2, 1, 2, 'Received. Technician will check today.', '2026-01-14 05:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `fee_periods`
--

CREATE TABLE `fee_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fee_periods`
--

INSERT INTO `fee_periods` (`id`, `name`, `start_date`, `end_date`) VALUES
(1, 'Jan-2026', '2026-01-01', '2026-01-31');

-- --------------------------------------------------------

--
-- Table structure for table `floors`
--

CREATE TABLE `floors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hostel_id` bigint(20) UNSIGNED NOT NULL,
  `floor_no` int(11) NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `floors`
--

INSERT INTO `floors` (`id`, `hostel_id`, `floor_no`, `label`, `created_at`) VALUES
(1, 1, 1, 'Ground', '2026-01-14 05:21:44'),
(2, 1, 2, 'First', '2026-01-14 05:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `hostels`
--

CREATE TABLE `hostels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `code` varchar(50) NOT NULL,
  `address` text DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hostels`
--

INSERT INTO `hostels` (`id`, `name`, `code`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Prime Hostel A', 'PHA', '123 Campus Road', 'ACTIVE', '2026-01-14 05:21:44', '2026-01-14 05:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `hostel_managers`
--

CREATE TABLE `hostel_managers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hostel_id` bigint(20) UNSIGNED NOT NULL,
  `manager_user_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hostel_managers`
--

INSERT INTO `hostel_managers` (`id`, `hostel_id`, `manager_user_id`, `assigned_at`) VALUES
(1, 1, 2, '2026-01-14 05:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scope` enum('GLOBAL','HOSTEL') NOT NULL DEFAULT 'GLOBAL',
  `hostel_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `status` enum('PUBLISHED','ARCHIVED') NOT NULL DEFAULT 'PUBLISHED',
  `publish_at` datetime DEFAULT NULL,
  `expire_at` datetime DEFAULT NULL,
  `created_by_user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `scope`, `hostel_id`, `title`, `body`, `status`, `publish_at`, `expire_at`, `created_by_user_id`, `created_at`) VALUES
(1, 'GLOBAL', NULL, 'Welcome', 'Welcome to the Smart Hostel system.', 'PUBLISHED', '2026-01-14 05:21:44', NULL, 1, '2026-01-14 05:21:44'),
(2, 'HOSTEL', 1, 'Water supply', 'Water supply will be off from 10PM-12AM tonight.', 'PUBLISHED', '2026-01-14 05:21:44', NULL, 2, '2026-01-14 05:21:44');

--
-- Triggers `notices`
--
DELIMITER $$
CREATE TRIGGER `trg_notices_scope_ins` BEFORE INSERT ON `notices` FOR EACH ROW BEGIN
  IF (NEW.`scope` = 'GLOBAL' AND NEW.hostel_id IS NOT NULL)
     OR (NEW.`scope` = 'HOSTEL' AND NEW.hostel_id IS NULL) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid hostel_id for notice scope';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_notices_scope_upd` BEFORE UPDATE ON `notices` FOR EACH ROW BEGIN
  IF (NEW.`scope` = 'GLOBAL' AND NEW.hostel_id IS NOT NULL)
     OR (NEW.`scope` = 'HOSTEL' AND NEW.hostel_id IS NULL) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Invalid hostel_id for notice scope';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `method` enum('CASH','BKASH','BANK','OTHER') NOT NULL,
  `reference_no` varchar(120) DEFAULT NULL,
  `recorded_by_user_id` bigint(20) UNSIGNED NOT NULL,
  `paid_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `amount_paid`, `method`, `reference_no`, `recorded_by_user_id`, `paid_at`) VALUES
(1, 1, 1000.00, 'CASH', 'RCPT-0001', 2, '2026-01-14 05:21:44'),
(2, 2, 3500.00, 'BKASH', 'BKASH-TXN-0001', 2, '2026-01-14 05:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'ADMIN'),
(2, 'MANAGER'),
(3, 'STUDENT');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `floor_id` bigint(20) UNSIGNED NOT NULL,
  `room_type_id` bigint(20) UNSIGNED NOT NULL,
  `room_no` varchar(30) NOT NULL,
  `capacity` int(11) NOT NULL,
  `status` enum('ACTIVE','MAINTENANCE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `floor_id`, `room_type_id`, `room_no`, `capacity`, `status`, `created_at`) VALUES
(1, 1, 2, '101', 2, 'ACTIVE', '2026-01-14 05:21:44'),
(2, 1, 1, '102', 1, 'ACTIVE', '2026-01-14 05:21:44'),
(3, 2, 2, '201', 2, 'ACTIVE', '2026-01-14 05:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `room_applications`
--

CREATE TABLE `room_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_user_id` bigint(20) UNSIGNED NOT NULL,
  `hostel_id` bigint(20) UNSIGNED NOT NULL,
  `preferred_room_type_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('DRAFT','SUBMITTED','APPROVED','REJECTED','CANCELLED') NOT NULL DEFAULT 'DRAFT',
  `notes` text DEFAULT NULL,
  `reject_reason` text DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `reviewed_by_manager_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_applications`
--

INSERT INTO `room_applications` (`id`, `student_user_id`, `hostel_id`, `preferred_room_type_id`, `status`, `notes`, `reject_reason`, `submitted_at`, `reviewed_at`, `reviewed_by_manager_user_id`, `created_at`) VALUES
(1, 3, 1, 2, 'APPROVED', 'Prefer a quiet room.', NULL, '2026-01-14 05:21:44', '2026-01-14 05:21:44', 2, '2026-01-14 05:21:44'),
(2, 4, 1, 1, 'APPROVED', 'Near study room if possible.', NULL, '2026-01-14 05:21:44', '2026-01-14 05:21:44', 2, '2026-01-14 05:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `default_capacity` int(11) NOT NULL,
  `default_fee` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `name`, `default_capacity`, `default_fee`, `description`) VALUES
(1, 'Single', 1, 3500.00, 'Single occupancy room'),
(2, 'Double', 2, 2500.00, 'Double occupancy room');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `seat_label` varchar(20) NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `room_id`, `seat_label`, `status`) VALUES
(1, 1, 'A1', 'ACTIVE'),
(2, 1, 'A2', 'ACTIVE'),
(3, 2, 'B1', 'ACTIVE'),
(4, 3, 'C1', 'ACTIVE'),
(5, 3, 'C2', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `student_invoices`
--

CREATE TABLE `student_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_user_id` bigint(20) UNSIGNED NOT NULL,
  `hostel_id` bigint(20) UNSIGNED NOT NULL,
  `period_id` bigint(20) UNSIGNED NOT NULL,
  `amount_due` decimal(10,2) NOT NULL,
  `status` enum('DUE','PARTIAL','PAID','WAIVED') NOT NULL DEFAULT 'DUE',
  `generated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_invoices`
--

INSERT INTO `student_invoices` (`id`, `student_user_id`, `hostel_id`, `period_id`, `amount_due`, `status`, `generated_at`) VALUES
(1, 3, 1, 1, 2500.00, 'PARTIAL', '2026-01-14 05:21:44'),
(2, 4, 1, 1, 3500.00, 'PAID', '2026-01-14 05:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_profiles`
--

CREATE TABLE `student_profiles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `department` varchar(120) DEFAULT NULL,
  `session_year` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_profiles`
--

INSERT INTO `student_profiles` (`user_id`, `student_id`, `department`, `session_year`, `dob`, `address`) VALUES
(3, 'STU-2026-0001', 'CSE', '2025-2026', '2004-05-20', 'Dormitory Lane'),
(4, 'STU-2026-0002', 'EEE', '2025-2026', '2004-11-02', 'Hall Street');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `status` enum('ACTIVE','INACTIVE','LOCKED') NOT NULL DEFAULT 'ACTIVE',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password_hash`, `status`, `created_at`, `updated_at`, `last_login_at`) VALUES
(1, 'Admin User', 'admin@localhost.test', '+8801000000001', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8xV0bT9N8wFZ4q6Gv5Y2oD5dD5o9jC', 'ACTIVE', '2026-01-14 05:21:44', '2026-01-14 05:21:44', NULL),
(2, 'Hostel Manager', 'manager@localhost.test', '+8801000000002', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8xV0bT9N8wFZ4q6Gv5Y2oD5dD5o9jC', 'ACTIVE', '2026-01-14 05:21:44', '2026-01-14 05:21:44', NULL),
(3, 'Student One', 'student1@localhost.test', '+8801000000003', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8xV0bT9N8wFZ4q6Gv5Y2oD5dD5o9jC', 'ACTIVE', '2026-01-14 05:21:44', '2026-01-14 05:21:44', NULL),
(4, 'Student Two', 'student2@localhost.test', '+8801000000004', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8xV0bT9N8wFZ4q6Gv5Y2oD5dD5o9jC', 'ACTIVE', '2026-01-14 05:21:44', '2026-01-14 05:21:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` smallint(5) UNSIGNED NOT NULL,
  `assigned_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`, `assigned_at`) VALUES
(1, 1, '2026-01-14 05:21:44'),
(2, 2, '2026-01-14 05:21:44'),
(3, 3, '2026-01-14 05:21:44'),
(4, 3, '2026-01-14 05:21:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allocations`
--
ALTER TABLE `allocations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_alloc_one_active_per_student` (`student_user_id`,`student_active_flag`),
  ADD UNIQUE KEY `uk_alloc_one_active_per_seat` (`seat_id`,`seat_active_flag`),
  ADD KEY `idx_alloc_student` (`student_user_id`),
  ADD KEY `idx_alloc_seat` (`seat_id`),
  ADD KEY `idx_alloc_hostel` (`hostel_id`),
  ADD KEY `idx_alloc_status` (`status`),
  ADD KEY `fk_alloc_creator` (`created_by_manager_user_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_actor` (`actor_user_id`),
  ADD KEY `idx_audit_entity` (`entity_type`,`entity_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_complaints_student` (`student_user_id`),
  ADD KEY `idx_complaints_hostel` (`hostel_id`),
  ADD KEY `idx_complaints_category` (`category_id`),
  ADD KEY `idx_complaints_status` (`status`);

--
-- Indexes for table `complaint_categories`
--
ALTER TABLE `complaint_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_complaint_categories_name` (`name`);

--
-- Indexes for table `complaint_messages`
--
ALTER TABLE `complaint_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_complaint_messages_complaint` (`complaint_id`),
  ADD KEY `idx_complaint_messages_sender` (`sender_user_id`);

--
-- Indexes for table `fee_periods`
--
ALTER TABLE `fee_periods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_fee_periods_name` (`name`),
  ADD KEY `idx_fee_periods_dates` (`start_date`,`end_date`);

--
-- Indexes for table `floors`
--
ALTER TABLE `floors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_floors_hostel_floorno` (`hostel_id`,`floor_no`),
  ADD KEY `idx_floors_hostel` (`hostel_id`);

--
-- Indexes for table `hostels`
--
ALTER TABLE `hostels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_hostels_code` (`code`),
  ADD KEY `idx_hostels_status` (`status`);

--
-- Indexes for table `hostel_managers`
--
ALTER TABLE `hostel_managers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_hostel_managers_pair` (`hostel_id`,`manager_user_id`),
  ADD KEY `idx_hostel_managers_manager` (`manager_user_id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notices_scope` (`scope`),
  ADD KEY `idx_notices_hostel` (`hostel_id`),
  ADD KEY `idx_notices_status` (`status`),
  ADD KEY `fk_notices_creator` (`created_by_user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_payments_invoice` (`invoice_id`),
  ADD KEY `idx_payments_method` (`method`),
  ADD KEY `fk_payments_recorder` (`recorded_by_user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_roles_name` (`name`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_rooms_floor_roomno` (`floor_id`,`room_no`),
  ADD KEY `idx_rooms_floor` (`floor_id`),
  ADD KEY `idx_rooms_type` (`room_type_id`),
  ADD KEY `idx_rooms_status` (`status`);

--
-- Indexes for table `room_applications`
--
ALTER TABLE `room_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_room_apps_student` (`student_user_id`),
  ADD KEY `idx_room_apps_hostel` (`hostel_id`),
  ADD KEY `idx_room_apps_status` (`status`),
  ADD KEY `fk_room_apps_room_type` (`preferred_room_type_id`),
  ADD KEY `fk_room_apps_reviewer` (`reviewed_by_manager_user_id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_room_types_name` (`name`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_seats_room_label` (`room_id`,`seat_label`),
  ADD KEY `idx_seats_room` (`room_id`),
  ADD KEY `idx_seats_status` (`status`);

--
-- Indexes for table `student_invoices`
--
ALTER TABLE `student_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_invoices_student` (`student_user_id`),
  ADD KEY `idx_invoices_period` (`period_id`),
  ADD KEY `idx_invoices_status` (`status`),
  ADD KEY `fk_invoices_hostel` (`hostel_id`);

--
-- Indexes for table `student_profiles`
--
ALTER TABLE `student_profiles`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uk_student_profiles_student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_users_email` (`email`),
  ADD KEY `idx_users_status` (`status`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `fk_user_roles_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allocations`
--
ALTER TABLE `allocations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `complaint_categories`
--
ALTER TABLE `complaint_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `complaint_messages`
--
ALTER TABLE `complaint_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fee_periods`
--
ALTER TABLE `fee_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `floors`
--
ALTER TABLE `floors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hostels`
--
ALTER TABLE `hostels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hostel_managers`
--
ALTER TABLE `hostel_managers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `room_applications`
--
ALTER TABLE `room_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_invoices`
--
ALTER TABLE `student_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `allocations`
--
ALTER TABLE `allocations`
  ADD CONSTRAINT `fk_alloc_creator` FOREIGN KEY (`created_by_manager_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_alloc_hostel` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_alloc_seat` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_alloc_student` FOREIGN KEY (`student_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `fk_audit_actor` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `fk_complaints_category` FOREIGN KEY (`category_id`) REFERENCES `complaint_categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_complaints_hostel` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_complaints_student` FOREIGN KEY (`student_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `complaint_messages`
--
ALTER TABLE `complaint_messages`
  ADD CONSTRAINT `fk_complaint_messages_complaint` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_complaint_messages_sender` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `floors`
--
ALTER TABLE `floors`
  ADD CONSTRAINT `fk_floors_hostel` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hostel_managers`
--
ALTER TABLE `hostel_managers`
  ADD CONSTRAINT `fk_hostel_managers_hostel` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hostel_managers_user` FOREIGN KEY (`manager_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notices`
--
ALTER TABLE `notices`
  ADD CONSTRAINT `fk_notices_creator` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notices_hostel` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payments_recorder` FOREIGN KEY (`recorded_by_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `fk_rooms_floor` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rooms_room_type` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `room_applications`
--
ALTER TABLE `room_applications`
  ADD CONSTRAINT `fk_room_apps_hostel` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_room_apps_reviewer` FOREIGN KEY (`reviewed_by_manager_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_room_apps_room_type` FOREIGN KEY (`preferred_room_type_id`) REFERENCES `room_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_room_apps_student` FOREIGN KEY (`student_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `fk_seats_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_invoices`
--
ALTER TABLE `student_invoices`
  ADD CONSTRAINT `fk_invoices_hostel` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoices_period` FOREIGN KEY (`period_id`) REFERENCES `fee_periods` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoices_student` FOREIGN KEY (`student_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_profiles`
--
ALTER TABLE `student_profiles`
  ADD CONSTRAINT `fk_student_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `fk_user_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
