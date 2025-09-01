-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 04, 2025 at 01:24 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jetstream_livewire`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('asset','liability','equity','income','expense') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'asset',
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accounts_account_code_unique` (`account_code`),
  KEY `accounts_created_by_foreign` (`created_by`),
  KEY `accounts_updated_by_foreign` (`updated_by`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `account_name`, `account_code`, `account_type`, `opening_balance`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Cash', 'CASH001', 'asset', '1000.00', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Bank', 'BANK001', 'asset', '5000.00', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Sales', 'SALES001', 'income', '0.00', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Receivables', 'REC001', 'asset', '2000.00', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_branch_code_unique` (`branch_code`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `branch_code`, `location`, `contact_number`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Kiribathgoda', 'MGK', 'Kiribathgoda', '0123656655', 'Kiribathgoda', NULL, '2025-04-01 19:51:42', '2025-04-01 19:51:42'),
(2, 'Maharagama', 'MGM', 'Maharagama', '0123654477', 'Maharagama', NULL, '2025-04-01 19:51:42', '2025-04-01 19:51:42');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `brand_logo` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_brand_name_unique` (`brand_name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brand_name`, `brand_description`, `brand_logo`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Goodyear', 'Leading rubber manufacturer', NULL, 'active', '2025-03-06 10:59:19', '2025-03-06 10:59:19', NULL),
(2, 'Michelin', 'High-performance rubber products', NULL, 'active', '2025-03-06 10:59:57', '2025-03-06 10:59:57', NULL),
(3, 'Bridgestone', 'Tire and industrial rubber products', NULL, 'active', '2025-03-06 11:00:21', '2025-03-06 11:00:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('21f269a6ff45e8dd3e92aceeb57c1575:timer', 'i:1741574069;', 1741574069),
('21f269a6ff45e8dd3e92aceeb57c1575', 'i:1;', 1741574069),
('356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1742536359;', 1742536359),
('356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1742536359),
('kanishka@gmail.com|127.0.0.1:timer', 'i:1741574069;', 1741574069),
('kanishka@gmail.com|127.0.0.1', 'i:1;', 1741574069),
('c525a5357e97fef8d3db25841c86da1a:timer', 'i:1743701079;', 1743701079),
('c525a5357e97fef8d3db25841c86da1a', 'i:3;', 1743701079),
('8e489fb2e0ad1a8a3701160446a03b6a:timer', 'i:1743305554;', 1743305554),
('8e489fb2e0ad1a8a3701160446a03b6a', 'i:1;', 1743305554),
('8d6207cd75181254abd6038a2904404c:timer', 'i:1743383982;', 1743383982),
('8d6207cd75181254abd6038a2904404c', 'i:1;', 1743383982),
('974cb3f1f29fb34ecf8393fa13d8b282:timer', 'i:1743384129;', 1743384129),
('974cb3f1f29fb34ecf8393fa13d8b282', 'i:1;', 1743384129),
('52d8db826e29758ab4073b6c19d3a91c:timer', 'i:1743701110;', 1743701110),
('52d8db826e29758ab4073b6c19d3a91c', 'i:1;', 1743701110),
('admin@gmail.com|127.0.0.1:timer', 'i:1743701079;', 1743701079),
('admin@gmail.com|127.0.0.1', 'i:3;', 1743701079),
('1c31ecdcf43a4c45335e125fdd661c66:timer', 'i:1743701163;', 1743701163),
('1c31ecdcf43a4c45335e125fdd661c66', 'i:1;', 1743701163),
('b1fc577b6333e50aa85f49407067475c:timer', 'i:1743543625;', 1743543625),
('b1fc577b6333e50aa85f49407067475c', 'i:1;', 1743543625),
('83231cb191b00e213ab490519030a0cd:timer', 'i:1743757170;', 1743757170),
('83231cb191b00e213ab490519030a0cd', 'i:1;', 1743757170),
('2729d756f43032b55b8ece1b2bd84f0a:timer', 'i:1743746539;', 1743746539),
('2729d756f43032b55b8ece1b2bd84f0a', 'i:1;', 1743746539),
('b8c0b6c36cb6b4fa906297c369584313:timer', 'i:1743746454;', 1743746454),
('b8c0b6c36cb6b4fa906297c369584313', 'i:1;', 1743746454),
('695d5d2e4e11153843353049bbd482ef:timer', 'i:1743617248;', 1743617248),
('695d5d2e4e11153843353049bbd482ef', 'i:1;', 1743617248),
('admin@|127.0.0.1:timer', 'i:1743617248;', 1743617248),
('admin@|127.0.0.1', 'i:1;', 1743617248),
('0dabc4b6dfbd7f70e305726ccd4562a8:timer', 'i:1743619070;', 1743619070),
('0dabc4b6dfbd7f70e305726ccd4562a8', 'i:2;', 1743619070),
('acco1|127.0.0.1:timer', 'i:1743619070;', 1743619070),
('acco1|127.0.0.1', 'i:2;', 1743619070),
('print@gmail.com|127.0.0.1:timer', 'i:1743701110;', 1743701110),
('print@gmail.com|127.0.0.1', 'i:1;', 1743701110);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `city`, `country`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Kanishka Kodithuwakku', 'infomail.kbk@gmail.com', '0711536713', '10, Nandani, Kotamuduna, Passara.', 'Passara', 'Sri Lanka', 'active', '2025-03-08 12:16:27', '2025-03-08 12:16:27', NULL),
(2, 'Test Gallage', 'infddddmail.kbk@gmail.com', '0711536713', '10, Nandani, Kotamuduna, Passara.', 'Passara', 'Sri Lanka', 'active', '2025-03-08 12:28:31', '2025-03-08 12:28:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

DROP TABLE IF EXISTS `customer_orders`;
CREATE TABLE IF NOT EXISTS `customer_orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` bigint UNSIGNED NOT NULL,
  `order_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('plan','released','invoiced','printed','delivered','cancelled','deleted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plan',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `advance_payment` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('cash','bank','credit','cheque') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_realization_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_orders_order_number_unique` (`order_number`),
  KEY `customer_orders_customer_id_foreign` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_order_items`
--

DROP TABLE IF EXISTS `customer_order_items`;
CREATE TABLE IF NOT EXISTS `customer_order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_order_items_order_id_foreign` (`order_id`),
  KEY `customer_order_items_item_id_foreign` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dispatch_items`
--

DROP TABLE IF EXISTS `dispatch_items`;
CREATE TABLE IF NOT EXISTS `dispatch_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `dispatch_id` bigint UNSIGNED NOT NULL,
  `job_order_item_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `branch_assigned` bigint UNSIGNED DEFAULT NULL,
  `branch_done` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dispatch_items_item_id_foreign` (`item_id`),
  KEY `dispatch_items_order_id_foreign` (`order_id`),
  KEY `dispatch_items_dispatch_id_foreign` (`dispatch_id`),
  KEY `dispatch_items_job_order_item_id_foreign` (`job_order_item_id`),
  KEY `dispatch_items_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dispatch_items`
--

INSERT INTO `dispatch_items` (`id`, `item_id`, `order_id`, `dispatch_id`, `job_order_item_id`, `user_id`, `quantity`, `total_amount`, `status`, `branch_assigned`, `branch_done`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, 1, 3, 10, '5000.00', '7500.00', 1, 1, '2025-03-30 10:30:19', '2025-03-30 10:30:49'),
(2, 4, 1, 1, 2, 3, 10, '5000.00', '7500.00', 1, 1, '2025-03-30 10:30:19', '2025-03-30 10:30:49'),
(3, 3, 1, 2, 1, 3, 5, '2500.00', '7500.00', 1, 1, '2025-03-30 10:31:13', '2025-03-30 10:31:19'),
(4, 4, 1, 2, 2, 3, 5, '2500.00', '7500.00', 1, 1, '2025-03-30 10:31:13', '2025-03-30 10:31:19'),
(5, 3, 2, 3, 3, 3, 10, '5000.00', '6500.00', 1, 1, '2025-03-30 19:44:54', '2025-03-30 19:45:10'),
(6, 5, 2, 3, 4, 3, 5, '2500.00', '3500.00', 1, 1, '2025-03-30 19:44:54', '2025-03-30 19:45:10'),
(7, 4, 2, 3, 5, 3, 4, '2000.00', '2000.00', 1, 1, '2025-03-30 19:44:54', '2025-03-30 19:45:10'),
(8, 3, 2, 4, 3, 3, 3, '1500.00', '6500.00', 1, 1, '2025-03-30 19:45:18', '2025-03-30 19:45:23'),
(9, 5, 2, 4, 4, 3, 2, '1000.00', '3500.00', 1, 1, '2025-03-30 19:45:18', '2025-03-30 19:45:23'),
(11, 6, 4, 5, 7, 3, 5, '2500.00', '5000.00', 1, 1, '2025-03-30 19:50:10', '2025-03-30 19:50:19'),
(12, 3, 4, 5, 8, 3, 2, '1000.00', '1000.00', 1, 1, '2025-03-30 19:50:10', '2025-03-30 19:50:19'),
(13, 6, 4, 6, 7, 3, 5, '2500.00', '5000.00', 1, 1, '2025-03-30 19:50:33', '2025-03-30 19:50:40'),
(15, 4, 25, 7, 25, 11, 3, '1500.00', '3000.00', 1, 1, '2025-04-01 21:57:51', '2025-04-01 21:58:59'),
(16, 4, 25, 8, 25, 11, 3, '1500.00', '3000.00', 1, 1, '2025-04-01 22:02:59', '2025-04-01 22:05:45'),
(17, 3, 24, 9, 24, 11, 4, '2000.00', '2000.00', 1, 1, '2025-04-02 18:34:00', '2025-04-02 18:34:05'),
(18, 3, 27, 10, 27, 1, 2, '1000.00', '5000.00', 1, 1, '2025-04-02 18:59:51', '2025-04-02 18:59:57'),
(19, 3, 27, 11, 27, 1, 8, '4000.00', '5000.00', 1, 1, '2025-04-02 19:04:30', '2025-04-02 19:04:39'),
(20, 3, 19, 12, 19, 1, 1, '500.00', '500.00', 1, 1, '2025-04-03 18:32:34', '2025-04-03 18:32:46'),
(21, 4, 28, 13, 28, 11, 1, '500.00', '5000.00', 1, 1, '2025-04-04 05:23:38', '2025-04-04 05:26:00'),
(22, 4, 28, 14, 28, 11, 9, '4500.00', '5000.00', 1, 1, '2025-04-04 05:26:10', '2025-04-04 05:26:40'),
(23, 4, 29, 15, 29, 11, 5, '2500.00', '2500.00', 1, 1, '2025-04-04 06:01:36', '2025-04-04 06:01:55');

-- --------------------------------------------------------

--
-- Table structure for table `dispatch_notes`
--

DROP TABLE IF EXISTS `dispatch_notes`;
CREATE TABLE IF NOT EXISTS `dispatch_notes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `dispatch_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_order_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `balance_qty` int NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `dispatched_at` timestamp NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dispatch_notes_dispatch_number_unique` (`dispatch_number`),
  KEY `dispatch_notes_job_order_id_foreign` (`job_order_id`),
  KEY `dispatch_notes_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dispatch_notes`
--

INSERT INTO `dispatch_notes` (`id`, `dispatch_number`, `job_order_id`, `user_id`, `quantity`, `balance_qty`, `total_amount`, `status`, `dispatched_at`, `description`, `created_at`, `updated_at`) VALUES
(1, 'DN-25-03-00001', 1, 3, 0, 0, '0.00', 'complete', '2025-03-30 10:30:19', '', '2025-03-30 10:30:19', '2025-03-30 10:30:49'),
(2, 'DN-25-03-00002', 1, 3, 0, 0, '0.00', 'complete', '2025-03-30 10:31:13', '', '2025-03-30 10:31:13', '2025-03-30 10:31:19'),
(3, 'DN-25-03-00003', 2, 3, 0, 0, '0.00', 'complete', '2025-03-30 19:44:54', '', '2025-03-30 19:44:54', '2025-03-30 19:45:10'),
(4, 'DN-25-03-00004', 2, 3, 0, 0, '0.00', 'complete', '2025-03-30 19:45:18', '', '2025-03-30 19:45:18', '2025-03-30 19:45:23'),
(5, 'DN-25-03-00005', 4, 3, 0, 0, '0.00', 'complete', '2025-03-30 19:50:10', '', '2025-03-30 19:50:10', '2025-03-30 19:50:19'),
(6, 'DN-25-03-00006', 4, 3, 0, 0, '0.00', 'complete', '2025-03-30 19:50:33', '', '2025-03-30 19:50:33', '2025-03-30 19:50:40'),
(7, 'DN-25-04-00001', 25, 11, 3, 0, '0.00', 'complete', '2025-04-01 21:57:51', '', '2025-04-01 21:57:51', '2025-04-01 21:58:59'),
(8, 'DN-25-04-00002', 25, 11, 3, 0, '0.00', 'complete', '2025-04-01 22:02:59', '', '2025-04-01 22:02:59', '2025-04-01 22:05:45'),
(9, 'DN-25-04-00003', 24, 11, 4, 0, '0.00', 'complete', '2025-04-02 18:34:00', '', '2025-04-02 18:34:00', '2025-04-02 18:34:05'),
(10, 'DN-25-04-00004', 27, 1, 2, 0, '0.00', 'complete', '2025-04-02 18:59:51', '', '2025-04-02 18:59:51', '2025-04-02 18:59:57'),
(11, 'DN-25-04-00005', 27, 1, 8, 0, '0.00', 'complete', '2025-04-02 19:04:30', '', '2025-04-02 19:04:30', '2025-04-02 19:04:39'),
(12, 'DN-25-04-00006', 19, 1, 1, 0, '0.00', 'complete', '2025-04-03 18:32:34', '', '2025-04-03 18:32:34', '2025-04-03 18:32:46'),
(13, 'DN-25-04-00007', 28, 11, 1, 0, '0.00', 'complete', '2025-04-04 05:23:38', '', '2025-04-04 05:23:38', '2025-04-04 05:26:00'),
(14, 'DN-25-04-00008', 28, 11, 9, 0, '0.00', 'complete', '2025-04-04 05:26:10', '', '2025-04-04 05:26:10', '2025-04-04 05:26:40'),
(15, 'DN-25-04-00009', 29, 11, 5, 0, '0.00', 'complete', '2025-04-04 06:01:36', '', '2025-04-04 06:01:36', '2025-04-04 06:01:55');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grns`
--

DROP TABLE IF EXISTS `grns`;
CREATE TABLE IF NOT EXISTS `grns` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `grn_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_type` enum('PO','MR') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PO',
  `order_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `grn_date` date NOT NULL,
  `eff_date` date NOT NULL DEFAULT '2025-03-18',
  `total_amount` decimal(15,2) NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `delivery_date` date NOT NULL,
  `delivery_location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_remark` text COLLATE utf8mb4_unicode_ci,
  `grn_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grns_grn_code_unique` (`grn_code`),
  KEY `grns_supplier_id_foreign` (`supplier_id`),
  KEY `grns_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grns`
--

INSERT INTO `grns` (`id`, `grn_code`, `order_type`, `order_id`, `user_id`, `supplier_id`, `grn_date`, `eff_date`, `total_amount`, `remark`, `delivery_date`, `delivery_location`, `delivery_remark`, `grn_type`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'GRN-25-03-00001', 'PO', 1, 1, 1, '2025-03-26', '2025-03-27', '0.00', 'Test', '2025-03-27', 'Main WH', 'call befor come', 'purchase', 'completed', '2025-03-26 08:15:35', '2025-03-26 13:16:03', NULL),
(2, 'GRN-25-03-00002', 'PO', 1, 1, 1, '2025-03-26', '2025-03-27', '0.00', 'e', '2025-03-27', 'we', 'e', 'purchase', 'completed', '2025-03-26 15:22:11', '2025-03-26 15:22:41', NULL),
(3, 'GRN-25-03-00003', 'PO', 1, 1, 1, '2025-03-27', '2025-03-28', '0.00', 'rer', '2025-03-28', 'e3', 'rerer', 'purchase', 'completed', '2025-03-27 02:08:16', '2025-03-29 03:11:31', NULL),
(4, 'GRN-25-03-00004', 'PO', 1, 1, 1, '2025-03-29', '2025-03-30', '0.00', 'sdsdsds', '2025-03-30', 'tets', 'sdsdsd', 'purchase', 'completed', '2025-03-29 08:45:34', '2025-03-30 09:48:50', NULL),
(5, 'GRN-25-03-00005', 'PO', 1, 2, 1, '2025-03-30', '2025-05-31', '2000.00', 're', '2025-05-31', 'Tst', 'ts', 'purchase', 'completed', '2025-03-30 10:06:50', '2025-03-30 10:11:31', NULL),
(6, 'GRN-25-04-00001', 'PO', 1, 1, 1, '2025-04-02', '2025-04-03', '400.00', 'sdsdsd', '2025-04-03', 'zsdsdsd', 'dsdsd', 'purchase', 'pending', '2025-04-02 07:10:19', '2025-04-02 15:24:02', NULL),
(7, 'GRN-25-04-00002', 'PO', 1, 3, 1, '2025-04-04', '2025-04-05', '0.00', 'dfd', '2025-04-05', 'Main Warehouse', 'dd', 'purchase', 'pending', '2025-04-04 05:18:06', '2025-04-04 05:18:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `grn_items`
--

DROP TABLE IF EXISTS `grn_items`;
CREATE TABLE IF NOT EXISTS `grn_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `grn_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `brands_id` bigint UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint UNSIGNED DEFAULT NULL,
  `location_id` bigint UNSIGNED DEFAULT NULL,
  `uom_id` bigint UNSIGNED DEFAULT NULL,
  `order_type` enum('PO','MR') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PO',
  `order_item_id` bigint UNSIGNED DEFAULT NULL,
  `batch` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lot` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_quantity` int NOT NULL,
  `quantity` int NOT NULL,
  `purchase_price` decimal(15,2) NOT NULL,
  `selling_price` decimal(15,2) NOT NULL,
  `mrp` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grn_items_sku_code_unique` (`sku_code`),
  KEY `grn_items_grn_id_foreign` (`grn_id`),
  KEY `grn_items_item_id_foreign` (`item_id`),
  KEY `grn_items_brands_id_foreign` (`brands_id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grn_items`
--

INSERT INTO `grn_items` (`id`, `grn_id`, `item_id`, `brands_id`, `warehouse_id`, `location_id`, `uom_id`, `order_type`, `order_item_id`, `batch`, `lot`, `color`, `sku_code`, `order_quantity`, `quantity`, `purchase_price`, `selling_price`, `mrp`, `total`, `created_at`, `updated_at`) VALUES
(45, 1, 7, 1, NULL, NULL, NULL, 'PO', NULL, NULL, NULL, NULL, 'SKU-1-000003', 0, 10, '200.00', '500.00', '0.00', '5000.00', '2025-03-26 13:06:58', '2025-03-26 13:06:58'),
(44, 1, 3, 1, NULL, NULL, NULL, 'PO', NULL, NULL, NULL, NULL, 'SKU-1-000001', 0, 14, '200.00', '500.00', '0.00', '7000.00', '2025-03-26 13:06:58', '2025-03-26 13:06:58'),
(43, 1, 5, 1, NULL, NULL, NULL, 'PO', NULL, NULL, NULL, NULL, 'SKU-1-000002', 0, 2, '200.00', '500.00', '0.00', '1000.00', '2025-03-26 13:06:58', '2025-03-26 13:06:58'),
(46, 2, 5, 1, NULL, NULL, NULL, 'PO', NULL, NULL, NULL, NULL, 'SKU-1-000004', 0, 3, '200.00', '500.00', '0.00', '1500.00', '2025-03-26 15:22:35', '2025-03-26 15:22:35'),
(49, 3, 3, 1, NULL, NULL, NULL, 'PO', NULL, NULL, NULL, NULL, 'SKU-1-000005', 0, 40, '200.00', '500.00', '0.00', '20000.00', '2025-03-29 03:11:24', '2025-03-29 03:11:24'),
(50, 3, 4, 1, NULL, NULL, NULL, 'PO', NULL, NULL, NULL, NULL, 'SKU-1-000006', 0, 50, '200.00', '500.00', '0.00', '25000.00', '2025-03-29 03:11:24', '2025-03-29 03:11:24'),
(51, 3, 5, 1, NULL, NULL, NULL, 'PO', NULL, NULL, NULL, NULL, 'SKU-1-000007', 0, 60, '200.00', '500.00', '0.00', '30000.00', '2025-03-29 03:11:24', '2025-03-29 03:11:24'),
(61, 5, 3, 1, NULL, NULL, NULL, 'PO', NULL, NULL, NULL, NULL, 'SKU-1-000011', 0, 5, '200.00', '500.00', '0.00', '1000.00', '2025-03-30 10:11:25', '2025-03-30 10:11:25'),
(60, 4, 6, 1, NULL, NULL, NULL, 'PO', NULL, NULL, NULL, NULL, 'SKU-1-000010', 0, 100, '200.00', '500.00', '0.00', '50000.00', '2025-03-30 09:48:48', '2025-03-30 09:48:48'),
(62, 5, 6, 1, NULL, NULL, NULL, 'PO', NULL, NULL, NULL, NULL, 'SKU-1-000012', 0, 5, '200.00', '500.00', '0.00', '1000.00', '2025-03-30 10:11:25', '2025-03-30 10:11:25'),
(64, 6, 3, 1, NULL, NULL, NULL, 'PO', NULL, NULL, NULL, NULL, 'SKU-1-000013', 0, 1, '200.00', '500.00', '0.00', '200.00', '2025-04-02 15:24:02', '2025-04-02 15:24:02'),
(65, 6, 4, 1, NULL, NULL, NULL, 'PO', NULL, NULL, NULL, NULL, 'SKU-1-000014', 0, 1, '200.00', '500.00', '0.00', '200.00', '2025-04-02 15:24:02', '2025-04-02 15:24:02');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `order_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('released','invoiced','printed','delivered','cancelled','deleted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'released',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `advance_payment` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('cash','bank','credit','cheque') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_realization_date` date DEFAULT NULL,
  `invoice_details` text COLLATE utf8mb4_unicode_ci,
  `backed_plates_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `print_count` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_customer_id_foreign` (`customer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `order_id`, `order_type`, `customer_id`, `invoice_number`, `status`, `total_amount`, `advance_payment`, `payment_method`, `bank_name`, `cheque_no`, `cheque_realization_date`, `invoice_details`, `backed_plates_price`, `created_at`, `updated_at`, `deleted_at`, `print_count`) VALUES
(1, 1, 'App\\Models\\JobOrder', 1, 'INV-25-03-00001', 'invoiced', '1000.00', '0.00', NULL, NULL, NULL, NULL, NULL, '0.00', '2025-03-29 03:26:10', '2025-03-29 03:26:10', NULL, 0),
(2, 4, 'App\\Models\\JobOrder', 1, 'INV-25-03-00002', 'invoiced', '2500.00', '0.00', NULL, NULL, NULL, NULL, NULL, '0.00', '2025-03-29 23:25:27', '2025-03-29 23:25:27', NULL, 0),
(3, 11, 'App\\Models\\JobOrder', 1, 'INV-25-03-00003', 'invoiced', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, '0.00', '2025-03-30 06:51:15', '2025-03-30 06:51:15', NULL, 0),
(4, 18, 'App\\Models\\JobOrder', 1, 'INV-25-03-00004', 'invoiced', '1000.00', '0.00', NULL, NULL, NULL, NULL, NULL, '0.00', '2025-03-30 10:14:51', '2025-03-30 10:17:57', NULL, 1),
(5, 2, 'App\\Models\\JobOrder', 1, 'INV-25-03-00005', 'invoiced', '12000.00', '0.00', NULL, NULL, NULL, NULL, NULL, '0.00', '2025-03-30 19:45:58', '2025-03-30 19:45:58', NULL, 0),
(6, 2, 'App\\Models\\JobOrder', 1, 'INV-25-03-00006', 'invoiced', '12000.00', '0.00', NULL, NULL, NULL, NULL, NULL, '0.00', '2025-03-30 19:46:07', '2025-03-30 19:46:07', NULL, 0),
(7, 4, 'App\\Models\\JobOrder', 1, 'INV-25-03-00007', 'invoiced', '6000.00', '0.00', NULL, NULL, NULL, NULL, NULL, '0.00', '2025-03-30 19:51:54', '2025-03-30 19:51:54', NULL, 0),
(8, 24, 'App\\Models\\JobOrder', 1, 'INV-25-04-00001', 'invoiced', '2000.00', '0.00', NULL, NULL, NULL, NULL, NULL, '0.00', '2025-04-02 18:37:55', '2025-04-02 18:37:55', NULL, 0),
(9, 27, 'App\\Models\\JobOrder', 1, 'INV-25-04-00002', 'invoiced', '5000.00', '0.00', NULL, NULL, NULL, NULL, NULL, '0.00', '2025-04-02 19:05:49', '2025-04-02 19:06:06', NULL, 4),
(10, 19, 'App\\Models\\JobOrder', 1, 'INV-25-04-00003', 'invoiced', '3000.00', '0.00', NULL, NULL, NULL, NULL, NULL, '1000.00', '2025-04-03 19:07:33', '2025-04-04 13:18:11', NULL, 12),
(12, 28, 'App\\Models\\JobOrder', 1, 'INV-25-04-00004', '', '5300.00', '0.00', NULL, NULL, NULL, NULL, NULL, '100.00', '2025-04-04 05:48:42', '2025-04-04 05:57:38', NULL, 2),
(13, 25, 'App\\Models\\JobOrder', 2, 'INV-25-04-00005', '', '3200.00', '0.00', NULL, NULL, NULL, NULL, NULL, '100.00', '2025-04-04 08:59:48', '2025-04-04 09:32:47', NULL, 16);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
CREATE TABLE IF NOT EXISTS `invoice_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_items_item_id_foreign` (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `item_id`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 4, '500.00', '2000.00', '2025-03-29 03:26:10', '2025-03-29 03:26:10'),
(2, 1, 3, 2, '500.00', '1000.00', '2025-03-29 03:26:10', '2025-03-29 03:26:10'),
(3, 2, 3, 1, '500.00', '500.00', '2025-03-29 23:25:27', '2025-03-29 23:25:27'),
(4, 3, 3, 2, '500.00', '1000.00', '2025-03-30 06:51:15', '2025-03-30 06:51:15'),
(5, 4, 3, 2, '500.00', '1000.00', '2025-03-30 10:14:51', '2025-03-30 10:14:51'),
(6, 5, 3, 13, '500.00', '6500.00', '2025-03-30 19:45:58', '2025-03-30 19:45:58'),
(7, 5, 5, 7, '500.00', '3500.00', '2025-03-30 19:45:58', '2025-03-30 19:45:58'),
(8, 5, 4, 4, '500.00', '2000.00', '2025-03-30 19:45:58', '2025-03-30 19:45:58'),
(9, 6, 3, 13, '500.00', '6500.00', '2025-03-30 19:46:07', '2025-03-30 19:46:07'),
(10, 6, 5, 7, '500.00', '3500.00', '2025-03-30 19:46:07', '2025-03-30 19:46:07'),
(11, 6, 4, 4, '500.00', '2000.00', '2025-03-30 19:46:07', '2025-03-30 19:46:07'),
(12, 7, 6, 10, '500.00', '5000.00', '2025-03-30 19:51:54', '2025-03-30 19:51:54'),
(13, 7, 3, 2, '500.00', '1000.00', '2025-03-30 19:51:54', '2025-03-30 19:51:54'),
(14, 8, 3, 4, '500.00', '2000.00', '2025-04-02 18:37:55', '2025-04-02 18:37:55'),
(15, 9, 3, 10, '500.00', '5000.00', '2025-04-02 19:05:49', '2025-04-02 19:05:49'),
(16, 10, 3, 1, '1000.00', '1000.00', '2025-04-03 19:07:33', '2025-04-04 02:39:57'),
(17, 11, 4, 10, '500.00', '5000.00', '2025-04-04 05:42:26', '2025-04-04 05:42:26'),
(18, 12, 4, 10, '500.00', '5000.00', '2025-04-04 05:48:42', '2025-04-04 05:55:49'),
(19, 13, 4, 6, '500.00', '3000.00', '2025-04-04 08:59:48', '2025-04-04 09:19:24');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `brands_id` bigint UNSIGNED DEFAULT NULL,
  `item_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_description` text COLLATE utf8mb4_unicode_ci,
  `item_short_description` text COLLATE utf8mb4_unicode_ci,
  `mrp` decimal(10,2) DEFAULT NULL,
  `uom` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `item_type` enum('RW','FG','SR','PR') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FG',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `returnable` tinyint(1) NOT NULL DEFAULT '0',
  `dimensions` json DEFAULT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `mpn` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isbn` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ean` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_price` decimal(10,2) DEFAULT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `sales_account` bigint UNSIGNED DEFAULT NULL,
  `purchase_account` bigint UNSIGNED DEFAULT NULL,
  `sales_tax` bigint UNSIGNED DEFAULT NULL,
  `purchase_tax` bigint UNSIGNED DEFAULT NULL,
  `preferred_vendor` bigint UNSIGNED DEFAULT NULL,
  `inventory_account` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_item_code_unique` (`item_code`),
  KEY `items_brands_id_foreign` (`brands_id`),
  KEY `items_sales_account_foreign` (`sales_account`),
  KEY `items_purchase_account_foreign` (`purchase_account`),
  KEY `items_sales_tax_foreign` (`sales_tax`),
  KEY `items_purchase_tax_foreign` (`purchase_tax`),
  KEY `items_preferred_vendor_foreign` (`preferred_vendor`),
  KEY `items_inventory_account_foreign` (`inventory_account`),
  KEY `items_created_by_foreign` (`created_by`),
  KEY `items_updated_by_foreign` (`updated_by`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `brands_id`, `item_code`, `item_name`, `item_description`, `item_short_description`, `mrp`, `uom`, `discount`, `item_type`, `status`, `returnable`, `dimensions`, `weight`, `mpn`, `isbn`, `upc`, `ean`, `sales_price`, `purchase_price`, `sales_account`, `purchase_account`, `sales_tax`, `purchase_tax`, `preferred_vendor`, `inventory_account`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'ITEM-PLATE-550', 'Plate 550', 'CTOP KORD 0550', 'CTOP KORD 0550', '1000.00', 'PLATE', NULL, 'RW', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', 1, 1, NULL, NULL, 1, 1, NULL, NULL, NULL, '2025-03-19 00:47:41', '2025-03-19 00:47:41'),
(2, 1, 'ITEM-PLATE-650', 'Plate 650', NULL, 'Des', '1000.00', 'PLATE', NULL, 'RW', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', 1, 1, NULL, NULL, 1, 1, NULL, NULL, NULL, '2025-03-19 00:52:13', '2025-03-19 09:09:37'),
(3, 1, 'ITEM-R-001', 'Natural Rubber Latex', 'Natural Rubber Latex', 'Natural Rubber Latex', '0.00', '1', '0.00', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(4, 1, 'ITEM-R-002', 'Synthetic Rubber (SBR - Styrene Butadiene Rubber)', 'Synthetic Rubber (SBR - Styrene Butadiene Rubber)', 'Synthetic Rubber (SBR - Styrene Butadiene Rubber)', '0.00', '1', '0.00', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(5, 1, 'ITEM-R-003', 'Butyl Rubber (IIR - Isobutylene Isoprene Rubber)', 'Butyl Rubber (IIR - Isobutylene Isoprene Rubber)', 'Butyl Rubber (IIR - Isobutylene Isoprene Rubber)', '0.00', '1', '0.00', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(6, 1, 'ITEM-R-004', 'EPDM Rubber (Ethylene Propylene Diene Monomer)', 'EPDM Rubber (Ethylene Propylene Diene Monomer)', 'EPDM Rubber (Ethylene Propylene Diene Monomer)', '0.00', '1', '0.00', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(7, 1, 'ITEM-R-005', 'Silicone Rubber Compound', 'Silicone Rubber Compound', 'Silicone Rubber Compound', '0.00', '1', '0.00', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(8, 1, 'ITEM-R-006', 'Carbon Black Filler', 'Carbon Black Filler', 'Carbon Black Filler', '0.00', '1', '0.00', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(9, 1, 'ITEM-R-007', 'Rubber Processing Oil', 'Rubber Processing Oil', 'Rubber Processing Oil', '0.00', '1', '0.00', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(10, 1, 'ITEM-R-008', 'Vulcanization Agents (Sulfur & Accelerators)', 'Vulcanization Agents (Sulfur & Accelerators)', 'Vulcanization Agents (Sulfur & Accelerators)', '0.00', '1', '0.00', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(11, 1, 'ITEM-F-001', 'Automotive Rubber Seals', 'Automotive Rubber Seals', 'Automotive Rubber Seals', '6400.00', '1', '0.20', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(12, 1, 'ITEM-F-002', 'Industrial Rubber Sheets', 'Industrial Rubber Sheets', 'Industrial Rubber Sheets', '4300.00', '1', '0.00', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(13, 1, 'ITEM-F-003', 'Rubber Conveyor Belts', 'Rubber Conveyor Belts', 'Rubber Conveyor Belts', '2600.00', '1', '0.00', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(14, 1, 'ITEM-F-004', 'Rubber O-Rings and Gaskets', 'Rubber O-Rings and Gaskets', 'Rubber O-Rings and Gaskets', '1500.00', '1', '0.00', 'FG', 'inactive', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(15, 1, 'ITEM-F-005', 'Rubber Hoses (High-Pressure & Industrial)', 'Rubber Hoses (High-Pressure & Industrial)', 'Rubber Hoses (High-Pressure & Industrial)', '2000.00', '1', '0.00', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(16, 1, 'ITEM-F-006', 'Rubber Footwear (Soles & Sandals)', 'Rubber Footwear (Soles & Sandals)', 'Rubber Footwear (Soles & Sandals)', '4500.00', '1', '0.10', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(17, 1, 'ITEM-F-007', 'Rubber Gloves (Medical & Industrial Use)', 'Rubber Gloves (Medical & Industrial Use)', 'Rubber Gloves (Medical & Industrial Use)', '400.00', '1', '0.00', 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', '200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-21 00:21:41', '2025-03-21 00:21:41'),
(18, 1, NULL, 'thfghfgfgf', '4545', '44', '2333.00', '1', NULL, 'FG', 'active', 1, NULL, NULL, NULL, NULL, NULL, NULL, '54666.00', NULL, 1, NULL, 88, 55, 1, 1, NULL, NULL, NULL, '2025-03-29 05:50:16', '2025-03-29 05:50:16'),
(19, 1, NULL, 'sdsdsdsdsds', 'sdsd', 'sdsdsd', '646.00', '1', NULL, 'FG', 'active', 1, NULL, NULL, NULL, NULL, NULL, NULL, '56456.00', NULL, 1, NULL, 56, 55, 1, 1, NULL, NULL, NULL, '2025-03-29 05:51:17', '2025-03-29 05:51:17'),
(20, 1, NULL, 'asasasasasasasa', '55', '55', '5454.00', '1', NULL, 'FG', 'active', 1, NULL, NULL, NULL, NULL, NULL, NULL, '545.00', NULL, 1, NULL, 55, 555, 1, 1, NULL, NULL, NULL, '2025-03-29 05:55:10', '2025-03-29 05:55:10'),
(21, 1, 'ITEM-25-03-00001', 'dsdsdsdererefdscfsf', '4245', '2121421', '14514.00', '1', NULL, 'FG', 'active', 1, NULL, NULL, NULL, NULL, NULL, NULL, '21212.00', NULL, 1, NULL, 445, 44, 1, 1, NULL, NULL, NULL, '2025-03-29 05:56:27', '2025-03-29 05:56:27'),
(22, 1, 'ITEM-25-03-00002', 'sff3343', NULL, NULL, '122.22', NULL, NULL, 'FG', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-29 06:12:07', '2025-03-29 06:12:07'),
(23, NULL, 'ITEM-25-03-00003', 'TEST 1', NULL, NULL, NULL, NULL, NULL, 'RW', 'active', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-29 09:37:29', '2025-03-29 09:37:29'),
(24, 1, 'ITEM-25-03-00004', 'TEST2', 'testd', 'test', '1000.00', '1', NULL, 'FG', 'active', 1, NULL, NULL, NULL, NULL, NULL, NULL, '1500.00', NULL, 1, NULL, 10, 5, 1, 1, NULL, NULL, NULL, '2025-03-29 09:50:41', '2025-03-29 10:01:33');

-- --------------------------------------------------------

--
-- Table structure for table `item_details`
--

DROP TABLE IF EXISTS `item_details`;
CREATE TABLE IF NOT EXISTS `item_details` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` bigint UNSIGNED NOT NULL,
  `attribute_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribute_value` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_details_item_id_foreign` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_orders`
--

DROP TABLE IF EXISTS `job_orders`;
CREATE TABLE IF NOT EXISTS `job_orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_po_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `assign_to` bigint UNSIGNED DEFAULT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `special_instruction` text COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(15,2) NOT NULL,
  `job_done_by` bigint UNSIGNED DEFAULT NULL,
  `job_checked_by` bigint UNSIGNED DEFAULT NULL,
  `delivery_date` timestamp NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `plate_backing` tinyint(1) NOT NULL DEFAULT '0',
  `backing_qty` int DEFAULT NULL,
  `print_count` int NOT NULL DEFAULT '0',
  `reorder_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `job_orders_job_number_unique` (`job_number`),
  KEY `job_orders_assign_to_foreign` (`assign_to`),
  KEY `job_orders_customer_id_foreign` (`customer_id`),
  KEY `job_orders_user_id_foreign` (`user_id`),
  KEY `job_orders_job_done_by_foreign` (`job_done_by`),
  KEY `job_orders_job_checked_by_foreign` (`job_checked_by`),
  KEY `job_orders_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_orders`
--

INSERT INTO `job_orders` (`id`, `job_number`, `customer_po_number`, `date_created`, `customer_id`, `user_id`, `assign_to`, `branch_id`, `description`, `special_instruction`, `total_amount`, `job_done_by`, `job_checked_by`, `delivery_date`, `status`, `plate_backing`, `backing_qty`, `print_count`, `reorder_count`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'JO2500001/1N', 'PO87888', '2025-04-01 20:22:08', 1, 1, 5, 1, 'TEST', 'TEST', '2000.00', NULL, NULL, '2025-04-01 18:30:00', 'printing', 1, 2, 0, 6, '2025-04-01 20:22:08', '2025-04-02 19:30:42', NULL),
(19, 'JO2500001/1N/R1', 'PO87888', '2025-04-01 21:32:46', 1, 1, 1, 1, 'TEST', 'TEST', '500.00', NULL, NULL, '2025-04-01 18:30:00', 'invoiced', 1, 2, 0, 2, '2025-04-01 21:32:46', '2025-04-04 13:18:11', NULL),
(20, 'JO2500001/1N/R2', 'PO87888', '2025-04-01 21:32:53', 1, 1, NULL, 1, 'TEST', 'TEST', '2000.00', NULL, NULL, '2025-04-01 18:30:00', 'pending', 1, 2, 0, 4, '2025-04-01 21:32:53', '2025-04-01 21:33:11', NULL),
(21, 'JO2500001/1N/R3', 'PO87888', '2025-04-01 21:33:02', 1, 1, NULL, 1, 'TEST', 'TEST', '2000.00', NULL, NULL, '2025-04-01 18:30:00', 'pending', 1, 2, 0, 0, '2025-04-01 21:33:02', '2025-04-01 21:33:02', NULL),
(22, 'JO2500001/1N/R4', 'PO87888', '2025-04-01 21:33:11', 1, 1, NULL, 1, 'TEST', 'TEST', '2000.00', NULL, NULL, '2025-04-01 18:30:00', 'pending', 1, 2, 0, 5, '2025-04-01 21:33:11', '2025-04-01 21:34:02', NULL),
(23, 'JO2500001/1N/R5', 'PO87888', '2025-04-01 21:34:02', 1, 1, NULL, 1, 'TEST', 'TEST', '2000.00', NULL, NULL, '2025-04-01 18:30:00', 'pending', 1, 2, 0, 0, '2025-04-01 21:34:02', '2025-04-01 21:34:02', NULL),
(24, 'JO2500001/1N/R6', 'PO87888', '2025-04-01 21:34:07', 1, 1, 5, 1, 'TEST', 'TEST', '2000.00', NULL, NULL, '2025-04-01 18:30:00', 'invoiced', 1, 2, 0, 0, '2025-04-01 21:34:07', '2025-04-02 18:37:55', NULL),
(25, 'JO2500002/1N', 'PO78888', '2025-04-01 21:34:54', 2, 1, 6, 1, 'TEST', 'TEST', '3000.00', NULL, NULL, '2025-04-01 18:30:00', 'invoiced', 1, 2, 0, 1, '2025-04-01 21:34:54', '2025-04-04 09:32:47', NULL),
(26, 'JO2500002/1N/R1', 'PO78888', '2025-04-01 21:35:04', 2, 1, NULL, 1, 'TEST', 'TEST', '3000.00', NULL, NULL, '2025-04-01 18:30:00', 'pending', 1, 2, 0, 0, '2025-04-01 21:35:04', '2025-04-01 21:35:04', NULL),
(27, 'JO2500003/1N', 'PO564561215', '2025-04-02 16:28:03', 1, 1, 5, 1, 'TEST', 'TEST', '5000.00', NULL, NULL, '2025-04-01 18:30:00', 'invoiced', 1, 3, 0, 0, '2025-04-02 16:28:03', '2025-04-02 19:05:49', NULL),
(28, 'JO2500001/3L', '123', '2025-04-04 05:20:45', 1, 3, 5, 1, 'sd', 'sd', '5000.00', NULL, NULL, '2025-04-03 18:30:00', 'invoiced', 1, 3, 0, 0, '2025-04-04 05:20:45', '2025-04-04 05:57:38', NULL),
(29, 'JO2500002/3L', '009', '2025-04-04 05:59:31', 1, 3, 5, 1, 'sss', 'ds', '2500.00', NULL, NULL, '2025-04-03 18:30:00', 'dispatching', 1, 1, 0, 0, '2025-04-04 05:59:31', '2025-04-04 06:01:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_order_items`
--

DROP TABLE IF EXISTS `job_order_items`;
CREATE TABLE IF NOT EXISTS `job_order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'printing',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_order_items_order_id_foreign` (`order_id`),
  KEY `job_order_items_item_id_foreign` (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_order_items`
--

INSERT INTO `job_order_items` (`id`, `order_id`, `item_id`, `quantity`, `price`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 13:30:38', '2025-04-01 20:22:08'),
(2, 2, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:01:37', '2025-04-01 21:01:37'),
(28, 28, 4, 10, '500.00', '5000.00', 'printing', '2025-04-04 05:20:45', '2025-04-04 05:20:45'),
(4, 4, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:02:22', '2025-04-01 21:02:22'),
(5, 5, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:02:38', '2025-04-01 21:02:38'),
(6, 6, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:03:36', '2025-04-01 21:03:36'),
(7, 7, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:04:06', '2025-04-01 21:04:06'),
(8, 8, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:04:52', '2025-04-01 21:04:52'),
(9, 9, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:08:45', '2025-04-01 21:08:45'),
(10, 10, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:11:10', '2025-04-01 21:11:10'),
(11, 11, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:11:25', '2025-04-01 21:11:25'),
(12, 12, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:19:11', '2025-04-01 21:19:11'),
(13, 13, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:19:37', '2025-04-01 21:19:37'),
(14, 14, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:19:46', '2025-04-01 21:19:46'),
(15, 15, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:23:08', '2025-04-01 21:23:08'),
(16, 16, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:23:15', '2025-04-01 21:23:15'),
(17, 17, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:29:28', '2025-04-01 21:29:28'),
(18, 18, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:29:51', '2025-04-01 21:29:51'),
(19, 19, 3, 1, '500.00', '500.00', 'printing', '2025-04-01 21:32:46', '2025-04-03 18:31:06'),
(20, 20, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:32:53', '2025-04-01 21:32:53'),
(21, 21, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:33:02', '2025-04-01 21:33:02'),
(22, 22, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:33:11', '2025-04-01 21:33:11'),
(23, 23, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:34:02', '2025-04-01 21:34:02'),
(24, 24, 3, 4, '500.00', '2000.00', 'printing', '2025-04-01 21:34:07', '2025-04-01 21:34:07'),
(25, 25, 4, 6, '500.00', '3000.00', 'printing', '2025-04-01 21:34:54', '2025-04-01 21:34:54'),
(26, 26, 4, 6, '500.00', '3000.00', 'printing', '2025-04-01 21:35:04', '2025-04-01 21:35:04'),
(27, 27, 3, 10, '500.00', '5000.00', 'printing', '2025-04-02 16:28:03', '2025-04-02 17:25:06'),
(29, 29, 4, 5, '500.00', '2500.00', 'printing', '2025-04-04 05:59:32', '2025-04-04 05:59:32');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(13, '2025_03_02_135833_create_item_details_table', 2),
(14, '2025_03_04_061319_add_two_factor_columns_to_users_table', 2),
(15, '2025_03_04_061347_create_personal_access_tokens_table', 2),
(17, '2025_03_06_161549_create_brands_table', 4),
(40, '2025_03_02_135631_create_items_table', 17),
(50, '2025_03_05_104417_create_stocks_table', 24),
(25, '2025_03_08_171007_create_customers_table', 7),
(26, '2025_03_08_171159_create_customers_table', 8),
(59, '2025_03_08_180519_create_customer_orders_table', 32),
(60, '2025_03_08_180642_create_customer_order_items_table', 32),
(32, '2025_03_09_165936_create_suppliers_table', 11),
(43, '2025_03_09_170106_create_grn_items_table', 19),
(45, '2025_03_09_165419_create_grns_table', 20),
(34, '2025_03_09_172654_add_deleted_at_to_suppliers_table', 12),
(35, '2025_03_09_173206_add_status_to_suppliers_table', 13),
(37, '2025_03_16_052526_create_accounts_table', 15),
(38, '2025_03_16_070940_create_transactions_table', 15),
(71, '0001_01_01_000000_create_users_table', 37),
(53, '2025_03_23_061241_create_invoices_table', 26),
(54, '2025_03_23_063132_create_invoice_items_table', 27),
(55, '2025_03_25_032827_add_print_count_to_invoices_table', 28),
(56, '2025_03_25_081023_create_uoms_table', 29),
(64, '2025_03_28_080223_create_dispatch_items_table', 33),
(63, '2025_03_27_174823_create_job_order_items_table', 33),
(62, '2025_03_19_172857_create_dispatch_notes_table', 32),
(70, '2025_04_02_005257_create_branches_table', 36),
(72, '2025_03_19_111659_create_jobs_table', 37);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('qOyBoASd3Lsa9cd2C7dJuLjXtlGWjjF12owx9xy0', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiRDZlRXFTMmhydzROZEN1NXBDMkh5MVI3TVQ1NXE0a2I5MUdNZ21aRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvaW52b2ljZXMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO3M6MjE6InBhc3N3b3JkX2hhc2hfc2FuY3R1bSI7czo2MDoiJDJ5JDEyJHMuaTcueVVOWVJ4ZnJ5QlMzaVZDbk9UbklCUXRJVzd2N2c3aXE0c09FY1JKbGw3aktaSEYuIjt9', 1743773010);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
CREATE TABLE IF NOT EXISTS `stocks` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `brands_id` bigint UNSIGNED DEFAULT NULL,
  `items_id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `p_id` bigint UNSIGNED DEFAULT NULL,
  `f_id` bigint UNSIGNED DEFAULT NULL,
  `inventory_account` bigint UNSIGNED DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `mrp` decimal(10,2) DEFAULT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `purchase_tax` decimal(10,2) DEFAULT NULL,
  `sales_price` decimal(10,2) DEFAULT NULL,
  `sales_tax` bigint UNSIGNED DEFAULT NULL,
  `total_purchase_price` decimal(10,2) DEFAULT NULL,
  `payment_type` enum('cash','card','cheque','bank') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `purchase_date` date DEFAULT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `dimensions` json DEFAULT NULL,
  `mpn` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isbn` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ean` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `effective_date` date NOT NULL,
  `sku_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `online` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stocks_brands_id_foreign` (`brands_id`),
  KEY `stocks_items_id_foreign` (`items_id`),
  KEY `stocks_supplier_id_foreign` (`supplier_id`),
  KEY `stocks_p_id_foreign` (`p_id`),
  KEY `stocks_f_id_foreign` (`f_id`),
  KEY `stocks_inventory_account_foreign` (`inventory_account`),
  KEY `stocks_sales_tax_foreign` (`sales_tax`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `brands_id`, `items_id`, `supplier_id`, `p_id`, `f_id`, `inventory_account`, `quantity`, `mrp`, `purchase_price`, `purchase_tax`, `sales_price`, `sales_tax`, `total_purchase_price`, `payment_type`, `purchase_date`, `weight`, `dimensions`, `mpn`, `isbn`, `upc`, `ean`, `effective_date`, `sku_code`, `online`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 3, NULL, 3, 49, NULL, 40, NULL, '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, '{\"width\": null, \"height\": null, \"length\": null}', NULL, NULL, NULL, NULL, '2025-03-28', 'SKU-1-000005', 1, '2025-03-29 03:11:31', '2025-03-29 03:11:31', NULL),
(2, 1, 4, NULL, 3, 50, NULL, 50, NULL, '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, '{\"width\": null, \"height\": null, \"length\": null}', NULL, NULL, NULL, NULL, '2025-03-28', 'SKU-1-000006', 1, '2025-03-29 03:11:31', '2025-03-29 03:11:31', NULL),
(3, 1, 5, NULL, 3, 51, NULL, 60, NULL, '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, '{\"width\": null, \"height\": null, \"length\": null}', NULL, NULL, NULL, NULL, '2025-03-28', 'SKU-1-000007', 1, '2025-03-29 03:11:31', '2025-03-29 03:11:31', NULL),
(29, 1, 3, NULL, 5, 12, NULL, -2, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-31', 'SKU-1-000005', 1, '2025-03-30 19:50:19', '2025-03-30 19:50:19', NULL),
(28, 1, 6, NULL, 5, 11, NULL, -5, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-31', 'SKU-1-000010', 1, '2025-03-30 19:50:19', '2025-03-30 19:50:19', NULL),
(27, 1, 5, NULL, 4, 9, NULL, -2, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-31', 'SKU-1-000007', 1, '2025-03-30 19:45:23', '2025-03-30 19:45:23', NULL),
(26, 1, 3, NULL, 4, 8, NULL, -3, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-31', 'SKU-1-000005', 1, '2025-03-30 19:45:23', '2025-03-30 19:45:23', NULL),
(25, 1, 4, NULL, 3, 7, NULL, -4, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-31', 'SKU-1-000006', 1, '2025-03-30 19:45:10', '2025-03-30 19:45:10', NULL),
(24, 1, 5, NULL, 3, 6, NULL, -5, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-31', 'SKU-1-000007', 1, '2025-03-30 19:45:10', '2025-03-30 19:45:10', NULL),
(23, 1, 3, NULL, 3, 5, NULL, -10, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-31', 'SKU-1-000005', 1, '2025-03-30 19:45:10', '2025-03-30 19:45:10', NULL),
(22, 1, 4, NULL, 2, 4, NULL, -5, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-30', 'SKU-1-000006', 1, '2025-03-30 10:31:19', '2025-03-30 10:31:19', NULL),
(21, 1, 3, NULL, 2, 3, NULL, -5, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-30', 'SKU-1-000005', 1, '2025-03-30 10:31:19', '2025-03-30 10:31:19', NULL),
(20, 1, 4, NULL, 1, 2, NULL, -10, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-30', 'SKU-1-000006', 1, '2025-03-30 10:30:50', '2025-03-30 10:30:50', NULL),
(19, 1, 3, NULL, 1, 1, NULL, -10, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-30', 'SKU-1-000005', 1, '2025-03-30 10:30:50', '2025-03-30 10:30:50', NULL),
(16, 1, 6, NULL, 4, 60, NULL, 100, NULL, '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, '{\"width\": null, \"height\": null, \"length\": null}', NULL, NULL, NULL, NULL, '2025-03-30', 'SKU-1-000010', 1, '2025-03-30 09:48:50', '2025-03-30 09:48:50', NULL),
(17, 1, 3, NULL, 5, 61, NULL, 5, NULL, '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, '{\"width\": null, \"height\": null, \"length\": null}', NULL, NULL, NULL, NULL, '2025-05-31', 'SKU-1-000011', 1, '2025-03-30 10:11:31', '2025-03-30 10:11:31', NULL),
(18, 1, 6, NULL, 5, 62, NULL, 5, NULL, '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, '{\"width\": null, \"height\": null, \"length\": null}', NULL, NULL, NULL, NULL, '2025-05-31', 'SKU-1-000012', 1, '2025-03-30 10:11:31', '2025-03-30 10:11:31', NULL),
(30, 1, 6, NULL, 6, 13, NULL, -5, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-31', 'SKU-1-000012', 1, '2025-03-30 19:50:40', '2025-03-30 19:50:40', NULL),
(31, 1, 4, NULL, 7, 15, NULL, -3, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02', 'SKU-1-000006', 1, '2025-04-01 21:58:59', '2025-04-01 21:58:59', NULL),
(32, 1, 4, NULL, 8, 16, NULL, -3, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-02', 'SKU-1-000006', 1, '2025-04-01 22:05:45', '2025-04-01 22:05:45', NULL),
(33, 1, 3, NULL, 9, 17, NULL, -4, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-03', 'SKU-1-000005', 1, '2025-04-02 18:34:05', '2025-04-02 18:34:05', NULL),
(34, 1, 3, NULL, 10, 18, NULL, -2, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-03', 'SKU-1-000005', 1, '2025-04-02 18:59:57', '2025-04-02 18:59:57', NULL),
(35, 1, 3, NULL, 11, 19, NULL, -4, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-03', 'SKU-1-000005', 1, '2025-04-02 19:04:39', '2025-04-02 19:04:39', NULL),
(36, 1, 3, NULL, 11, 19, NULL, -4, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-03', 'SKU-1-000011', 1, '2025-04-02 19:04:39', '2025-04-02 19:04:39', NULL),
(37, 1, 3, NULL, 12, 20, NULL, -1, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-04', 'SKU-1-000011', 1, '2025-04-03 18:32:46', '2025-04-03 18:32:46', NULL),
(38, 1, 4, NULL, 13, 21, NULL, -1, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-04', 'SKU-1-000006', 1, '2025-04-04 05:26:00', '2025-04-04 05:26:00', NULL),
(39, 1, 4, NULL, 14, 22, NULL, -9, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-04', 'SKU-1-000006', 1, '2025-04-04 05:26:40', '2025-04-04 05:26:40', NULL),
(40, 1, 4, NULL, 15, 23, NULL, -5, '0.00', '200.00', NULL, '500.00', NULL, NULL, 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-04', 'SKU-1-000006', 1, '2025-04-04 06:01:55', '2025-04-04 06:01:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `email`, `phone`, `address`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ABC Suppliers', 'abc@suppliers.com', '1234567890', '123 Supplier Street, City', 'active', '2025-03-09 12:03:06', '2025-03-09 12:03:06', NULL),
(2, 'DC Suppliers', 'dc@suppliers.com', '1234567890', '123 Supplier Street, City', 'active', '2025-03-09 12:03:06', '2025-03-09 12:03:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id` bigint UNSIGNED NOT NULL,
  `table_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `debit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `transaction_type` enum('debit','credit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_sale_id_foreign` (`sale_id`),
  KEY `transactions_account_id_foreign` (`account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uoms`
--

DROP TABLE IF EXISTS `uoms`;
CREATE TABLE IF NOT EXISTS `uoms` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `uoms`
--

INSERT INTO `uoms` (`id`, `name`, `abbreviation`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Kilogram', 'Kg', NULL, 'active', '2025-03-25 03:11:08', '2025-03-25 03:11:08'),
(2, 'Liter', 'Li', NULL, 'active', '2025-03-25 03:11:50', '2025-03-25 03:11:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'design',
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `mode`, `branch_id`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', '2025-04-01 19:38:03', '$2y$12$WmXYZV3EbA2.i/kvhVbvuuxPnEUqGIDFswfaFQUiJv6QRFIdNahoe', 'admin', 1, NULL, NULL, NULL, '2025-04-01 19:38:04', '2025-04-01 19:38:04'),
(2, 'Admin Head Office', 'adminh', '2025-04-01 19:38:04', '$2y$12$9YFsuKhjjHKoxGuqlyi4cOXAX0VNKt3mZ3bzXH0v95sQZWn10umwm', 'design', 2, NULL, NULL, NULL, '2025-04-01 19:38:04', '2025-04-01 19:38:04'),
(3, 'Lakmal', 'acc01', '2025-04-01 19:38:04', '$2y$12$s.i7.yUNYRxfryBS3iVCnOTnIBQtIW7v7g7iq4sOEcRJll7jKZHF.', 'billing', 1, NULL, NULL, NULL, '2025-04-01 19:38:04', '2025-04-01 19:38:04'),
(4, 'Iroshi', 'acc02', '2025-04-01 19:38:04', '$2y$12$mw6Si/aM3GxlH7LlXJSyXuOR9WaPC2OolFSMqmDamGlZLhVNbPbmm', 'billing', 1, NULL, NULL, NULL, '2025-04-01 19:38:04', '2025-04-01 19:38:04'),
(5, 'Pro_N', 'pro_n', '2025-04-01 19:38:04', '$2y$12$/DSrZxDVPWEKf3GkS.ObxuJ5r7wOm9EVxqE6cFoUUzrTYCuF76Kwi', 'design', 1, NULL, NULL, NULL, '2025-04-01 19:38:05', '2025-04-01 19:38:05'),
(6, 'Pro_A', 'pro_a', '2025-04-01 19:38:05', '$2y$12$1p..7GLNJbJyF.rQ3C3PoeQwQzGRcrbcwXn.z/Fc89aqTckFg4LlC', 'design', 1, NULL, NULL, NULL, '2025-04-01 19:38:05', '2025-04-01 19:38:05'),
(7, 'Pro_C', 'pro_c', '2025-04-01 19:38:05', '$2y$12$tUfXswhd1TiqtmTBjKmHhuFlpPKS7Un0OkbPxUIyF5vBn0XBj0V2W', 'design', 1, NULL, NULL, NULL, '2025-04-01 19:38:05', '2025-04-01 19:38:05'),
(8, 'Pro_K', 'pro_k', '2025-04-01 19:38:05', '$2y$12$HLSOo/0/Vfw5pJpChntGKOmidY7lRINpjhAO50ALE9r7gfycrDsi.', 'design', 1, NULL, NULL, NULL, '2025-04-01 19:38:05', '2025-04-01 19:38:05'),
(9, 'Pro_J', 'pro_j', '2025-04-01 19:38:05', '$2y$12$vxL2UZ/nP5BctvrUddFpb.gXeDhUtTngxRMryG3Jz2TBqMpwifl0W', 'design', 1, NULL, NULL, NULL, '2025-04-01 19:38:05', '2025-04-01 19:38:05'),
(10, 'Pro_R', 'pro_r', '2025-04-01 19:38:05', '$2y$12$xVv7WAZNVF/lqqHsGby9BumQjPBA3vh.XQQIrCniuckTpgLWCn1Au', 'design', 1, NULL, NULL, NULL, '2025-04-01 19:38:06', '2025-04-01 19:38:06'),
(11, 'CT_01', 'ct_01', '2025-04-01 19:38:06', '$2y$12$DkyqJmYzEjk9Y5YQ4olS0O0mHIbZHlq2e7RxyWplyqXTnCPfrWrmu', 'dispatch', 1, NULL, NULL, NULL, '2025-04-01 19:38:06', '2025-04-01 19:38:06'),
(12, 'CT_02', 'ct_02', '2025-04-01 19:38:06', '$2y$12$d/qsMsJoLdttevc7ohlbGeEQgAV5YHAjOTHYWq/oEBD/8kqk9775m', 'dispatch', 1, NULL, NULL, NULL, '2025-04-01 19:38:06', '2025-04-01 19:38:06'),
(13, 'CT_03', 'ct_03', '2025-04-01 19:38:06', '$2y$12$CEJC2S0xnab09J/dfQSrquGal5O3AhXqU.B9tvA1ZnT0RRYKaYGmK', 'dispatch', 2, NULL, NULL, NULL, '2025-04-01 19:38:06', '2025-04-01 19:38:06'),
(14, 'Pro_L', 'pro_l', '2025-04-01 19:38:06', '$2y$12$ECZ/bwxfPvWnRLIScEA7JOiPc3A7Hz6hK7fQJGbtIgwd4a7sZAj3i', 'design', 2, NULL, NULL, NULL, '2025-04-01 19:38:07', '2025-04-01 19:38:07'),
(15, 'Danoja', 'acc03', '2025-04-01 19:38:07', '$2y$12$xUCdhiruGoToF7QyyF2zPOLXRa6D6HkZon3GgX.Mjq.ZPJYXZVZhK', 'billing', 2, NULL, NULL, NULL, '2025-04-01 19:38:07', '2025-04-01 19:38:07');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
