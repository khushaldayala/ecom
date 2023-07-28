-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2023 at 12:45 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecom`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_to_carts`
--

CREATE TABLE `add_to_carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `filter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filteroption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` bigint(20) NOT NULL,
  `total` bigint(20) NOT NULL,
  `qty` bigint(20) NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `add_to_carts`
--

INSERT INTO `add_to_carts` (`id`, `user_id`, `product_id`, `filter`, `filteroption`, `product_name`, `price`, `total`, `qty`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'size', 'M', 'saree', 5000, 15000, 3, 'skfasjflsdjflsdjfslkdjfsljf.png', '2023-05-31 04:14:29', '2023-05-31 04:17:21'),
(2, 1, 2, 'size', 'M', 'saree', 5000, 25000, 5, 'skfasjflsdjflsdjfslkdjfsljf.png', '2023-05-31 04:19:59', '2023-05-31 04:19:59'),
(3, 1, 3, 'size', 'M', 'saree', 5000, 25000, 5, 'skfasjflsdjflsdjfslkdjfsljf.png', '2023-05-31 04:20:00', '2023-05-31 04:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `advertises`
--

CREATE TABLE `advertises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `showtype` enum('mobile','desktop','','') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `section_id`, `title`, `description`, `image`, `showtype`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(7, 3, 'front first banner', 'this is the front first banner', '1687857765.png', 'desktop', 'active', '2023-06-27 03:52:45', '2023-06-27 04:08:11', NULL),
(8, 3, 'front first banner', 'this is the front first banner', '1687857767.png', 'desktop', 'active', '2023-06-27 03:52:47', '2023-06-27 04:08:54', NULL),
(9, 3, 'front first banner', 'this is the front first banner', '1687857768.png', 'desktop', 'active', '2023-06-27 03:52:48', '2023-06-27 03:52:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `section_id`, `title`, `description`, `image`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 3, 'categories', 'this is the categories section', '1687865898.png', 'active', '2023-06-27 06:08:18', '2023-06-27 06:08:18', NULL),
(4, 3, 'categories', 'this is the categories section', '1687865901.png', 'active', '2023-06-27 06:08:21', '2023-06-27 06:08:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `checkouts`
--

CREATE TABLE `checkouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` bigint(20) NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` bigint(20) NOT NULL,
  `billing_full_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_phone` bigint(20) NOT NULL,
  `billing_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_city` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_state` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_coutry` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_zipcode` bigint(20) NOT NULL,
  `order_comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checkout_products`
--

CREATE TABLE `checkout_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `checkout_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `filter_id` bigint(20) NOT NULL,
  `filter_option_id` bigint(20) NOT NULL,
  `product_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` bigint(20) NOT NULL,
  `qty` bigint(20) NOT NULL,
  `total` bigint(20) NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fabrics`
--

CREATE TABLE `fabrics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `fab_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `filters`
--

CREATE TABLE `filters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `filters`
--

INSERT INTO `filters` (`id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'test filter', NULL, 'active', '2023-05-31 03:43:44', '2023-05-31 03:43:44');

-- --------------------------------------------------------

--
-- Table structure for table `filter_options`
--

CREATE TABLE `filter_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `filter_id` bigint(20) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `filter_options`
--

INSERT INTO `filter_options` (`id`, `filter_id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'test', NULL, 'active', '2023-05-31 03:48:08', '2023-05-31 03:48:08'),
(2, 1, 'test', NULL, 'active', '2023-05-31 03:48:39', '2023-05-31 03:48:39');

-- --------------------------------------------------------

--
-- Table structure for table `intro_screens`
--

CREATE TABLE `intro_screens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` bigint(20) NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keywords`
--

CREATE TABLE `keywords` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_point` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `keywords`
--

INSERT INTO `keywords` (`id`, `title`, `description`, `end_point`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Advertise', 'this is the banner section', 'advertise', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43'),
(2, 'SliderBanner', 'this is the banner section', 'banner', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43'),
(3, 'Categories', 'this is the categories section', 'categories', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43'),
(4, 'Product', 'this is the product section', 'product', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43'),
(5, 'Brand', 'this is the brand section', 'brand', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43'),
(6, 'Offer', 'this is the offer section', 'offer', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_04_17_120805_create_banners_table', 1),
(6, '2023_04_17_123105_create_categories_table', 1),
(7, '2023_04_17_123525_create_subcategories_table', 1),
(8, '2023_04_18_090317_create_fabrics_table', 1),
(9, '2023_04_18_103116_create_offers_table', 1),
(10, '2023_04_18_104118_create_sections_table', 1),
(11, '2023_04_18_120134_create_products_table', 1),
(12, '2023_04_18_120757_create_product_images_table', 1),
(13, '2023_04_18_121209_create_variants_table', 1),
(14, '2023_04_18_121344_create_variant_options_table', 1),
(15, '2023_04_18_122518_create_product_variants_table', 1),
(16, '2023_04_18_123834_create_wishlists_table', 1),
(17, '2023_04_18_124157_create_add_to_carts_table', 1),
(18, '2023_04_18_125102_create_contact_us_table', 1),
(19, '2023_04_18_125305_create_orders_table', 1),
(20, '2023_04_18_130032_create_order_notes_table', 1),
(21, '2023_04_18_130242_create_checkouts_table', 1),
(22, '2023_04_24_101755_create_advertises_table', 1),
(23, '2023_05_03_055510_create_keywords_table', 1),
(24, '2023_05_03_065750_create_product_keywords_table', 1),
(25, '2023_05_03_105331_create_brands_table', 1),
(26, '2023_05_04_081518_create_intro_screens_table', 1),
(27, '2023_05_05_050128_create_ratings_table', 1),
(28, '2023_05_05_050322_create_reviews_table', 1),
(29, '2023_05_17_071015_create_notifications_table', 1),
(30, '2023_05_23_121307_create_filters_table', 1),
(31, '2023_05_23_121340_create_filter_options_table', 1),
(32, '2023_05_30_081508_create_notification_schedules_table', 2),
(33, '2023_05_31_123356_create_checkout_products_table', 3),
(34, '2023_06_27_060332_add_deleted_at_to_banners', 3),
(35, '2023_06_27_112809_add_deleted_at_to_categories', 4),
(36, '2023_07_03_105818_add_deleted_at_to_sections', 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `fcm_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` enum('false','true') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `fcm_token`, `date`, `title`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 'dgdfgdfgdfgdfgdfgdfgdfgdfgdfgdfgdf', '2023-05-31', 'test title', 'test message to message', 'true', '2023-05-31 10:28:41', '2023-05-31 06:03:01'),
(2, 1, 'gdfgdfgdfgdfgdfgdfgdfgdfgdfg', '2023-05-31', 'test2 title2', 'test message2 message2', 'true', '2023-05-31 10:29:42', '2023-05-31 10:29:42');

-- --------------------------------------------------------

--
-- Table structure for table `notification_schedules`
--

CREATE TABLE `notification_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `schedule_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `notification_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `coupon_code` bigint(20) DEFAULT NULL,
  `link` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` bigint(20) DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `checkout_id` int(20) NOT NULL,
  `order_amount` bigint(20) NOT NULL,
  `order_qty` bigint(20) NOT NULL,
  `dicount` int(20) NOT NULL,
  `shipping_charge` int(11) NOT NULL,
  `user_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` bigint(20) NOT NULL,
  `shiping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` bigint(20) NOT NULL,
  `place_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_notes`
--

CREATE TABLE `order_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'my-app-token', 'e7bf36e43501a676f3b057828b4cbbba83997bf4237c53d703c17c52392486cb', '[\"*\"]', NULL, '2023-06-07 05:38:47', '2023-06-07 05:38:47'),
(2, 'App\\Models\\User', 1, 'my-app-token', '35c2a56334a20d9ebd75974c9cafe3968afb6e362b2f4f6f9ef5084dcf06630e', '[\"*\"]', '2023-06-07 05:40:31', '2023-06-07 05:39:29', '2023-06-07 05:40:31'),
(3, 'App\\Models\\User', 1, 'my-app-token', '055f3a7bf0ebfd79dbd06bea759c05e6373b2df9a11f6ebc5305decb39486143', '[\"*\"]', '2023-06-07 07:12:26', '2023-06-07 07:11:41', '2023-06-07 07:12:26'),
(4, 'App\\Models\\User', 1, 'my-app-token', 'ab3fe261bc3bba369d96d6c647bc4e0a1b52e76d9ff1b9e0daccebfc281f3798', '[\"*\"]', '2023-06-08 00:59:56', '2023-06-08 00:59:41', '2023-06-08 00:59:56');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `brand_id` bigint(20) NOT NULL DEFAULT 0,
  `subcategory_id` bigint(20) NOT NULL,
  `fabric_id` bigint(20) NOT NULL,
  `color_id` bigint(20) NOT NULL,
  `section_id` bigint(20) NOT NULL,
  `wishlist` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `more_info` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `view_count` bigint(20) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `subcategory_id`, `fabric_id`, `color_id`, `section_id`, `wishlist`, `product_name`, `description`, `more_info`, `view_count`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 1, 1, 1, 1, '0', 'yello saree', 'this is the best project in india', 'one of the most expensive and best product', 0, 'active', '2023-05-31 04:45:36', '2023-05-31 04:45:36'),
(2, 1, 0, 1, 1, 1, 1, '0', 'red saree', 'this is the best project in india', 'one of the most expensive and best product', 0, 'active', '2023-05-31 04:49:15', '2023-05-31 04:49:15'),
(3, 1, 0, 1, 1, 1, 1, '0', 'red saree', 'this is the best project in india', 'one of the most expensive and best product', 0, 'active', '2023-05-31 04:49:27', '2023-05-31 04:49:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, '16855283670.PNG', 'active', '2023-05-31 04:49:27', '2023-05-31 04:49:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_keywords`
--

CREATE TABLE `product_keywords` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_keywords`
--

INSERT INTO `product_keywords` (`id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'NEW-ARRIVALS', 'this is the new arrival', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43'),
(2, 'RECENTLY-VIEWED', 'this is the recently viewed', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43'),
(3, 'BEST-SELLERS', 'this is the best sellers', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43'),
(4, 'TOP-RATED', 'this is the top rated', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43'),
(5, 'MOST-VIEWED', 'this is the most viewed', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43'),
(6, 'OTHER', 'this is the other type of limited editions, trending items, deals of the day, seasonal products, featured products', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `variant_id` bigint(20) NOT NULL,
  `variant_option_id` bigint(20) NOT NULL,
  `discount_type` enum('percentage','price') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `off_price` bigint(20) DEFAULT NULL,
  `off_percentage` bigint(20) DEFAULT NULL,
  `original_price` bigint(20) DEFAULT NULL,
  `discount_price` bigint(20) DEFAULT NULL,
  `qty` bigint(20) NOT NULL,
  `sku` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` bigint(20) NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `variant_id`, `variant_option_id`, `discount_type`, `off_price`, `off_percentage`, `original_price`, `discount_price`, `qty`, `sku`, `weight`, `color`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, 'percentage', NULL, 20, 5000, 4000, 5, 'fsdfsfsdfsfdafafsdfsdfsdf', 1200, 'yellow', 'active', '2023-05-31 04:49:27', '2023-05-31 04:49:27');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `rating` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `review` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyword_option` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_point` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL,
  `dlink` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `title`, `description`, `keywords`, `keyword_option`, `end_point`, `order`, `dlink`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'advertise', 'this is the advertise section', 'Advertise', '', 'advertise', 1, 'http://fsdfsfasfsdfsdfsfd.com', 'active', '2023-05-23 06:45:43', '2023-07-03 06:00:43', NULL),
(2, 'banner', 'this is the banner section', 'SliderBanner', '', 'banner', 2, 'http://fsdfsfasfsdfsdfsfd.com', 'active', '2023-05-23 06:45:43', '2023-07-03 05:59:24', NULL),
(3, 'categories', 'this is the categories section', 'Categories', '', 'categories', 3, 'http://fsdfsfasfsdfsdfsfd.com', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43', NULL),
(4, 'trending product', 'this is the product section', 'Product', 'trending_product', 'product', 4, 'http://fsdfsfasfsdfsdfsfd.com', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43', NULL),
(5, 'brand', 'this is the brand section', 'Brand', '', 'brand', 5, 'http://fsdfsfasfsdfsdfsfd.com', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43', NULL),
(6, 'offer', 'this is the offer section', 'Offer', '', 'offer', 6, 'http://fsdfsfasfsdfsdfsfd.com', 'active', '2023-05-23 06:45:43', '2023-05-23 06:45:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `fabric_id` int(11) NOT NULL,
  `sub_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` bigint(20) NOT NULL,
  `otp` int(11) DEFAULT NULL,
  `status` enum('inactive','active') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `birthdate`, `gender`, `image`, `email`, `password`, `phone_number`, `otp`, `status`, `remember_token`, `version`, `fcm_token`, `created_at`, `updated_at`) VALUES
(1, 'jony david', NULL, NULL, NULL, 'test@gmail.com', '$2y$10$o1ROQiXPPfmP72pFt2ZIEeDaiKq2Ut/k9urPDrE1ldBBVBvtlYR6K', 7096895215, 8218, 'active', NULL, 'android', NULL, '2023-06-07 05:37:13', '2023-06-07 07:12:26');

-- --------------------------------------------------------

--
-- Table structure for table `variants`
--

CREATE TABLE `variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variant_options`
--

CREATE TABLE `variant_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) NOT NULL,
  `option` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` bigint(20) NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_to_carts`
--
ALTER TABLE `add_to_carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advertises`
--
ALTER TABLE `advertises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `checkout_products`
--
ALTER TABLE `checkout_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fabrics`
--
ALTER TABLE `fabrics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `filters`
--
ALTER TABLE `filters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filter_options`
--
ALTER TABLE `filter_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `intro_screens`
--
ALTER TABLE `intro_screens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keywords`
--
ALTER TABLE `keywords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_schedules`
--
ALTER TABLE `notification_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_notes`
--
ALTER TABLE `order_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_keywords`
--
ALTER TABLE `product_keywords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `variants`
--
ALTER TABLE `variants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variant_options`
--
ALTER TABLE `variant_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_to_carts`
--
ALTER TABLE `add_to_carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `advertises`
--
ALTER TABLE `advertises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `checkouts`
--
ALTER TABLE `checkouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checkout_products`
--
ALTER TABLE `checkout_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fabrics`
--
ALTER TABLE `fabrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `filters`
--
ALTER TABLE `filters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `filter_options`
--
ALTER TABLE `filter_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `intro_screens`
--
ALTER TABLE `intro_screens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keywords`
--
ALTER TABLE `keywords`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notification_schedules`
--
ALTER TABLE `notification_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_notes`
--
ALTER TABLE `order_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_keywords`
--
ALTER TABLE `product_keywords`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `variants`
--
ALTER TABLE `variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variant_options`
--
ALTER TABLE `variant_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
