-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 04:08 AM
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
-- Database: `lbh`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `license_number` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `years_of_experience` int(11) DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `user_id`, `first_name`, `last_name`, `phone`, `address`, `license_number`, `specialization`, `years_of_experience`, `avatar_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'Rudy', 'Boringot', '09914212332', 'San Rafael Buhi Camarines Sur', 'PRC-MD-2018-0001234', 'Midwife', 5, 'images/avatars/687505bf77fc5.jpg', '2025-07-09 12:45:43', '2025-07-14 06:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('medicine','supply') NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `unit` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `min_stock` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_items`
--

INSERT INTO `inventory_items` (`id`, `item_code`, `name`, `type`, `quantity`, `unit`, `expiry_date`, `min_stock`, `description`, `manufacturer`, `batch_number`, `supplier`, `cost`, `image_path`, `created_at`, `updated_at`) VALUES
(11, 'SUPSDD001', 'Alcohol', 'supply', 14, 'pcs', '2025-07-23', 56, 'asfsdgsdSFDasf', 'sdsv', 'sevs', 'fgfdg', 34.00, 'images/inventory/SYsS81rVNJCUx4lKNUiQHz8hBFakXc9FE3weV8Wj.jpg', '2025-07-01 18:28:37', '2025-07-09 04:43:07'),
(29, 'MED001', 'Paracetamol', 'medicine', 56, 'pcs', '2025-07-24', 56, 'HAHAHA', 'sdsv', 'sevs', 'fgfdg', 34.00, 'images/inventory/H7FngviHgQaofuB0samjR5iDI3zNMwDSF50OFusb.jpg', '2025-07-05 02:49:01', '2025-07-09 18:58:35'),
(30, 'MED002', 'Paracetamol', 'medicine', 23, 'pcs', '2025-07-24', 56, 'adcs', 'sdsv', 'sevs', 'fgfdg', 34.00, 'images/inventory/zpOnxF6AnPF3BU5DF1yItVah75jl4UMRtpDQ6ghE.jpg', '2025-07-05 03:06:13', '2025-07-05 03:20:22'),
(31, 'MED003', 'Paracetamolsd', 'medicine', 0, 'pcs', '2025-07-24', 56, NULL, 'sdsv', 'sevs', 'fgfdg', 34.00, 'images/inventory/J9c7QsIekzz4ScXBdtYf3pNh1hxYtqKTHeBMuHM2.jpg', '2025-07-05 03:11:00', '2025-07-05 03:21:16'),
(32, 'MED004', 'Paracetamdsgfsd', 'medicine', 26, 'pcs', '2025-07-24', 56, NULL, 'sdsv', 'sevs', 'fgfdg', 34.00, 'images/inventory/iW8JVWjyiDJMzqjDigIODa1gq8QxJvV8EqA4cYn2.jpg', '2025-07-05 03:11:09', '2025-07-09 19:26:37');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_details`
--

CREATE TABLE `medicine_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventory_item_id` bigint(20) UNSIGNED NOT NULL,
  `medicine_type` enum('tablet','syrup','injection','capsule','ointment','drops') NOT NULL,
  `dosage` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medicine_details`
--

INSERT INTO `medicine_details` (`id`, `inventory_item_id`, `medicine_type`, `dosage`, `created_at`, `updated_at`) VALUES
(15, 29, 'tablet', '500mg', '2025-07-05 02:49:01', '2025-07-05 02:49:01'),
(16, 30, 'tablet', '500mg', '2025-07-05 03:06:13', '2025-07-05 03:06:13'),
(17, 31, 'syrup', '500mg', '2025-07-05 03:11:00', '2025-07-05 03:11:00'),
(18, 32, 'tablet', '500mg', '2025-07-05 03:11:09', '2025-07-05 03:11:09');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_07_01_042631_users', 1),
(2, '2025_07_01_043829_create_sessions_table', 1),
(3, '2025_07_01_053539_add_two_factor_expires_at_to_users_table', 1),
(4, '2025_07_01_115817_system_settings', 1),
(5, '2025_07_01_152442_create_inventory_items_table', 2),
(6, '2025_07_01_152443_create_medicine_details_table', 2),
(7, '2025_07_01_152444_create_supply_details_table', 2),
(8, '2025_07_05_123200_create_admin_profile_table', 3),
(9, '2025_07_05_123813_create_admin_table', 4),
(10, '2025_07_06_052626_create_cache_table', 5),
(11, '2025_07_09_041326_create_staff_table', 6),
(12, '2025_07_09_041358_create_staff_employment_table', 6),
(13, '2025_07_09_041418_create_staff_work_days_table', 6),
(14, '2025_07_09_041442_create_staff_qualifications_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('RmOJ1d34WwAUMz5XCmy8rHRL54QqFNE3pK3QPBkZ', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUWp0RTJLY1ByV252TDZIWXQ5cUo2VzhFbXdaSUUydmJ2Y2hIY2dGeSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdGFmZi9wcm9maWxlIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1752856067);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `address` text NOT NULL,
  `status` enum('active','inactive','on-leave') NOT NULL DEFAULT 'active',
  `branch` varchar(255) NOT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `user_id`, `staff_id`, `first_name`, `last_name`, `phone`, `date_of_birth`, `gender`, `address`, `status`, `branch`, `avatar_path`, `created_at`, `updated_at`) VALUES
(1, 2, 'ST002', 'Jerald', 'Ricabuertaa', '09123456789', '2025-07-02', 'female', 'Divino Rostro, Buhi Camarines Sur', 'active', 'San Pedro', 'images/avatars/687a5334dbe64.jpg', '2025-07-16 04:26:19', '2025-07-18 08:22:55'),
(9, 9, 'ST005', 'Rudy', 'Boringot', '09564353242', '2025-06-10', 'male', 'San Rafael', 'active', 'San Pedro', NULL, '2025-07-09 18:44:35', '2025-07-09 19:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `staff_employment`
--

CREATE TABLE `staff_employment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `hire_date` date NOT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `employment_type` enum('full-time','part-time','contract') DEFAULT NULL,
  `shift` enum('day','night','regular') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_employment`
--

INSERT INTO `staff_employment` (`id`, `staff_id`, `hire_date`, `salary`, `employment_type`, `shift`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-07-09', 500.00, 'full-time', 'day', '2025-07-09 04:28:50', '2025-07-14 05:19:37'),
(9, 9, '2025-07-01', 800.00, 'full-time', 'night', '2025-07-09 18:44:35', '2025-07-09 18:53:23');

-- --------------------------------------------------------

--
-- Table structure for table `staff_qualifications`
--

CREATE TABLE `staff_qualifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `education` varchar(255) DEFAULT NULL,
  `license_number` varchar(255) DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `years_experience` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_qualifications`
--

INSERT INTO `staff_qualifications` (`id`, `staff_id`, `education`, `license_number`, `license_expiry`, `years_experience`, `created_at`, `updated_at`) VALUES
(1, 1, 'Midwifery', 'MW-12345-2019', '2025-07-17', 6, '2025-07-09 04:29:31', '2025-07-09 04:29:31'),
(9, 9, 'dthjtfdh', 'rgfdsg', '2025-07-14', 56, '2025-07-09 18:44:35', '2025-07-09 18:44:35');

-- --------------------------------------------------------

--
-- Table structure for table `staff_work_days`
--

CREATE TABLE `staff_work_days` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `day` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_work_days`
--

INSERT INTO `staff_work_days` (`id`, `staff_id`, `day`) VALUES
(78, 9, 'thursday'),
(93, 1, 'tuesday'),
(94, 1, 'wednesday');

-- --------------------------------------------------------

--
-- Table structure for table `supply_details`
--

CREATE TABLE `supply_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventory_item_id` bigint(20) UNSIGNED NOT NULL,
  `supply_type` enum('disposable','equipment','consumable','instrument','safety') NOT NULL,
  `unit_size` varchar(255) NOT NULL,
  `unit_measure` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supply_details`
--

INSERT INTO `supply_details` (`id`, `inventory_item_id`, `supply_type`, `unit_size`, `unit_measure`, `created_at`, `updated_at`) VALUES
(4, 11, 'consumable', 'large', 'pcs', '2025-07-01 18:28:37', '2025-07-04 06:51:46');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `facility_name` varchar(100) NOT NULL,
  `license_number` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `opening_time` time NOT NULL,
  `closing_time` time NOT NULL,
  `emergency_service` tinyint(1) NOT NULL DEFAULT 1,
  `language` varchar(20) NOT NULL DEFAULT 'English',
  `date_format` varchar(20) NOT NULL DEFAULT 'DD/MM/YYYY',
  `timezone` varchar(50) NOT NULL DEFAULT 'Asia/Manila (UTC+8)',
  `currency` varchar(50) NOT NULL DEFAULT 'Philippine Peso (₱)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `logo_path`, `facility_name`, `license_number`, `address`, `phone_number`, `email`, `opening_time`, `closing_time`, `emergency_service`, `language`, `date_format`, `timezone`, `currency`, `created_at`, `updated_at`) VALUES
(1, 'img/imglogo.png', 'Birthing Home', 'LBH-2024-001', 'San Pedro', '09243654352', 'lettys@gmail.com', '09:00:00', '17:00:00', 1, 'English', 'DD/MM/YYYY', 'Asia/Manila (UTC+8)', 'Philippine Peso (₱)', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') NOT NULL DEFAULT 'staff',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `two_factor_code` int(11) DEFAULT NULL,
  `two_factor_expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `created_at`, `updated_at`, `two_factor_code`, `two_factor_expires_at`) VALUES
(1, 'rudy@gmail.com', '$2y$12$CGz/YL6G/rTEOTpMFmiLFOqY1LlbriF1IjOjZBvXKPi70XdT9o8Nm', 'admin', '2025-06-29 19:56:24', '2025-07-18 07:25:07', NULL, NULL),
(2, 'jerald@gmail.com', '$2y$12$J2jGRp20dF0A2MzOIZTFwepb733DJX7rJ48Al3XUTvcc7qC5h0MwK', 'staff', '2025-06-29 21:14:07', '2025-07-18 08:27:46', NULL, NULL),
(9, 'boringot@gmail.com', '$2y$12$iJN/rn8Huf6HsErONBQRGubMJgNJxTCpgfOotVNkiwbU0uq3t8IcK', 'staff', '2025-07-09 18:44:35', '2025-07-09 18:44:35', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_items_item_code_unique` (`item_code`);

--
-- Indexes for table `medicine_details`
--
ALTER TABLE `medicine_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicine_details_inventory_item_id_foreign` (`inventory_item_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_staff_id_unique` (`staff_id`),
  ADD KEY `staff_user_id_foreign` (`user_id`);

--
-- Indexes for table `staff_employment`
--
ALTER TABLE `staff_employment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_employment_staff_id_foreign` (`staff_id`);

--
-- Indexes for table `staff_qualifications`
--
ALTER TABLE `staff_qualifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_qualifications_license_number_unique` (`license_number`),
  ADD KEY `staff_qualifications_staff_id_foreign` (`staff_id`);

--
-- Indexes for table `staff_work_days`
--
ALTER TABLE `staff_work_days`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_work_days_staff_id_foreign` (`staff_id`);

--
-- Indexes for table `supply_details`
--
ALTER TABLE `supply_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supply_details_inventory_item_id_foreign` (`inventory_item_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `medicine_details`
--
ALTER TABLE `medicine_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `staff_employment`
--
ALTER TABLE `staff_employment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `staff_qualifications`
--
ALTER TABLE `staff_qualifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `staff_work_days`
--
ALTER TABLE `staff_work_days`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `supply_details`
--
ALTER TABLE `supply_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medicine_details`
--
ALTER TABLE `medicine_details`
  ADD CONSTRAINT `medicine_details_inventory_item_id_foreign` FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff_employment`
--
ALTER TABLE `staff_employment`
  ADD CONSTRAINT `staff_employment_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff_qualifications`
--
ALTER TABLE `staff_qualifications`
  ADD CONSTRAINT `staff_qualifications_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff_work_days`
--
ALTER TABLE `staff_work_days`
  ADD CONSTRAINT `staff_work_days_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supply_details`
--
ALTER TABLE `supply_details`
  ADD CONSTRAINT `supply_details_inventory_item_id_foreign` FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
