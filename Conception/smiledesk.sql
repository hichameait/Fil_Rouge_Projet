-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2025 at 10:28 AM
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
(2, 5, 'Dr Mohammed Salmi', '45 RUE MELOUIA TADAOUT', '0631318173', 'its.aitbenalla.hichame@gmail.com', '', '', '', '{\"monday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"tuesday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"wednesday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"thursday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"friday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"saturday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false},\"sunday\":{\"open\":\"09:00\",\"close\":\"17:00\",\"closed\":false}}', NULL, '2025-06-13 09:44:13', '2025-06-13 09:44:34', '{\"sms_reminders_enabled\":true,\"sms_reminder_time\":\"24\",\"sms_provider\":\"twilio\",\"sms_sender_name\":\"\",\"sms_api_key\":\"\",\"email_notifications_enabled\":true,\"email_appointment_confirmation\":true,\"email_appointment_reminder\":true,\"email_payment_receipt\":true,\"email_treatment_summary\":true,\"email_custom_template\":false,\"chatbot_enabled\":false}', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
