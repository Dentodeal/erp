-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 07, 2020 at 04:23 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--


--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `phonecode`, `currencycode`) VALUES
(1, 'Afghanistan', '+93', 'AFN'),
(2, 'Albania', '+355', 'ALL'),
(3, 'Algeria', '+213', 'DZD'),
(4, 'American Samoa', '+1684', 'USD'),
(5, 'Andorra', '+376', 'EUR'),
(6, 'Angola', '+244', 'AOA'),
(7, 'Anguilla', '+1264', 'XCD'),
(8, 'Antigua And Barbuda', '+1268', 'XCD'),
(9, 'Argentina', '+54', 'ARS'),
(10, 'Armenia', '+374', 'AMD'),
(11, 'Aruba', '+297', 'AWG'),
(12, 'Australia', '+61', 'AUD'),
(13, 'Austria', '+43', 'EUR'),
(14, 'Azerbaijan', '+994', 'AZN'),
(15, 'Bahamas', '+1242', 'BSD'),
(16, 'Bahrain', '+973', 'BHD'),
(17, 'Bangladesh', '+880', 'BDT'),
(18, 'Barbados', '+1246', 'BBD'),
(19, 'Belarus', '+375', 'BYN'),
(20, 'Belgium', '+32', 'EUR'),
(21, 'Belize', '+501', 'BZD'),
(22, 'Benin', '+229', 'XOF'),
(23, 'Bermuda', '+1441', 'BMD'),
(24, 'Bhutan', '+975', 'BTN'),
(25, 'Bolivia', '+591', 'BOB'),
(26, 'Bosnia and Herzegovina', '+387', 'BAM'),
(27, 'Botswana', '+267', 'BWP'),
(28, 'Brazil', '+55', 'BRL'),
(29, 'British Indian Ocean Territory', '+246', 'USD'),
(30, 'British Virgin Islands', '+1284', 'USD'),
(31, 'Brunei', '+673', 'BND'),
(32, 'Bulgaria', '+359', 'BGN'),
(33, 'Burkina Faso', '+226', 'XOF'),
(34, 'Burundi', '+257', 'BIF'),
(35, 'Cambodia', '+855', 'KHR'),
(36, 'Cameroon', '+237', 'XAF'),
(37, 'Canada', '+1', 'CAD'),
(38, 'Cape Verde', '+238', 'CVE'),
(39, 'Cayman Islands', '+1345', 'KYD'),
(40, 'Central African Republic', '+236', 'XAF'),
(41, 'Chad', '+235', 'XAF'),
(42, 'Chile', '+56', 'CLP'),
(43, 'China', '+86', 'CNY'),
(44, 'Christmas Island', '+61', 'AUD'),
(45, 'Cocos Islands', '+61', 'AUD'),
(46, 'Colombia', '+57', 'COP'),
(47, 'Comoros', '+269', 'KMF'),
(48, 'Cook Islands', '+682', 'NZD'),
(49, 'Costa Rica', '+506', 'CRC'),
(50, 'Croatia', '+385', 'HRK'),
(51, 'Cuba', '+53', 'CUP'),
(52, 'Curacao', '+599', 'NLG'),
(53, 'Cyprus', '+357', 'EUR'),
(54, 'Czech Republic', '+420', 'CZK'),
(55, 'Democratic Republic of the Congo', '+243', 'CDF'),
(56, 'Denmark', '+45', 'DKK'),
(57, 'Djibouti', '+253', 'DJF'),
(58, 'Dominica', '+1767', 'XCD'),
(59, 'Dominican Republic (+1-809)', '+1809', 'DOP'),
(60, 'Dominican Republic (+1-829)', '+1829', 'DOP'),
(61, 'East Timor', '+670', 'USD'),
(62, 'Ecuador', '+593', 'USD'),
(63, 'Egypt', '+20', 'EGP'),
(64, 'El Salvador', '+503', 'USD'),
(65, 'Equatorial Guinea', '+240', 'XAF'),
(66, 'Eritrea', '+291', 'ERN'),
(67, 'Estonia', '+372', 'EUR'),
(68, 'Ethiopia', '+251', 'ETB'),
(69, 'Falkland Islands', '+500', 'FKP'),
(70, 'Faroe Islands', '+298', 'DKK'),
(71, 'Fiji Islands', '+679', 'FJD'),
(72, 'Finland', '+358', 'EUR'),
(73, 'France', '+33', 'EUR'),
(74, 'French Polynesia', '+689', 'XPF'),
(75, 'Gabon', '+241', 'XAF'),
(76, 'Gambia', '+220', 'GMD'),
(77, 'Georgia', '+995', 'GEL'),
(78, 'Germany', '+49', 'EUR'),
(79, 'Ghana', '+233', 'GHS'),
(80, 'Gibraltar', '+350', 'GIP'),
(81, 'Greece', '+30', 'EUR'),
(82, 'Greenland', '+299', 'DKK'),
(83, 'Grenada', '+1473', 'XCD'),
(84, 'Guam', '+1671', 'USD'),
(85, 'Guatemala', '+502', 'GTQ'),
(86, 'Guernsey', '+441481', 'GBP'),
(87, 'Guinea', '+224', 'GNF'),
(88, 'Guinea-Bissau', '+245', 'XOF'),
(89, 'Guyana', '+592', 'GYD'),
(90, 'Haiti', '+509', 'HTG'),
(91, 'Honduras', '+504', 'HNL'),
(92, 'Hong Kong', '+852', 'HKD'),
(93, 'Hungary', '+36', 'HUF'),
(94, 'Iceland', '+354', 'ISK'),
(95, 'India', '+91', 'INR'),
(96, 'Indonesia', '+62', 'IDR'),
(97, 'Iran', '+98', 'IRR'),
(98, 'Iraq', '+964', 'IQD'),
(99, 'Ireland', '+353', 'EUR'),
(100, 'Isle of Man', '+441624', 'GBP'),
(101, 'Israel', '+972', 'ILS'),
(102, 'Italy', '+39', 'EUR'),
(103, 'Ivory Coast', '+225', 'XOF'),
(104, 'Jamaica', '+1876', 'JMD'),
(105, 'Japan', '+81', 'JPY'),
(106, 'Jersey', '+441534', 'GBP'),
(107, 'Jordan', '+962', 'JOD'),
(108, 'Kazakhstan', '+7', 'KZT'),
(109, 'Kenya', '+254', 'KES'),
(110, 'Kiribati', '+686', 'AUD'),
(111, 'Kosovo', '+383', 'EUR'),
(112, 'Kuwait', '+965', 'KWD'),
(113, 'Kyrgyzstan', '+996', 'KGS'),
(114, 'Laos', '+856', 'LAK'),
(115, 'Latvia', '+371', 'EUR'),
(116, 'Lebanon', '+961', 'LBP'),
(117, 'Lesotho', '+266', 'LSL'),
(118, 'Liberia', '+231', 'LRD'),
(119, 'Libya', '+218', 'LYD'),
(120, 'Liechtenstein', '+423', 'CHF'),
(121, 'Lithuania', '+370', 'EUR'),
(122, 'Luxembourg', '+352', 'EUR'),
(123, 'Macau', '+853', 'MOP'),
(124, 'Macedonia', '+389', 'MKD'),
(125, 'Madagascar', '+261', 'MGA'),
(126, 'Malawi', '+265', 'MWK'),
(127, 'Malaysia', '+60', 'MYR'),
(128, 'Maldives', '+960', 'MVR'),
(129, 'Mali', '+223', 'XOF'),
(130, 'Malta', '+356', 'EUR'),
(131, 'Marshall Islands', '+692', 'USD'),
(132, 'Mauritania', '+222', 'MRO'),
(133, 'Mauritius', '+230', 'MUR'),
(134, 'Mayotte', '+262', 'EUR'),
(135, 'Mexico', '+52', 'MXN'),
(136, 'Micronesia', '+691', 'USD'),
(137, 'Moldova', '+373', 'MDL'),
(138, 'Monaco', '+377', 'EUR'),
(139, 'Mongolia', '+976', 'MNT'),
(140, 'Montenegro', '+382', 'EUR'),
(141, 'Montserrat', '+1664', 'XCD'),
(142, 'Morocco', '+212', 'MAD'),
(143, 'Mozambique', '+258', 'MZN'),
(144, 'Myanmar', '+95', 'MMK'),
(145, 'Namibia', '+264', 'NAD'),
(146, 'Nauru', '+674', 'AUD'),
(147, 'Nepal', '+977', 'NPR'),
(148, 'Netherlands', '+31', 'EUR'),
(149, 'New Caledonia', '+687', 'XPF'),
(150, 'New Zealand', '+64', 'NZD'),
(151, 'Nicaragua', '+505', 'NIO'),
(152, 'Niger', '+227', 'XOF'),
(153, 'Nigeria', '+234', 'NGN'),
(154, 'Niue', '+683', 'NZD'),
(155, 'North Korea', '+850', 'KPW'),
(156, 'Northern Mariana Islands', '+1670', 'USD'),
(157, 'Norway', '+47', 'NOK'),
(158, 'Oman', '+968', 'OMR'),
(159, 'Pakistan', '+92', 'PKR'),
(160, 'Palau', '+680', 'USD'),
(161, 'Palestine', '+970', 'ILS'),
(162, 'Panama', '+507', 'PAB'),
(163, 'Papua new Guinea', '+675', 'PGK'),
(164, 'Paraguay', '+595', 'PYG'),
(165, 'Peru', '+51', 'PEN'),
(166, 'Philippines', '+63', 'PHP'),
(167, 'Pitcairn', '+870', 'NZD'),
(168, 'Poland', '+48', 'PLN'),
(169, 'Portugal', '+351', 'EUR'),
(170, 'Puerto Rico (+1-787)', '+1787', 'USD'),
(171, 'Puerto Rico (+1-939)', '+1939', 'USD'),
(172, 'Qatar', '+974', 'QAR'),
(173, 'Republic of the Congo', '+242', 'XAF'),
(174, 'Reunion', '+262', 'EUR'),
(175, 'Romania', '+40', 'RON'),
(176, 'Russia', '+7', 'RUB'),
(177, 'Rwanda', '+250', 'RWF'),
(178, 'Saint Barthelemy', '+590', 'EUR'),
(179, 'Saint Helena', '+290', 'SHP'),
(180, 'Saint Kitts And Nevis', '+1869', 'XCD'),
(181, 'Saint Lucia', '+1758', 'XCD'),
(182, 'Saint Martin', '+590', 'EUR'),
(183, 'Saint Pierre and Miquelon', '+508', 'EUR'),
(184, 'Saint Vincent And The Grenadines', '+1784', 'XCD'),
(185, 'Samoa', '+685', 'WST'),
(186, 'San Marino', '+378', 'EUR'),
(187, 'Sao Tome and Principe', '+239', 'STD'),
(188, 'Saudi Arabia', '+966', 'SAR'),
(189, 'Senegal', '+221', 'XOF'),
(190, 'Serbia', '+381', 'RSD'),
(191, 'Seychelles', '+248', 'SCR'),
(192, 'Sierra Leone', '+232', 'SLL'),
(193, 'Singapore', '+65', 'SGD'),
(194, 'Sint Maarten', '+1721', 'NLG'),
(195, 'Slovakia', '+421', 'EUR'),
(196, 'Slovenia', '+386', 'EUR'),
(197, 'Solomon Islands', '+677', 'SBD'),
(198, 'Somalia', '+252', 'SOS'),
(199, 'South Africa', '+27', 'ZAR'),
(200, 'South Korea', '+82', 'KRW'),
(201, 'South Sudan', '+211', 'SSP'),
(202, 'Spain', '+34', 'EUR'),
(203, 'Sri Lanka', '+94', 'LKR'),
(204, 'Sudan', '+249', 'SDG'),
(205, 'Suriname', '+597', 'SRD'),
(206, 'Svalbard and Jan Mayen', '+47', 'NOK'),
(207, 'Swaziland', '+268', 'SZL'),
(208, 'Sweden', '+46', 'SEK'),
(209, 'Switzerland', '+41', 'CHF'),
(210, 'Syria', '+963', 'SYP'),
(211, 'Taiwan', '+886', 'TWD'),
(212, 'Tajikistan', '+992', 'TJS'),
(213, 'Tanzania', '+255', 'TZS'),
(214, 'Thailand', '+66', 'THB'),
(215, 'Togo', '+228', 'XOF'),
(216, 'Tokelau', '+690', 'NZD'),
(217, 'Tonga', '+676', 'TOP'),
(218, 'Trinidad And Tobago', '+1868', 'TTD'),
(219, 'Tunisia', '+216', 'TND'),
(220, 'Turkey', '+90', 'TRY'),
(221, 'Turkmenistan', '+993', 'TMT'),
(222, 'Turks And Caicos Islands', '+1649', 'USD'),
(223, 'Tuvalu', '+688', 'AUD'),
(224, 'U.S. Virgin Islands', '+1340', 'USD'),
(225, 'Uganda', '+256', 'UGX'),
(226, 'Ukraine', '+380', 'UAH'),
(227, 'United Arab Emirates', '+971', 'AED'),
(228, 'United Kingdom', '+44', 'GBP'),
(229, 'United States', '+1', 'USD'),
(230, 'Uruguay', '+598', 'UYU'),
(231, 'Uzbekistan', '+998', 'UZS'),
(232, 'Vanuatu', '+678', 'VUV'),
(233, 'Vatican', '+379', 'EUR'),
(234, 'Venezuela', '+58', 'VEF'),
(235, 'Vietnam', '+84', 'VND'),
(236, 'Wallis and Futuna', '+681', 'XPF'),
(237, 'Western Sahara', '+212', 'MAD'),
(238, 'Yemen', '+967', 'YER'),
(239, 'Zambia', '+260', 'ZMW'),
(240, 'Zimbabwe', '+263', 'ZWL');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
