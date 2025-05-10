-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 09:33 AM
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
-- Database: `beer-restaurant`
--

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
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `table_id` bigint(20) UNSIGNED NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Đồ Uống', 'fa fa-beer', NULL, '2025-04-23 13:54:45', '2025-04-23 13:54:45'),
(2, 'Món nhậu', 'fa fa-utensils', NULL, '2025-04-23 13:54:45', '2025-04-23 13:54:45'),
(3, 'Đồ khô', 'fa fa-hamburger', NULL, '2025-04-23 13:59:36', '2025-04-23 13:59:36');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_04_21_144132_create_categories_table', 1),
(5, '2025_04_21_144142_create_products_table', 1),
(6, '2025_04_21_144145_create_tables_table', 1),
(7, '2025_04_21_144148_create_carts_table', 1),
(8, '2025_04_21_144156_create_cart_items_table', 1),
(9, '2025_04_21_144203_create_orders_table', 1),
(10, '2025_04_21_144209_create_order_items_table', 1),
(11, '2025_04_21_144741_add_google_fields_to_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `table_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','serving','done') NOT NULL DEFAULT 'pending',
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `table_id`, `status`, `total_price`, `created_at`, `updated_at`) VALUES
(2, 1, 'done', 2050000.00, '2025-05-09 12:12:49', '2025-05-09 12:12:49'),
(3, 2, 'done', 700000.00, '2025-05-09 12:13:28', '2025-05-09 12:13:28'),
(4, 3, 'done', 340000.00, '2025-05-09 12:14:43', '2025-05-09 12:14:43'),
(5, 1, 'done', 680000.00, '2025-05-09 12:17:58', '2025-05-09 12:17:58'),
(6, 1, 'done', 45000.00, '2025-05-09 12:18:08', '2025-05-09 12:18:08'),
(7, 1, 'done', 45000.00, '2025-05-09 12:22:03', '2025-05-09 12:22:03'),
(8, 1, 'done', 45000.00, '2025-05-09 12:22:50', '2025-05-09 12:22:50'),
(9, 1, 'done', 235000.00, '2025-05-09 12:23:47', '2025-05-09 12:23:47'),
(10, 1, 'done', 710000.00, '2025-05-09 12:35:22', '2025-05-09 12:35:22'),
(11, 1, 'done', 1125000.00, '2025-05-09 12:43:41', '2025-05-09 12:43:41'),
(12, 1, 'done', 45000.00, '2025-05-09 12:49:26', '2025-05-09 12:49:26'),
(13, 1, 'done', 10000.00, '2025-05-09 12:50:16', '2025-05-09 12:50:16'),
(14, 1, 'done', 275000.00, '2025-05-09 12:51:25', '2025-05-09 12:51:25'),
(15, 1, 'done', 515000.00, '2025-05-09 12:59:52', '2025-05-09 12:59:52');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`, `note`, `created_at`, `updated_at`) VALUES
(1, 2, 4, 7, 240000.00, NULL, '2025-05-09 12:12:49', '2025-05-09 12:12:49'),
(2, 2, 6, 6, 10000.00, NULL, '2025-05-09 12:12:49', '2025-05-09 12:12:49'),
(3, 2, 2, 1, 150000.00, NULL, '2025-05-09 12:12:49', '2025-05-09 12:12:49'),
(4, 2, 1, 3, 40000.00, NULL, '2025-05-09 12:12:49', '2025-05-09 12:12:49'),
(5, 2, 3, 1, 40000.00, NULL, '2025-05-09 12:12:49', '2025-05-09 12:12:49'),
(6, 3, 3, 10, 40000.00, NULL, '2025-05-09 12:13:28', '2025-05-09 12:13:28'),
(7, 3, 4, 1, 240000.00, NULL, '2025-05-09 12:13:28', '2025-05-09 12:13:28'),
(8, 3, 6, 6, 10000.00, NULL, '2025-05-09 12:13:28', '2025-05-09 12:13:28'),
(9, 4, 5, 2, 35000.00, NULL, '2025-05-09 12:14:43', '2025-05-09 12:14:43'),
(10, 4, 1, 3, 40000.00, NULL, '2025-05-09 12:14:43', '2025-05-09 12:14:43'),
(11, 4, 2, 1, 150000.00, NULL, '2025-05-09 12:14:43', '2025-05-09 12:14:43'),
(12, 5, 5, 6, 35000.00, NULL, '2025-05-09 12:17:58', '2025-05-09 12:17:58'),
(13, 5, 1, 1, 40000.00, NULL, '2025-05-09 12:17:58', '2025-05-09 12:17:58'),
(14, 5, 2, 1, 150000.00, NULL, '2025-05-09 12:17:58', '2025-05-09 12:17:58'),
(15, 5, 4, 1, 240000.00, NULL, '2025-05-09 12:17:58', '2025-05-09 12:17:58'),
(16, 5, 3, 1, 40000.00, NULL, '2025-05-09 12:17:58', '2025-05-09 12:17:58'),
(17, 6, 5, 1, 35000.00, NULL, '2025-05-09 12:18:08', '2025-05-09 12:18:08'),
(18, 6, 6, 1, 10000.00, NULL, '2025-05-09 12:18:08', '2025-05-09 12:18:08'),
(19, 7, 5, 1, 35000.00, NULL, '2025-05-09 12:22:03', '2025-05-09 12:22:03'),
(20, 7, 6, 1, 10000.00, NULL, '2025-05-09 12:22:03', '2025-05-09 12:22:03'),
(21, 8, 5, 1, 35000.00, NULL, '2025-05-09 12:22:50', '2025-05-09 12:22:50'),
(22, 8, 6, 1, 10000.00, NULL, '2025-05-09 12:22:50', '2025-05-09 12:22:50'),
(23, 9, 6, 1, 10000.00, NULL, '2025-05-09 12:23:47', '2025-05-09 12:23:47'),
(24, 9, 5, 1, 35000.00, NULL, '2025-05-09 12:23:47', '2025-05-09 12:23:47'),
(25, 9, 2, 1, 150000.00, NULL, '2025-05-09 12:23:47', '2025-05-09 12:23:47'),
(26, 9, 1, 1, 40000.00, NULL, '2025-05-09 12:23:47', '2025-05-09 12:23:47'),
(27, 10, 6, 20, 10000.00, NULL, '2025-05-09 12:35:22', '2025-05-09 12:35:22'),
(28, 10, 1, 2, 40000.00, NULL, '2025-05-09 12:35:22', '2025-05-09 12:35:22'),
(29, 10, 2, 1, 150000.00, NULL, '2025-05-09 12:35:22', '2025-05-09 12:35:22'),
(30, 10, 4, 1, 240000.00, NULL, '2025-05-09 12:35:22', '2025-05-09 12:35:22'),
(31, 10, 3, 1, 40000.00, NULL, '2025-05-09 12:35:22', '2025-05-09 12:35:22'),
(32, 11, 5, 1, 35000.00, NULL, '2025-05-09 12:43:41', '2025-05-09 12:43:41'),
(33, 11, 6, 15, 10000.00, NULL, '2025-05-09 12:43:41', '2025-05-09 12:43:41'),
(34, 11, 2, 2, 150000.00, NULL, '2025-05-09 12:43:41', '2025-05-09 12:43:41'),
(35, 11, 1, 2, 40000.00, NULL, '2025-05-09 12:43:41', '2025-05-09 12:43:41'),
(36, 11, 4, 2, 240000.00, NULL, '2025-05-09 12:43:41', '2025-05-09 12:43:41'),
(37, 11, 3, 2, 40000.00, NULL, '2025-05-09 12:43:41', '2025-05-09 12:43:41'),
(38, 12, 6, 1, 10000.00, NULL, '2025-05-09 12:49:26', '2025-05-09 12:49:26'),
(39, 12, 5, 1, 35000.00, NULL, '2025-05-09 12:49:26', '2025-05-09 12:49:26'),
(40, 13, 6, 1, 10000.00, NULL, '2025-05-09 12:50:16', '2025-05-09 12:50:16'),
(41, 14, 5, 1, 35000.00, NULL, '2025-05-09 12:51:25', '2025-05-09 12:51:25'),
(42, 14, 6, 5, 10000.00, NULL, '2025-05-09 12:51:25', '2025-05-09 12:51:25'),
(43, 14, 2, 1, 150000.00, NULL, '2025-05-09 12:51:25', '2025-05-09 12:51:25'),
(44, 14, 1, 1, 40000.00, NULL, '2025-05-09 12:51:25', '2025-05-09 12:51:25'),
(45, 15, 1, 1, 40000.00, NULL, '2025-05-09 12:59:52', '2025-05-09 12:59:52'),
(46, 15, 2, 1, 150000.00, NULL, '2025-05-09 12:59:52', '2025-05-09 12:59:52'),
(47, 15, 5, 1, 35000.00, NULL, '2025-05-09 12:59:52', '2025-05-09 12:59:52'),
(48, 15, 6, 1, 10000.00, NULL, '2025-05-09 12:59:52', '2025-05-09 12:59:52'),
(49, 15, 3, 1, 40000.00, NULL, '2025-05-09 12:59:52', '2025-05-09 12:59:52'),
(50, 15, 4, 1, 240000.00, NULL, '2025-05-09 12:59:52', '2025-05-09 12:59:52');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'ly',
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `unit`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 2, 'Rau xu xu xào', NULL, 40000.00, 'đĩa', 'https://images.immediate.co.uk/production/volatile/sites/30/2020/08/chorizo-mozarella-gnocchi-bake-cropped-9ab73a3.jpg?quality=90&resize=556,505', 1, '2025-04-23 14:01:27', '2025-04-23 14:01:27'),
(2, 2, 'Thịt lợn nướng', NULL, 150000.00, 'đĩa', 'https://images.immediate.co.uk/production/volatile/sites/30/2020/08/chorizo-mozarella-gnocchi-bake-cropped-9ab73a3.jpg?quality=90&resize=556,505', 1, '2025-04-23 14:01:27', '2025-04-23 14:01:27'),
(3, 3, 'Nem chua', NULL, 40000.00, 'đĩa', 'https://images.immediate.co.uk/production/volatile/sites/30/2020/08/chorizo-mozarella-gnocchi-bake-cropped-9ab73a3.jpg?quality=90&resize=556,505', 1, '2025-04-23 14:01:27', '2025-04-23 14:01:27'),
(4, 3, 'Mực nướng', NULL, 240000.00, 'đĩa', 'https://images.immediate.co.uk/production/volatile/sites/30/2020/08/chorizo-mozarella-gnocchi-bake-cropped-9ab73a3.jpg?quality=90&resize=556,505', 1, '2025-04-23 14:01:27', '2025-04-23 14:01:27'),
(5, 1, 'Rượu', NULL, 35000.00, 'chai', 'https://images.immediate.co.uk/production/volatile/sites/30/2020/08/chorizo-mozarella-gnocchi-bake-cropped-9ab73a3.jpg?quality=90&resize=556,505', 1, '2025-04-23 14:01:27', '2025-04-23 14:01:27'),
(6, 1, 'Bia hơi', NULL, 10000.00, 'cốc', 'https://images.immediate.co.uk/production/volatile/sites/30/2020/08/chorizo-mozarella-gnocchi-bake-cropped-9ab73a3.jpg?quality=90&resize=556,505', 1, '2025-04-23 14:01:27', '2025-04-23 14:01:27');

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
('byRibupntTXmmiSkZAyDoSl0xQdZeFXCetPS0PMV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT2dDQ1N6SmY0eldPcnJuSlJLQndyTGlvdmdZQ0hJRkdoaDg1NUx0TyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1746821171);

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'B1', '2025-04-23 15:32:45', '2025-04-23 15:32:45'),
(2, 'B2', '2025-04-23 12:36:32', '2025-04-23 12:36:32'),
(3, 'B3', '2025-04-23 15:32:45', '2025-04-23 15:32:45'),
(4, 'B4', '2025-04-23 12:36:32', '2025-04-23 12:36:32'),
(5, 'B5', '2025-04-23 15:32:45', '2025-04-23 15:32:45'),
(6, 'B6', '2025-04-23 12:36:32', '2025-04-23 12:36:32'),
(7, 'B7', '2025-04-23 15:32:45', '2025-04-23 15:32:45'),
(8, 'B8', '2025-04-23 12:36:32', '2025-04-23 12:36:32'),
(9, 'B9', '2025-04-23 15:32:45', '2025-04-23 15:32:45'),
(10, 'B10', '2025-04-23 12:36:32', '2025-04-23 12:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

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
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_table_id_foreign` (`table_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_table_id_foreign` (`table_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

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
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tables_name_unique` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
