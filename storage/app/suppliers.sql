-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 22, 2020 at 01:35 PM
-- Server version: 8.0.21
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erp`
--

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `status`, `gst_number`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'DAS DISTRIBUTORS PVT. LTD.', 'Draft', NULL, NULL, '2020-06-02 05:39:40', '2020-06-02 05:39:40'),
(2, 'IDS DENMED PVT. LTD.', 'Draft', NULL, NULL, '2020-06-02 08:12:21', '2020-06-02 08:12:21'),
(3, 'Chesa Dental Care Service LTD.', 'Draft', NULL, NULL, '2020-06-08 10:33:39', '2020-06-08 10:33:39'),
(4, 'MARVEL AGENCIES', 'Draft', NULL, NULL, '2020-06-09 10:33:35', '2020-06-09 10:33:35'),
(5, 'SUTURES MANUFACTURING COMPANY', 'Draft', NULL, NULL, '2020-06-09 10:38:24', '2020-06-09 10:38:24'),
(6, 'GDC FINE CRAFTED DENTAL PVT. LTD.', 'Draft', NULL, NULL, '2020-06-09 11:39:42', '2020-06-09 11:39:42'),
(7, 'R & D Impex International', 'Draft', NULL, NULL, '2020-06-10 11:56:54', '2020-06-10 11:56:54'),
(8, 'STANDARD SURGICALS', 'Draft', NULL, NULL, '2020-06-12 08:41:53', '2020-06-12 08:41:53'),
(9, 'GRACE MEDICALS', 'Draft', NULL, NULL, '2020-06-13 08:51:51', '2020-06-13 08:51:51'),
(10, 'ABZ DENTAL', 'Draft', NULL, NULL, '2020-06-13 09:04:39', '2020-06-13 09:04:39'),
(11, 'DENT 2 DENT', 'Draft', NULL, NULL, '2020-06-15 10:02:50', '2020-06-15 10:02:50'),
(12, 'VELCA RACKING SYSTEMS PVT LTD.', 'Draft', NULL, NULL, '2020-06-15 10:21:19', '2020-06-15 10:21:19'),
(13, 'KUNNAMKULAM DENTAL DEPOT', 'Draft', NULL, NULL, '2020-06-16 05:58:08', '2020-06-16 05:58:08'),
(14, 'NEELKANTH HEALTH CARE PVT LTD.', 'Draft', NULL, NULL, '2020-06-18 08:00:35', '2020-06-18 08:00:35'),
(15, 'VIJAI DENTAL DEPOT PVT. LTD.', 'Draft', NULL, NULL, '2020-06-18 08:07:23', '2020-06-18 08:07:23'),
(16, 'PREVEST DENPRO LIMITED', 'Draft', NULL, NULL, '2020-06-20 06:15:06', '2020-06-20 06:15:06'),
(17, 'AURANG DENTAL & SURGICALS', 'Draft', NULL, NULL, '2020-06-20 06:20:46', '2020-06-20 06:20:46'),
(18, 'DENTAL FRUITS', 'Draft', NULL, NULL, '2020-06-20 06:51:56', '2020-06-20 06:51:56'),
(19, 'KCK DENTALS', 'Draft', NULL, NULL, '2020-06-22 08:48:22', '2020-06-22 08:48:22'),
(20, 'THE HINDUSTAN DENTAL PRODUCTS', 'Draft', NULL, NULL, '2020-06-23 10:58:22', '2020-06-23 10:58:22'),
(21, 'RAYS UNIFORMS', 'Draft', NULL, NULL, '2020-06-25 08:05:59', '2020-06-25 08:05:59'),
(22, 'CHESA DENTAL CARE SERVICES LTD.', 'Draft', NULL, NULL, '2020-06-30 05:58:35', '2020-06-30 05:58:35'),
(23, 'UNITECH INDIA', 'Draft', NULL, NULL, '2020-06-30 07:37:08', '2020-06-30 07:37:08'),
(24, 'IATOME ELECTRICAL INDIA PRIVET LIMITED', 'Draft', NULL, NULL, '2020-07-01 10:17:24', '2020-07-01 10:17:24'),
(25, 'DUERR DENTAL INDIA PVT. LTD.', 'Draft', NULL, NULL, '2020-07-02 12:19:40', '2020-07-02 12:19:40'),
(26, 'DEVATHA DENTAL', 'Draft', NULL, NULL, '2020-07-07 06:29:19', '2020-07-07 06:29:19'),
(27, 'BOMBAY DENTAL & SURGICAL PVT. LTD.', 'Draft', NULL, NULL, '2020-07-07 06:41:08', '2020-07-07 06:41:08'),
(28, 'MMR DENTALS', 'Draft', NULL, NULL, '2020-07-14 08:39:36', '2020-07-14 08:39:36'),
(29, 'ADVANCED BIOTECH PRODUCT (P) Ltd.', 'Draft', NULL, NULL, '2020-07-14 11:41:51', '2020-07-14 11:41:51'),
(30, 'HETROGENOUS COMMUNICATION TECHNOLOGIES Pvt. Ltd.', 'Draft', NULL, NULL, '2020-07-15 05:28:58', '2020-07-15 05:28:58'),
(31, 'BIRDENT HEALTHCARE', 'Draft', NULL, NULL, '2020-07-15 10:18:03', '2020-07-15 10:18:03'),
(32, 'GREEN DENTAL', 'Draft', NULL, NULL, '2020-07-15 11:50:03', '2020-07-15 11:50:03'),
(33, 'MR ASSOCIATES', 'Draft', NULL, NULL, '2020-07-15 11:52:38', '2020-07-15 11:52:38'),
(34, 'DENTSALES', 'Draft', NULL, NULL, '2020-07-16 10:05:06', '2020-07-16 10:05:06'),
(35, 'VISHAL DENTOCARE PRIVATE LIMITED', 'Draft', NULL, NULL, '2020-07-17 10:28:30', '2020-07-17 10:28:30'),
(36, 'ROYAL ENTERPRISES', 'Draft', NULL, NULL, '2020-07-18 12:25:11', '2020-07-18 12:25:11'),
(37, 'ELEM HOLDINGS INDIA Pvt Ltd', 'Draft', NULL, NULL, '2020-07-18 12:28:06', '2020-07-18 12:28:06'),
(38, 'MANI MEDICAL INDIA Pvt Ltd.', 'Draft', NULL, NULL, '2020-07-20 11:48:41', '2020-07-20 11:48:41'),
(39, 'Central Agencies Calicut', 'Draft', NULL, NULL, '2020-07-22 05:21:22', '2020-07-22 05:21:22'),
(40, 'LOCAL PURCHASE', 'Draft', NULL, NULL, '2020-07-23 05:19:34', '2020-07-23 05:19:34'),
(41, 'ANJALI INC', 'Draft', NULL, NULL, '2020-07-23 05:31:55', '2020-07-23 05:31:55'),
(42, 'Libral Traders Pvt. Ltd.', 'Draft', NULL, NULL, '2020-07-23 05:58:28', '2020-07-23 05:58:28'),
(43, 'Ashoosons', 'Draft', NULL, NULL, '2020-07-23 07:09:29', '2020-07-23 07:09:29'),
(44, 'Radha Medicals', 'Draft', NULL, NULL, '2020-07-23 11:05:41', '2020-07-23 11:05:41'),
(45, 'Dentsply Sirona', 'Draft', NULL, NULL, '2020-07-24 08:09:16', '2020-07-24 08:09:16'),
(46, 'KCK DENTAL PVT. LTD.', 'Draft', NULL, NULL, '2020-07-24 12:21:27', '2020-07-24 12:21:27'),
(47, 'Dental Avenue India Pvt Ltd', 'Draft', NULL, NULL, '2020-07-27 11:40:37', '2020-07-27 11:40:37'),
(48, 'Jaypee General Agencies', 'Draft', NULL, NULL, '2020-07-28 11:36:37', '2020-07-28 11:36:37'),
(49, 'Aggarwal Dental Supply Co.', 'Draft', NULL, NULL, '2020-07-28 12:24:22', '2020-07-28 12:24:22'),
(50, 'Shenoi Agencies', 'Draft', NULL, NULL, '2020-07-30 05:55:02', '2020-07-30 05:55:02'),
(51, 'Durr Dental India Pvt.Ltd.', 'Draft', NULL, NULL, '2020-08-03 05:48:34', '2020-08-03 05:48:34'),
(52, 'Classic Products', 'Draft', NULL, NULL, '2020-08-04 06:59:04', '2020-08-04 06:59:04'),
(53, 'Quality Enterprise Chaganacherry', 'Draft', NULL, NULL, '2020-08-04 07:13:39', '2020-08-04 07:13:39'),
(54, 'Deepashree Products', 'Draft', NULL, NULL, '2020-08-04 07:33:20', '2020-08-04 07:33:20'),
(55, 'Denmax International', 'Draft', NULL, NULL, '2020-08-04 07:41:14', '2020-08-04 07:41:14'),
(56, 'Neelkanth Health Care P Ltd.', 'Draft', NULL, NULL, '2020-08-05 08:10:17', '2020-08-05 08:10:17'),
(57, 'Balaji Dental Supplies', 'Draft', NULL, NULL, '2020-08-05 08:20:56', '2020-08-05 08:20:56'),
(58, 'Medasta', 'Draft', NULL, NULL, '2020-08-06 11:10:16', '2020-08-06 11:10:16'),
(59, 'Solar Pharmaceutical', 'Draft', NULL, NULL, '2020-08-07 11:35:11', '2020-08-07 11:35:11'),
(60, 'Trichur Dental Depot', 'Draft', NULL, NULL, '2020-08-11 10:56:32', '2020-08-11 10:56:32'),
(61, 'Pyrax International', 'Draft', NULL, NULL, '2020-08-12 06:37:18', '2020-08-12 06:37:18'),
(62, 'Nexus Medodent', 'Draft', NULL, NULL, '2020-08-12 06:52:21', '2020-08-12 06:52:21'),
(63, 'Zam Zam Agencies', 'Draft', NULL, NULL, '2020-08-12 07:39:12', '2020-08-12 07:39:12'),
(64, 'Aditya Medical System', 'Draft', NULL, NULL, '2020-08-14 07:08:15', '2020-08-14 07:08:15'),
(65, 'Shree Ganesh Dental Depot', 'Draft', NULL, NULL, '2020-08-17 07:40:01', '2020-08-17 07:40:01'),
(66, 'Jamuna International', 'Draft', NULL, NULL, '2020-08-17 07:50:16', '2020-08-17 07:50:16'),
(67, 'Filay Dent', 'Draft', NULL, NULL, '2020-08-17 10:34:35', '2020-08-17 10:34:35'),
(68, 'Divya Health Care', 'Draft', NULL, NULL, '2020-08-19 05:55:33', '2020-08-19 05:55:33'),
(69, 'S.S.Surgicals', 'Draft', NULL, NULL, '2020-08-20 07:58:10', '2020-08-20 07:58:10'),
(70, 'Coltene Whaledent Pvt. Ltd', 'Draft', NULL, NULL, '2020-08-21 06:39:57', '2020-08-21 06:39:57'),
(71, 'Apex Industrial Electronics', 'Draft', NULL, NULL, '2020-08-21 07:21:15', '2020-08-21 07:21:15'),
(72, 'Mogli Labs Pvt Ltd', 'Draft', NULL, NULL, '2020-08-22 11:39:11', '2020-08-22 11:39:11'),
(73, 'Acteon India Pvt. Ltd.', 'Draft', NULL, NULL, '2020-08-25 06:01:18', '2020-08-25 06:01:18'),
(74, 'Q - Dent Products', 'Draft', NULL, NULL, '2020-08-25 07:19:32', '2020-08-25 07:19:32'),
(75, 'Dental Products Of India', 'Draft', NULL, NULL, '2020-08-28 10:52:49', '2020-08-28 10:52:49'),
(76, 'Radiance Online', 'Draft', NULL, NULL, '2020-08-28 11:28:37', '2020-08-28 11:28:37'),
(77, 'GC India Dental Private Limited', 'Draft', NULL, NULL, '2020-09-02 06:49:51', '2020-09-02 06:49:51'),
(78, 'STM Meditech', 'Draft', NULL, NULL, '2020-09-02 09:45:37', '2020-09-02 09:45:37'),
(79, 'K.S Mathur And Company', 'Draft', NULL, NULL, '2020-09-02 10:44:08', '2020-09-02 10:44:08'),
(80, 'Anand Drug Lines', 'Draft', NULL, NULL, '2020-09-03 09:56:48', '2020-09-03 09:56:48'),
(81, 'Nobel Biocare India Pvt. Ltd', 'Draft', NULL, NULL, '2020-09-04 07:47:30', '2020-09-04 07:47:30'),
(82, '3M India Limited', 'Draft', NULL, NULL, '2020-09-09 07:49:15', '2020-09-09 07:49:15'),
(83, 'Confident Dental Equipments Pvt Ltd.', 'Draft', NULL, NULL, '2020-09-10 08:42:13', '2020-09-10 08:42:13'),
(84, 'Sakshi Meditech', 'Draft', NULL, NULL, '2020-09-10 12:36:56', '2020-09-10 12:36:56'),
(85, 'Gujarat Dental Products', 'Draft', NULL, NULL, '2020-09-10 12:50:13', '2020-09-10 12:50:13'),
(86, 'Shree Marketings', 'Draft', NULL, NULL, '2020-09-12 07:21:12', '2020-09-12 07:21:12'),
(87, 'Kushako India', 'Draft', NULL, NULL, '2020-09-14 07:49:28', '2020-09-14 07:49:28'),
(88, 'Jayna Industries', 'Draft', NULL, NULL, '2020-09-15 10:50:07', '2020-09-15 10:50:07'),
(89, 'Confident Sales India Pvt Ltd', 'Draft', NULL, NULL, '2020-09-16 07:51:35', '2020-09-16 07:51:35'),
(90, 'Harsha Dental Equipments', 'Draft', NULL, NULL, '2020-09-16 08:03:22', '2020-09-16 08:03:22'),
(91, 'Jenco Industries', 'Draft', NULL, NULL, '2020-09-16 08:08:14', '2020-09-16 08:08:14'),
(92, 'Union Dental', 'Draft', NULL, NULL, '2020-09-17 10:11:37', '2020-09-17 10:11:37'),
(93, 'Surana Enterprises', 'Draft', NULL, NULL, '2020-09-18 05:27:41', '2020-09-18 05:27:41'),
(94, 'Recipe Drugs', 'Draft', NULL, NULL, '2020-09-23 05:50:53', '2020-09-23 05:50:53'),
(95, 'Smart Associates', 'Draft', NULL, NULL, '2020-09-23 06:36:17', '2020-09-23 06:36:17'),
(96, 'Kehr Surgical Private Limited', 'Draft', NULL, NULL, '2020-09-28 06:17:38', '2020-09-28 06:17:38'),
(97, 'Dentway Surgicals', 'Draft', NULL, NULL, '2020-09-28 06:28:08', '2020-09-28 06:28:08'),
(98, 'Hyderabad Dental Depot', 'Draft', NULL, NULL, '2020-09-29 10:24:07', '2020-09-29 10:24:07'),
(99, 'Vatech India', 'Draft', NULL, NULL, '2020-10-06 11:23:39', '2020-10-06 11:23:39'),
(100, 'Alliance Drugs Distributors', 'Draft', NULL, NULL, '2020-10-06 12:05:33', '2020-10-06 12:05:33'),
(101, 'M/S. Jabbar & Company', 'Draft', NULL, NULL, '2020-10-17 08:02:01', '2020-10-17 08:02:01'),
(102, 'Analyticals Medical Technologies Pvt Ltd.', 'Draft', NULL, NULL, '2020-10-19 08:42:32', '2020-10-19 08:42:32'),
(103, 'M&M Dental Associates', 'Draft', NULL, NULL, '2020-10-20 07:54:09', '2020-10-20 07:54:09'),
(104, 'S.K. Surgicals', 'Draft', NULL, NULL, '2020-10-22 06:45:50', '2020-10-22 08:18:59'),
(105, 'Pivo International', 'Draft', NULL, NULL, '2020-10-29 07:14:02', '2020-10-29 07:14:02'),
(106, 'Goodwill Lifesciences', 'Draft', NULL, NULL, '2020-10-29 07:19:04', '2020-10-29 07:19:04'),
(107, 'Medishield Healthcare', 'Draft', NULL, NULL, '2020-11-04 11:53:30', '2020-11-04 11:56:15'),
(108, 'Dent Surgicals', 'Draft', NULL, NULL, '2020-11-09 08:02:58', '2020-11-09 08:02:58'),
(109, 'Helix-Tech', 'Draft', NULL, NULL, '2020-11-10 10:18:10', '2020-11-10 10:18:10'),
(110, 'Golden Nimbus India Pvt Ltd', 'Draft', NULL, NULL, '2020-11-11 06:06:01', '2020-11-11 06:06:01'),
(111, 'ABM4 Trades', 'Draft', NULL, NULL, '2020-11-11 06:31:41', '2020-11-11 06:31:41'),
(112, 'Mas Enterprises', 'Draft', NULL, NULL, '2020-11-16 08:57:00', '2020-11-16 08:57:00'),
(113, 'Waldent Innovations Private Limited', 'Draft', NULL, NULL, '2020-11-18 07:13:58', '2020-11-18 07:13:58'),
(114, 'Malabar X Ray & Surgical Co.', 'Draft', NULL, NULL, '2020-11-19 11:30:04', '2020-11-19 11:30:04');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
