-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 19, 2025 at 12:36 AM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smiledesk`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'Dentist who performed the activity',
  `type` enum('appointment_scheduled','appointment_updated','appointment_cancelled','patient_added','patient_updated','patient_deleted','payment_received','document_uploaded','document_deleted') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `user_id`, `type`, `title`, `description`, `metadata`, `created_at`) VALUES
(1, 2, 'patient_added', 'New patient registered', 'Ahmed Benali completed registration', NULL, '2025-06-08 19:00:43'),
(2, 2, 'appointment_scheduled', 'Appointment scheduled', 'Cleaning appointment for Fatima Alaoui', NULL, '2025-06-08 19:00:43'),
(3, 2, 'payment_received', 'Payment received', 'Omar Tazi paid for consultation', NULL, '2025-06-08 19:00:43'),
(4, 2, 'appointment_scheduled', 'New appointment scheduled', 'Appointment scheduled for 2025-06-11 at 17:59', NULL, '2025-06-09 11:41:49'),
(5, 2, 'payment_received', 'New invoice created', 'Invoice INV-20250609-232 created', NULL, '2025-06-09 11:47:32'),
(6, 2, 'patient_added', 'New patient registered', 'LEA MAELYS NIEZ was registered', NULL, '2025-06-09 11:48:48'),
(7, 2, 'appointment_updated', 'Appointment updated', 'Appointment #4 was updated', NULL, '2025-06-09 12:10:48'),
(8, 2, 'appointment_scheduled', 'New appointment scheduled', 'Appointment scheduled for 2025-06-09 at 14:15', NULL, '2025-06-09 12:12:32'),
(9, 2, 'appointment_updated', 'Appointment updated', 'Appointment #6 was updated', NULL, '2025-06-09 12:12:45'),
(10, 2, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:07'),
(11, 2, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:11'),
(12, 2, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:12'),
(13, 2, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:12'),
(14, 2, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:12'),
(15, 2, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:12'),
(16, 2, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:12'),
(17, 2, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:13'),
(18, 2, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:49'),
(19, 2, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:51'),
(20, 2, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:21:06'),
(21, 2, 'document_uploaded', 'Document uploaded', 'kjkjkj was uploaded', NULL, '2025-06-09 13:10:59'),
(22, 2, 'appointment_updated', 'Appointment updated', 'Appointment #6 was updated', NULL, '2025-06-09 16:59:01'),
(23, 2, 'payment_received', 'New invoice created', 'Invoice INV-20250609-473 created', NULL, '2025-06-09 17:01:45'),
(24, 2, 'payment_received', 'Payment received', 'Payment received for invoice #2', NULL, '2025-06-09 17:02:03'),
(25, 2, 'payment_received', 'Payment received', 'Payment received for invoice #2', NULL, '2025-06-09 17:02:03'),
(26, 2, 'payment_received', 'Payment received', 'Payment received for invoice #2', NULL, '2025-06-09 17:02:03'),
(27, 2, 'document_deleted', 'Document deleted', 'Document ID 1 was deleted', NULL, '2025-06-09 17:06:16'),
(28, 5, 'patient_added', 'New patient registered', 'Adam Bouzine was registered', NULL, '2025-06-13 09:45:56'),
(29, 5, 'appointment_scheduled', 'New appointment scheduled', 'Appointment scheduled for 2025-06-15 at 11:10', NULL, '2025-06-13 09:47:19'),
(30, 5, 'payment_received', 'Payment received', 'Payment received for invoice #3', NULL, '2025-06-13 09:50:12'),
(31, 5, 'payment_received', 'Payment received', 'Payment received for invoice #3', NULL, '2025-06-13 09:50:12'),
(32, 5, 'payment_received', 'Payment received', 'Payment received for invoice #3', NULL, '2025-06-13 09:50:12'),
(33, 5, 'document_uploaded', 'Document uploaded', 'mcd was uploaded', NULL, '2025-06-13 09:50:43'),
(34, 5, 'appointment_updated', 'Appointment updated', 'Appointment #7 was updated', NULL, '2025-06-13 09:54:56'),
(35, 5, 'appointment_scheduled', 'New appointment scheduled', 'Appointment scheduled for 2025-06-13 at 17:42', NULL, '2025-06-13 18:42:05'),
(36, 5, 'payment_received', 'New invoice created', 'Invoice INV-20250615-299 created', NULL, '2025-06-15 13:53:49'),
(37, 5, 'patient_added', 'New patient registered', 'Itsme Diplo was registered', NULL, '2025-06-17 21:27:51'),
(38, 5, 'appointment_scheduled', 'New appointment scheduled', 'Appointment scheduled for 2025-06-18 at 00:32', NULL, '2025-06-17 21:29:09'),
(39, 5, 'document_deleted', 'Document deleted', 'Document ID 2 was deleted', NULL, '2025-06-17 21:29:32'),
(40, 5, 'document_uploaded', 'Document uploaded', 'cvvb was uploaded', NULL, '2025-06-17 21:42:11'),
(41, 5, 'document_deleted', 'Document deleted', 'Document ID 3 was deleted', NULL, '2025-06-17 21:42:34'),
(42, 5, 'appointment_updated', 'Appointment updated', 'Appointment #9 was updated', NULL, '2025-06-17 21:43:00'),
(43, 5, 'appointment_updated', 'Appointment updated', 'Appointment #9 was updated', NULL, '2025-06-17 21:43:04'),
(44, 5, 'appointment_updated', 'Appointment updated', 'Appointment #9 was updated', NULL, '2025-06-17 21:43:10'),
(45, 5, 'appointment_updated', 'Appointment updated', 'Appointment #7 was updated', NULL, '2025-06-17 21:43:16'),
(46, 5, 'appointment_updated', 'Appointment updated', 'Appointment #7 was updated', NULL, '2025-06-17 21:43:21'),
(47, 5, 'patient_added', 'New patient registered', 'SAAD TALHI was registered', NULL, '2025-06-18 18:00:22'),
(48, 5, 'appointment_updated', 'Appointment updated', 'Appointment #8 was updated', NULL, '2025-06-18 18:00:33'),
(49, 5, 'appointment_updated', 'Appointment updated', 'Appointment #8 was updated', NULL, '2025-06-18 18:00:36'),
(50, 5, 'appointment_scheduled', 'New appointment scheduled', 'Appointment scheduled for 2025-06-20 at 20:05', NULL, '2025-06-18 18:03:13'),
(51, 5, 'appointment_updated', 'Appointment updated', 'Appointment #10 was updated', NULL, '2025-06-18 18:03:30'),
(52, 5, 'appointment_updated', 'Appointment updated', 'Appointment #10 was updated', NULL, '2025-06-18 18:03:35'),
(53, 5, 'appointment_updated', 'Appointment updated', 'Appointment #10 was updated', NULL, '2025-06-18 18:03:40'),
(54, 14, 'patient_added', 'New patient registered', 'saad Diplo was registered', NULL, '2025-06-18 21:16:39'),
(55, 14, 'appointment_scheduled', 'New appointment scheduled', 'Appointment scheduled for 2025-06-21 at 08:23', NULL, '2025-06-18 21:25:14'),
(56, 5, 'patient_added', 'New patient registered', 'Itsmex Diplo was registered', NULL, '2025-06-19 00:18:04'),
(57, 5, 'patient_added', 'New patient registered', 'Hichame Ait benalla was registered', NULL, '2025-06-19 00:18:18'),
(58, 5, 'patient_added', 'New patient registered', 'YACINE FETOUH was registered', NULL, '2025-06-19 00:25:30'),
(59, 5, 'appointment_scheduled', 'New appointment scheduled', 'Appointment scheduled for 2025-06-29 at 10:40', NULL, '2025-06-19 00:26:13'),
(60, 5, 'payment_received', 'New invoice created', 'Invoice INV-20250619-644 created', NULL, '2025-06-19 00:27:20'),
(61, 5, 'appointment_scheduled', 'New appointment scheduled', 'Appointment scheduled for 2025-06-28 at 10:36', NULL, '2025-06-19 00:28:14'),
(62, 5, 'payment_received', 'Payment received', 'Payment received for invoice #5', NULL, '2025-06-19 00:28:24'),
(63, 5, 'payment_received', 'Payment received', 'Payment received for invoice #5', NULL, '2025-06-19 00:28:24'),
(64, 5, 'payment_received', 'Payment received', 'Payment received for invoice #5', NULL, '2025-06-19 00:28:24'),
(65, 5, 'payment_received', 'Payment received', 'Payment received for invoice #4', NULL, '2025-06-19 00:28:27'),
(66, 5, 'payment_received', 'Payment received', 'Payment received for invoice #4', NULL, '2025-06-19 00:28:27'),
(67, 5, 'payment_received', 'Payment received', 'Payment received for invoice #4', NULL, '2025-06-19 00:28:27'),
(68, 5, 'appointment_updated', 'Appointment updated', 'Appointment #7 was updated', NULL, '2025-06-19 00:28:38'),
(69, 5, 'appointment_updated', 'Appointment updated', 'Appointment #7 was updated', NULL, '2025-06-19 00:28:42');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'Dentist who owns this appointment',
  `patient_id` int NOT NULL,
  `base_service_id` int NOT NULL,
  `dentist_id` int NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `status` enum('scheduled','confirmed','in_progress','completed','cancelled','no_show') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `reminder_sent` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `patient_id`, `base_service_id`, `dentist_id`, `appointment_date`, `appointment_time`, `duration`, `status`, `notes`, `reminder_sent`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 2, '2025-06-08', '09:00:00', 60, 'scheduled', NULL, 0, '2025-06-08 18:00:43', '2025-06-08 18:00:43'),
(2, 2, 2, 3, 2, '2025-06-08', '10:30:00', 45, 'scheduled', NULL, 0, '2025-06-08 18:00:43', '2025-06-08 18:00:43'),
(3, 2, 3, 8, 2, '2025-06-08', '14:00:00', 30, 'scheduled', NULL, 0, '2025-06-08 18:00:43', '2025-06-08 18:00:43'),
(5, 2, 1, 3, 2, '2025-06-11', '17:59:00', 45, 'scheduled', '', 0, '2025-06-09 10:41:49', '2025-06-09 10:41:49'),
(6, 2, 5, 3, 2, '2025-06-09', '14:15:00', 45, 'completed', '', 0, '2025-06-09 11:12:32', '2025-06-09 15:59:01'),
(7, 5, 6, 7, 5, '2025-06-15', '11:10:00', 60, 'completed', '', 0, '2025-06-13 08:47:19', '2025-06-19 00:28:42'),
(16, 5, 38, 7, 5, '2025-06-29', '10:40:00', 60, 'scheduled', '', 0, '2025-06-19 00:26:13', '2025-06-19 00:26:13'),
(17, 5, 38, 6, 5, '2025-06-28', '10:36:00', 30, 'scheduled', '', 0, '2025-06-19 00:28:14', '2025-06-19 00:28:14');

-- --------------------------------------------------------

--
-- Table structure for table `base_services`
--

CREATE TABLE `base_services` (
  `id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `duration` int NOT NULL COMMENT 'Duration in minutes',
  `requires_tooth_selection` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `base_services`
--

INSERT INTO `base_services` (`id`, `category_id`, `name`, `description`, `duration`, `requires_tooth_selection`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Regular Cleaning', 'Professional dental cleaning and examination', 60, 0, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(2, 1, 'Deep Cleaning', 'Scaling and root planing', 90, 0, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(3, 2, 'Composite Filling', 'Tooth-colored filling', 45, 1, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(4, 2, 'Dental Crown', 'Porcelain crown placement', 90, 1, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(5, 2, 'Root Canal Treatment', 'Endodontic therapy', 120, 1, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(6, 4, 'Tooth Extraction', 'Simple tooth extraction', 30, 0, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(7, 3, 'Teeth Whitening', 'Professional whitening treatment', 60, 0, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(8, 1, 'Consultation', 'Initial examination and consultation', 30, 0, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58');

-- --------------------------------------------------------

--
-- Table structure for table `dentist_service_prices`
--

CREATE TABLE `dentist_service_prices` (
  `id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'Dentist who sets this price',
  `base_service_id` int NOT NULL COMMENT 'Reference to base service',
  `price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dentist_service_prices`
--

INSERT INTO `dentist_service_prices` (`id`, `user_id`, `base_service_id`, `price`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 150.00, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(2, 2, 2, 300.00, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(3, 2, 3, 200.00, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(4, 2, 4, 1200.00, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(5, 2, 5, 800.00, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(6, 2, 6, 240.00, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(7, 2, 7, 400.00, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(8, 2, 8, 75.00, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58'),
(9, 5, 7, 55.00, 1, '2025-06-17 21:38:30', '2025-06-17 21:38:30'),
(10, 5, 6, 552.00, 1, '2025-06-17 21:38:33', '2025-06-17 21:38:33'),
(11, 5, 8, 345.00, 1, '2025-06-17 21:38:35', '2025-06-17 21:38:35'),
(12, 5, 2, 355.00, 1, '2025-06-17 21:38:37', '2025-06-17 21:38:37'),
(13, 5, 1, 342.00, 1, '2025-06-17 21:38:39', '2025-06-17 21:38:39'),
(14, 5, 3, 123.00, 1, '2025-06-17 21:38:40', '2025-06-17 21:38:40'),
(15, 5, 4, 234.00, 1, '2025-06-17 21:38:42', '2025-06-17 21:38:42'),
(16, 5, 5, 444.00, 1, '2025-06-17 21:38:44', '2025-06-17 21:38:44'),
(17, 14, 7, 150.00, 1, '2025-06-18 21:28:58', '2025-06-18 21:28:58'),
(18, 14, 6, 200.00, 1, '2025-06-18 21:29:04', '2025-06-18 21:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'Dentist who owns this document',
  `patient_id` int DEFAULT NULL,
  `appointment_id` int DEFAULT NULL,
  `type` enum('prescription','xray','lab_result','consent_form','treatment_plan','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `global_settings`
--

CREATE TABLE `global_settings` (
  `id` int NOT NULL,
  `smtp_settings` json DEFAULT NULL,
  `sms_provider_settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'Dentist who owns this invoice',
  `patient_id` int NOT NULL,
  `appointment_id` int DEFAULT NULL,
  `base_service_id` int DEFAULT NULL,
  `invoice_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT '0.00',
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('draft','sent','paid','overdue','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `due_date` date DEFAULT NULL,
  `paid_date` timestamp NULL DEFAULT NULL,
  `payment_method` enum('cash','credit_card','bank_transfer','insurance','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `user_id`, `patient_id`, `appointment_id`, `base_service_id`, `invoice_number`, `description`, `quantity`, `unit_price`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `status`, `due_date`, `paid_date`, `payment_method`, `notes`, `created_at`, `updated_at`) VALUES
(2, 2, 5, NULL, 3, 'INV-20250609-473', 'Tooth-colored filling', 1, 200.00, 200.00, 0.00, 0.00, 200.00, 'paid', '2025-06-11', '2025-06-09 16:02:03', 'insurance', '', '2025-06-09 17:01:45', '2025-06-09 17:02:03'),
(4, 5, 6, NULL, NULL, 'INV-20250615-299', 'Professional whitening treatment', 1, 0.00, 359.00, 0.00, 0.00, 359.00, 'paid', '2025-06-20', '2025-06-18 23:28:27', 'cash', '', '2025-06-15 13:53:49', '2025-06-19 00:28:27'),
(5, 5, 38, NULL, NULL, 'INV-20250619-644', 'Simple tooth extraction', 1, 0.00, 552.00, 0.00, 0.00, 552.00, 'paid', '2025-06-22', '2025-06-18 23:28:24', 'cash', '', '2025-06-19 00:27:20', '2025-06-19 00:28:24');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `service_id` int DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `service_id`, `description`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 4, 7, 'Professional whitening treatment', 1, 200.00, 200.00),
(2, 4, 6, 'Simple tooth extraction', 1, 159.00, 159.00),
(3, 5, 6, 'Simple tooth extraction', 1, 552.00, 552.00);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `patient_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `emergency_contact_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medical_history` text COLLATE utf8mb4_unicode_ci,
  `allergies` text COLLATE utf8mb4_unicode_ci,
  `insurance_provider` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `insurance_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `patient_number`, `first_name`, `last_name`, `email`, `phone`, `date_of_birth`, `gender`, `address`, `emergency_contact_name`, `emergency_contact_phone`, `medical_history`, `allergies`, `insurance_provider`, `insurance_number`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'P001', 'Ahmed', 'Benali', 'ahmed.benali@email.com', '+212-661-123456', '1985-06-15', 'male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(2, 2, 'P002', 'Fatima', 'Alaoui', 'fatima.alaoui@email.com', '+212-662-234567', '1990-03-22', 'female', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', '2025-06-08 19:00:43', '2025-06-09 11:48:12'),
(3, 2, 'P003', 'Omar', 'Tazi', 'omar.tazi@email.com', '+212-663-345678', '1978-11-08', 'male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(5, 2, 'P0005', 'LEA MAELYS', 'NIEZ', 'midelt-city2021@solarunited.net', '0631318173', '1991-05-09', 'female', '17 Rue Saint-Jean\r\nLANGON', NULL, NULL, '', NULL, NULL, NULL, 'active', '2025-06-09 11:48:48', '2025-06-09 11:48:48'),
(6, 5, 'P00001', 'Adam', 'Bouzine', 'adam@gmail.com', '099828829', '2005-09-01', 'male', '', NULL, NULL, '', NULL, NULL, NULL, 'active', '2025-06-13 09:45:56', '2025-06-18 21:16:38'),
(9, 5, 'P0003', 'SAAD', 'TALHI', 'saad@gmail.com', '0600908909', '2025-06-26', 'male', '3 Allen Street', NULL, NULL, '', NULL, NULL, NULL, 'active', '2025-06-18 18:00:22', '2025-06-18 18:00:22'),
(38, 5, 'P005', 'Hichame', 'Ait benalla', 'midelt-city2021@solarunited.net', '0631318173', '2025-06-21', 'male', '45 NR 45 MILOUIYA TADAOUT', NULL, NULL, '', NULL, NULL, NULL, 'active', '2025-06-19 00:18:18', '2025-06-19 00:18:18'),
(40, 5, 'P522062025694', 'YACINE', 'FETOUH', 'yacine.fetouh@cd4key.com', '0777855102', '2025-06-22', 'male', '142 RUE MENILMONTANT', NULL, NULL, '', NULL, NULL, NULL, 'active', '2025-06-19 00:25:30', '2025-06-19 00:25:30');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'Dentist who owns this category',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `user_id`, `name`, `description`, `created_at`) VALUES
(1, 2, 'Preventive Care', 'Regular checkups and cleanings', '2025-06-08 19:00:43'),
(2, 2, 'Restorative', 'Fillings, crowns, and repairs', '2025-06-08 19:00:43'),
(3, 2, 'Cosmetic', 'Whitening and aesthetic procedures', '2025-06-08 19:00:43'),
(4, 2, 'Oral Surgery', 'Extractions and surgical procedures', '2025-06-08 19:00:43'),
(5, 2, 'Orthodontics', 'Braces and alignment treatments', '2025-06-08 19:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'Dentist who owns these settings',
  `clinic_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clinic_address` text COLLATE utf8mb4_unicode_ci,
  `clinic_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_logo_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_description` text COLLATE utf8mb4_unicode_ci,
  `working_hours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `other_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `automation_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `presentation` text COLLATE utf8mb4_unicode_ci,
  `certifications` json DEFAULT NULL,
  `experience` json DEFAULT NULL,
  `languages_spoken` json DEFAULT NULL
) ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `user_id`, `clinic_name`, `clinic_address`, `clinic_phone`, `clinic_email`, `clinic_website`, `clinic_logo_url`, `clinic_description`, `working_hours`, `other_settings`, `created_at`, `updated_at`, `automation_settings`, `presentation`, `certifications`, `experience`, `languages_spoken`) VALUES
(1, 2, 'SmileDesk Demo Clinic', '123 Dental Street, Casablanca, Morocco', '+212-522-123456', 'info@smiledesk-demo.com', NULL, NULL, NULL, NULL, NULL, '2025-06-08 19:00:43', '2025-06-08 19:00:43', NULL, NULL, NULL, NULL, NULL),
(2, 5, 'Dr Mohammed Salmi', '45 RUE MELOUIA TADAOUT', '0631318173', 'its.aitbenalla.hichame@gmail.com', 'http://localhost/profile.php?id=5', './uploads/clinic_logo_5_1749994621.png', 'HI', '{\"monday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"tuesday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"wednesday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"thursday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"friday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"saturday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":true},\"sunday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":true}}', NULL, '2025-06-13 09:44:13', '2025-06-19 00:34:53', '{\"send_email_enabled\":true,\"send_sms_enabled\":true,\"send_whatsapp_enabled\":false,\"receive_email_enabled\":false,\"receive_sms_enabled\":false,\"receive_whatsapp_enabled\":false,\"sms_reminder_time\":\"24\",\"email_notifications_enabled\":false,\"email_appointment_confirmation\":false,\"email_appointment_reminder\":false,\"email_payment_receipt\":false,\"email_treatment_summary\":false,\"email_custom_template\":false}', '', '[]', NULL, '[]'),
(3, 4, 'DR slime', '17 Rue Saint-Jean, LANGON, LANGON, LANGON\r\nLANGON', '0631318173', 'ipmymsbc@uniromax.com', '', '/uploads/clinic_logo_4_1749994349.jpg', '', '{\"monday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"tuesday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"wednesday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"thursday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"friday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"saturday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"sunday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false}}', NULL, '2025-06-13 22:41:31', '2025-06-15 13:32:29', '{\"send_email_enabled\":true,\"send_sms_enabled\":false,\"send_whatsapp_enabled\":false,\"receive_email_enabled\":false,\"receive_sms_enabled\":false,\"receive_whatsapp_enabled\":false,\"sms_reminder_time\":\"24\",\"email_notifications_enabled\":false,\"email_appointment_confirmation\":false,\"email_appointment_reminder\":false,\"email_payment_receipt\":false,\"email_treatment_summary\":false,\"email_custom_template\":false}', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL COMMENT 'Dentist who purchased the subscription',
  `plan_id` int NOT NULL COMMENT 'Reference to subscription plan',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','expired','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `plan_id`, `start_date`, `end_date`, `status`, `payment_method`, `transaction_id`, `created_at`, `updated_at`, `price`) VALUES
(1, 10, 1, '2025-06-15', '2025-07-15', 'active', 'stripe', 'pi_3RaFKzRuYmOMaUOh00sHcxBt', '2025-06-15 12:07:17', '2025-06-17 21:25:52', 149.00),
(2, 11, 2, '2025-06-17', '2025-07-17', 'active', 'stripe', 'pi_3Rb6RmRuYmOMaUOh0wR2KUWV', '2025-06-17 20:50:33', '2025-06-17 21:25:59', 249.00),
(3, 13, 1, '2025-06-18', '2025-07-18', 'active', 'stripe', 'pi_3RbPPzRuYmOMaUOh1S3WbLbe', '2025-06-18 17:06:08', '2025-06-18 17:06:08', 0.00),
(4, 14, 2, '2025-06-18', '2025-07-18', 'active', 'stripe', 'pi_3RbTEhRuYmOMaUOh1q6Ja6On', '2025-06-18 21:09:54', '2025-06-18 21:09:54', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `duration_months` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `name`, `description`, `duration_months`, `price`, `features`, `is_active`, `created_at`) VALUES
(1, 'Plan Essentiel', 'Idéal pour les dentistes indépendants qui veulent digitaliser les bases.', 1, 159.85, '[\"Prise de rendez-vous en ligne\",\"Cr\\u00e9ation et envoi de factures PDF\",\"Rappels automatiques\",\"Fiche patient (historique de soins)\",\"Stockage s\\u00e9curis\\u00e9 des documents\",\"Impression de factures et ordonnances\",\"Tableau de bord simplifi\\u00e9\",\"Support client en fran\\u00e7ais & darija\"]', 1, '2025-06-12 09:32:50'),
(2, 'Plan Pro', 'Pour les cabinets qui veulent automatiser davantage et suivre leurs performances.', 1, 249.00, '[\r\n  \"Prise de rendez-vous en ligne\",\r\n  \"Création et envoi de factures PDF\",\r\n  \"Rappels automatiques\", \r\n  \"Fiche patient (historique de soins)\",\r\n  \"Stockage sécurisé des documents\",\r\n  \"Impression de factures et ordonnances\",\r\n  \"Tableau de bord simplifié\",\r\n  \"Support client en français & darija\",\r\n  \"Gestion avancée du calendrier\",\r\n  \"Suivi des paiements\",\r\n  \"Statistiques détaillées\",\r\n  \"Suggestions intelligentes\",\r\n  \"Gestion des tarifs et remises\",\r\n  \"Accès multi-utilisateurs\",\r\n  \"Export Excel / PDF complet\"\r\n]', 1, '2025-06-12 09:32:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','dentist','assistant','receptionist') COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialization` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `clinic_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `role`, `phone`, `address`, `specialization`, `license_number`, `status`, `last_login`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 'admin@smiledesk.com', '$2y$10$Hg6/aBhuYKAaUxh06HN68eoPH0wQ7lwjaWE06v8X78MGN6UWxfsl.', 'Admin', 'User', 'admin', NULL, NULL, NULL, NULL, 'active', NULL, '2025-06-08 19:00:43', '2025-06-08 19:13:30', NULL),
(2, 'dr.smith@smiledesk.com', '$2y$10$Hg6/aBhuYKAaUxh06HN68eoPH0wQ7lwjaWE06v8X78MGN6UWxfsl.', 'John', 'Smith', 'dentist', NULL, NULL, NULL, NULL, 'active', NULL, '2025-06-08 19:00:43', '2025-06-08 19:13:27', NULL),
(3, 'assistant@smiledesk.com', '$2y$10$Hg6/aBhuYKAaUxh06HN68eoPH0wQ7lwjaWE06v8X78MGN6UWxfsl.', 'Sarah', 'Johnson', 'assistant', NULL, NULL, NULL, NULL, 'active', NULL, '2025-06-08 19:00:43', '2025-06-08 19:13:23', NULL),
(4, 'its.mediplo0aer@gmail.com', '$2y$10$Hg6/aBhuYKAaUxh06HN68eoPH0wQ7lwjaWE06v8X78MGN6UWxfsl.', 'Itsme', 'Diplo', 'admin', '8568748423', NULL, NULL, NULL, 'active', '2025-06-18 21:31:11', '2025-06-08 19:06:29', '2025-06-18 21:31:11', NULL),
(5, 'its.aitbenalla.hichame@gmail.com', '$2y$10$KgGCY/eyN8milJhczZoIWO.JOi7d.mTr8L/R.9/qqnsLDP.MCWSh.', 'Hichame', 'Ait benalla', 'dentist', NULL, '45 RUE MELOUIA TADAOUT\nMidelt, 93150', NULL, NULL, 'active', '2025-06-19 00:14:13', '2025-06-13 09:36:59', '2025-06-19 00:14:13', NULL),
(10, 'cdkeycom112@cd4key.com', '$2y$10$4BgDyiUY4BLjETm87vykh.3NmmqzK2Yvsv8i2O.fOxBBJg.pTmskm', 'admin', 'Diplo', 'dentist', NULL, 'Barbara Ave 41a\n10\nSolana Beach, 92075', NULL, NULL, 'active', NULL, '2025-06-15 12:06:55', '2025-06-15 12:07:17', NULL),
(11, 'joselascorzhtgyt@yahoo.com', '$2y$10$Mf.IrCTgtixq1uSLv0p4EeTf1W1zHoJLzE7m8ezX84GNAoI0YCpsW', 'joselascorz', 'Nizee', 'dentist', NULL, '17 Rue Saint-Jean\nLANGON\nLangon, 33210', NULL, NULL, 'active', NULL, '2025-06-17 20:49:32', '2025-06-17 20:53:33', NULL),
(12, 'midelt-city2021@solarunited.net', '$2y$10$cvAzIsnmxw8CIEVImhsFxOaOrFNEn5Z/YrnKWiPNGAVUSo5GS683W', 'its itbenal', '', 'dentist', NULL, NULL, NULL, NULL, 'inactive', NULL, '2025-06-17 21:52:24', '2025-06-17 21:52:24', NULL),
(13, 'ipmymsbc@uniromax.com', '$2y$10$RFmohhvhws3XomFTxfQZm.kLUNr12fwYCTK6pEHEUsYS7lYujbZbm', 'YACINE', 'FETOUH', 'dentist', NULL, '142 RUE MENILMONTANT\nPARIS, 75020', NULL, NULL, 'active', NULL, '2025-06-18 17:02:21', '2025-06-18 17:06:09', NULL),
(14, 'elcqdi@gg.com', '$2y$10$HfUb6faZOsf426jPXADUGerdfmrzBWTxVACiE8zEaIEG5tNtl2/ci', 'mohammad', 'Diplo', 'dentist', NULL, 'Barbara Ave 41a\n10\nSolana Beach, 92075', NULL, NULL, 'active', NULL, '2025-06-18 21:09:33', '2025-06-18 21:09:55', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `service_id` (`base_service_id`),
  ADD KEY `dentist_id` (`dentist_id`);

--
-- Indexes for table `base_services`
--
ALTER TABLE `base_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `dentist_service_prices`
--
ALTER TABLE `dentist_service_prices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_service_unique` (`user_id`,`base_service_id`),
  ADD KEY `base_service_id` (`base_service_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `global_settings`
--
ALTER TABLE `global_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `service_id` (`base_service_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_number` (`patient_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `users_ibfk_2` (`clinic_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `base_services`
--
ALTER TABLE `base_services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dentist_service_prices`
--
ALTER TABLE `dentist_service_prices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `global_settings`
--
ALTER TABLE `global_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`base_service_id`) REFERENCES `base_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_4` FOREIGN KEY (`dentist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `base_services`
--
ALTER TABLE `base_services`
  ADD CONSTRAINT `base_services_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dentist_service_prices`
--
ALTER TABLE `dentist_service_prices`
  ADD CONSTRAINT `dentist_service_prices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dentist_service_prices_ibfk_2` FOREIGN KEY (`base_service_id`) REFERENCES `base_services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_3` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_4` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_ibfk_3` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_ibfk_4` FOREIGN KEY (`base_service_id`) REFERENCES `base_services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `base_services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD CONSTRAINT `service_categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`clinic_id`) REFERENCES `settings` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
