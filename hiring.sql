-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 18 أكتوبر 2021 الساعة 19:41
-- إصدار الخادم: 10.4.20-MariaDB
-- PHP Version: 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hiring`
--

-- --------------------------------------------------------

--
-- بنية الجدول `ads`
--

CREATE TABLE `ads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish' COMMENT 'publish unpublish',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `avmeetings`
--

CREATE TABLE `avmeetings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) NOT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `available` tinyint(4) DEFAULT NULL COMMENT '0->available 1->book',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `avmeetings`
--

INSERT INTO `avmeetings` (`id`, `job_id`, `time_from`, `time_to`, `available`, `created_at`, `updated_at`) VALUES
(1, 1, '10:00:00', '10:30:00', 0, '2021-10-18 15:17:13', '2021-10-18 15:17:13'),
(2, 2, '10:00:00', '10:30:00', 0, '2021-10-18 15:17:54', '2021-10-18 15:17:54'),
(3, 3, '10:00:00', '10:30:00', 0, '2021-10-18 15:18:30', '2021-10-18 15:18:30');

-- --------------------------------------------------------

--
-- بنية الجدول `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, NULL, '2021-10-18 14:54:20', '2021-10-18 14:54:20'),
(2, 1, '2021-10-18 14:54:35', '2021-10-18 14:54:35'),
(3, 1, '2021-10-18 14:54:49', '2021-10-18 14:54:49'),
(4, NULL, '2021-10-18 14:54:59', '2021-10-18 14:54:59'),
(5, 4, '2021-10-18 14:55:08', '2021-10-18 14:55:08');

-- --------------------------------------------------------

--
-- بنية الجدول `category_translations`
--

CREATE TABLE `category_translations` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `category_translations`
--

INSERT INTO `category_translations` (`id`, `category_id`, `locale`, `name`) VALUES
(1, 1, 'en', 'engineer'),
(2, 2, 'en', 'civil engineer'),
(3, 3, 'en', 'electric engineer'),
(4, 4, 'en', 'medical'),
(5, 5, 'en', 'human');

-- --------------------------------------------------------

--
-- بنية الجدول `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `cities`
--

INSERT INTO `cities` (`id`, `name`, `country_id`, `created_at`, `updated_at`) VALUES
(1, 'cairo', 1, '2021-10-18 15:15:52', '2021-10-18 15:15:52'),
(2, 'Giza', 1, '2021-10-18 15:16:05', '2021-10-18 15:16:05');

-- --------------------------------------------------------

--
-- بنية الجدول `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `image`, `currency`, `created_at`, `updated_at`) VALUES
(1, 'EGYPT', 'eg', '4793001634570122.PNG', NULL, '2021-10-18 15:15:22', '2021-10-18 15:15:22');

-- --------------------------------------------------------

--
-- بنية الجدول `employeechats`
--

CREATE TABLE `employeechats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `employer_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `employeenotifications`
--

CREATE TABLE `employeenotifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL COMMENT '1->accept candate befor interview, 2->video call, 3->accept or reject after interview, 4 -> create job',
  `employee_id` bigint(20) NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_id` bigint(20) DEFAULT NULL,
  `candate_id` bigint(20) DEFAULT NULL,
  `employer_id` bigint(20) DEFAULT NULL,
  `viedo_channel_name` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `viedo_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `read_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `employeenotifications`
--

INSERT INTO `employeenotifications` (`id`, `type`, `employee_id`, `title`, `body`, `job_id`, `candate_id`, `employer_id`, `viedo_channel_name`, `viedo_token`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'sdsds', 'sdsdsdsd', 2, NULL, NULL, NULL, NULL, '2018-10-21', NULL, '2021-10-18 17:32:02');

-- --------------------------------------------------------

--
-- بنية الجدول `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `fullName` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` bigint(20) DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_id` bigint(20) DEFAULT NULL,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qualification` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `graduation_year` int(11) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `study_field` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deriving_licence` tinyint(4) DEFAULT NULL,
  `skills` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `languages` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cv` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `audio` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL COMMENT 'null -> not active, 1 -> active',
  `birth` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint(4) DEFAULT NULL,
  `block` tinyint(4) DEFAULT NULL COMMENT 'null->not bloked, 1->bloked',
  `socialite_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `employees`
--

INSERT INTO `employees` (`id`, `category_id`, `fullName`, `email`, `password`, `country_id`, `phone`, `city_id`, `title`, `qualification`, `university`, `graduation_year`, `experience`, `study_field`, `deriving_licence`, `skills`, `languages`, `cv`, `audio`, `video`, `image`, `active`, `birth`, `gender`, `block`, `socialite_id`, `token`, `failed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'ahmed maher', 'ahmed308@gmail.com', '$2y$10$RgPOXhxaeO69GlOBmTgalOvFUTLifv/2HAfrXkTLBoOZZY8jWhfN6', 1, '01096910528', 1, 'asd', 'as', 'asjh', 2000, 5, 'asdas', 1, '[\"asd\",\"asd2\"]', '[\"en\",\"ar\"]', NULL, NULL, NULL, NULL, NULL, '2000', 1, NULL, NULL, 'asdwq', '2021-10-18 15:19:36', '2021-10-18 15:19:36', '2021-10-18 15:19:36');

-- --------------------------------------------------------

--
-- بنية الجدول `employee_active`
--

CREATE TABLE `employee_active` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `employee_job`
--

CREATE TABLE `employee_job` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `candat_applay_status` tinyint(4) DEFAULT NULL COMMENT '0->reject 1->accept 2->shoertlist',
  `avmeeting_id` bigint(20) DEFAULT NULL,
  `meeting_time_status` tinyint(4) DEFAULT NULL COMMENT '0->reject 1->accept the candit who determine this',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `candat_status` tinyint(4) DEFAULT NULL COMMENT '0->reject 1->accept 2->underreview employer who detemine this',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `employee_job`
--

INSERT INTO `employee_job` (`id`, `job_id`, `employee_id`, `candat_applay_status`, `avmeeting_id`, `meeting_time_status`, `note`, `candat_status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `employee_password_resets`
--

CREATE TABLE `employee_password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `employerchats`
--

CREATE TABLE `employerchats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `employer_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `employernotifications`
--

CREATE TABLE `employernotifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL COMMENT '1->employee aplay job',
  `employer_id` bigint(20) NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `candate_id` bigint(20) DEFAULT NULL,
  `read_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `employernotifications`
--

INSERT INTO `employernotifications` (`id`, `type`, `employer_id`, `title`, `body`, `candate_id`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'sjdhsdu', 'sjhdsudys', 1, '2018-10-21', NULL, '2021-10-18 17:38:41');

-- --------------------------------------------------------

--
-- بنية الجدول `employers`
--

CREATE TABLE `employers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullName` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number1` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number2` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` bigint(20) DEFAULT NULL,
  `city_id` bigint(20) DEFAULT NULL,
  `business` bigint(20) DEFAULT NULL,
  `established_at` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL,
  `token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `employers`
--

INSERT INTO `employers` (`id`, `fullName`, `title`, `email`, `password`, `mobile_number1`, `mobile_number2`, `company_name`, `country_id`, `city_id`, `business`, `established_at`, `website`, `image`, `active`, `token`, `created_at`, `updated_at`) VALUES
(1, 'ahmed maher', 'asd asd asd', 'test@gmail.com', '$2y$10$tDDdp.SgF3uSKGko7jezq./j95N9y2Q4dcEmqGkbd3YD3Kb0uM74S', '12345676', NULL, 'asd', 1, 1, 1, '200', 'sjhafdkdgs.com', NULL, 1, 'ajhds', '2021-10-18 15:16:44', '2021-10-18 17:35:12');

-- --------------------------------------------------------

--
-- بنية الجدول `employer_active`
--

CREATE TABLE `employer_active` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `employer_password_resets`
--

CREATE TABLE `employer_password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employer_id` bigint(20) NOT NULL,
  `category_id` bigint(20) DEFAULT NULL COMMENT 'job field',
  `job_specialize` bigint(20) DEFAULT NULL COMMENT 'job_specialize',
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` double(8,2) DEFAULT NULL,
  `gender` tinyint(4) DEFAULT NULL COMMENT '0->male  1->female 2->other',
  `experience` int(11) DEFAULT NULL,
  `qualification` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interviewer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interviewer_role` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meeting_date` date DEFAULT NULL,
  `meeting_from` time DEFAULT NULL,
  `meeting_to` time DEFAULT NULL,
  `meeting_time` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '0->cancel 1->active 2->closed',
  `applies` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `jobs`
--

INSERT INTO `jobs` (`id`, `employer_id`, `category_id`, `job_specialize`, `title`, `details`, `country_id`, `city_id`, `note`, `salary`, `gender`, `experience`, `qualification`, `interviewer_name`, `interviewer_role`, `meeting_date`, `meeting_from`, `meeting_to`, `meeting_time`, `status`, `applies`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 'node js developer', 'as Developer at this large online retailer, you\'ll improve the systems we built ourselves. Relocation from A to Z, a close friend group, and more than 40 nationalities. Apply now! Ask A Question. Browse Specialties. View Our Culture', 1, 2, 'need ios developer', 3000.00, 1, 5, 'very good', 'ahmed ali', 'good', '2021-10-27', '10:00:00', '10:30:00', 30, 1, NULL, '2021-10-18 15:17:13', '2021-10-18 15:17:13'),
(2, 1, 1, 1, 'node js developer', 'as Developer at this large online retailer, you\'ll improve the systems we built ourselves. Relocation from A to Z, a close friend group, and more than 40 nationalities. Apply now! Ask A Question. Browse Specialties. View Our Culture', 1, 1, 'need ios developer', 800.00, 1, 5, 'very good', 'ahmed ali', 'good', '2021-10-30', '10:00:00', '10:30:00', 30, 1, NULL, '2021-10-18 15:17:54', '2021-10-18 15:17:54'),
(3, 1, 1, 1, 'java Developer', 'as Developer at this large online retailer, you\'ll improve the systems we built ourselves. Relocation from A to Z, a close friend group, and more than 40 nationalities. Apply now! Ask A Question. Browse Specialties. View Our Culture', 1, 1, 'need ios developer', 800.00, 1, 9, 'very good', 'ahmed ali', 'good', '2021-10-30', '10:00:00', '10:30:00', 30, 1, NULL, '2021-10-18 15:18:30', '2021-10-18 15:18:30');

-- --------------------------------------------------------

--
-- بنية الجدول `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2021_03_01_074238_create_categories_table', 1),
(10, '2021_03_01_074318_create_category_translations_table', 1),
(11, '2021_03_22_191340_laratrust_setup_tables', 1),
(12, '2021_08_03_112429_employees', 1),
(13, '2021_08_03_120647_employers', 1),
(14, '2021_08_04_102801_employee_password_resets', 1),
(15, '2021_08_04_102819_employer_password_resets', 1),
(16, '2021_08_05_110349_employee_active', 1),
(17, '2021_08_05_110415_employer_active', 1),
(18, '2021_08_18_093643_jobs', 1),
(19, '2021_08_18_101453_employee_job', 1),
(20, '2021_08_19_082241_reports', 1),
(21, '2021_08_27_063950_create_avmeetings_table', 1),
(22, '2021_09_14_075849_employer_chats', 1),
(23, '2021_09_14_075855_employee_chats', 1),
(24, '2021_09_15_135813_employee_notifications', 1),
(25, '2021_09_19_113313_create_ads_table', 1),
(26, '2021_09_23_150838_employer_notifications', 1),
(27, '2021_09_27_173648_countries', 1),
(28, '2021_09_27_173705_cities', 1);

-- --------------------------------------------------------

--
-- بنية الجدول `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'create-users', 'Create Users', 'Create Users', '2021-10-18 14:28:25', '2021-10-18 14:28:25'),
(2, 'read-users', 'Read Users', 'Read Users', '2021-10-18 14:28:25', '2021-10-18 14:28:25'),
(3, 'update-users', 'Update Users', 'Update Users', '2021-10-18 14:28:25', '2021-10-18 14:28:25'),
(4, 'delete-users', 'Delete Users', 'Delete Users', '2021-10-18 14:28:25', '2021-10-18 14:28:25'),
(5, 'create-roles', 'Create Roles', 'Create Roles', '2021-10-18 14:28:25', '2021-10-18 14:28:25'),
(6, 'read-roles', 'Read Roles', 'Read Roles', '2021-10-18 14:28:25', '2021-10-18 14:28:25'),
(7, 'update-roles', 'Update Roles', 'Update Roles', '2021-10-18 14:28:25', '2021-10-18 14:28:25'),
(8, 'delete-roles', 'Delete Roles', 'Delete Roles', '2021-10-18 14:28:25', '2021-10-18 14:28:25'),
(9, 'create-categories', 'Create Categories', 'Create Categories', '2021-10-18 14:28:25', '2021-10-18 14:28:25'),
(10, 'read-categories', 'Read Categories', 'Read Categories', '2021-10-18 14:28:25', '2021-10-18 14:28:25'),
(11, 'update-categories', 'Update Categories', 'Update Categories', '2021-10-18 14:28:25', '2021-10-18 14:28:25'),
(12, 'delete-categories', 'Delete Categories', 'Delete Categories', '2021-10-18 14:28:25', '2021-10-18 14:28:25'),
(13, 'create-employees', 'Create Employees', 'Create Employees', '2021-10-18 14:28:25', '2021-10-18 14:28:25'),
(14, 'read-employees', 'Read Employees', 'Read Employees', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(15, 'update-employees', 'Update Employees', 'Update Employees', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(16, 'delete-employees', 'Delete Employees', 'Delete Employees', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(17, 'create-employers', 'Create Employers', 'Create Employers', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(18, 'read-employers', 'Read Employers', 'Read Employers', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(19, 'update-employers', 'Update Employers', 'Update Employers', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(20, 'delete-employers', 'Delete Employers', 'Delete Employers', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(21, 'create-jobs', 'Create Jobs', 'Create Jobs', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(22, 'read-jobs', 'Read Jobs', 'Read Jobs', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(23, 'update-jobs', 'Update Jobs', 'Update Jobs', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(24, 'delete-jobs', 'Delete Jobs', 'Delete Jobs', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(25, 'create-employeejobs', 'Create Employeejobs', 'Create Employeejobs', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(26, 'read-employeejobs', 'Read Employeejobs', 'Read Employeejobs', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(27, 'update-employeejobs', 'Update Employeejobs', 'Update Employeejobs', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(28, 'delete-employeejobs', 'Delete Employeejobs', 'Delete Employeejobs', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(29, 'create-countries', 'Create Countries', 'Create Countries', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(30, 'read-countries', 'Read Countries', 'Read Countries', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(31, 'update-countries', 'Update Countries', 'Update Countries', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(32, 'delete-countries', 'Delete Countries', 'Delete Countries', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(33, 'create-cities', 'Create Cities', 'Create Cities', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(34, 'read-cities', 'Read Cities', 'Read Cities', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(35, 'update-cities', 'Update Cities', 'Update Cities', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(36, 'delete-cities', 'Delete Cities', 'Delete Cities', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(37, 'create-ads', 'Create Ads', 'Create Ads', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(38, 'read-ads', 'Read Ads', 'Read Ads', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(39, 'update-ads', 'Update Ads', 'Update Ads', '2021-10-18 14:28:26', '2021-10-18 14:28:26'),
(40, 'delete-ads', 'Delete Ads', 'Delete Ads', '2021-10-18 14:28:26', '2021-10-18 14:28:26');

-- --------------------------------------------------------

--
-- بنية الجدول `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1);

-- --------------------------------------------------------

--
-- بنية الجدول `permission_user`
--

CREATE TABLE `permission_user` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employer_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'super', 'Super', 'Super', '2021-10-18 14:28:25', '2021-10-18 14:28:25');

-- --------------------------------------------------------

--
-- بنية الجدول `role_user`
--

CREATE TABLE `role_user` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `role_user`
--

INSERT INTO `role_user` (`role_id`, `user_id`, `user_type`) VALUES
(1, 1, 'App\\User');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `email_verified_at`, `password`, `image`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super', 'super@eg.com', NULL, NULL, NULL, '$2y$10$9P4Egh2OudFVWt/AEttD8er/Rk44VaNA42DTNaqGUlHJa8CaeJVEy', NULL, NULL, '2021-10-18 14:28:27', '2021-10-18 14:28:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `avmeetings`
--
ALTER TABLE `avmeetings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_translations`
--
ALTER TABLE `category_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_translations_category_id_locale_unique` (`category_id`,`locale`),
  ADD KEY `category_translations_locale_index` (`locale`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employeechats`
--
ALTER TABLE `employeechats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employeenotifications`
--
ALTER TABLE `employeenotifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_active`
--
ALTER TABLE `employee_active`
  ADD KEY `employee_active_email_index` (`email`);

--
-- Indexes for table `employee_job`
--
ALTER TABLE `employee_job`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_password_resets`
--
ALTER TABLE `employee_password_resets`
  ADD KEY `employee_password_resets_email_index` (`email`);

--
-- Indexes for table `employerchats`
--
ALTER TABLE `employerchats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employernotifications`
--
ALTER TABLE `employernotifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employers`
--
ALTER TABLE `employers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employer_active`
--
ALTER TABLE `employer_active`
  ADD KEY `employer_active_email_index` (`email`);

--
-- Indexes for table `employer_password_resets`
--
ALTER TABLE `employer_password_resets`
  ADD KEY `employer_password_resets_email_index` (`email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`user_id`,`permission_id`,`user_type`),
  ADD KEY `permission_user_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`,`user_type`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `avmeetings`
--
ALTER TABLE `avmeetings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `category_translations`
--
ALTER TABLE `category_translations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employeechats`
--
ALTER TABLE `employeechats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employeenotifications`
--
ALTER TABLE `employeenotifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_job`
--
ALTER TABLE `employee_job`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employerchats`
--
ALTER TABLE `employerchats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employernotifications`
--
ALTER TABLE `employernotifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employers`
--
ALTER TABLE `employers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- قيود الجداول المحفوظة
--

--
-- القيود للجدول `category_translations`
--
ALTER TABLE `category_translations`
  ADD CONSTRAINT `category_translations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- القيود للجدول `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- القيود للجدول `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- القيود للجدول `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
