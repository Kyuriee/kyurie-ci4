-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 03, 2026 at 09:54 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sql_kyurie`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `level` enum('Superadmin','Admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Admin',
  `status` enum('On','Off') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'On',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `id` int UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort` int NOT NULL DEFAULT '0',
  `status` enum('On','Off') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'On',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`id`, `image`, `link`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(1, 'banner-1.png', '/promo/flash-sale', 1, 'On', '2026-06-30 13:19:31', '2026-06-30 13:19:31'),
(2, 'banner-1.png', '/promo/cashback', 2, 'On', '2026-06-30 13:19:31', '2026-06-30 13:19:31'),
(3, 'banner-1.png', '/games', 3, 'On', '2026-06-30 13:19:31', '2026-06-30 13:19:31');

-- --------------------------------------------------------

--
-- Table structure for table `credentials`
--

CREATE TABLE `credentials` (
  `id` int UNSIGNED NOT NULL,
  `provider` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `c_key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `c_value` longtext COLLATE utf8mb4_general_ci,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'text',
  `mode` enum('sandbox','production') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'production',
  `status` enum('On','Off') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'On',
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flashsale`
--

CREATE TABLE `flashsale` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `status` enum('On','Off') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'On',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flashsale`
--

INSERT INTO `flashsale` (`id`, `title`, `description`, `image`, `date_start`, `date_end`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Flash Sale Juli 2026', 'Promo spesial top up dengan stok terbatas.', NULL, '2026-06-30 00:00:00', '2026-07-08 09:48:26', 'On', '2026-07-01 09:48:26', '2026-07-01 09:48:26');

-- --------------------------------------------------------

--
-- Table structure for table `flashsale_items`
--

CREATE TABLE `flashsale_items` (
  `id` int UNSIGNED NOT NULL,
  `flashsale_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `stock` int UNSIGNED NOT NULL DEFAULT '0',
  `sold` int UNSIGNED NOT NULL DEFAULT '0',
  `discount_type` enum('fixed','percent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `discount_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flashsale_items`
--

INSERT INTO `flashsale_items` (`id`, `flashsale_id`, `product_id`, `stock`, `sold`, `discount_type`, `discount_value`, `sort_order`, `created_at`, `updated_at`) VALUES
(13, 1, 13, 100, 30, 'fixed', 3000.00, 1, '2026-07-01 09:54:44', '2026-07-01 09:54:44'),
(14, 1, 14, 80, 22, 'fixed', 5000.00, 2, '2026-07-01 09:54:44', '2026-07-01 09:54:44'),
(15, 1, 15, 60, 18, 'percent', 10.00, 3, '2026-07-01 09:54:44', '2026-07-01 09:54:44'),
(16, 1, 16, 120, 45, 'fixed', 2000.00, 4, '2026-07-01 09:54:44', '2026-07-01 09:54:44'),
(17, 1, 17, 75, 28, 'percent', 8.00, 5, '2026-07-01 09:54:44', '2026-07-01 09:54:44'),
(18, 1, 18, 100, 37, 'fixed', 2000.00, 6, '2026-07-01 09:54:44', '2026-07-01 09:54:44'),
(19, 1, 19, 50, 15, 'percent', 10.00, 7, '2026-07-01 09:54:44', '2026-07-01 09:54:44'),
(20, 1, 20, 90, 31, 'fixed', 2500.00, 8, '2026-07-01 09:54:44', '2026-07-01 09:54:44'),
(21, 1, 21, 60, 21, 'percent', 12.00, 9, '2026-07-01 09:54:44', '2026-07-01 09:54:44'),
(22, 1, 22, 80, 36, 'fixed', 2500.00, 10, '2026-07-01 09:54:44', '2026-07-01 09:54:44'),
(23, 1, 23, 120, 62, 'fixed', 2000.00, 11, '2026-07-01 09:54:44', '2026-07-01 09:54:44'),
(24, 1, 24, 40, 12, 'percent', 9.00, 12, '2026-07-01 09:54:44', '2026-07-01 09:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int UNSIGNED NOT NULL,
  `game_category_id` int UNSIGNED DEFAULT NULL,
  `games` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `publisher` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `provider` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `banner` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `target` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'default',
  `is_popular` enum('Y','N') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'N',
  `sort` int NOT NULL DEFAULT '0',
  `status` enum('On','Off') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'On',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `game_category_id`, `games`, `publisher`, `slug`, `code`, `provider`, `image`, `banner`, `description`, `target`, `is_popular`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(7, 1, 'Mobile Legends', 'Moonton', 'mobile-legends', 'MLBB', NULL, 'mobile-legends.webp', 'mobile-legends-banner.webp', 'Top Up Mobile Legends cepat dan aman.', 'default', 'Y', 1, 'On', '2026-07-01 09:35:37', '2026-07-01 09:35:37'),
(8, 2, 'Free Fire', 'Garena', 'free-fire', 'FF', NULL, 'free-fire.webp', 'free-fire-banner.webp', 'Top Up Free Fire murah.', 'default', 'Y', 2, 'On', '2026-07-01 09:35:37', '2026-07-01 09:35:37'),
(9, 2, 'PUBG Mobile', 'Tencent', 'pubg-mobile', 'PUBGM', NULL, 'pubg-mobile.webp', 'pubg-mobile-banner.webp', 'Top Up PUBG Mobile instan.', 'default', 'N', 3, 'On', '2026-07-01 09:35:37', '2026-07-01 09:35:37'),
(10, 1, 'Honor of Kings', 'Level Infinite', 'honor-of-kings', 'HOK', NULL, 'honor-of-kings.webp', 'honor-of-kings-banner.webp', 'Top Up Honor of Kings.', 'default', 'Y', 4, 'On', '2026-07-01 09:35:37', '2026-07-01 09:35:37'),
(11, 1, 'Arena of Valor', 'Garena', 'arena-of-valor', 'AOV', NULL, 'arena-of-valor.webp', 'arena-of-valor-banner.webp', 'Top Up Arena of Valor.', 'default', 'N', 5, 'On', '2026-07-01 09:35:37', '2026-07-01 09:35:37'),
(12, 1, 'Genshin Impact', 'HoYoverse', 'genshin-impact', 'GI', NULL, 'genshin-impact.webp', 'genshin-impact-banner.webp', 'Top Up Genesis Crystal.', 'default', 'Y', 6, 'On', '2026-07-01 09:35:37', '2026-07-01 09:35:37');

-- --------------------------------------------------------

--
-- Table structure for table `game_categories`
--

CREATE TABLE `game_categories` (
  `id` int UNSIGNED NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort` int NOT NULL DEFAULT '0',
  `status` enum('On','Off') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'On',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_categories`
--

INSERT INTO `game_categories` (`id`, `category`, `slug`, `image`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MOBA', 'moba', 'moba.webp', 1, 'On', '2026-07-01 09:35:25', '2026-07-01 09:35:25'),
(2, 'Battle Royale', 'battle-royale', 'battle-royale.webp', 2, 'On', '2026-07-01 09:35:25', '2026-07-01 09:35:25'),
(3, 'RPG', 'rpg', 'rpg.webp', 3, 'On', '2026-07-01 09:35:25', '2026-07-01 09:35:25'),
(4, 'FPS', 'fps', 'fps.webp', 4, 'On', '2026-07-01 09:35:25', '2026-07-01 09:35:25'),
(5, 'Voucher', 'voucher', 'voucher.webp', 5, 'On', '2026-07-01 09:35:25', '2026-07-01 09:35:25'),
(6, 'Entertainment', 'entertainment', 'entertainment.webp', 6, 'On', '2026-07-01 09:35:25', '2026-07-01 09:35:25');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-05-30-082711', 'App\\Database\\Migrations\\CreateCoreTables', 'default', 'App', 1781331734, 1),
(2, '2026-05-30-084246', 'App\\Database\\Migrations\\CreateSettingTables', 'default', 'App', 1781331734, 1),
(3, '2026-06-09-100000', 'App\\Database\\Migrations\\CreatePaymentMethodsAndOrders', 'default', 'App', 1781331734, 1),
(4, '2026-06-09-110000', 'App\\Database\\Migrations\\AddPaymentTokenToOrders', 'default', 'App', 1781331734, 1),
(5, '2026-06-10-100000', 'App\\Database\\Migrations\\AddAuthTokensToUsers', 'default', 'App', 1781331734, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int UNSIGNED NOT NULL,
  `invoice` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `payment_token` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `payment_method_id` int UNSIGNED DEFAULT NULL,
  `customer_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `zone_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `game_provider` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_provider` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `game_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `fee` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','processing','success','failed') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `sn` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `game_trx_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_trx_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `note` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `provider` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `config` text COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort` int NOT NULL DEFAULT '0',
  `status` enum('On','Off') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'On',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int UNSIGNED NOT NULL,
  `games_id` int UNSIGNED NOT NULL,
  `product` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `sku` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `provider` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `raw_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sort` int NOT NULL DEFAULT '0',
  `status` enum('On','Off') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'On',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `games_id`, `product`, `sku`, `provider`, `raw_price`, `price`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(13, 7, '86 Diamonds', 'ML-86', 'Moonton', 18000.00, 20000.00, 1, 'On', '2026-07-01 09:45:21', '2026-07-01 09:45:21'),
(14, 7, '172 Diamonds', 'ML-172', 'Moonton', 36000.00, 39000.00, 2, 'On', '2026-07-01 09:45:21', '2026-07-01 09:45:21'),
(15, 7, '257 Diamonds', 'ML-257', 'Moonton', 54000.00, 59000.00, 3, 'On', '2026-07-01 09:45:21', '2026-07-01 09:45:21'),
(16, 8, '100 Diamonds', 'FF-100', 'Garena', 14000.00, 16000.00, 1, 'On', '2026-07-01 09:45:21', '2026-07-01 09:45:21'),
(17, 8, '310 Diamonds', 'FF-310', 'Garena', 43000.00, 47000.00, 2, 'On', '2026-07-01 09:45:21', '2026-07-01 09:45:21'),
(18, 9, '60 UC', 'PUBG-60', 'Tencent', 15000.00, 17000.00, 1, 'On', '2026-07-01 09:45:21', '2026-07-01 09:45:21'),
(19, 9, '325 UC', 'PUBG-325', 'Tencent', 70000.00, 76000.00, 2, 'On', '2026-07-01 09:45:21', '2026-07-01 09:45:21'),
(20, 10, '80 Tokens', 'HOK-80', 'Level Infinite', 18000.00, 20000.00, 1, 'On', '2026-07-01 09:45:21', '2026-07-01 09:45:21'),
(21, 10, '240 Tokens', 'HOK-240', 'Level Infinite', 50000.00, 55000.00, 2, 'On', '2026-07-01 09:45:21', '2026-07-01 09:45:21'),
(22, 11, '90 Vouchers', 'AOV-90', 'Garena', 17000.00, 19000.00, 1, 'On', '2026-07-01 09:45:21', '2026-07-01 09:45:21'),
(23, 12, '60 Genesis Crystal', 'GI-60', 'HoYoverse', 16000.00, 18000.00, 1, 'On', '2026-07-01 09:45:21', '2026-07-01 09:45:21'),
(24, 12, '330 Genesis Crystal', 'GI-330', 'HoYoverse', 72000.00, 79000.00, 2, 'On', '2026-07-01 09:45:21', '2026-07-01 09:45:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `level` enum('Member','Silver','Gold') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Member',
  `status` enum('On','Off') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'On',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `phone`, `balance`, `level`, `status`, `created_at`, `updated_at`, `remember_token`, `reset_token`, `reset_expires_at`) VALUES
(1, 'kyurie778', 'ihsannulmubin@gmail.com', '$2y$10$wTtd7g4hI6rSNatiooHRXOrGXCkj11KLPIDENfO2WmSWggiXI//dm', '089636230494', 0.00, 'Member', 'On', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `utilities`
--

CREATE TABLE `utilities` (
  `id` int UNSIGNED NOT NULL,
  `u_key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `u_value` longtext COLLATE utf8mb4_general_ci,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'text',
  `description` text COLLATE utf8mb4_general_ci,
  `is_public` enum('Y','N') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Y',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `credentials`
--
ALTER TABLE `credentials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provider_c_key_mode` (`provider`,`c_key`,`mode`),
  ADD KEY `provider` (`provider`),
  ADD KEY `mode` (`mode`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `flashsale`
--
ALTER TABLE `flashsale`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `flashsale_items`
--
ALTER TABLE `flashsale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_flashsale` (`flashsale_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `game_category_id` (`game_category_id`),
  ADD KEY `status` (`status`),
  ADD KEY `is_popular` (`is_popular`);

--
-- Indexes for table `game_categories`
--
ALTER TABLE `game_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `status` (`status`);

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
  ADD UNIQUE KEY `invoice` (`invoice`),
  ADD KEY `orders_product_id_foreign` (`product_id`),
  ADD KEY `orders_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider` (`provider`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `games_id` (`games_id`),
  ADD KEY `sku` (`sku`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `utilities`
--
ALTER TABLE `utilities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_key` (`u_key`),
  ADD KEY `is_public` (`is_public`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `credentials`
--
ALTER TABLE `credentials`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flashsale`
--
ALTER TABLE `flashsale`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `flashsale_items`
--
ALTER TABLE `flashsale_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `game_categories`
--
ALTER TABLE `game_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `utilities`
--
ALTER TABLE `utilities`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `flashsale_items`
--
ALTER TABLE `flashsale_items`
  ADD CONSTRAINT `fk_flashsale_items_flashsale` FOREIGN KEY (`flashsale_id`) REFERENCES `flashsale` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_flashsale_items_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_game_category_id_foreign` FOREIGN KEY (`game_category_id`) REFERENCES `game_categories` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `orders_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_games_id_foreign` FOREIGN KEY (`games_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
