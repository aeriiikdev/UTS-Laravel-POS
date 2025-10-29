-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 19, 2025 at 04:20 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Makanan', 'Kategori untuk produk makanan.', 1, '2025-10-17 21:53:11', '2025-10-18 20:09:29'),
(2, 'Minuman', 'Kategori untuk produk minuman', 1, '2025-10-17 21:53:11', '2025-10-17 21:53:11'),
(3, 'Snack', 'Kategori untuk produk snack dan cemilan', 1, '2025-10-17 21:53:11', '2025-10-17 21:53:11'),
(4, 'Elektronik', 'Kategori untuk produk elektronik', 1, '2025-10-17 21:53:11', '2025-10-17 21:53:11'),
(5, 'Alat Tulis', 'Kategori untuk alat tulis dan perlengkapan kantor', 1, '2025-10-17 21:53:11', '2025-10-17 21:53:11'),
(6, 'sdfghj', NULL, 1, '2025-10-18 08:22:28', '2025-10-18 08:22:28'),
(7, 'ihg', NULL, 1, '2025-10-18 08:22:34', '2025-10-18 08:22:34'),
(8, 'uyhgv', NULL, 1, '2025-10-18 08:22:38', '2025-10-18 08:22:38'),
(9, 'pokjn', NULL, 1, '2025-10-18 08:22:43', '2025-10-18 08:22:43'),
(10, 'ohp', NULL, 1, '2025-10-18 08:22:48', '2025-10-18 08:22:48'),
(11, 'dhfjn', NULL, 1, '2025-10-18 08:22:53', '2025-10-18 08:22:53'),
(12, 'zxcvbn', NULL, 1, '2025-10-18 08:22:57', '2025-10-18 08:22:57');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_10_12_000000_create_users_table', 1),
(2, '2025_10_12_000001_create_categories_table', 1),
(3, '2025_10_12_000002_create_products_table', 1),
(4, '2025_10_12_100000_create_password_resets_table', 1),
(5, '2025_10_17_153450_create_transactions_table', 1),
(7, '2025_10_17_153451_create_transaction_details_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `code`, `name`, `description`, `price`, `stock`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'PRD-MAKAN001', 'Nasi Goreng', 'Nasi goreng spesial dengan telur', 25000.00, 47, 'images/products/1760767006_flowers.png', 1, '2025-10-17 21:53:11', '2025-10-18 09:12:33'),
(2, 1, 'PRD-MAKAN002', 'Mie Ayam', 'Mie ayam dengan bakso', 20000.00, 32, NULL, 1, '2025-10-17 21:53:11', '2025-10-18 20:11:18'),
(3, 1, 'PRD-MAKAN003', 'Ayam Goreng', 'Ayam goreng crispy', 30000.00, 24, NULL, 1, '2025-10-17 21:53:11', '2025-10-18 09:12:33'),
(4, 2, 'PRD-MINUM001', 'Es Teh Manis', 'Es teh manis segar', 5000.00, 97, NULL, 1, '2025-10-17 21:53:11', '2025-10-17 22:54:22'),
(5, 2, 'PRD-MINUM002', 'Kopi Hitam', 'Kopi hitam tanpa gula', 8000.00, 80, NULL, 1, '2025-10-17 21:53:11', '2025-10-17 21:53:11'),
(6, 2, 'PRD-MINUM003', 'Jus Jeruk', 'Jus jeruk segar asli', 12000.00, 55, NULL, 1, '2025-10-17 21:53:11', '2025-10-17 22:54:22'),
(7, 2, 'PRD-MINUM004', 'Air Mineral', 'Air mineral kemasan 600ml', 3000.00, 198, NULL, 1, '2025-10-17 21:53:11', '2025-10-17 22:54:22'),
(8, 3, 'PRD-SNACK001', 'Keripik Kentang', 'Keripik kentang rasa original', 15000.00, 73, NULL, 1, '2025-10-17 21:53:11', '2025-10-18 19:59:51'),
(9, 3, 'PRD-SNACK002', 'Coklat Batangan', 'Coklat batangan premium', 10000.00, 90, NULL, 1, '2025-10-17 21:53:11', '2025-10-17 21:53:11'),
(10, 3, 'PRD-SNACK003', 'Biskuit', 'Biskuit krim vanilla', 8000.00, 100, NULL, 1, '2025-10-17 21:53:11', '2025-10-17 21:53:11'),
(11, 4, 'PRD-ELEKT001', 'Kabel USB Type-C', 'Kabel USB Type-C 1 meter', 25000.00, 44, NULL, 1, '2025-10-17 21:53:11', '2025-10-18 19:59:51'),
(12, 4, 'PRD-ELEKT002', 'Earphone', 'Earphone dengan mic', 50000.00, 29, NULL, 1, '2025-10-17 21:53:11', '2025-10-18 19:59:51'),
(13, 4, 'PRD-ELEKT003', 'Power Bank', 'Power bank 10000mAh', 150000.00, 5, NULL, 1, '2025-10-17 21:53:11', '2025-10-18 01:32:31'),
(14, 5, 'PRD-TULIS001', 'Pulpen', 'Pulpen tinta hitam', 3000.00, 147, NULL, 1, '2025-10-17 21:53:11', '2025-10-18 19:59:51'),
(15, 5, 'PRD-TULIS002', 'Buku Tulis', 'Buku tulis 40 lembar', 5000.00, 95, NULL, 1, '2025-10-17 21:53:11', '2025-10-18 01:22:33'),
(16, 5, 'PRD-TULIS003', 'Penghapus', 'Penghapus karet putih', 2000.00, 199, NULL, 1, '2025-10-17 21:53:11', '2025-10-18 01:22:33'),
(17, 5, 'PRD-TULIS004', 'Pensil 2B', 'Pensil 2B per batang', 2500.00, 179, NULL, 1, '2025-10-17 21:53:11', '2025-10-18 01:22:33');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('bQ67MF8QZ00uJWTQsJNQkkVoHadgNSDdaFsBaFCl', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYjRpSm9nd1FKVXVmTXhkV2FCWlNhUEs5RVhqOFNJa2VpZGNBandETSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1760847089),
('MHmlQwSGVeRDgi3Pwwojnwy01hzU1E2ysHTw9SNV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaHpzdFVhdG5Wb0RsNVpRMHhUUU1JSEdVUTVBd1hyblJmNkhTdmFBViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1760845621);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `transaction_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` datetime NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_amount` decimal(12,2) NOT NULL,
  `change_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `transaction_code`, `transaction_date`, `total_amount`, `payment_method`, `payment_amount`, `change_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 'TRX-20251018-0001', '2025-10-18 06:41:56', 50000.00, 'cash', 100000.00, 50000.00, 'completed', NULL, '2025-10-17 22:41:56', '2025-10-17 22:41:56'),
(2, 2, 'TRX-20251018-0002', '2025-10-18 06:43:34', 30000.00, 'cash', 50000.00, 20000.00, 'completed', NULL, '2025-10-17 22:43:34', '2025-10-17 22:43:34'),
(3, 2, 'TRX-20251018-0003', '2025-10-18 06:45:32', 57000.00, 'cash', 100000.00, 43000.00, 'completed', NULL, '2025-10-17 22:45:32', '2025-10-17 22:45:32'),
(4, 2, 'TRX-20251018-0004', '2025-10-18 06:48:47', 79000.00, 'cash', 100000.00, 21000.00, 'completed', NULL, '2025-10-17 22:48:47', '2025-10-17 22:48:47'),
(5, 2, 'TRX-20251018-0005', '2025-10-18 06:53:08', 75000.00, 'cash', 100000.00, 25000.00, 'completed', NULL, '2025-10-17 22:53:08', '2025-10-17 22:53:08'),
(6, 2, 'TRX-20251018-0006', '2025-10-18 06:54:22', 70000.00, 'cash', 100000.00, 30000.00, 'completed', NULL, '2025-10-17 22:54:22', '2025-10-17 22:54:22'),
(7, 2, 'TRX-20251018-0007', '2025-10-18 09:22:33', 35500.00, 'cash', 50000.00, 14500.00, 'completed', NULL, '2025-10-18 01:22:33', '2025-10-18 01:22:33'),
(8, 2, 'TRX-20251018-0008', '2025-10-18 09:32:31', 2250000.00, 'cash', 2500000.00, 250000.00, 'completed', NULL, '2025-10-18 01:32:31', '2025-10-18 01:32:31'),
(9, 2, 'TRX-20251018-0009', '2025-10-18 16:01:36', 20000.00, 'cash', 50000.00, 30000.00, 'completed', NULL, '2025-10-18 08:01:36', '2025-10-18 08:01:36'),
(10, 2, 'TRX-20251018-0010', '2025-10-18 17:12:33', 75000.00, 'cash', 100000.00, 25000.00, 'completed', NULL, '2025-10-18 09:12:33', '2025-10-18 09:12:33'),
(11, 3, 'TRX-20251019-0001', '2025-10-19 03:59:51', 108000.00, 'cash', 120000.00, 12000.00, 'completed', NULL, '2025-10-18 19:59:51', '2025-10-18 19:59:51'),
(12, 3, 'TRX-20251019-0002', '2025-10-19 04:11:18', 40000.00, 'cash', 50000.00, 10000.00, 'completed', NULL, '2025-10-18 20:11:18', '2025-10-18 20:11:18');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`id`, `transaction_id`, `product_id`, `quantity`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 20000.00, 20000.00, '2025-10-17 22:41:56', '2025-10-17 22:41:56'),
(2, 1, 3, 1, 30000.00, 30000.00, '2025-10-17 22:41:56', '2025-10-17 22:41:56'),
(3, 2, 3, 1, 30000.00, 30000.00, '2025-10-17 22:43:34', '2025-10-17 22:43:34'),
(4, 3, 4, 1, 5000.00, 5000.00, '2025-10-17 22:45:32', '2025-10-17 22:45:32'),
(5, 3, 6, 1, 12000.00, 12000.00, '2025-10-17 22:45:32', '2025-10-17 22:45:32'),
(6, 3, 2, 2, 20000.00, 40000.00, '2025-10-17 22:45:32', '2025-10-17 22:45:32'),
(7, 4, 1, 1, 25000.00, 25000.00, '2025-10-17 22:48:47', '2025-10-17 22:48:47'),
(8, 4, 3, 1, 30000.00, 30000.00, '2025-10-17 22:48:47', '2025-10-17 22:48:47'),
(9, 4, 6, 2, 12000.00, 24000.00, '2025-10-17 22:48:47', '2025-10-17 22:48:47'),
(10, 5, 3, 1, 30000.00, 30000.00, '2025-10-17 22:53:08', '2025-10-17 22:53:08'),
(11, 5, 1, 1, 25000.00, 25000.00, '2025-10-17 22:53:08', '2025-10-17 22:53:08'),
(12, 5, 4, 1, 5000.00, 5000.00, '2025-10-17 22:53:08', '2025-10-17 22:53:08'),
(13, 5, 6, 1, 12000.00, 12000.00, '2025-10-17 22:53:08', '2025-10-17 22:53:08'),
(14, 5, 7, 1, 3000.00, 3000.00, '2025-10-17 22:53:08', '2025-10-17 22:53:08'),
(15, 6, 2, 1, 20000.00, 20000.00, '2025-10-17 22:54:22', '2025-10-17 22:54:22'),
(16, 6, 3, 1, 30000.00, 30000.00, '2025-10-17 22:54:22', '2025-10-17 22:54:22'),
(17, 6, 6, 1, 12000.00, 12000.00, '2025-10-17 22:54:22', '2025-10-17 22:54:22'),
(18, 6, 4, 1, 5000.00, 5000.00, '2025-10-17 22:54:22', '2025-10-17 22:54:22'),
(19, 6, 7, 1, 3000.00, 3000.00, '2025-10-17 22:54:22', '2025-10-17 22:54:22'),
(20, 7, 17, 1, 2500.00, 2500.00, '2025-10-18 01:22:33', '2025-10-18 01:22:33'),
(21, 7, 16, 1, 2000.00, 2000.00, '2025-10-18 01:22:33', '2025-10-18 01:22:33'),
(22, 7, 15, 5, 5000.00, 25000.00, '2025-10-18 01:22:33', '2025-10-18 01:22:33'),
(23, 7, 14, 2, 3000.00, 6000.00, '2025-10-18 01:22:33', '2025-10-18 01:22:33'),
(24, 8, 13, 15, 150000.00, 2250000.00, '2025-10-18 01:32:31', '2025-10-18 01:32:31'),
(25, 9, 2, 1, 20000.00, 20000.00, '2025-10-18 08:01:36', '2025-10-18 08:01:36'),
(26, 10, 1, 1, 25000.00, 25000.00, '2025-10-18 09:12:33', '2025-10-18 09:12:33'),
(27, 10, 2, 1, 20000.00, 20000.00, '2025-10-18 09:12:33', '2025-10-18 09:12:33'),
(28, 10, 3, 1, 30000.00, 30000.00, '2025-10-18 09:12:33', '2025-10-18 09:12:33'),
(29, 11, 11, 1, 25000.00, 25000.00, '2025-10-18 19:59:51', '2025-10-18 19:59:51'),
(30, 11, 12, 1, 50000.00, 50000.00, '2025-10-18 19:59:51', '2025-10-18 19:59:51'),
(31, 11, 14, 1, 3000.00, 3000.00, '2025-10-18 19:59:51', '2025-10-18 19:59:51'),
(32, 11, 8, 2, 15000.00, 30000.00, '2025-10-18 19:59:51', '2025-10-18 19:59:51'),
(33, 12, 2, 2, 20000.00, 40000.00, '2025-10-18 20:11:18', '2025-10-18 20:11:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','cashier') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cashier',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@pos.com', NULL, '$2y$12$IkLQVQ8Qo/FhVJ2cQc6Ic.nGhhL.w363ErXe6B3cERa81Pv1AjCpW', 'cashier', 1, NULL, '2025-10-17 21:53:11', '2025-10-17 21:53:11'),
(2, 'Kasir 1', 'kasir@pos.com', NULL, '$2y$12$dPRWDZFih1F1ZevVPdGLgeJy/t1m5f.pzV6Ai8hS7yZXbGZzTW4Nu', 'cashier', 1, NULL, '2025-10-17 21:53:11', '2025-10-17 21:56:00'),
(3, 'Ariii', 'Arikesuma26@gmail.com', NULL, '$2y$12$vr.Mtl98IEYWDUEawFJlU.4paWg6EL5Xf5UTzDaHzjhJvDO4tyZnK', 'cashier', 1, NULL, '2025-10-18 19:59:06', '2025-10-18 19:59:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_code_unique` (`code`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_transaction_code_unique` (`transaction_code`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_details_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_details_product_id_foreign` (`product_id`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
