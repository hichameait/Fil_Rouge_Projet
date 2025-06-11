

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
  `clinic_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `type` enum('appointment_scheduled','appointment_updated','appointment_cancelled','patient_added','patient_updated','patient_deleted','payment_received','document_uploaded','document_deleted') DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `clinic_id`, `user_id`, `type`, `title`, `description`, `metadata`, `created_at`) VALUES
(1, 1, NULL, 'patient_added', 'New patient registered', 'Ahmed Benali completed registration', NULL, '2025-06-08 19:00:43'),
(2, 1, NULL, 'appointment_scheduled', 'Appointment scheduled', 'Cleaning appointment for Fatima Alaoui', NULL, '2025-06-08 19:00:43'),
(3, 1, NULL, 'payment_received', 'Payment received', 'Omar Tazi paid for consultation', NULL, '2025-06-08 19:00:43'),
(4, 1, NULL, 'appointment_scheduled', 'New appointment scheduled', 'Appointment scheduled for 2025-06-11 at 17:59', NULL, '2025-06-09 11:41:49'),
(5, 1, NULL, 'payment_received', 'New invoice created', 'Invoice INV-20250609-232 created', NULL, '2025-06-09 11:47:32'),
(6, 1, NULL, 'patient_added', 'New patient registered', 'LEA MAELYS NIEZ was registered', NULL, '2025-06-09 11:48:48'),
(7, 1, NULL, 'appointment_updated', 'Appointment updated', 'Appointment #4 was updated', NULL, '2025-06-09 12:10:48'),
(8, 1, NULL, 'appointment_scheduled', 'New appointment scheduled', 'Appointment scheduled for 2025-06-09 at 14:15', NULL, '2025-06-09 12:12:32'),
(9, 1, NULL, 'appointment_updated', 'Appointment updated', 'Appointment #6 was updated', NULL, '2025-06-09 12:12:45'),
(10, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:07'),
(11, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:11'),
(12, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:12'),
(13, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:12'),
(14, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:12'),
(15, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:12'),
(16, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:12'),
(17, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:13'),
(18, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:49'),
(19, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:18:51'),
(20, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #1', NULL, '2025-06-09 12:21:06'),
(21, 1, NULL, 'document_uploaded', 'Document uploaded', 'kjkjkj was uploaded', NULL, '2025-06-09 13:10:59'),
(22, 1, NULL, 'appointment_updated', 'Appointment updated', 'Appointment #6 was updated', NULL, '2025-06-09 16:59:01'),
(23, 1, NULL, 'payment_received', 'New invoice created', 'Invoice INV-20250609-473 created', NULL, '2025-06-09 17:01:45'),
(24, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #2', NULL, '2025-06-09 17:02:03'),
(25, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #2', NULL, '2025-06-09 17:02:03'),
(26, 1, NULL, 'payment_received', 'Payment received', 'Payment received for invoice #2', NULL, '2025-06-09 17:02:03'),
(27, 1, NULL, 'document_deleted', 'Document deleted', 'Document ID 1 was deleted', NULL, '2025-06-09 17:06:16');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int NOT NULL,
  `clinic_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `service_id` int NOT NULL,
  `dentist_id` int NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `duration` int NOT NULL,
  `selected_teeth` json DEFAULT NULL,
  `status` enum('scheduled','confirmed','in_progress','completed','cancelled','no_show') DEFAULT NULL,
  `notes` text,
  `reminder_sent` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `clinic_id`, `patient_id`, `service_id`, `dentist_id`, `appointment_date`, `appointment_time`, `duration`, `selected_teeth`, `status`, `notes`, `reminder_sent`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 2, '2025-06-08', '09:00:00', 60, NULL, 'scheduled', NULL, 0, '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(2, 1, 2, 3, 2, '2025-06-08', '10:30:00', 45, NULL, 'scheduled', NULL, 0, '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(3, 1, 3, 8, 2, '2025-06-08', '14:00:00', 30, NULL, 'scheduled', NULL, 0, '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(4, 1, 4, 1, 2, '2025-06-09', '09:00:00', 60, NULL, 'completed', NULL, 0, '2025-06-08 19:00:43', '2025-06-09 12:10:48'),
(5, 1, 1, 3, 2, '2025-06-11', '17:59:00', 45, NULL, 'scheduled', '', 0, '2025-06-09 11:41:49', '2025-06-09 11:41:49'),
(6, 1, 5, 3, 2, '2025-06-09', '14:15:00', 45, NULL, 'completed', '', 0, '2025-06-09 12:12:32', '2025-06-09 16:59:01');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_logs`
--

CREATE TABLE `appointment_logs` (
  `id` int NOT NULL,
  `clinic_id` int NOT NULL,
  `appointment_id` int NOT NULL,
  `scheduled_time` time NOT NULL,
  `actual_start_time` time DEFAULT NULL,
  `actual_end_time` time DEFAULT NULL,
  `wait_time` int DEFAULT NULL,
  `log_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clinics`
--

CREATE TABLE `clinics` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `working_hours` json DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clinics`
--

INSERT INTO `clinics` (`id`, `name`, `address`, `phone`, `email`, `website`, `working_hours`, `settings`, `created_at`, `updated_at`) VALUES
(1, 'SmileDesk Demo Clinic', '123 Dental Street, Casablanca, Morocco', '+212-522-123456', 'info@smiledesk-demo.com', NULL, NULL, NULL, '2025-06-08 19:00:43', '2025-06-08 19:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int NOT NULL,
  `clinic_id` int NOT NULL,
  `patient_id` int DEFAULT NULL,
  `appointment_id` int DEFAULT NULL,
  `type` enum('prescription','xray','lab_result','consent_form','treatment_plan','other') DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `file_path` varchar(500) DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `uploaded_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `clinic_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `appointment_id` int DEFAULT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT '0.00',
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('draft','sent','paid','overdue','cancelled') DEFAULT 'draft',
  `due_date` date DEFAULT NULL,
  `paid_date` timestamp NULL DEFAULT NULL,
  `payment_method` enum('cash','credit_card','bank_transfer','insurance','other') DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `clinic_id`, `patient_id`, `appointment_id`, `invoice_number`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `status`, `due_date`, `paid_date`, `payment_method`, `notes`, `created_at`, `updated_at`) VALUES
(2, 1, 5, NULL, 'INV-20250609-473', 200.00, 0.00, 0.00, 200.00, 'paid', '2025-06-11', '2025-06-09 16:02:03', 'insurance', '', '2025-06-09 17:01:45', '2025-06-09 17:02:03');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `service_id` int DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `service_id`, `description`, `quantity`, `unit_price`, `total_price`) VALUES
(2, 2, 3, 'Tooth-colored filling', 1, 200.00, 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `clinic_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `type` enum('appointment_reminder','payment_due','system_alert','other') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int NOT NULL,
  `clinic_id` int NOT NULL,
  `patient_number` varchar(20) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `address` text,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `medical_history` text,
  `allergies` text,
  `insurance_provider` varchar(100) DEFAULT NULL,
  `insurance_number` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `clinic_id`, `patient_number`, `first_name`, `last_name`, `email`, `phone`, `date_of_birth`, `gender`, `address`, `emergency_contact_name`, `emergency_contact_phone`, `medical_history`, `allergies`, `insurance_provider`, `insurance_number`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'P001', 'Ahmed', 'Benali', 'ahmed.benali@email.com', '+212-661-123456', '1985-06-15', 'male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(2, 1, 'P002', 'Fatima', 'Alaoui', 'fatima.alaoui@email.com', '+212-662-234567', '1990-03-22', 'female', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inactive', '2025-06-08 19:00:43', '2025-06-09 11:48:12'),
(3, 1, 'P003', 'Omar', 'Tazi', 'omar.tazi@email.com', '+212-663-345678', '1978-11-08', 'male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(4, 1, 'P004', 'Aicha', 'Mansouri', 'aicha.mansouri@email.com', '+212-664-456789', '1995-09-12', 'female', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(5, 1, 'P0005', 'LEA MAELYS', 'NIEZ', 'midelt-city2021@solarunited.net', '0631318173', '1991-05-09', 'female', '17 Rue Saint-Jean\r\nLANGON', NULL, NULL, '', NULL, NULL, NULL, 'active', '2025-06-09 11:48:48', '2025-06-09 11:48:48');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `clinic_id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `duration` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `requires_tooth_selection` tinyint(1) DEFAULT '0',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `clinic_id`, `category_id`, `name`, `description`, `duration`, `price`, `requires_tooth_selection`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Regular Cleaning', 'Professional dental cleaning and examination', 60, 150.00, 0, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(2, 1, 1, 'Deep Cleaning', 'Scaling and root planing', 90, 300.00, 0, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(3, 1, 2, 'Composite Filling', 'Tooth-colored filling', 45, 200.00, 1, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(4, 1, 2, 'Dental Crown', 'Porcelain crown placement', 90, 1200.00, 1, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(5, 1, 2, 'Root Canal Treatment', 'Endodontic therapy', 120, 800.00, 1, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(6, 1, 4, 'Tooth Extraction', 'Simple tooth extraction', 30, 240.00, 0, 'active', '2025-06-08 19:00:43', '2025-06-09 17:20:29'),
(7, 1, 3, 'Teeth Whitening', 'Professional whitening treatment', 60, 400.00, 0, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43'),
(8, 1, 1, 'Consultation', 'Initial examination and consultation', 30, 75.00, 0, 'active', '2025-06-08 19:00:43', '2025-06-08 19:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` int NOT NULL,
  `clinic_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `clinic_id`, `name`, `description`, `created_at`) VALUES
(1, 1, 'Preventive Care', 'Regular checkups and cleanings', '2025-06-08 19:00:43'),
(2, 1, 'Restorative', 'Fillings, crowns, and repairs', '2025-06-08 19:00:43'),
(3, 1, 'Cosmetic', 'Whitening and aesthetic procedures', '2025-06-08 19:00:43'),
(4, 1, 'Oral Surgery', 'Extractions and surgical procedures', '2025-06-08 19:00:43'),
(5, 1, 'Orthodontics', 'Braces and alignment treatments', '2025-06-08 19:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` int NOT NULL,
  `clinic_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `appointment_id` int DEFAULT NULL,
  `phone_number` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','sent','delivered','failed') DEFAULT 'pending',
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `treatment_history`
--

CREATE TABLE `treatment_history` (
  `id` int NOT NULL,
  `clinic_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `appointment_id` int DEFAULT NULL,
  `service_id` int NOT NULL,
  `dentist_id` int NOT NULL,
  `teeth_treated` json DEFAULT NULL,
  `diagnosis` text,
  `treatment_notes` text,
  `treatment_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `clinic_id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `role` enum('admin','dentist','assistant','receptionist') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `clinic_id`, `email`, `password`, `first_name`, `last_name`, `role`, `phone`, `specialization`, `license_number`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin@smiledesk.com', '$2y$10$Hg6/aBhuYKAaUxh06HN68eoPH0wQ7lwjaWE06v8X78MGN6UWxfsl.', 'Admin', 'User', 'admin', NULL, NULL, NULL, 'active', NULL, '2025-06-08 19:00:43', '2025-06-08 19:13:30'),
(2, 1, 'dr.smith@smiledesk.com', '$2y$10$Hg6/aBhuYKAaUxh06HN68eoPH0wQ7lwjaWE06v8X78MGN6UWxfsl.', 'John', 'Smith', 'dentist', NULL, NULL, NULL, 'active', NULL, '2025-06-08 19:00:43', '2025-06-08 19:13:27'),
(3, 1, 'assistant@smiledesk.com', '$2y$10$Hg6/aBhuYKAaUxh06HN68eoPH0wQ7lwjaWE06v8X78MGN6UWxfsl.', 'Sarah', 'Johnson', 'assistant', NULL, NULL, NULL, 'active', NULL, '2025-06-08 19:00:43', '2025-06-08 19:13:23'),
(4, 1, 'its.mediplo0aer@gmail.com', '$2y$10$Hg6/aBhuYKAaUxh06HN68eoPH0wQ7lwjaWE06v8X78MGN6UWxfsl.', 'Itsme', 'Diplo', 'admin', '8568748423', NULL, NULL, 'active', '2025-06-10 22:29:37', '2025-06-08 19:06:29', '2025-06-10 22:29:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_id` (`clinic_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_id` (`clinic_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `dentist_id` (`dentist_id`);

--
-- Indexes for table `appointment_logs`
--
ALTER TABLE `appointment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_id` (`clinic_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `clinics`
--
ALTER TABLE `clinics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_id` (`clinic_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `clinic_id` (`clinic_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_id` (`clinic_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_number` (`patient_number`),
  ADD KEY `clinic_id` (`clinic_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_id` (`clinic_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_id` (`clinic_id`);

--
-- Indexes for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_id` (`clinic_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `treatment_history`
--
ALTER TABLE `treatment_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clinic_id` (`clinic_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `dentist_id` (`dentist_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `clinic_id` (`clinic_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `appointment_logs`
--
ALTER TABLE `appointment_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clinics`
--
ALTER TABLE `clinics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sms_logs`
--
ALTER TABLE `sms_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `treatment_history`
--
ALTER TABLE `treatment_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activities_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_4` FOREIGN KEY (`dentist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `appointment_logs`
--
ALTER TABLE `appointment_logs`
  ADD CONSTRAINT `appointment_logs_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_logs_ibfk_2` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_3` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_4` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_ibfk_3` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD CONSTRAINT `service_categories_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD CONSTRAINT `sms_logs_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sms_logs_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sms_logs_ibfk_3` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `treatment_history`
--
ALTER TABLE `treatment_history`
  ADD CONSTRAINT `treatment_history_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatment_history_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatment_history_ibfk_3` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `treatment_history_ibfk_4` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatment_history_ibfk_5` FOREIGN KEY (`dentist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
