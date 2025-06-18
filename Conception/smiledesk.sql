-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2025 at 11:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Dentist who performed the activity',
  `type` enum('appointment_scheduled','appointment_updated','appointment_cancelled','patient_added','patient_updated','patient_deleted','payment_received','document_uploaded','document_deleted') DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(34, 5, 'appointment_updated', 'Appointment updated', 'Appointment #7 was updated', NULL, '2025-06-13 09:54:56');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Dentist who owns this appointment',
  `patient_id` int(11) NOT NULL,
  `base_service_id` int(11) NOT NULL,
  `dentist_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `duration` int(11) NOT NULL,
  `selected_teeth` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`selected_teeth`)),
  `status` enum('scheduled','confirmed','in_progress','completed','cancelled','no_show') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `reminder_sent` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `patient_id`, `base_service_id`, `dentist_id`, `appointment_date`, `appointment_time`, `duration`, `selected_teeth`, `status`, `notes`, `reminder_sent`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 2, '2025-06-08', '09:00:00', 60, NULL, 'scheduled', NULL, 0, '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(2, 2, 2, 3, 2, '2025-06-08', '10:30:00', 45, NULL, 'scheduled', NULL, 0, '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(3, 2, 3, 8, 2, '2025-06-08', '14:00:00', 30, NULL, 'scheduled', NULL, 0, '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(4, 2, 4, 1, 2, '2025-06-09', '09:00:00', 60, NULL, 'completed', NULL, 0, '2025-06-08 19:00:43', '2025-06-09 12:10:48'),
(5, 2, 1, 3, 2, '2025-06-11', '17:59:00', 45, NULL, 'scheduled', '', 0, '2025-06-09 11:41:49', '2025-06-09 11:41:49'),
(6, 2, 5, 3, 2, '2025-06-09', '14:15:00', 45, NULL, 'completed', '', 0, '2025-06-09 12:12:32', '2025-06-09 16:59:01'),
(7, 5, 6, 7, 5, '2025-06-15', '11:10:00', 60, NULL, 'confirmed', '', 0, '2025-06-13 09:47:19', '2025-06-13 09:54:56');

-- --------------------------------------------------------

--
-- Table structure for table `base_services`
--

CREATE TABLE `base_services` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) NOT NULL COMMENT 'Duration in minutes',
  `requires_tooth_selection` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Dentist who sets this price',
  `base_service_id` int(11) NOT NULL COMMENT 'Reference to base service',
  `price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
(8, 2, 8, 75.00, 1, '2025-06-12 09:26:58', '2025-06-12 09:26:58');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Dentist who owns this document',
  `patient_id` int(11) DEFAULT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `type` enum('prescription','xray','lab_result','consent_form','treatment_plan','other') DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `user_id`, `patient_id`, `appointment_id`, `type`, `title`, `description`, `file_path`, `file_size`, `mime_type`, `uploaded_by`, `created_at`) VALUES
(2, 5, NULL, NULL, 'other', 'mcd', '', 'uploads/documents/684bf473d9428.pdf', 129821, 'application/pdf', 5, '2025-06-13 09:50:43');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Dentist who owns this invoice',
  `patient_id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `base_service_id` int(11) DEFAULT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('draft','sent','paid','overdue','cancelled') DEFAULT 'draft',
  `due_date` date DEFAULT NULL,
  `paid_date` timestamp NULL DEFAULT NULL,
  `payment_method` enum('cash','credit_card','bank_transfer','insurance','other') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `user_id`, `patient_id`, `appointment_id`, `base_service_id`, `invoice_number`, `description`, `quantity`, `unit_price`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `status`, `due_date`, `paid_date`, `payment_method`, `notes`, `created_at`, `updated_at`) VALUES
(2, 2, 5, NULL, 3, 'INV-20250609-473', 'Tooth-colored filling', 1, 200.00, 200.00, 0.00, 0.00, 200.00, 'paid', '2025-06-11', '2025-06-09 16:02:03', 'insurance', '', '2025-06-09 17:01:45', '2025-06-09 17:02:03'),
(3, 5, 6, NULL, NULL, 'INV-20250613-993', '', 1, 0.00, 669.00, 0.00, 0.00, 669.00, 'paid', '2025-06-20', '2025-06-13 08:50:12', 'cash', '', '2025-06-13 09:48:03', '2025-06-13 09:50:12');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `patient_number` varchar(20) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `insurance_provider` varchar(100) DEFAULT NULL,
  `insurance_number` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `patient_number`, `first_name`, `last_name`, `email`, `phone`, `date_of_birth`, `gender`, `address`, `emergency_contact_name`, `emergency_contact_phone`, `medical_history`, `allergies`, `insurance_provider`, `insurance_number`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'P001', 'Ahmed', 'Benali', 'ahmed.benali@email.com', '+212-661-123456', '1985-06-15', 'male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(2, 2, 'P002', 'Fatima', 'Alaoui', 'fatima.alaoui@email.com', '+212-662-234567', '1990-03-22', 'female', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', '2025-06-08 19:00:43', '2025-06-09 11:48:12'),
(3, 2, 'P003', 'Omar', 'Tazi', 'omar.tazi@email.com', '+212-663-345678', '1978-11-08', 'male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(4, 2, 'P004', 'Aicha', 'Mansouri', 'aicha.mansouri@email.com', '+212-664-456789', '1995-09-12', 'female', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(5, 2, 'P0005', 'LEA MAELYS', 'NIEZ', 'midelt-city2021@solarunited.net', '0631318173', '1991-05-09', 'female', '17 Rue Saint-Jean\r\nLANGON', NULL, NULL, '', NULL, NULL, NULL, 'active', '2025-06-09 11:48:48', '2025-06-09 11:48:48'),
(6, 5, 'P0001', 'Adam', 'Bouzine', 'adam@gmail.com', '099828829', '2005-09-01', 'male', '', NULL, NULL, '', NULL, NULL, NULL, 'active', '2025-06-13 09:45:56', '2025-06-13 09:45:56');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Dentist who owns this category',
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
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
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Dentist who owns these settings',
  `clinic_name` varchar(255) NOT NULL,
  `clinic_address` text DEFAULT NULL,
  `clinic_phone` varchar(20) DEFAULT NULL,
  `clinic_email` varchar(100) DEFAULT NULL,
  `clinic_website` varchar(255) DEFAULT NULL,
  `clinic_logo_url` varchar(500) DEFAULT NULL,
  `clinic_description` text DEFAULT NULL,
  `working_hours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`working_hours`)),
  `other_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`other_settings`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `automation_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`automation_settings`)),
  `smtp_settings` text DEFAULT NULL,
  `sms_provider_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sms_provider_settings`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `user_id`, `clinic_name`, `clinic_address`, `clinic_phone`, `clinic_email`, `clinic_website`, `clinic_logo_url`, `clinic_description`, `working_hours`, `other_settings`, `created_at`, `updated_at`, `automation_settings`, `smtp_settings`, `sms_provider_settings`) VALUES
(1, 2, 'SmileDesk Demo Clinic', '123 Dental Street, Casablanca, Morocco', '+212-522-123456', 'info@smiledesk-demo.com', NULL, NULL, NULL, NULL, NULL, '2025-06-08 19:00:43', '2025-06-08 19:00:43', NULL, NULL, NULL),
(2, 5, 'Dr Mohammed Salmi', '45 RUE MELOUIA TADAOUT', '0631318173', 'its.aitbenalla.hichame@gmail.com', '', '', '', '{\"monday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"tuesday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"wednesday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"thursday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"friday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"saturday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"sunday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false}}', NULL, '2025-06-13 09:44:13', '2025-06-16 08:47:31', '{\"send_email_enabled\":true,\"send_sms_enabled\":true,\"send_whatsapp_enabled\":true,\"receive_email_enabled\":false,\"receive_sms_enabled\":false,\"receive_whatsapp_enabled\":false,\"sms_reminder_time\":\"24\",\"email_notifications_enabled\":false,\"email_appointment_confirmation\":false,\"email_appointment_reminder\":false,\"email_payment_receipt\":false,\"email_treatment_summary\":false,\"email_custom_template\":false}', NULL, NULL),
(3, 4, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-16 08:35:26', '2025-06-16 08:36:00', NULL, '{\"host\":\"smtp.hostinger.com\",\"port\":\"587\",\"username\":\"SmileDesk\",\"password\":\"Diplo@3334\",\"encryption\":\"tls\"}', '{\"provider\":\"twilio\",\"twilio_account_sid\":\"AA132423245RZER45\",\"twilio_auth_token\":\"JTOIAZIREAZR923583285J2RFJEJO42RTO42GJ4O\",\"twilio_from_number\":\"+2125600012\"}');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Dentist who purchased the subscription',
  `plan_id` int(11) NOT NULL COMMENT 'Reference to subscription plan',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','expired','cancelled') DEFAULT 'active',
  `payment_method` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `plan_id`, `start_date`, `end_date`, `status`, `payment_method`, `transaction_id`, `created_at`, `updated_at`) VALUES
(1, 5, 1, '2025-06-16', '2025-07-16', 'active', 'stripe', 'pi_3RaYhrRuYmOMaUOh1n2MwO9M', '2025-06-16 08:49:08', '2025-06-16 08:49:08');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `duration_months` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `name`, `description`, `duration_months`, `price`, `features`, `is_active`, `created_at`) VALUES
(1, 'Plan Essentiel', 'Idéal pour les dentistes indépendants qui veulent digitaliser les bases.', 1, 149.00, '[\r\n  \"Prise de rendez-vous en ligne\",\r\n  \"Création et envoi de factures PDF\", \r\n  \"Rappels automatiques\",\r\n  \"Fiche patient (historique de soins)\",\r\n  \"Stockage sécurisé des documents\",\r\n  \"Impression de factures et ordonnances\",\r\n  \"Tableau de bord simplifié\",\r\n  \"Support client en français & darija\"\r\n]', 1, '2025-06-12 09:32:50'),
(2, 'Plan Pro', 'Pour les cabinets qui veulent automatiser davantage et suivre leurs performances.', 1, 249.00, '[\r\n  \"Prise de rendez-vous en ligne\",\r\n  \"Création et envoi de factures PDF\",\r\n  \"Rappels automatiques\", \r\n  \"Fiche patient (historique de soins)\",\r\n  \"Stockage sécurisé des documents\",\r\n  \"Impression de factures et ordonnances\",\r\n  \"Tableau de bord simplifié\",\r\n  \"Support client en français & darija\",\r\n  \"Gestion avancée du calendrier\",\r\n  \"Suivi des paiements\",\r\n  \"Statistiques détaillées\",\r\n  \"Suggestions intelligentes\",\r\n  \"Gestion des tarifs et remises\",\r\n  \"Accès multi-utilisateurs\",\r\n  \"Export Excel / PDF complet\"\r\n]', 1, '2025-06-12 09:32:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `role` enum('admin','dentist','assistant','receptionist') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `clinic_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `role`, `phone`, `address`, `specialization`, `license_number`, `status`, `last_login`, `created_at`, `updated_at`, `clinic_id`) VALUES
(1, 'admin@smiledesk.com', '$2y$10$Hg6/aBhuYKAaUxh06HN68eoPH0wQ7lwjaWE06v8X78MGN6UWxfsl.', 'Admin', 'User', 'admin', NULL, NULL, NULL, NULL, 'active', NULL, '2025-06-08 19:00:43', '2025-06-08 19:13:30', NULL),
(2, 'dr.smith@smiledesk.com', '$2y$10$Hg6/aBhuYKAaUxh06HN68eoPH0wQ7lwjaWE06v8X78MGN6UWxfsl.', 'John', 'Smith', 'dentist', NULL, NULL, NULL, NULL, 'active', NULL, '2025-06-08 19:00:43', '2025-06-08 19:13:27', NULL),
(3, 'assistant@smiledesk.com', '$2y$10$Hg6/aBhuYKAaUxh06HN68eoPH0wQ7lwjaWE06v8X78MGN6UWxfsl.', 'Sarah', 'Johnson', 'assistant', NULL, NULL, NULL, NULL, 'active', NULL, '2025-06-08 19:00:43', '2025-06-08 19:13:23', NULL),
(4, 'its.mediplo0aer@gmail.com', '$2y$10$Hg6/aBhuYKAaUxh06HN68eoPH0wQ7lwjaWE06v8X78MGN6UWxfsl.', 'Itsme', 'Diplo', 'admin', '8568748423', NULL, NULL, NULL, 'active', '2025-06-16 08:49:32', '2025-06-08 19:06:29', '2025-06-16 08:49:32', NULL),
(5, 'its.aitbenalla.hichame@gmail.com', '$2y$10$KgGCY/eyN8milJhczZoIWO.JOi7d.mTr8L/R.9/qqnsLDP.MCWSh.', 'Hichame Ait benalla', 'Ait benalla', 'dentist', NULL, '45 RUE MELOUIA TADAOUT\nMidelt, 93150', NULL, NULL, 'active', '2025-06-16 08:45:39', '2025-06-13 09:36:59', '2025-06-16 08:49:07', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `base_services`
--
ALTER TABLE `base_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dentist_service_prices`
--
ALTER TABLE `dentist_service_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
