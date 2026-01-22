-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 22, 2026 at 04:45 AM
-- Server version: 8.0.43-cll-lve
-- PHP Version: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rezwoanm_hostel-management-system`
--

-- --------------------------------------------------------

--
-- Table structure for table `allocations`
--

CREATE TABLE `allocations` (
  `id` bigint UNSIGNED NOT NULL,
  `student_user_id` bigint UNSIGNED NOT NULL,
  `seat_id` bigint UNSIGNED NOT NULL,
  `hostel_id` bigint UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('ACTIVE','ENDED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `created_by_manager_user_id` bigint UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `student_active_flag` tinyint GENERATED ALWAYS AS (if((`status` = _utf8mb4'ACTIVE'),1,NULL)) STORED,
  `seat_active_flag` tinyint GENERATED ALWAYS AS (if((`status` = _utf8mb4'ACTIVE'),1,NULL)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `allocations`
--

INSERT INTO `allocations` (`id`, `student_user_id`, `seat_id`, `hostel_id`, `start_date`, `end_date`, `status`, `created_by_manager_user_id`, `created_at`) VALUES
(2, 7, 2, 2, '2026-01-16 06:44:09', NULL, 'ACTIVE', 4, '2026-01-16 06:44:09'),
(3, 8, 3, 3, '2026-01-16 06:44:10', NULL, 'ACTIVE', 5, '2026-01-16 06:44:10'),
(4, 9, 4, 1, '2026-01-16 06:44:10', NULL, 'ACTIVE', 3, '2026-01-16 06:44:10'),
(5, 10, 5, 2, '2026-01-16 06:44:10', NULL, 'ACTIVE', 4, '2026-01-16 06:44:10'),
(6, 11, 6, 3, '2026-01-16 06:44:11', NULL, 'ACTIVE', 5, '2026-01-16 06:44:11'),
(8, 17, 11, 1, '2026-01-20 00:00:00', NULL, 'ACTIVE', 1, '2026-01-20 18:20:49'),
(9, 12, 14, 1, '2026-01-21 00:00:00', NULL, 'ACTIVE', 1, '2026-01-21 04:37:40'),
(10, 18, 15, 1, '2026-01-21 00:00:00', NULL, 'ACTIVE', 1, '2026-01-21 05:48:41'),
(11, 19, 23, 1, '2026-01-21 11:27:37', NULL, 'ACTIVE', 3, '2026-01-21 10:27:40');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `actor_user_id` bigint UNSIGNED NOT NULL,
  `action` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint UNSIGNED DEFAULT NULL,
  `meta_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `actor_user_id`, `action`, `entity_type`, `entity_id`, `meta_json`, `created_at`) VALUES
(1, 1, 'CREATE', 'fee_periods', 5, '{\"name\":\"April 2026\",\"start_date\":\"2026-04-01\",\"end_date\":\"2026-04-30\"}', '2026-01-16 06:43:47'),
(2, 1, 'CREATE', 'hostels', 1, '{\"name\": \"Alpha Hostel\", \"code\": \"ALPHA\"}', '2026-01-16 06:44:22'),
(3, 1, 'CREATE', 'hostels', 2, '{\"name\": \"Beta Hostel\", \"code\": \"BETA\"}', '2026-01-16 06:44:23'),
(4, 1, 'CREATE', 'hostels', 3, '{\"name\": \"Gamma Hostel\", \"code\": \"GAMMA\"}', '2026-01-16 06:44:23'),
(5, 3, 'APPROVE', 'room_applications', 1, '{\"status\": \"APPROVED\"}', '2026-01-16 06:44:24'),
(6, 3, 'ASSIGN', 'allocations', 1, '{\"seat_id\": 1, \"student_id\": 6}', '2026-01-16 06:44:24'),
(7, 4, 'APPROVE', 'room_applications', 2, '{\"status\": \"APPROVED\"}', '2026-01-16 06:44:24'),
(8, 4, 'CREATE', 'student_invoices', 1, '{\"amount\": 4500}', '2026-01-16 06:44:25'),
(9, 3, 'RECORD_PAYMENT', 'payments', 1, '{\"amount\": 4500, \"method\": \"CASH\"}', '2026-01-16 06:44:25'),
(10, 1, 'CREATE', 'users', 6, '{\"email\": \"rahim.ahmed@student.hms\", \"role\": \"STUDENT\"}', '2026-01-16 06:44:25'),
(11, 1, 'UPDATE', 'notices', 1, '{\"title\": \"Welcome to HMS\", \"status\": \"PUBLISHED\"}', '2026-01-16 06:44:26'),
(12, 1, 'CREATE', 'student_invoices', 7, '{\"student_user_id\":15,\"amount_due\":100,\"period_id\":5}', '2026-01-16 06:45:07'),
(13, 1, 'DELETE', 'student_invoices', 3, '{\"student_user_id\":\"8\",\"amount_due\":\"2800.00\"}', '2026-01-16 06:56:29'),
(14, 1, 'RECORD_PAYMENT', 'payments', 5, '{\"invoice_id\":7,\"amount_paid\":100,\"method\":\"CARD\",\"reference_no\":\"\"}', '2026-01-16 07:00:12'),
(15, 1, 'DELETE', 'student_invoices', 4, '{\"student_user_id\":\"9\",\"amount_due\":\"2200.00\"}', '2026-01-16 07:04:43'),
(16, 1, 'RECORD_PAYMENT', 'payments', 6, '{\"invoice_id\":6,\"amount_paid\":2666,\"method\":\"BANK_TRANSFER\",\"reference_no\":\"\"}', '2026-01-16 07:05:40'),
(17, 1, 'RECORD_PAYMENT', 'payments', 7, '{\"invoice_id\":5,\"amount_paid\":4500,\"method\":\"CASH\",\"reference_no\":\"\"}', '2026-01-16 08:28:39'),
(18, 1, 'DELETE', 'payments', 2, NULL, '2026-01-16 08:31:08'),
(19, 1, 'DELETE', 'student_invoices', 2, NULL, '2026-01-16 08:35:48'),
(20, 1, 'RECORD_PAYMENT', 'payments', 8, '{\"invoice_id\":6,\"amount_paid\":834,\"method\":\"ONLINE\",\"reference_no\":\"\"}', '2026-01-16 08:40:48'),
(21, 1, 'LOGOUT', 'users', 1, '{\"ip_address\":\"::1\"}', '2026-01-16 11:49:49'),
(22, 1, 'LOGIN', 'users', 1, '{\"email\":\"admin1@admin.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"ADMIN\"}', '2026-01-16 11:50:00'),
(23, 1, 'LOGIN_FAILED', 'users', NULL, '{\"email\":\"frezwoan@gmail.com\",\"ip_address\":\"202.134.10.141\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":false}', '2026-01-16 12:02:20'),
(24, 1, 'LOGIN', 'users', 1, '{\"email\":\"admin1@admin.hms\",\"ip_address\":\"202.134.10.141\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"ADMIN\"}', '2026-01-16 12:02:47'),
(25, 1, 'LOGOUT', 'users', 1, '{\"ip_address\":\"202.134.10.141\"}', '2026-01-16 12:03:31'),
(29, 1, 'LOGIN', 'users', 1, '{\"email\":\"admin1@admin.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"ADMIN\"}', '2026-01-20 16:01:12'),
(30, 2, 'LOGIN', 'users', 2, '{\"email\":\"admin2@admin.hms\",\"ip_address\":\"162.251.62.66\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"ADMIN\"}', '2026-01-20 16:02:35'),
(31, 1, 'LOGIN', 'users', 1, '{\"email\":\"admin1@admin.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"ADMIN (Remember Me)\"}', '2026-01-20 16:34:14'),
(32, 1, 'UPDATE', 'users', 16, '{\"action\":\"deactivated_instead_of_deleted\",\"email\":\"soumik32100@gmail.com\",\"name\":\"Soumik Das\",\"reason\":\"has_audit_logs\"}', '2026-01-20 16:53:09'),
(33, 1, 'UPDATE', 'users', 16, '{\"old\":{\"name\":\"Soumik Das\",\"email\":\"soumik32100@gmail.com\",\"status\":\"INACTIVE\"},\"new\":{\"name\":\"Soumik Das\",\"email\":\"soumik32100@gmail.com\",\"status\":\"ACTIVE\"}}', '2026-01-20 16:53:38'),
(34, 1, 'DELETE', 'users', 16, '{\"deleted_email\":\"soumik32100@gmail.com\",\"deleted_name\":\"Soumik Das\"}', '2026-01-20 16:56:21'),
(35, 1, 'DELETE', 'users', 15, '{\"deleted_email\":\"sabina.yasmin@student.hms\",\"deleted_name\":\"Sabina Yasmin\"}', '2026-01-20 16:58:47'),
(36, 1, 'LOGOUT', 'users', 1, '{\"ip_address\":\"::1\"}', '2026-01-20 17:21:16'),
(37, 1, 'LOGIN_FAILED', 'users', NULL, '{\"email\":\"rahim.ahmed@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":false}', '2026-01-20 17:22:15'),
(38, 17, 'SIGNUP', 'users', 17, '{\"email\":\"soumik@student.hms\",\"name\":\"Soumik Das\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\"}', '2026-01-20 17:25:22'),
(39, 17, 'LOGIN', 'users', 17, '{\"email\":\"soumik@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-20 17:25:54'),
(40, 1, 'LOGIN', 'users', 1, '{\"email\":\"admin1@admin.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"ADMIN\"}', '2026-01-20 18:05:03'),
(41, 1, 'APPROVED', 'room_applications', 11, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"APPROVED\",\"student_user_id\":\"17\",\"reject_reason\":\"\"}', '2026-01-20 18:05:53'),
(42, 1, 'ASSIGN', 'allocations', 8, '{\"student_user_id\":17,\"seat_id\":11,\"hostel_id\":1}', '2026-01-20 18:20:50'),
(43, 1, 'CREATE', 'student_invoices', 8, '{\"student_user_id\":17,\"amount_due\":4500,\"period_id\":5}', '2026-01-20 18:41:45'),
(44, 1, 'RECORD_PAYMENT', 'payments', 9, '{\"invoice_id\":8,\"amount_paid\":4500,\"method\":\"CARD\",\"reference_no\":\"231243523\"}', '2026-01-20 19:45:03'),
(45, 1, 'UPDATE_STATUS', 'complaints', 6, '{\"old_status\":\"OPEN\",\"new_status\":\"IN_PROGRESS\",\"student_user_id\":\"17\"}', '2026-01-20 19:59:35'),
(46, 1, 'DELETE', 'complaints', 7, '{\"student_user_id\":\"17\",\"subject\":\"Light switch does not work.\"}', '2026-01-20 20:00:09'),
(47, 1, 'UPDATE_STATUS', 'complaints', 6, '{\"old_status\":\"IN_PROGRESS\",\"new_status\":\"RESOLVED\",\"student_user_id\":\"17\"}', '2026-01-20 20:04:35'),
(48, 1, 'UPDATE_STATUS', 'complaints', 6, '{\"old_status\":\"RESOLVED\",\"new_status\":\"CLOSED\",\"student_user_id\":\"17\"}', '2026-01-20 20:05:05'),
(49, 17, 'LOGOUT', 'users', 17, '{\"ip_address\":\"::1\"}', '2026-01-20 20:52:54'),
(50, 18, 'SIGNUP', 'users', 18, '{\"email\":\"din@student.hms\",\"name\":\"DIN MUHAMMAD REZWOAN\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\"}', '2026-01-20 20:54:42'),
(51, 18, 'LOGIN', 'users', 18, '{\"email\":\"din@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-20 20:55:05'),
(52, 18, 'LOGOUT', 'users', 18, '{\"ip_address\":\"::1\"}', '2026-01-20 20:55:57'),
(53, 18, 'LOGIN', 'users', 18, '{\"email\":\"din@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-20 20:56:15'),
(54, 18, 'LOGIN', 'users', 18, '{\"email\":\"din@student.hms\",\"ip_address\":\"165.101.133.55\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-20 21:34:08'),
(55, 1, 'LOGIN', 'users', 1, '{\"email\":\"admin1@admin.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"ADMIN (Remember Me)\"}', '2026-01-21 04:28:04'),
(56, 1, 'LOGOUT', 'users', 1, '{\"ip_address\":\"::1\"}', '2026-01-21 04:28:13'),
(57, 18, 'LOGIN', 'users', 18, '{\"email\":\"din@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-21 04:28:46'),
(58, 1, 'LOGIN', 'users', 1, '{\"email\":\"admin1@admin.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"ADMIN\"}', '2026-01-21 04:31:10'),
(59, 1, 'APPROVED', 'room_applications', 7, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"APPROVED\",\"student_user_id\":\"12\",\"reject_reason\":\"\"}', '2026-01-21 04:35:36'),
(60, 1, 'ASSIGN', 'allocations', 9, '{\"student_user_id\":12,\"seat_id\":14,\"hostel_id\":1}', '2026-01-21 04:37:41'),
(61, 1, 'CREATE', 'student_invoices', 9, '{\"student_user_id\":12,\"amount_due\":2800,\"period_id\":2}', '2026-01-21 04:46:47'),
(62, 1, 'APPROVED', 'room_applications', 12, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"APPROVED\",\"student_user_id\":\"18\",\"reject_reason\":\"\"}', '2026-01-21 04:48:35'),
(63, 1, 'REVERT', 'room_applications', 12, '{\"old_status\":\"APPROVED\",\"new_status\":\"SUBMITTED\",\"student_user_id\":\"18\",\"action\":\"REVERTED\"}', '2026-01-21 04:56:45'),
(64, 1, 'APPROVED', 'room_applications', 13, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"APPROVED\",\"student_user_id\":\"18\",\"reject_reason\":\"\"}', '2026-01-21 05:02:58'),
(65, 1, 'REVERT', 'room_applications', 13, '{\"old_status\":\"APPROVED\",\"new_status\":\"SUBMITTED\",\"student_user_id\":\"18\",\"action\":\"REVERTED\"}', '2026-01-21 05:03:42'),
(66, 1, 'REJECTED', 'room_applications', 14, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"REJECTED\",\"student_user_id\":\"18\",\"reject_reason\":\"No room available\"}', '2026-01-21 05:06:27'),
(67, 1, 'REJECTED', 'room_applications', 16, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"REJECTED\",\"student_user_id\":\"18\",\"reject_reason\":\"No seat available\"}', '2026-01-21 05:19:41'),
(68, 1, 'REJECTED', 'room_applications', 17, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"REJECTED\",\"student_user_id\":\"18\",\"reject_reason\":\"No room available\"}', '2026-01-21 05:26:27'),
(69, 1, 'REJECTED', 'room_applications', 18, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"REJECTED\",\"student_user_id\":\"18\",\"reject_reason\":\"No seat available.\"}', '2026-01-21 05:37:59'),
(70, 1, 'REJECTED', 'room_applications', 19, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"REJECTED\",\"student_user_id\":\"18\",\"reject_reason\":\"no seat\"}', '2026-01-21 05:41:05'),
(71, 1, 'REJECTED', 'room_applications', 20, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"REJECTED\",\"student_user_id\":\"18\",\"reject_reason\":\"no seat\"}', '2026-01-21 05:45:23'),
(72, 1, 'APPROVED', 'room_applications', 21, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"APPROVED\",\"student_user_id\":\"18\",\"reject_reason\":\"\"}', '2026-01-21 05:47:52'),
(73, 1, 'ASSIGN', 'allocations', 10, '{\"student_user_id\":18,\"seat_id\":15,\"hostel_id\":1}', '2026-01-21 05:48:42'),
(74, 1, 'REJECTED', 'room_applications', 8, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"REJECTED\",\"student_user_id\":\"13\",\"reject_reason\":\"\"}', '2026-01-21 05:58:53'),
(75, 1, 'REVERT', 'room_applications', 8, '{\"old_status\":\"REJECTED\",\"new_status\":\"SUBMITTED\",\"student_user_id\":\"13\",\"action\":\"REVERTED\"}', '2026-01-21 05:59:15'),
(76, 1, 'APPROVED', 'room_applications', 8, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"APPROVED\",\"student_user_id\":\"13\",\"reject_reason\":\"\"}', '2026-01-21 06:00:27'),
(77, 1, 'REVERT', 'room_applications', 8, '{\"old_status\":\"APPROVED\",\"new_status\":\"SUBMITTED\",\"student_user_id\":\"13\",\"action\":\"REVERTED\"}', '2026-01-21 06:00:42'),
(78, 1, 'APPROVED', 'room_applications', 8, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"APPROVED\",\"student_user_id\":\"13\",\"reject_reason\":\"\"}', '2026-01-21 08:23:40'),
(79, 3, 'LOGIN', 'users', 3, '{\"email\":\"manager1@manager.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"MANAGER\"}', '2026-01-21 08:27:35'),
(80, 18, 'LOGOUT', 'users', 18, '{\"ip_address\":\"::1\"}', '2026-01-21 08:40:56'),
(81, 19, 'SIGNUP', 'users', 19, '{\"email\":\"tamal@student.hms\",\"name\":\"Tamal Das\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\"}', '2026-01-21 08:43:38'),
(82, 19, 'LOGIN', 'users', 19, '{\"email\":\"tamal@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-21 08:43:59'),
(83, 18, 'LOGIN', 'users', 18, '{\"email\":\"din@student.hms\",\"ip_address\":\"165.101.133.55\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-21 08:45:28'),
(84, 1, 'LOGOUT', 'users', 1, '{\"ip_address\":\"::1\"}', '2026-01-21 09:07:18'),
(85, 19, 'LOGIN', 'users', 19, '{\"email\":\"tamal@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-21 09:07:48'),
(86, 19, 'LOGOUT', 'users', 19, '{\"ip_address\":\"::1\"}', '2026-01-21 09:08:16'),
(87, 3, 'LOGIN', 'users', 3, '{\"email\":\"manager1@manager.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"MANAGER\"}', '2026-01-21 09:08:53'),
(88, 1, 'LOGIN', 'users', 1, '{\"email\":\"admin1@admin.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"ADMIN\"}', '2026-01-21 09:25:54'),
(89, 1, 'REJECTED', 'room_applications', 22, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"REJECTED\",\"student_user_id\":\"19\",\"reject_reason\":\"\"}', '2026-01-21 10:05:38'),
(90, 3, 'DELETE', 'room_applications', 20, '{\"deleted_by\":3}', '2026-01-21 10:13:01'),
(91, 3, 'DELETE', 'room_applications', 19, '{\"deleted_by\":3}', '2026-01-21 10:13:15'),
(92, 3, 'DELETE', 'room_applications', 15, '{\"deleted_by\":3}', '2026-01-21 10:13:29'),
(93, 3, 'DELETE', 'room_applications', 13, '{\"deleted_by\":3}', '2026-01-21 10:13:41'),
(94, 3, 'DELETE', 'room_applications', 18, '{\"deleted_by\":3}', '2026-01-21 10:13:53'),
(95, 3, 'REJECT', 'room_applications', 23, '{\"reviewed_by\":3,\"reason\":\"No seat available.\"}', '2026-01-21 10:14:22'),
(96, 1, 'REJECTED', 'room_applications', 24, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"REJECTED\",\"student_user_id\":\"19\",\"reject_reason\":\"\"}', '2026-01-21 10:15:45'),
(97, 1, 'REJECTED', 'room_applications', 25, '{\"old_status\":\"SUBMITTED\",\"new_status\":\"REJECTED\",\"student_user_id\":\"19\",\"reject_reason\":\"No seat is empty.\"}', '2026-01-21 10:21:40'),
(98, 3, 'APPROVE', 'room_applications', 26, '{\"reviewed_by\":3}', '2026-01-21 10:23:21'),
(99, 3, 'ASSIGN', 'allocations', 11, '{\"seat_id\":23,\"student_user_id\":19}', '2026-01-21 10:27:41'),
(100, 3, 'CREATE', 'student_invoices', 10, '{\"student_user_id\":19,\"amount_due\":3500,\"period_id\":2}', '2026-01-21 10:52:50'),
(101, 3, 'RECORD_PAYMENT', 'payments', 10, '{\"invoice_id\":10,\"amount_paid\":3500,\"method\":\"CASH\",\"reference_no\":\"\"}', '2026-01-21 11:05:54'),
(102, 3, 'UPDATE_STATUS', 'complaints', 8, '{\"status\":\"RESOLVED\"}', '2026-01-21 11:15:42'),
(103, 3, 'UPDATE_STATUS', 'complaints', 9, '{\"status\":\"RESOLVED\"}', '2026-01-21 11:18:50'),
(104, 3, 'UPDATE_STATUS', 'complaints', 9, '{\"status\":\"CLOSED\"}', '2026-01-21 11:19:11'),
(105, 3, 'CREATE', 'notices', 6, '{\"hostel_id\":1,\"scope\":\"HOSTEL\"}', '2026-01-21 11:35:10'),
(106, 3, 'UPDATE', 'notices', 6, NULL, '2026-01-21 11:35:28'),
(107, 19, 'LOGOUT', 'users', 19, '{\"ip_address\":\"::1\"}', '2026-01-21 11:38:31'),
(108, 18, 'LOGIN', 'users', 18, '{\"email\":\"din@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-21 11:39:04'),
(109, 18, 'LOGOUT', 'users', 18, '{\"ip_address\":\"::1\"}', '2026-01-21 11:52:28'),
(110, 17, 'LOGIN', 'users', 17, '{\"email\":\"soumik@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-21 11:53:06'),
(111, 1, 'LOGIN', 'users', 1, '{\"email\":\"admin1@admin.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"ADMIN (Remember Me)\"}', '2026-01-21 16:43:15'),
(112, 1, 'LOGIN', 'users', 1, '{\"email\":\"admin1@admin.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"ADMIN (Remember Me)\"}', '2026-01-21 16:58:57'),
(113, 17, 'LOGIN', 'users', 17, '{\"email\":\"soumik@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT (Remember Me)\"}', '2026-01-21 16:59:32'),
(114, 17, 'LOGOUT', 'users', 17, '{\"ip_address\":\"::1\"}', '2026-01-21 16:59:47'),
(115, 1, 'LOGIN', 'users', 1, '{\"email\":\"admin1@admin.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"ADMIN (Remember Me)\"}', '2026-01-21 17:53:33'),
(116, 1, 'LOGIN', 'users', 1, '{\"email\":\"admin1@admin.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"ADMIN (Remember Me)\"}', '2026-01-21 17:53:41'),
(117, 1, 'CREATE', 'hostels', 4, '{\"code\":\"H004\",\"name\":\"Omega\"}', '2026-01-21 17:54:36'),
(118, 1, 'UPDATE', 'hostels', 4, '{\"old\":{\"name\":\"Omega\",\"status\":\"ACTIVE\"},\"new\":{\"name\":\"Omega Hostel\",\"status\":\"ACTIVE\"}}', '2026-01-21 17:55:09'),
(119, 1, 'CREATE', 'users', 20, '{\"email\":\"manager4@manager.hms\",\"role_id\":2}', '2026-01-21 17:56:35'),
(120, 1, 'ASSIGN_MANAGER', 'hostel_managers', 5, '{\"hostel_id\":4,\"manager_user_id\":20}', '2026-01-21 18:01:26'),
(121, 1, 'CREATE', 'floors', 13, '{\"hostel_id\":4,\"floor_no\":0,\"label\":\"Floor 0\"}', '2026-01-21 18:08:09'),
(122, 1, 'CREATE', 'rooms', 49, '{\"floor_id\":13,\"room_no\":\"001\",\"capacity\":1}', '2026-01-21 18:20:15'),
(123, 1, 'UPDATE', 'rooms', 49, '{\"old\":{\"room_no\":\"001\",\"status\":\"ACTIVE\"},\"new\":{\"room_no\":\"001\",\"status\":\"ACTIVE\"}}', '2026-01-21 18:26:27'),
(124, 1, 'CREATE', 'seats', 121, '{\"room_id\":49,\"seat_label\":\"A\"}', '2026-01-21 18:34:32'),
(125, 1, 'DELETE', 'seats', 121, '{\"room_id\":\"49\",\"seat_label\":\"A\"}', '2026-01-21 18:35:12'),
(126, 1, 'CREATE', 'seats', 122, '{\"room_id\":49,\"seat_label\":\"A\"}', '2026-01-21 18:41:26'),
(127, 1, 'LOGOUT', 'users', 1, '{\"ip_address\":\"::1\"}', '2026-01-21 18:45:29'),
(128, 20, 'LOGIN', 'users', 20, '{\"email\":\"manager4@manager.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"MANAGER\"}', '2026-01-21 18:45:45'),
(129, 1, 'LOGOUT', 'users', 1, '{\"ip_address\":\"::1\"}', '2026-01-21 21:28:55'),
(130, 1, 'LOGIN_FAILED', 'users', NULL, '{\"email\":\"rahim.ahmed@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\",\"success\":false}', '2026-01-21 21:29:28'),
(131, 7, 'LOGIN', 'users', 7, '{\"email\":\"karim.hossain@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-21 21:30:06'),
(132, 2, 'PASSWORD_CHANGE', 'users', 18, NULL, '2026-01-21 21:34:20'),
(133, 7, 'LOGOUT', 'users', 7, '{\"ip_address\":\"::1\"}', '2026-01-21 21:34:33'),
(134, 18, 'LOGIN', 'users', 18, '{\"email\":\"din@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-21 21:34:43'),
(135, 18, 'LOGOUT', 'users', 18, '{\"ip_address\":\"::1\"}', '2026-01-21 21:38:24'),
(136, 18, 'LOGIN', 'users', 18, '{\"email\":\"din@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-21 21:47:11'),
(137, 18, 'LOGOUT', 'users', 18, '{\"ip_address\":\"::1\"}', '2026-01-21 21:47:30'),
(138, 18, 'LOGIN', 'users', 18, '{\"email\":\"din@student.hms\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\",\"success\":true,\"role\":\"STUDENT\"}', '2026-01-21 21:52:49');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` bigint UNSIGNED NOT NULL,
  `student_user_id` bigint UNSIGNED NOT NULL,
  `hostel_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('OPEN','IN_PROGRESS','RESOLVED','CLOSED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OPEN',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `student_user_id`, `hostel_id`, `category_id`, `subject`, `description`, `status`, `created_at`, `updated_at`) VALUES
(2, 7, 1, 2, 'Bathroom Cleanliness Issue', 'The common bathroom on the first floor needs more frequent cleaning.', 'IN_PROGRESS', '2026-01-16 06:44:19', '2026-01-16 06:44:19'),
(3, 8, 2, 5, 'Leaking Tap', 'The tap in the washroom is leaking continuously, wasting water.', 'RESOLVED', '2026-01-16 06:44:19', '2026-01-16 06:44:19'),
(4, 9, 2, 4, 'Noise from Construction', 'There is excessive noise from nearby construction during study hours.', 'OPEN', '2026-01-16 06:44:20', '2026-01-16 06:44:20'),
(6, 17, 1, 1, 'Light switch does not work.', 'My light does not turn on the lights.', 'CLOSED', '2026-01-20 19:47:39', '2026-01-20 20:05:04'),
(8, 19, 1, 2, 'Bathroom is unclean', 'Bathroom in 2nd floor has not been cleaned.', 'RESOLVED', '2026-01-21 11:11:26', '2026-01-21 11:15:41'),
(9, 19, 1, 2, 'Bathroom is unclean', 'Bathroom in 2nd floor has not been cleaned.', 'CLOSED', '2026-01-21 11:15:53', '2026-01-21 11:19:10');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_categories`
--

CREATE TABLE `complaint_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaint_categories`
--

INSERT INTO `complaint_categories` (`id`, `name`) VALUES
(2, 'Cleanliness'),
(6, 'Electrical'),
(1, 'Maintenance'),
(4, 'Noise'),
(5, 'Plumbing'),
(3, 'Security');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_messages`
--

CREATE TABLE `complaint_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `complaint_id` bigint UNSIGNED NOT NULL,
  `sender_user_id` bigint UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaint_messages`
--

INSERT INTO `complaint_messages` (`id`, `complaint_id`, `sender_user_id`, `message`, `created_at`) VALUES
(3, 2, 7, 'The bathroom needs urgent attention. Cleanliness has been poor lately.', '2026-01-16 06:44:21'),
(4, 2, 3, 'We are arranging additional cleaning staff. Thank you for your feedback.', '2026-01-16 06:44:21'),
(5, 3, 8, 'The tap has been fixed. Thank you for the quick response!', '2026-01-16 06:44:22'),
(6, 3, 4, 'Glad the issue is resolved. Please let us know if there are any other problems.', '2026-01-16 06:44:22');

-- --------------------------------------------------------

--
-- Table structure for table `fee_periods`
--

CREATE TABLE `fee_periods` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fee_periods`
--

INSERT INTO `fee_periods` (`id`, `name`, `start_date`, `end_date`) VALUES
(1, 'January 2026', '2026-01-01', '2026-01-31'),
(2, 'February 2026', '2026-02-01', '2026-02-28'),
(3, 'March 2026', '2026-03-01', '2026-03-31'),
(4, 'Q1 2026', '2026-01-01', '2026-03-31'),
(5, 'April 2026', '2026-04-01', '2026-04-30');

-- --------------------------------------------------------

--
-- Table structure for table `floors`
--

CREATE TABLE `floors` (
  `id` bigint UNSIGNED NOT NULL,
  `hostel_id` bigint UNSIGNED NOT NULL,
  `floor_no` int NOT NULL,
  `label` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `floors`
--

INSERT INTO `floors` (`id`, `hostel_id`, `floor_no`, `label`, `created_at`) VALUES
(1, 1, 0, 'Ground Floor', '2026-01-16 06:42:59'),
(2, 1, 1, 'First Floor', '2026-01-16 06:43:00'),
(3, 1, 2, 'Second Floor', '2026-01-16 06:43:00'),
(4, 1, 3, 'Third Floor', '2026-01-16 06:43:01'),
(5, 2, 0, 'Ground Floor', '2026-01-16 06:43:01'),
(6, 2, 1, 'First Floor', '2026-01-16 06:43:01'),
(7, 2, 2, 'Second Floor', '2026-01-16 06:43:02'),
(8, 2, 3, 'Third Floor', '2026-01-16 06:43:02'),
(9, 3, 0, 'Ground Floor', '2026-01-16 06:43:02'),
(10, 3, 1, 'First Floor', '2026-01-16 06:43:03'),
(11, 3, 2, 'Second Floor', '2026-01-16 06:43:03'),
(12, 3, 3, 'Third Floor', '2026-01-16 06:43:03'),
(13, 4, 0, 'Floor 0', '2026-01-21 18:08:08');

-- --------------------------------------------------------

--
-- Table structure for table `hostels`
--

CREATE TABLE `hostels` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hostels`
--

INSERT INTO `hostels` (`id`, `name`, `code`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Alpha Hostel', 'ALPHA', '123 University Road, Block A, Campus Area', 'ACTIVE', '2026-01-16 06:42:57', '2026-01-16 06:42:57'),
(2, 'Beta Hostel', 'BETA', '125 University Road, Block B, Campus Area', 'ACTIVE', '2026-01-16 06:42:58', '2026-01-16 06:42:58'),
(3, 'Gamma Hostel', 'GAMMA', '127 University Road, Block C, Campus Area', 'ACTIVE', '2026-01-16 06:42:58', '2026-01-16 06:42:58'),
(4, 'Omega Hostel', 'Omega', 'Dhaka', 'ACTIVE', '2026-01-21 17:54:35', '2026-01-21 17:55:08');

-- --------------------------------------------------------

--
-- Table structure for table `hostel_managers`
--

CREATE TABLE `hostel_managers` (
  `id` bigint UNSIGNED NOT NULL,
  `hostel_id` bigint UNSIGNED NOT NULL,
  `manager_user_id` bigint UNSIGNED NOT NULL,
  `assigned_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hostel_managers`
--

INSERT INTO `hostel_managers` (`id`, `hostel_id`, `manager_user_id`, `assigned_at`) VALUES
(1, 1, 3, '2026-01-16 06:42:58'),
(2, 2, 4, '2026-01-16 06:42:59'),
(3, 3, 5, '2026-01-16 06:42:59'),
(5, 4, 20, '2026-01-21 18:01:25');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` bigint UNSIGNED NOT NULL,
  `scope` enum('GLOBAL','HOSTEL') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GLOBAL',
  `hostel_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('PUBLISHED','ARCHIVED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PUBLISHED',
  `publish_at` datetime DEFAULT NULL,
  `expire_at` datetime DEFAULT NULL,
  `created_by_user_id` bigint UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `scope`, `hostel_id`, `title`, `body`, `status`, `publish_at`, `expire_at`, `created_by_user_id`, `created_at`) VALUES
(1, 'GLOBAL', NULL, 'Welcome to HMS', 'Welcome to the Hostel Management System. Please familiarize yourself with the rules and regulations.', 'PUBLISHED', '2026-01-16 06:44:17', NULL, 1, '2026-01-16 06:44:17'),
(2, 'GLOBAL', NULL, 'Fee Payment Reminder', 'Please ensure all hostel fees are paid by the end of this month to avoid late charges.', 'PUBLISHED', '2026-01-16 06:44:17', NULL, 1, '2026-01-16 06:44:17'),
(3, 'HOSTEL', 1, 'Water Supply Notice', 'Water supply will be interrupted tomorrow from 10 AM to 2 PM for maintenance work.', 'PUBLISHED', '2026-01-16 06:44:17', NULL, 3, '2026-01-16 06:44:17'),
(4, 'HOSTEL', 2, 'Room Inspection', 'Room inspection scheduled for this weekend. Please keep your rooms clean and tidy.', 'PUBLISHED', '2026-01-16 06:44:18', NULL, 4, '2026-01-16 06:44:18'),
(5, 'HOSTEL', 3, 'WiFi Upgrade', 'WiFi network will be upgraded tonight. Expect brief disconnections between 11 PM and 1 AM.', 'PUBLISHED', '2026-01-16 06:44:18', NULL, 5, '2026-01-16 06:44:18'),
(6, 'HOSTEL', 1, 'Water Wasting Reduction', 'Everyone ensure water taps are properly closed. Don\'t Waste Water.', 'PUBLISHED', '2026-01-21 17:33:00', '2026-01-30 17:34:00', 3, '2026-01-21 11:35:09');

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
  `id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `method` enum('CASH','BKASH','BANK','OTHER') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_no` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recorded_by_user_id` bigint UNSIGNED NOT NULL,
  `paid_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `amount_paid`, `method`, `reference_no`, `recorded_by_user_id`, `paid_at`) VALUES
(6, 6, 2666.00, '', NULL, 1, '2026-01-16 07:05:38'),
(7, 5, 4500.00, 'CASH', NULL, 1, '2026-01-16 08:28:37'),
(8, 6, 834.00, '', NULL, 1, '2026-01-16 08:40:46'),
(9, 8, 4500.00, '', '231243523', 1, '2026-01-20 19:45:01'),
(10, 10, 3500.00, 'CASH', NULL, 3, '2026-01-21 11:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `selector` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_validator` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `remember_tokens`
--

INSERT INTO `remember_tokens` (`id`, `user_id`, `selector`, `hashed_validator`, `expires_at`, `created_at`) VALUES
(14, 3, '089de7e16e0b9ba843e6205a768db142', 'e9c238462832b8db033f4e91aac58ecd13637d8f2dc5223790af5190b52a27ef', '2026-02-20 10:08:51', '2026-01-21 09:08:54'),
(18, 20, '06e6c73052b19117d7913d3f6881e6a0', 'f187b3a5509aae535fa1a693ebdbd9406b53e2a9fc5ece0edfd01f6b16e9f87e', '2026-02-20 19:45:42', '2026-01-21 18:45:47'),
(20, 18, '8212e1d31c1b6ea464e2a492f516abb2', 'dbb61bb2f5a166493c5f40f556d39953821cf344c7daf3d6e92ff4fe60228104', '2026-02-20 22:52:50', '2026-01-21 21:52:50');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` smallint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
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
  `id` bigint UNSIGNED NOT NULL,
  `floor_id` bigint UNSIGNED NOT NULL,
  `room_type_id` bigint UNSIGNED NOT NULL,
  `room_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL,
  `status` enum('ACTIVE','MAINTENANCE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `floor_id`, `room_type_id`, `room_no`, `capacity`, `status`, `created_at`) VALUES
(1, 1, 1, '001', 1, 'ACTIVE', '2026-01-16 06:43:04'),
(2, 1, 2, '002', 2, 'ACTIVE', '2026-01-16 06:43:04'),
(3, 1, 3, '003', 3, 'ACTIVE', '2026-01-16 06:43:05'),
(4, 1, 4, '004', 4, 'ACTIVE', '2026-01-16 06:43:05'),
(5, 2, 1, '101', 1, 'ACTIVE', '2026-01-16 06:43:05'),
(6, 2, 2, '102', 2, 'ACTIVE', '2026-01-16 06:43:06'),
(7, 2, 3, '103', 3, 'ACTIVE', '2026-01-16 06:43:06'),
(8, 2, 4, '104', 4, 'ACTIVE', '2026-01-16 06:43:06'),
(9, 3, 1, '201', 1, 'ACTIVE', '2026-01-16 06:43:07'),
(10, 3, 2, '202', 2, 'ACTIVE', '2026-01-16 06:43:07'),
(11, 3, 3, '203', 3, 'ACTIVE', '2026-01-16 06:43:07'),
(12, 3, 4, '204', 4, 'ACTIVE', '2026-01-16 06:43:08'),
(13, 4, 1, '301', 1, 'ACTIVE', '2026-01-16 06:43:08'),
(14, 4, 2, '302', 2, 'ACTIVE', '2026-01-16 06:43:09'),
(15, 4, 3, '303', 3, 'ACTIVE', '2026-01-16 06:43:09'),
(16, 4, 4, '304', 4, 'ACTIVE', '2026-01-16 06:43:09'),
(17, 5, 1, '001', 1, 'ACTIVE', '2026-01-16 06:43:10'),
(18, 5, 2, '002', 2, 'ACTIVE', '2026-01-16 06:43:10'),
(19, 5, 3, '003', 3, 'ACTIVE', '2026-01-16 06:43:10'),
(20, 5, 4, '004', 4, 'ACTIVE', '2026-01-16 06:43:11'),
(21, 6, 1, '101', 1, 'ACTIVE', '2026-01-16 06:43:11'),
(22, 6, 2, '102', 2, 'ACTIVE', '2026-01-16 06:43:11'),
(23, 6, 3, '103', 3, 'ACTIVE', '2026-01-16 06:43:12'),
(24, 6, 4, '104', 4, 'ACTIVE', '2026-01-16 06:43:12'),
(25, 7, 1, '201', 1, 'ACTIVE', '2026-01-16 06:43:12'),
(26, 7, 2, '202', 2, 'ACTIVE', '2026-01-16 06:43:13'),
(27, 7, 3, '203', 3, 'ACTIVE', '2026-01-16 06:43:13'),
(28, 7, 4, '204', 4, 'ACTIVE', '2026-01-16 06:43:14'),
(29, 8, 1, '301', 1, 'ACTIVE', '2026-01-16 06:43:14'),
(30, 8, 2, '302', 2, 'ACTIVE', '2026-01-16 06:43:14'),
(31, 8, 3, '303', 3, 'ACTIVE', '2026-01-16 06:43:15'),
(32, 8, 4, '304', 4, 'ACTIVE', '2026-01-16 06:43:15'),
(33, 9, 1, '001', 1, 'ACTIVE', '2026-01-16 06:43:15'),
(34, 9, 2, '002', 2, 'ACTIVE', '2026-01-16 06:43:16'),
(35, 9, 3, '003', 3, 'ACTIVE', '2026-01-16 06:43:16'),
(36, 9, 4, '004', 4, 'ACTIVE', '2026-01-16 06:43:16'),
(37, 10, 1, '101', 1, 'ACTIVE', '2026-01-16 06:43:17'),
(38, 10, 2, '102', 2, 'ACTIVE', '2026-01-16 06:43:17'),
(39, 10, 3, '103', 3, 'ACTIVE', '2026-01-16 06:43:18'),
(40, 10, 4, '104', 4, 'ACTIVE', '2026-01-16 06:43:18'),
(41, 11, 1, '201', 1, 'ACTIVE', '2026-01-16 06:43:18'),
(42, 11, 2, '202', 2, 'ACTIVE', '2026-01-16 06:43:19'),
(43, 11, 3, '203', 3, 'ACTIVE', '2026-01-16 06:43:19'),
(44, 11, 4, '204', 4, 'ACTIVE', '2026-01-16 06:43:19'),
(45, 12, 1, '301', 1, 'ACTIVE', '2026-01-16 06:43:20'),
(46, 12, 2, '302', 2, 'ACTIVE', '2026-01-16 06:43:20'),
(47, 12, 3, '303', 3, 'ACTIVE', '2026-01-16 06:43:20'),
(48, 12, 4, '304', 4, 'ACTIVE', '2026-01-16 06:43:21'),
(49, 13, 1, '001', 1, 'ACTIVE', '2026-01-21 18:20:14');

-- --------------------------------------------------------

--
-- Table structure for table `room_applications`
--

CREATE TABLE `room_applications` (
  `id` bigint UNSIGNED NOT NULL,
  `student_user_id` bigint UNSIGNED NOT NULL,
  `hostel_id` bigint UNSIGNED NOT NULL,
  `preferred_room_type_id` bigint UNSIGNED NOT NULL,
  `status` enum('DRAFT','SUBMITTED','APPROVED','REJECTED','CANCELLED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `reject_reason` text COLLATE utf8mb4_unicode_ci,
  `submitted_at` datetime DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `reviewed_by_manager_user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active_flag` tinyint GENERATED ALWAYS AS (if((`status` in (_utf8mb4'SUBMITTED',_utf8mb4'APPROVED')),1,NULL)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_applications`
--

INSERT INTO `room_applications` (`id`, `student_user_id`, `hostel_id`, `preferred_room_type_id`, `status`, `notes`, `reject_reason`, `submitted_at`, `reviewed_at`, `reviewed_by_manager_user_id`, `created_at`) VALUES
(7, 12, 1, 3, 'APPROVED', 'Waiting for room assignment', NULL, '2026-01-16 06:44:08', '2026-01-21 04:35:35', 1, '2026-01-16 06:44:08'),
(8, 13, 2, 4, 'APPROVED', 'Waiting for room assignment', NULL, '2026-01-16 06:44:08', '2026-01-21 08:23:39', 1, '2026-01-16 06:44:08'),
(9, 14, 3, 1, 'REJECTED', NULL, 'No reason', NULL, '2026-01-16 10:52:00', 1, '2026-01-16 06:44:08'),
(11, 17, 1, 1, 'APPROVED', 'I want a attached bathroom.', NULL, '2026-01-20 18:03:15', '2026-01-20 18:05:52', 1, '2026-01-20 18:03:15'),
(14, 18, 1, 3, 'REJECTED', '', 'No room available', '2026-01-21 05:05:23', '2026-01-21 05:06:26', 1, '2026-01-21 05:05:23'),
(16, 18, 1, 3, 'REJECTED', '', 'No seat available', '2026-01-21 05:18:53', '2026-01-21 05:19:39', 1, '2026-01-21 05:18:53'),
(17, 18, 1, 3, 'REJECTED', '', 'No room available', '2026-01-21 05:19:54', '2026-01-21 05:26:26', 1, '2026-01-21 05:19:54'),
(21, 18, 1, 3, 'APPROVED', '', NULL, '2026-01-21 05:46:19', '2026-01-21 05:47:50', 1, '2026-01-21 05:46:19'),
(23, 19, 1, 2, 'REJECTED', 'Room with balcony is preferable.', 'No seat available.', '2026-01-21 10:06:21', '2026-01-21 11:14:17', 3, '2026-01-21 10:06:21'),
(24, 19, 2, 2, 'REJECTED', '', NULL, '2026-01-21 10:14:54', '2026-01-21 10:15:44', 1, '2026-01-21 10:14:54'),
(25, 19, 2, 2, 'REJECTED', '', 'No seat is empty.', '2026-01-21 10:18:36', '2026-01-21 10:21:39', 1, '2026-01-21 10:18:36'),
(26, 19, 1, 2, 'APPROVED', 'Room with balcony is preferable.', NULL, '2026-01-21 10:22:46', '2026-01-21 11:23:17', 3, '2026-01-21 10:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_capacity` int NOT NULL,
  `default_fee` decimal(10,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `name`, `default_capacity`, `default_fee`, `description`) VALUES
(1, 'Single', 1, 4500.00, 'Single occupancy room with private space'),
(2, 'Double', 2, 3500.00, 'Double sharing room with shared amenities'),
(3, 'Triple', 3, 2800.00, 'Triple sharing room, budget friendly'),
(4, 'Quad', 4, 2200.00, 'Four person room, most economical option');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED NOT NULL,
  `seat_label` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `room_id`, `seat_label`, `status`) VALUES
(1, 1, 'A', 'ACTIVE'),
(2, 2, 'A', 'ACTIVE'),
(3, 2, 'B', 'ACTIVE'),
(4, 3, 'A', 'ACTIVE'),
(5, 3, 'B', 'ACTIVE'),
(6, 3, 'C', 'ACTIVE'),
(7, 4, 'A', 'ACTIVE'),
(8, 4, 'B', 'ACTIVE'),
(9, 4, 'C', 'ACTIVE'),
(10, 4, 'D', 'ACTIVE'),
(11, 5, 'A', 'ACTIVE'),
(12, 6, 'A', 'ACTIVE'),
(13, 6, 'B', 'ACTIVE'),
(14, 7, 'A', 'ACTIVE'),
(15, 7, 'B', 'ACTIVE'),
(16, 7, 'C', 'ACTIVE'),
(17, 8, 'A', 'ACTIVE'),
(18, 8, 'B', 'ACTIVE'),
(19, 8, 'C', 'ACTIVE'),
(20, 8, 'D', 'ACTIVE'),
(21, 9, 'A', 'ACTIVE'),
(22, 10, 'A', 'ACTIVE'),
(23, 10, 'B', 'ACTIVE'),
(24, 11, 'A', 'ACTIVE'),
(25, 11, 'B', 'ACTIVE'),
(26, 11, 'C', 'ACTIVE'),
(27, 12, 'A', 'ACTIVE'),
(28, 12, 'B', 'ACTIVE'),
(29, 12, 'C', 'ACTIVE'),
(30, 12, 'D', 'ACTIVE'),
(31, 13, 'A', 'ACTIVE'),
(32, 14, 'A', 'ACTIVE'),
(33, 14, 'B', 'ACTIVE'),
(34, 15, 'A', 'ACTIVE'),
(35, 15, 'B', 'ACTIVE'),
(36, 15, 'C', 'ACTIVE'),
(37, 16, 'A', 'ACTIVE'),
(38, 16, 'B', 'ACTIVE'),
(39, 16, 'C', 'ACTIVE'),
(40, 16, 'D', 'ACTIVE'),
(41, 17, 'A', 'ACTIVE'),
(42, 18, 'A', 'ACTIVE'),
(43, 18, 'B', 'ACTIVE'),
(44, 19, 'A', 'ACTIVE'),
(45, 19, 'B', 'ACTIVE'),
(46, 19, 'C', 'ACTIVE'),
(47, 20, 'A', 'ACTIVE'),
(48, 20, 'B', 'ACTIVE'),
(49, 20, 'C', 'ACTIVE'),
(50, 20, 'D', 'ACTIVE'),
(51, 21, 'A', 'ACTIVE'),
(52, 22, 'A', 'ACTIVE'),
(53, 22, 'B', 'ACTIVE'),
(54, 23, 'A', 'ACTIVE'),
(55, 23, 'B', 'ACTIVE'),
(56, 23, 'C', 'ACTIVE'),
(57, 24, 'A', 'ACTIVE'),
(58, 24, 'B', 'ACTIVE'),
(59, 24, 'C', 'ACTIVE'),
(60, 24, 'D', 'ACTIVE'),
(61, 25, 'A', 'ACTIVE'),
(62, 26, 'A', 'ACTIVE'),
(63, 26, 'B', 'ACTIVE'),
(64, 27, 'A', 'ACTIVE'),
(65, 27, 'B', 'ACTIVE'),
(66, 27, 'C', 'ACTIVE'),
(67, 28, 'A', 'ACTIVE'),
(68, 28, 'B', 'ACTIVE'),
(69, 28, 'C', 'ACTIVE'),
(70, 28, 'D', 'ACTIVE'),
(71, 29, 'A', 'ACTIVE'),
(72, 30, 'A', 'ACTIVE'),
(73, 30, 'B', 'ACTIVE'),
(74, 31, 'A', 'ACTIVE'),
(75, 31, 'B', 'ACTIVE'),
(76, 31, 'C', 'ACTIVE'),
(77, 32, 'A', 'ACTIVE'),
(78, 32, 'B', 'ACTIVE'),
(79, 32, 'C', 'ACTIVE'),
(80, 32, 'D', 'ACTIVE'),
(81, 33, 'A', 'ACTIVE'),
(82, 34, 'A', 'ACTIVE'),
(83, 34, 'B', 'ACTIVE'),
(84, 35, 'A', 'ACTIVE'),
(85, 35, 'B', 'ACTIVE'),
(86, 35, 'C', 'ACTIVE'),
(87, 36, 'A', 'ACTIVE'),
(88, 36, 'B', 'ACTIVE'),
(89, 36, 'C', 'ACTIVE'),
(90, 36, 'D', 'ACTIVE'),
(91, 37, 'A', 'ACTIVE'),
(92, 38, 'A', 'ACTIVE'),
(93, 38, 'B', 'ACTIVE'),
(94, 39, 'A', 'ACTIVE'),
(95, 39, 'B', 'ACTIVE'),
(96, 39, 'C', 'ACTIVE'),
(97, 40, 'A', 'ACTIVE'),
(98, 40, 'B', 'ACTIVE'),
(99, 40, 'C', 'ACTIVE'),
(100, 40, 'D', 'ACTIVE'),
(101, 41, 'A', 'ACTIVE'),
(102, 42, 'A', 'ACTIVE'),
(103, 42, 'B', 'ACTIVE'),
(104, 43, 'A', 'ACTIVE'),
(105, 43, 'B', 'ACTIVE'),
(106, 43, 'C', 'ACTIVE'),
(107, 44, 'A', 'ACTIVE'),
(108, 44, 'B', 'ACTIVE'),
(109, 44, 'C', 'ACTIVE'),
(110, 44, 'D', 'ACTIVE'),
(111, 45, 'A', 'ACTIVE'),
(112, 46, 'A', 'ACTIVE'),
(113, 46, 'B', 'ACTIVE'),
(114, 47, 'A', 'ACTIVE'),
(115, 47, 'B', 'ACTIVE'),
(116, 47, 'C', 'ACTIVE'),
(117, 48, 'A', 'ACTIVE'),
(118, 48, 'B', 'ACTIVE'),
(119, 48, 'C', 'ACTIVE'),
(120, 48, 'D', 'ACTIVE'),
(122, 49, 'A', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `student_invoices`
--

CREATE TABLE `student_invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `student_user_id` bigint UNSIGNED NOT NULL,
  `hostel_id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `amount_due` decimal(10,2) NOT NULL,
  `status` enum('DUE','PARTIAL','PAID','WAIVED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DUE',
  `generated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_invoices`
--

INSERT INTO `student_invoices` (`id`, `student_user_id`, `hostel_id`, `period_id`, `amount_due`, `status`, `generated_at`) VALUES
(5, 10, 2, 1, 4500.00, 'PAID', '2026-01-16 06:44:14'),
(6, 11, 3, 1, 3500.00, 'PAID', '2026-01-16 06:44:14'),
(8, 17, 1, 5, 4500.00, 'PAID', '2026-01-20 18:41:43'),
(9, 12, 1, 2, 2800.00, 'DUE', '2026-01-21 04:46:46'),
(10, 19, 1, 2, 3500.00, 'PAID', '2026-01-21 10:52:48');

-- --------------------------------------------------------

--
-- Table structure for table `student_profiles`
--

CREATE TABLE `student_profiles` (
  `user_id` bigint UNSIGNED NOT NULL,
  `student_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_year` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'uploads/profile_pictures/default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_profiles`
--

INSERT INTO `student_profiles` (`user_id`, `student_id`, `department`, `session_year`, `dob`, `address`, `profile_picture`) VALUES
(7, 'STU-2026-0002', 'EEE', '2024-2025', '2003-08-22', 'Chittagong, Bangladesh', 'uploads/profile_pictures/student_7_1769031125.jpg'),
(8, 'STU-2026-0003', 'BBA', '2024-2025', '2004-01-10', 'Sylhet, Bangladesh', 'uploads/profile_pictures/default.png'),
(9, 'STU-2026-0004', 'CSE', '2023-2024', '2002-11-30', 'Rajshahi, Bangladesh', 'uploads/profile_pictures/default.png'),
(10, 'STU-2026-0005', 'ME', '2024-2025', '2003-07-18', 'Khulna, Bangladesh', 'uploads/profile_pictures/default.png'),
(11, 'STU-2026-0006', 'Pharmacy', '2024-2025', '2003-03-25', 'Comilla, Bangladesh', 'uploads/profile_pictures/default.png'),
(12, 'STU-2026-0007', 'Civil', '2023-2024', '2002-09-08', 'Mymensingh, Bangladesh', 'uploads/profile_pictures/default.png'),
(13, 'STU-2026-0008', 'Architecture', '2024-2025', '2003-12-05', 'Barishal, Bangladesh', 'uploads/profile_pictures/default.png'),
(14, 'STU-2026-0009', 'CSE', '2024-2025', '2003-04-20', 'Rangpur, Bangladesh', 'uploads/profile_pictures/default.png'),
(17, '23-51709-2', 'CSE', '2023-24', '2003-05-29', 'A-R6, H-185, Road-5, Block-A, Bashundhora R/A', 'uploads/profile_pictures/student_17_1768996409.webp'),
(18, '23-51712-2', 'CSE', '2023-24', '2004-01-05', 'Binodpur', 'uploads/profile_pictures/student_18_1769031342.jpg'),
(19, '23-51780-2', 'EEE', '2022-23', '2002-03-13', 'Rajbari, Dhaka', 'uploads/profile_pictures/default.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ACTIVE','INACTIVE','LOCKED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password_hash`, `status`, `created_at`, `updated_at`, `last_login_at`) VALUES
(1, 'Super Admin', 'admin1@admin.hms', '+8801700000001', '$2y$10$6H9v50jg907UYsfqPg/HxuiFjrSvn73q8u5ESVIv0uPI5OsYbwpsi', 'ACTIVE', '2026-01-16 06:42:43', '2026-01-21 09:25:53', '2026-01-21 09:25:53'),
(2, 'System Admin', 'admin2@admin.hms', '+8801700000002', '$2y$10$6H9v50jg907UYsfqPg/HxuiFjrSvn73q8u5ESVIv0uPI5OsYbwpsi', 'ACTIVE', '2026-01-16 06:42:43', '2026-01-20 16:02:35', '2026-01-20 16:02:35'),
(3, 'Hostel Manager Alpha', 'manager1@manager.hms', '+8801800000001', '$2y$10$6H9v50jg907UYsfqPg/HxuiFjrSvn73q8u5ESVIv0uPI5OsYbwpsi', 'ACTIVE', '2026-01-16 06:42:43', '2026-01-21 09:08:52', '2026-01-21 09:08:52'),
(4, 'Hostel Manager Beta', 'manager2@manager.hms', '+8801800000002', '$2y$10$6H9v50jg907UYsfqPg/HxuiFjrSvn73q8u5ESVIv0uPI5OsYbwpsi', 'ACTIVE', '2026-01-16 06:42:44', '2026-01-16 06:42:44', NULL),
(5, 'Hostel Manager Gamma', 'manager3@manager.hms', '+8801800000003', '$2y$10$6H9v50jg907UYsfqPg/HxuiFjrSvn73q8u5ESVIv0uPI5OsYbwpsi', 'ACTIVE', '2026-01-16 06:42:44', '2026-01-16 06:42:44', NULL),
(7, 'Karim Hossain', 'karim.hossain@student.hms', '+8801900000002', '$2y$10$6H9v50jg907UYsfqPg/HxuiFjrSvn73q8u5ESVIv0uPI5OsYbwpsi', 'ACTIVE', '2026-01-16 06:42:45', '2026-01-21 21:30:04', '2026-01-21 21:30:04'),
(8, 'Fatima Khan', 'fatima.khan@student.hms', '+8801900000003', '$2y$10$6H9v50jg907UYsfqPg/HxuiFjrSvn73q8u5ESVIv0uPI5OsYbwpsi', 'ACTIVE', '2026-01-16 06:42:45', '2026-01-16 06:42:45', NULL),
(9, 'Nusrat Jahan', 'nusrat.jahan@student.hms', '+8801900000004', '$2y$10$6H9v50jg907UYsfqPg/HxuiFjrSvn73q8u5ESVIv0uPI5OsYbwpsi', 'ACTIVE', '2026-01-16 06:42:46', '2026-01-16 06:42:46', NULL),
(10, 'Arif Rahman', 'arif.rahman@student.hms', '+8801900000005', '$2y$10$6H9v50jg907UYsfqPg/HxuiFjrSvn73q8u5ESVIv0uPI5OsYbwpsi', 'ACTIVE', '2026-01-16 06:42:46', '2026-01-16 06:42:46', NULL),
(11, 'Tasnim Akter', 'tasnim.akter@student.hms', '+8801900000006', '$2y$10$6H9v50jg907UYsfqPg/HxuiFjrSvn73q8u5ESVIv0uPI5OsYbwpsi', 'ACTIVE', '2026-01-16 06:42:46', '2026-01-16 06:42:46', NULL),
(12, 'Sakib Hassan', 'sakib.hassan@student.hms', '+8801900000007', '$2y$10$6H9v50jg907UYsfqPg/HxuiFjrSvn73q8u5ESVIv0uPI5OsYbwpsi', 'ACTIVE', '2026-01-16 06:42:47', '2026-01-16 06:42:47', NULL),
(13, 'Maliha Islam', 'maliha.islam@student.hms', '+8801900000008', '$2y$10$6H9v50jg907UYsfqPg/HxuiFjrSvn73q8u5ESVIv0uPI5OsYbwpsi', 'ACTIVE', '2026-01-16 06:42:47', '2026-01-16 06:42:47', NULL),
(14, 'Rafiq Uddin', 'rafiq.uddin@student.hms', '+8801900000009', '$2y$10$6H9v50jg907UYsfqPg/HxuiFjrSvn73q8u5ESVIv0uPI5OsYbwpsi', 'ACTIVE', '2026-01-16 06:42:47', '2026-01-16 06:42:47', NULL),
(17, 'Soumik Das', 'soumik@student.hms', '01927430420', '$2y$10$KzuO1HMjkj.dfRMQtvQdmexY6oJWHAxOccrbK1y47fy/2RoUgE0ia', 'ACTIVE', '2026-01-20 17:25:20', '2026-01-21 11:53:05', '2026-01-21 11:53:05'),
(18, 'DIN MUHAMMAD REZWOAN', 'din@student.hms', '012345678999', '$2y$10$tJYbwri.Fqkn7UBncDF6p.wao07IaJ/mknIWE07LPY8RJndlb5Leu', 'ACTIVE', '2026-01-20 20:54:39', '2026-01-21 21:52:48', '2026-01-21 21:52:48'),
(19, 'Tamal Das', 'tamal@student.hms', '01343254363', '$2y$10$ZS6Ve2LVobKQGcLbyKFnoObSSapG2TS4mgFRBMxg41zJYKR8h0eNi', 'ACTIVE', '2026-01-21 08:43:36', '2026-01-21 09:07:47', '2026-01-21 09:07:47'),
(20, 'Kamrul Hasan', 'manager4@manager.hms', '01917846428', '$2y$10$kXhXIFQ6g9tEXBXDqLDnAeqMgtF8xYQ5PAsKtZaJ.2eq.0MbjHbDm', 'ACTIVE', '2026-01-21 17:56:34', '2026-01-21 18:45:44', '2026-01-21 18:45:44');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` smallint UNSIGNED NOT NULL,
  `assigned_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`, `assigned_at`) VALUES
(1, 1, '2026-01-16 06:42:48'),
(2, 1, '2026-01-16 06:42:49'),
(3, 2, '2026-01-16 06:42:49'),
(4, 2, '2026-01-16 06:42:49'),
(5, 2, '2026-01-16 06:42:50'),
(7, 3, '2026-01-16 06:42:50'),
(8, 3, '2026-01-16 06:42:51'),
(9, 3, '2026-01-16 06:42:51'),
(10, 3, '2026-01-16 06:42:52'),
(11, 3, '2026-01-16 06:42:52'),
(12, 3, '2026-01-16 06:42:52'),
(13, 3, '2026-01-16 06:42:53'),
(14, 3, '2026-01-16 06:42:53'),
(17, 3, '2026-01-20 17:25:21'),
(18, 3, '2026-01-20 20:54:39'),
(19, 3, '2026-01-21 08:43:37'),
(20, 2, '2026-01-21 17:56:34');

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
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector_unique` (`selector`),
  ADD KEY `user_id_idx` (`user_id`),
  ADD KEY `expires_at_idx` (`expires_at`);

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
  ADD UNIQUE KEY `uk_one_active_app_per_student` (`student_user_id`,`active_flag`),
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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `complaint_categories`
--
ALTER TABLE `complaint_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `complaint_messages`
--
ALTER TABLE `complaint_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `fee_periods`
--
ALTER TABLE `fee_periods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `floors`
--
ALTER TABLE `floors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `hostels`
--
ALTER TABLE `hostels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hostel_managers`
--
ALTER TABLE `hostel_managers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` smallint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `room_applications`
--
ALTER TABLE `room_applications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `student_invoices`
--
ALTER TABLE `student_invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `fk_remember_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
