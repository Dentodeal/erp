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
-- Table structure for table `districts`
--


--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `name`, `state_id`) VALUES
(1, 'Nicobar', 1585),
(2, 'North and Middle Andaman', 1585),
(3, 'South Andaman', 1585),
(4, 'Anantapur', 1586),
(5, 'Chittoor', 1586),
(6, 'East Godavari', 1586),
(7, 'Guntur', 1586),
(8, 'YSR Kadapa', 1586),
(9, 'Krishna', 1586),
(10, 'Kurnool', 1586),
(11, 'Prakasam', 1586),
(12, 'Sri Potti Sriramulu Nellore', 1586),
(13, 'Srikakulam', 1586),
(14, 'Visakhapatnam', 1586),
(15, 'Vizianagaram', 1586),
(16, 'West Godavari', 1586),
(17, 'Anjaw', 1587),
(18, 'Changlang', 1587),
(19, 'East Kameng', 1587),
(20, 'East Siang', 1587),
(21, 'Kamle', 1587),
(22, 'Kra Daadi', 1587),
(23, 'Kurung Kumey', 1587),
(24, 'Lepa Rada', 1587),
(25, 'Lohit', 1587),
(26, 'Longding', 1587),
(27, 'Lower Dibang Valley', 1587),
(28, 'Lower Siang', 1587),
(29, 'Lower Subansiri', 1587),
(30, 'Namsai', 1587),
(31, 'Pakke-Kessang', 1587),
(32, 'Papum Pare', 1587),
(33, 'Shi Yomi', 1587),
(34, 'Siang', 1587),
(35, 'Tawang', 1587),
(36, 'Tirap', 1587),
(37, 'Upper Dibang Valley', 1587),
(38, 'Upper Siang', 1587),
(39, 'Upper Subansiri', 1587),
(40, 'West Kameng', 1587),
(41, 'West Siang', 1587),
(42, 'Baksa', 1588),
(43, 'Barpeta', 1588),
(44, 'Bishwanath', 1588),
(45, 'Bongaigaon', 1588),
(46, 'Cachar', 1588),
(47, 'Charaideo', 1588),
(48, 'Chirang', 1588),
(49, 'Darrang', 1588),
(50, 'Dhemaji', 1588),
(51, 'Dhubri', 1588),
(52, 'Dibrugarh', 1588),
(53, 'Dima Hasao', 1588),
(54, 'Goalpara', 1588),
(55, 'Golaghat', 1588),
(56, 'Hailakandi', 1588),
(57, 'Hojai', 1588),
(58, 'Jorhat', 1588),
(59, 'Kamrup', 1588),
(60, 'Kamrup Metropolitan', 1588),
(61, 'Karbi Anglong', 1588),
(62, 'Karimganj', 1588),
(63, 'Kokrajhar', 1588),
(64, 'Lakhimpur', 1588),
(65, 'Majuli', 1588),
(66, 'Morigaon', 1588),
(67, 'Nagaon', 1588),
(68, 'Nalbari', 1588),
(69, 'Sivasagar', 1588),
(70, 'South Salmara', 1588),
(71, 'Sonitpur', 1588),
(72, 'Tinsukia', 1588),
(73, 'Udalguri', 1588),
(74, 'West Karbi Anglong', 1588),
(75, 'Araria', 1589),
(76, 'Arwal', 1589),
(77, 'Aurangabad', 1589),
(78, 'Banka', 1589),
(79, 'Begusarai', 1589),
(80, 'Bhagalpur', 1589),
(81, 'Bhojpur', 1589),
(82, 'Buxar', 1589),
(83, 'Darbhanga', 1589),
(84, 'East Champaran', 1589),
(85, 'Gaya', 1589),
(86, 'Gopalganj', 1589),
(87, 'Jamui', 1589),
(88, 'Jehanabad', 1589),
(89, 'Kaimur', 1589),
(90, 'Katihar', 1589),
(91, 'Khagaria', 1589),
(92, 'Kishanganj', 1589),
(93, 'Lakhisarai', 1589),
(94, 'Madhepura', 1589),
(95, 'Madhubani', 1589),
(96, 'Munger', 1589),
(97, 'Muzaffarpur', 1589),
(98, 'Nalanda', 1589),
(99, 'Nawada', 1589),
(100, 'Patna', 1589),
(101, 'Purnia', 1589),
(102, 'Rohtas', 1589),
(103, 'Saharsa', 1589),
(104, 'Samastipur', 1589),
(105, 'Saran', 1589),
(106, 'Sheikhpura', 1589),
(107, 'Sheohar', 1589),
(108, 'Sitamarhi', 1589),
(109, 'Siwan', 1589),
(110, 'Supaul', 1589),
(111, 'Vaishali', 1589),
(112, 'West Champaran', 1589),
(113, 'Chandigarh', 1590),
(114, 'Balod', 1591),
(115, 'Baloda Bazar', 1591),
(116, 'Balrampur', 1591),
(117, 'Bastar', 1591),
(118, 'Bemetara', 1591),
(119, 'Bijapur', 1591),
(120, 'Bilaspur', 1591),
(121, 'Dantewada', 1591),
(122, 'Dhamtari', 1591),
(123, 'Durg', 1591),
(124, 'Gariaband', 1591),
(125, 'Gaurela-Pendra-Marwahi', 1591),
(126, 'Janjgir-Champa', 1591),
(127, 'Jashpur', 1591),
(128, 'Kabirdham', 1591),
(129, 'Kanker', 1591),
(130, 'Kondagaon', 1591),
(131, 'Korba', 1591),
(132, 'Koriya', 1591),
(133, 'Mahasamund', 1591),
(134, 'Mungeli', 1591),
(135, 'Narayanpur', 1591),
(136, 'Raigarh', 1591),
(137, 'Raipur', 1591),
(138, 'Rajnandgaon', 1591),
(139, 'Sukma', 1591),
(140, 'Surajpur', 1591),
(141, 'Surguja', 1591),
(142, 'Dadra and Nagar Haveli', 1592),
(143, 'Daman', 1593),
(144, 'Diu', 1593),
(145, 'Central Delhi', 1594),
(146, 'East Delhi', 1594),
(147, 'New Delhi', 1594),
(148, 'North Delhi', 1594),
(149, 'North East Delhi', 1594),
(150, 'North West Delhi', 1594),
(151, 'Shahdara', 1594),
(152, 'South Delhi', 1594),
(153, 'South East Delhi', 1594),
(154, 'South West Delhi', 1594),
(155, 'West Delhi', 1594),
(156, 'North Goa', 1595),
(157, 'South Goa', 1595),
(158, 'Ahmedabad', 1596),
(159, 'Amreli', 1596),
(160, 'Anand', 1596),
(161, 'Aravalli', 1596),
(162, 'Banaskantha', 1596),
(163, 'Bharuch', 1596),
(164, 'Bhavnagar', 1596),
(165, 'Botad', 1596),
(166, 'Chhota Udepur', 1596),
(167, 'Dahod', 1596),
(168, 'Dang', 1596),
(169, 'Devbhoomi Dwarka', 1596),
(170, 'Gandhinagar', 1596),
(171, 'Gir Somnath', 1596),
(172, 'Jamnagar', 1596),
(173, 'Junagadh', 1596),
(174, 'Kheda', 1596),
(175, 'Kutch', 1596),
(176, 'Mahisagar', 1596),
(177, 'Mehsana', 1596),
(178, 'Morbi', 1596),
(179, 'Narmada', 1596),
(180, 'Navsari', 1596),
(181, 'Panchmahal', 1596),
(182, 'Patan', 1596),
(183, 'Porbandar', 1596),
(184, 'Rajkot', 1596),
(185, 'Sabarkantha', 1596),
(186, 'Surat', 1596),
(187, 'Surendranagar', 1596),
(188, 'Tapi', 1596),
(189, 'Vadodara', 1596),
(190, 'Valsad', 1596),
(191, 'Ambala', 1597),
(192, 'Bhiwani', 1597),
(193, 'Charkhi Dadri', 1597),
(194, 'Faridabad', 1597),
(195, 'Fatehabad', 1597),
(196, 'Gurgaon', 1597),
(197, 'Hissar', 1597),
(198, 'Jhajjar', 1597),
(199, 'Jind', 1597),
(200, 'Kaithal', 1597),
(201, 'Karnal', 1597),
(202, 'Kurukshetra', 1597),
(203, 'Mahendragarh', 1597),
(204, 'Nuh', 1597),
(205, 'Palwal', 1597),
(206, 'Panchkula', 1597),
(207, 'Panipat', 1597),
(208, 'Rewari', 1597),
(209, 'Rohtak', 1597),
(210, 'Sirsa', 1597),
(211, 'Sonipat', 1597),
(212, 'Yamuna Nagar', 1597),
(213, 'Bilaspur', 1598),
(214, 'Chamba', 1598),
(215, 'Hamirpur', 1598),
(216, 'Kangra', 1598),
(217, 'Kinnaur', 1598),
(218, 'Kullu', 1598),
(219, 'Lahaul and Spiti', 1598),
(220, 'Mandi', 1598),
(221, 'Shimla', 1598),
(222, 'Sirmaur', 1598),
(223, 'Solan', 1598),
(224, 'Una', 1598),
(225, 'Anantnag', 1599),
(226, 'Bandipora', 1599),
(227, 'Baramulla', 1599),
(228, 'Badgam', 1599),
(229, 'Doda', 1599),
(230, 'Ganderbal', 1599),
(231, 'Jammu', 1599),
(232, 'Kathua', 1599),
(233, 'Kishtwar', 1599),
(234, 'Kulgam', 1599),
(235, 'Kupwara', 1599),
(236, 'Poonch', 1599),
(237, 'Pulwama', 1599),
(238, 'Rajouri', 1599),
(239, 'Ramban', 1599),
(240, 'Reasi', 1599),
(241, 'Samba', 1599),
(242, 'Shopian', 1599),
(243, 'Srinagar', 1599),
(244, 'Udhampur', 1599),
(245, 'Bokaro', 1600),
(246, 'Chatra', 1600),
(247, 'Deoghar', 1600),
(248, 'Dhanbad', 1600),
(249, 'Dumka', 1600),
(250, 'East Singhbhum', 1600),
(251, 'Garhwa', 1600),
(252, 'Giridih', 1600),
(253, 'Godda', 1600),
(254, 'Gumla', 1600),
(255, 'Hazaribag', 1600),
(256, 'Jamtara', 1600),
(257, 'Khunti', 1600),
(258, 'Koderma', 1600),
(259, 'Latehar', 1600),
(260, 'Lohardaga', 1600),
(261, 'Pakur', 1600),
(262, 'Palamu', 1600),
(263, 'Ramgarh', 1600),
(264, 'Ranchi', 1600),
(265, 'Sahibganj', 1600),
(266, 'Seraikela Kharsawan', 1600),
(267, 'Simdega', 1600),
(268, 'West Singhbhum', 1600),
(269, 'Bagalkot', 1601),
(270, 'Ballari', 1601),
(271, 'Belgaum', 1601),
(272, 'Bangalore', 1601),
(273, 'Bidar', 1601),
(274, 'Chamarajanagara', 1601),
(275, 'Chikkaballapura', 1601),
(276, 'Chikmagalur', 1601),
(277, 'Chitradurga', 1601),
(278, 'Dakshina Kannada', 1601),
(279, 'Davanagere', 1601),
(280, 'Dharwad', 1601),
(281, 'Gadag', 1601),
(282, 'Gulbarga', 1601),
(283, 'Hassan', 1601),
(284, 'Haveri', 1601),
(285, 'Kodagu', 1601),
(286, 'Kolar', 1601),
(287, 'Koppal', 1601),
(288, 'Mandya', 1601),
(289, 'Mysore', 1601),
(290, 'Raichur', 1601),
(291, 'Ramanagara', 1601),
(292, 'Shimoga', 1601),
(293, 'Tumakuru', 1601),
(294, 'Udupi', 1601),
(295, 'Uttara Kannada', 1601),
(296, 'Bijapur', 1601),
(297, 'Yadgir', 1601),
(298, 'Alappuzha', 1602),
(299, 'Ernakulam', 1602),
(300, 'Idukki', 1602),
(301, 'Kannur', 1602),
(302, 'Kasaragod', 1602),
(303, 'Kollam', 1602),
(304, 'Kottayam', 1602),
(305, 'Kozhikode', 1602),
(306, 'Malappuram', 1602),
(307, 'Palakkad', 1602),
(308, 'Pathanamthitta', 1602),
(309, 'Thrissur', 1602),
(310, 'Thiruvananthapuram', 1602),
(311, 'Wayanad', 1602),
(312, 'Kargil', 1603),
(313, 'Leh', 1603),
(314, 'Lakshadweep', 1604),
(315, 'Agar Malwa', 1605),
(316, 'Alirajpur', 1605),
(317, 'Anuppur', 1605),
(318, 'Ashok Nagar', 1605),
(319, 'Balaghat', 1605),
(320, 'Barwani', 1605),
(321, 'Betul', 1605),
(322, 'Bhind', 1605),
(323, 'Bhopal', 1605),
(324, 'Burhanpur', 1605),
(325, 'Chachaura-Binaganj', 1605),
(326, 'Chhatarpur', 1605),
(327, 'Chhindwara', 1605),
(328, 'Damoh', 1605),
(329, 'Datia', 1605),
(330, 'Dewas', 1605),
(331, 'Dhar', 1605),
(332, 'Dindori', 1605),
(333, 'Guna', 1605),
(334, 'Gwalior', 1605),
(335, 'Harda', 1605),
(336, 'Hoshangabad', 1605),
(337, 'Indore', 1605),
(338, 'Jabalpur', 1605),
(339, 'Jhabua', 1605),
(340, 'Katni', 1605),
(341, 'Khandwa', 1605),
(342, 'Khargone', 1605),
(343, 'Maihar', 1605),
(344, 'Mandla', 1605),
(345, 'Mandsaur', 1605),
(346, 'Morena', 1605),
(347, 'Narsinghpur', 1605),
(348, 'Nagda', 1605),
(349, 'Neemuch', 1605),
(350, 'Niwari', 1605),
(351, 'Panna', 1605),
(352, 'Raisen', 1605),
(353, 'Rajgarh', 1605),
(354, 'Ratlam', 1605),
(355, 'Rewa', 1605),
(356, 'Sagar', 1605),
(357, 'Satna', 1605),
(358, 'Sehore', 1605),
(359, 'Seoni', 1605),
(360, 'Shahdol', 1605),
(361, 'Shajapur', 1605),
(362, 'Sheopur', 1605),
(363, 'Shivpuri', 1605),
(364, 'Sidhi', 1605),
(365, 'Singrauli', 1605),
(366, 'Tikamgarh', 1605),
(367, 'Ujjain', 1605),
(368, 'Umaria', 1605),
(369, 'Vidisha', 1605),
(370, 'Ahmednagar', 1606),
(371, 'Akola', 1606),
(372, 'Amravati', 1606),
(373, 'Aurangabad', 1606),
(374, 'Beed', 1606),
(375, 'Bhandara', 1606),
(376, 'Buldhana', 1606),
(377, 'Chandrapur', 1606),
(378, 'Dhule', 1606),
(379, 'Gadchiroli', 1606),
(380, 'Gondia', 1606),
(381, 'Hingoli', 1606),
(382, 'Jalgaon', 1606),
(383, 'Jalna', 1606),
(384, 'Kolhapur', 1606),
(385, 'Latur', 1606),
(386, 'Mumbai', 1606),
(387, 'Nanded', 1606),
(388, 'Nandurbar', 1606),
(389, 'Nagpur', 1606),
(390, 'Nashik', 1606),
(391, 'Osmanabad', 1606),
(392, 'Palghar', 1606),
(393, 'Parbhani', 1606),
(394, 'Pune', 1606),
(395, 'Raigad', 1606),
(396, 'Ratnagiri', 1606),
(397, 'Sangli', 1606),
(398, 'Satara', 1606),
(399, 'Sindhudurg', 1606),
(400, 'Solapur', 1606),
(401, 'Thane', 1606),
(402, 'Wardha', 1606),
(403, 'Washim', 1606),
(404, 'Yavatmal', 1606),
(405, 'Bishnupur', 1607),
(406, 'Chandel', 1607),
(407, 'Churachandpur', 1607),
(408, 'Imphal East', 1607),
(409, 'Imphal West', 1607),
(410, 'Jiribam', 1607),
(411, 'Kakching', 1607),
(412, 'Kamjong', 1607),
(413, 'Kangpokpi', 1607),
(414, 'Noney', 1607),
(415, 'Pherzawl', 1607),
(416, 'Senapati', 1607),
(417, 'Tamenglong', 1607),
(418, 'Tengnoupal', 1607),
(419, 'Thoubal', 1607),
(420, 'Ukhrul', 1607),
(421, 'East Garo Hills', 1608),
(422, 'East Khasi Hills', 1608),
(423, 'East Jaintia Hills', 1608),
(424, 'North Garo Hills', 1608),
(425, 'Ri Bhoi', 1608),
(426, 'South Garo Hills', 1608),
(427, 'South West Garo Hills', 1608),
(428, 'South West Khasi Hills', 1608),
(429, 'West Jaintia Hills', 1608),
(430, 'West Garo Hills', 1608),
(431, 'West Khasi Hills', 1608),
(432, 'Aizawl', 1609),
(433, 'Champhai', 1609),
(434, 'Hnahthial', 1609),
(435, 'Khawzawl', 1609),
(436, 'Kolasib', 1609),
(437, 'Lawngtlai', 1609),
(438, 'Lunglei', 1609),
(439, 'Mamit', 1609),
(440, 'Saiha', 1609),
(441, 'Saitual', 1609),
(442, 'Serchhip', 1609),
(443, 'Dimapur', 1610),
(444, 'Kiphire', 1610),
(445, 'Kohima', 1610),
(446, 'Longleng', 1610),
(447, 'Mokokchung', 1610),
(448, 'Mon', 1610),
(449, 'Noklak', 1610),
(450, 'Peren', 1610),
(451, 'Phek', 1610),
(452, 'Tuensang', 1610),
(453, 'Wokha', 1610),
(454, 'Zunheboto', 1610),
(455, 'Angul', 1611),
(456, 'Boudh', 1611),
(457, 'Bhadrak', 1611),
(458, 'Balangir', 1611),
(459, 'Bargarh', 1611),
(460, 'Balasore', 1611),
(461, 'Cuttack', 1611),
(462, 'Debagarh', 1611),
(463, 'Dhenkanal', 1611),
(464, 'Ganjam', 1611),
(465, 'Gajapati', 1611),
(466, 'Jharsuguda', 1611),
(467, 'Jajpur', 1611),
(468, 'Jagatsinghpur', 1611),
(469, 'Khordha', 1611),
(470, 'Kendujhar', 1611),
(471, 'Kalahandi', 1611),
(472, 'Kandhamal', 1611),
(473, 'Koraput', 1611),
(474, 'Kendrapara', 1611),
(475, 'Malkangiri', 1611),
(476, 'Mayurbhanj', 1611),
(477, 'Nabarangpur', 1611),
(478, 'Nuapada', 1611),
(479, 'Nayagarh', 1611),
(480, 'Puri', 1611),
(481, 'Rayagada', 1611),
(482, 'Sambalpur', 1611),
(483, 'Subarnapur', 1611),
(484, 'Sundargarh', 1611),
(485, 'Karaikal', 1612),
(486, 'Mahe', 1612),
(487, 'Pondicherry', 1612),
(488, 'Yanam', 1612),
(489, 'Amritsar', 1613),
(490, 'Barnala', 1613),
(491, 'Bathinda', 1613),
(492, 'Firozpur', 1613),
(493, 'Faridkot', 1613),
(494, 'Fatehgarh Sahib', 1613),
(495, 'Fazilka', 1613),
(496, 'Gurdaspur', 1613),
(497, 'Hoshiarpur', 1613),
(498, 'Jalandhar', 1613),
(499, 'Kapurthala', 1613),
(500, 'Ludhiana', 1613),
(501, 'Mansa', 1613),
(502, 'Moga', 1613),
(503, 'Sri Muktsar Sahib', 1613),
(504, 'Pathankot', 1613),
(505, 'Patiala', 1613),
(506, 'Rupnagar', 1613),
(507, 'Sahibzada Ajit Singh Nagar', 1613),
(508, 'Sangrur', 1613),
(509, 'Shahid Bhagat Singh Nagar', 1613),
(510, 'Tarn Taran', 1613),
(511, 'Ajmer', 1614),
(512, 'Alwar', 1614),
(513, 'Bikaner', 1614),
(514, 'Barmer', 1614),
(515, 'Banswara', 1614),
(516, 'Bharatpur', 1614),
(517, 'Baran', 1614),
(518, 'Bundi', 1614),
(519, 'Bhilwara', 1614),
(520, 'Churu', 1614),
(521, 'Chittorgarh', 1614),
(522, 'Dausa', 1614),
(523, 'Dholpur', 1614),
(524, 'Dungarpur', 1614),
(525, 'Ganganagar', 1614),
(526, 'Hanumangarh', 1614),
(527, 'Jhunjhunu', 1614),
(528, 'Jalore', 1614),
(529, 'Jodhpur', 1614),
(530, 'Jaipur', 1614),
(531, 'Jaisalmer', 1614),
(532, 'Jhalawar', 1614),
(533, 'Karauli', 1614),
(534, 'Kota', 1614),
(535, 'Nagaur', 1614),
(536, 'Pali', 1614),
(537, 'Pratapgarh', 1614),
(538, 'Rajsamand', 1614),
(539, 'Sikar', 1614),
(540, 'Sawai Madhopur', 1614),
(541, 'Sirohi', 1614),
(542, 'Tonk', 1614),
(543, 'Udaipur', 1614),
(544, 'East Sikkim', 1615),
(545, 'North Sikkim', 1615),
(546, 'South Sikkim', 1615),
(547, 'West Sikkim', 1615),
(548, 'Ariyalur', 1616),
(549, 'Chengalpattu', 1616),
(550, 'Chennai', 1616),
(551, 'Coimbatore', 1616),
(552, 'Cuddalore', 1616),
(553, 'Dharmapuri', 1616),
(554, 'Dindigul', 1616),
(555, 'Erode', 1616),
(556, 'Kallakurichi', 1616),
(557, 'Kanchipuram', 1616),
(558, 'Kanyakumari', 1616),
(559, 'Karur', 1616),
(560, 'Krishnagiri', 1616),
(561, 'Madurai', 1616),
(562, 'Mayiladuthurai', 1616),
(563, 'Nagapattinam', 1616),
(564, 'Nilgiris', 1616),
(565, 'Namakkal', 1616),
(566, 'Perambalur', 1616),
(567, 'Pudukkottai', 1616),
(568, 'Ramanathapuram', 1616),
(569, 'Ranipet', 1616),
(570, 'Salem', 1616),
(571, 'Sivaganga', 1616),
(572, 'Tenkasi', 1616),
(573, 'Tirupur', 1616),
(574, 'Tiruchirappalli', 1616),
(575, 'Theni', 1616),
(576, 'Tirunelveli', 1616),
(577, 'Thanjavur', 1616),
(578, 'Thoothukudi', 1616),
(579, 'Tirupattur', 1616),
(580, 'Tiruvallur', 1616),
(581, 'Tiruvarur', 1616),
(582, 'Tiruvannamalai', 1616),
(583, 'Vellore', 1616),
(584, 'Viluppuram', 1616),
(585, 'Virudhunagar', 1616),
(586, 'Adilabad', 1617),
(587, 'Komaram Bheem', 1617),
(588, 'Bhadradri Kothagudem', 1617),
(589, 'Hyderabad', 1617),
(590, 'Jagtial', 1617),
(591, 'Jangaon', 1617),
(592, 'Jayashankar Bhupalpally', 1617),
(593, 'Jogulamba Gadwal', 1617),
(594, 'Kamareddy', 1617),
(595, 'Karimnagar', 1617),
(596, 'Khammam', 1617),
(597, 'Mahabubabad', 1617),
(598, 'Mahbubnagar', 1617),
(599, 'Mancherial', 1617),
(600, 'Medak', 1617),
(601, 'Medchal-Malkajgiri', 1617),
(602, 'Mulugu', 1617),
(603, 'Nalgonda', 1617),
(604, 'Narayanpet', 1617),
(605, 'Nagarkurnool', 1617),
(606, 'Nirmal', 1617),
(607, 'Nizamabad', 1617),
(608, 'Peddapalli', 1617),
(609, 'Rajanna Sircilla', 1617),
(610, 'Ranga Reddy', 1617),
(611, 'Sangareddy', 1617),
(612, 'Siddipet', 1617),
(613, 'Suryapet', 1617),
(614, 'Vikarabad', 1617),
(615, 'Wanaparthy', 1617),
(616, 'Warangal Urban', 1617),
(617, 'Warangal Rural', 1617),
(618, 'Yadadri Bhuvanagiri', 1617),
(619, 'Dhalai', 1618),
(620, 'Gomati', 1618),
(621, 'Khowai', 1618),
(622, 'North Tripura', 1618),
(623, 'Sepahijala', 1618),
(624, 'South Tripura', 1618),
(625, 'Unokoti', 1618),
(626, 'West Tripura', 1618),
(627, 'Agra', 1619),
(628, 'Aligarh', 1619),
(629, 'Allahabad', 1619),
(630, 'Ambedkar Nagar', 1619),
(631, 'Amethi', 1619),
(632, 'Amroha', 1619),
(633, 'Auraiya', 1619),
(634, 'Azamgarh', 1619),
(635, 'Bagpat', 1619),
(636, 'Bahraich', 1619),
(637, 'Ballia', 1619),
(638, 'Balrampur', 1619),
(639, 'Banda', 1619),
(640, 'Barabanki', 1619),
(641, 'Bareilly', 1619),
(642, 'Basti', 1619),
(643, 'Bhadohi', 1619),
(644, 'Bijnor', 1619),
(645, 'Budaun', 1619),
(646, 'Bulandshahr', 1619),
(647, 'Chandauli', 1619),
(648, 'Chitrakoot', 1619),
(649, 'Deoria', 1619),
(650, 'Etah', 1619),
(651, 'Etawah', 1619),
(652, 'Faizabad', 1619),
(653, 'Farrukhabad', 1619),
(654, 'Fatehpur', 1619),
(655, 'Firozabad', 1619),
(656, 'Gautam Buddh Nagar', 1619),
(657, 'Ghaziabad', 1619),
(658, 'Ghazipur', 1619),
(659, 'Gonda', 1619),
(660, 'Gorakhpur', 1619),
(661, 'Hamirpur', 1619),
(662, 'Hapur', 1619),
(663, 'Hardoi', 1619),
(664, 'Hathras', 1619),
(665, 'Jalaun', 1619),
(666, 'Jaunpur', 1619),
(667, 'Jhansi', 1619),
(668, 'Kannauj', 1619),
(669, 'Kanpur', 1619),
(670, 'Kasganj', 1619),
(671, 'Kaushambi', 1619),
(672, 'Kushinagar', 1619),
(673, 'Lakhimpur Kheri', 1619),
(674, 'Lalitpur', 1619),
(675, 'Lucknow', 1619),
(676, 'Maharajganj', 1619),
(677, 'Mahoba', 1619),
(678, 'Mainpuri', 1619),
(679, 'Mathura', 1619),
(680, 'Mau', 1619),
(681, 'Meerut', 1619),
(682, 'Mirzapur', 1619),
(683, 'Moradabad', 1619),
(684, 'Muzaffarnagar', 1619),
(685, 'Pilibhit', 1619),
(686, 'Pratapgarh', 1619),
(687, 'Raebareli', 1619),
(688, 'Rampur', 1619),
(689, 'Saharanpur', 1619),
(690, 'Sambhal', 1619),
(691, 'Sant Kabir Nagar', 1619),
(692, 'Shahjahanpur', 1619),
(693, 'Shamli', 1619),
(694, 'Shravasti', 1619),
(695, 'Siddharthnagar', 1619),
(696, 'Sitapur', 1619),
(697, 'Sonbhadra', 1619),
(698, 'Sultanpur', 1619),
(699, 'Unnao', 1619),
(700, 'Varanasi', 1619),
(701, 'Almora', 1620),
(702, 'Bageshwar', 1620),
(703, 'Chamoli', 1620),
(704, 'Champawat', 1620),
(705, 'Dehradun', 1620),
(706, 'Haridwar', 1620),
(707, 'Nainital', 1620),
(708, 'Pauri Garhwal', 1620),
(709, 'Pithoragarh', 1620),
(710, 'Rudraprayag', 1620),
(711, 'Tehri Garhwal', 1620),
(712, 'Udham Singh Nagar', 1620),
(713, 'Uttarkashi', 1620),
(714, 'Alipurduar', 1621),
(715, 'Bankura', 1621),
(716, 'Paschim Bardhaman', 1621),
(717, 'Purba Bardhaman', 1621),
(718, 'Birbhum', 1621),
(719, 'Cooch Behar', 1621),
(720, 'Dakshin Dinajpur', 1621),
(721, 'Darjeeling', 1621),
(722, 'Hooghly', 1621),
(723, 'Howrah', 1621),
(724, 'Jalpaiguri', 1621),
(725, 'Jhargram', 1621),
(726, 'Kalimpong', 1621),
(727, 'Kolkata', 1621),
(728, 'Maldah', 1621),
(729, 'Murshidabad', 1621),
(730, 'Nadia', 1621),
(731, 'North 24 Parganas', 1621),
(732, 'Paschim Medinipur', 1621),
(733, 'Purba Medinipur', 1621),
(734, 'Purulia', 1621),
(735, 'South 24 Parganas', 1621),
(736, 'Uttar Dinajpur', 1621);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=737;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
