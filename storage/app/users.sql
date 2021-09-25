-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 07, 2020 at 04:38 AM
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
-- Table structure for table `users`
--


--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `active`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Afsal', 1, 'a@a.com', NULL, '$2y$10$SEXJvFsItduxl80SMwKVsO0bev32.tHrRmtUQlRJ7bwmO4ghLLrE6', NULL, '2020-05-22 09:08:51', '2020-05-22 09:08:51'),
(3, 'Ajesh', 1, 'a1@a.com', NULL, '$2y$10$kyHy7VWGALgeOsI1puGZKu2i/nTsg3Oq0MatXtC8vfzV0Cd8ooGPW', NULL, '2020-05-22 09:09:31', '2020-05-22 09:09:31'),
(4, 'Akbar', 1, 'a2@a.com', NULL, '$2y$10$PbT2fUkiwsT1e3HZ9qyKzOOjqvCq8IsChWi4kAfjrd24139M7Skt.', NULL, '2020-05-22 09:09:52', '2020-05-22 09:09:52'),
(5, 'Althaf', 1, 'a3@a.com', NULL, '$2y$10$rAp6Ikj0kJyqc0k32nKM4.dD53800/s2MYIaRzcLAlyNZoRyDHlsy', NULL, '2020-05-22 09:10:12', '2020-05-22 09:10:12'),
(6, 'Anver Shafeeque', 1, 'a4@a.com', NULL, '$2y$10$VNAkHg9PVVHdXO46fphrveYsZ7zx51dnWwCz8ACAbwd.EA4CILt7a', NULL, '2020-05-22 09:10:32', '2020-05-22 09:10:32'),
(7, 'Bajeel', 1, 'bajeel@apexiondental.com', NULL, '$2y$10$F.Qwx.saXtXbXVajYv92tu95x1v7KiBKNHS08Rdc/6uLCZhlmLGRS', NULL, '2020-05-22 09:10:50', '2020-05-28 11:32:09'),
(8, 'Danish', 1, 'a6@a.com', NULL, '$2y$10$SusGnMeOk42J8HwzY4WhlOemRlSrngGQiMqm5qDL5XAJ5J1k0ptZC', NULL, '2020-05-22 09:11:10', '2020-05-22 09:11:10'),
(9, 'Hakeem Colleges', 1, 'a7@a.com', NULL, '$2y$10$HWo/qOxCIEpM2K2lw.Mr1.xNHvoUcc6VgADly6EFpZPj2UyHylLei', NULL, '2020-05-22 09:11:43', '2020-05-22 09:11:43'),
(10, 'Hakeem Dealers', 1, 'a8@a.com', NULL, '$2y$10$xznW177PTI147oJvhpyyLOD/JZAju0pbD28NXuNqTdKy7SJnj1y.2', NULL, '2020-05-22 09:12:10', '2020-05-22 09:12:10'),
(11, 'Hakeem Doctors', 1, 'a9@a.com', NULL, '$2y$10$GHb577lKmvmH384boU1IHu9yOXsEnlpH7pINW9tFm5zW8/2Mg1vFy', NULL, '2020-05-22 09:12:40', '2020-05-22 09:12:40'),
(12, 'Hakeem Student Debtors', 1, 'a10@a.com', NULL, '$2y$10$EHAqZdehOxORfDLcAUVJeuX5ou2RHJkyTdb0UFuTc4K8YPa2gI3VK', NULL, '2020-05-22 09:12:58', '2020-05-22 09:12:58'),
(13, 'Irshad', 1, 'a11@a.com', NULL, '$2y$10$K/9iJYkUTWTOfEP1EX8hp.D3HFDLKtKHmChbyAU8RtJ0QZs77IVcm', NULL, '2020-05-22 09:13:19', '2020-05-22 09:13:19'),
(14, 'Jamshir', 1, 'a12@a.com', NULL, '$2y$10$eEdgOt.gU6a1.TPocjDryeUBc6DuXwt/YN/o3fbY0ooocaVHwUmLa', NULL, '2020-05-22 09:13:36', '2020-05-22 09:13:36'),
(15, 'Nabeel', 1, 'a13@a.com', NULL, '$2y$10$4/OXoA5W3.v/MjYElO/UheOrDIjg1xDRF/UXUVBlPK8k1K1UuvHcq', NULL, '2020-05-22 09:13:57', '2020-05-22 09:13:57'),
(16, 'Navin', 1, 'a14@a.com', NULL, '$2y$10$8NgFqeC783LDiD9Yt17yVOzSCLy4s4iUg009Ge9SCjnXQ6DkFZxU2', NULL, '2020-05-22 09:14:14', '2020-05-22 09:14:14'),
(17, 'Rishab', 1, 'a15@a.com', NULL, '$2y$10$Hiiwx8esSP0vOPJJfNlg8eKv6VpKk4QGhEBbxDDZdAWlqOawgYIA.', NULL, '2020-05-22 09:14:35', '2020-05-22 09:14:35'),
(18, 'Roopesh', 1, 'a16@a.com', NULL, '$2y$10$yj6OV2ZZR5sJLshhPsWcmuck4dD8Z26SIDrDnk/FDxTOSjnmG2xGK', NULL, '2020-05-22 09:14:55', '2020-05-22 09:14:55'),
(19, 'Dr. Shabeel ', 1, 'shabeel@apexiondental.com', NULL, '12345678', NULL, NULL, NULL),
(20, 'Thansih', 1, 'a18@a.com', NULL, '$2y$10$yr6uq/wY7NkX8NNWjc1rI.Hv76kwHuHPIFL3YkwG1.uVZm8H6LoZC', NULL, '2020-05-22 09:15:35', '2020-05-22 09:15:35'),
(21, 'Nabeel V', 1, 'nabeel@apexiondental.com', NULL, '$2y$10$suiS3fls8qPBsORJ9ipgle5Uc/w7F6IzAgC/gKI5LDefHEJ0kM2Te', NULL, NULL, '2020-09-07 08:40:32'),
(22, 'Muhammed Nabeel', 1, 'mnabeel619@gmail.com', NULL, '$2y$10$4EOvbUAP1a6aLPwnPG0H9OZsWS/hc2oSru.CqB5wGZF95YIK0NxO.', '16zj4ymp7JfA8npvCY3L9JhsTdDbfPa3VsmvzvlTvrVqNSvb7qXpAtspnjEB', '2020-05-23 05:34:13', '2020-07-18 05:46:25'),
(23, 'Muneer MP', 1, 'muneermp82@gmail.com', NULL, '$2y$10$ftIgwNTaZHIWufK50e16SuPJFgRIyPUz2WM6kCxxE7sMORq5EbRr6', 'pSzj0bFWGqWgGKK9OtJdLiWZF6tr3QNV0eGqd6sd7KqSYTDv2REqM3gzGYs3', '2020-05-23 05:35:40', '2020-05-23 05:35:40'),
(24, 'Shanid', 1, 'shanidaslamkp0007@gmail.com', NULL, '$2y$10$nIy2MCHNkBX0VNp1fpZ9pO3.n2y.izP3wzb2ik4yg0FsAUb0oOa1S', 'lNYOhbSvLH1vK4uJZgz4bs0IPU3EdBwNlctS7SRMIegWfdHllMfTDsmuZHK0', '2020-05-23 05:36:52', '2020-05-23 05:36:52'),
(25, 'Asker', 1, 'askerpym@gmail.com', NULL, '$2y$10$I4suEfT.ODSe6cLvClWZfeYl2Ca416p0d8XfAmWKGvPYZU9Ss5IPW', '2DSaFL431Ghgr4y6LquplUPXQABR8sRiVlBjuBh2KcWm6s6ZgZpMbOf6Ac4C', '2020-05-23 05:37:12', '2020-05-23 05:37:12'),
(26, 'Fazil VP', 1, 'fazilvp09@gmail.com', NULL, '$2y$10$8g0IRh4n8nbu9hCbx3Dyiut3TlwZjrs3MewWZXSX3dMfM0x6AQOmC', 'mQaiBbYIIBfkcXfl0N5ihi0T3dyOXu9kQvIKYkFDcaf1f9NEzookliyTcSPG', '2020-05-23 08:07:40', '2020-05-23 08:07:40'),
(27, 'Muhammed Salih CP', 1, 'muhammedsalihcp01@gmail.com', NULL, '$2y$10$jhClQqv3tFbkPSgrQWTSYuAU8UD.UPuMnHRG4vpVTiJbjkfmt/SEO', 'Sqk0tvETL42H9Zn1uassdMRLUby4ES8EQpZEjtFoktihta7XzeHnJL6pLmW9', '2020-05-23 08:08:23', '2020-05-23 08:08:23'),
(28, 'Shibul Nas', 1, 'shibulkt17@gmail.com', NULL, '$2y$10$f/q/kyajCN6Iig0KAzv0gudt0hvHqxrqlYI09zW1Nxta/7tA0/6AG', 'Jxe8CZO90Ft3szEX0u4NYHIzggL55cQ04jFzkj0VbFvvcIvopR8q9xRu84yM', '2020-05-23 08:09:07', '2020-05-23 08:09:07'),
(29, 'George E George', 1, 'george@apexiondental.com', NULL, '$2y$10$qgKYucuoz.7AtxQv.mo7FOND7XYLwJ8mG3kLPfAQOF/vYwoUhVjV2', 'TTidWZdgs6XTlks4qN2Kp5Y3Q4qAB5UFGl4m3kCaR0FCsVop9Dn08HJhOgqO', '2020-05-23 11:27:20', '2020-05-23 11:27:20'),
(30, 'Dentodeal', 1, 'admin@dentodeal.com', NULL, '123456787', NULL, NULL, NULL),
(31, 'Ibrahim', 1, 'pnibm7@gmail.com', NULL, '$2y$10$eMKHBXQ2/fmMMQUtO9/eGO2pEY6z5iPkXdc7D56NOwhdL9KPJF8jm', 'AekXN2x3owDEQ4z8vT1CxoqRjIWjVEvk3dJJTqLZ2GjCJk5E9JTpL0MBv0UN', '2020-07-24 06:36:21', '2020-07-24 06:36:21'),
(32, 'Adarsh', 1, 'adarsh@example.com', NULL, '$2y$10$gbOcXP9xRGru58s2hzvUI.KqVxQZmhzPtr474nOGJB8XQo6hkyqVK', NULL, '2020-08-26 11:00:48', '2020-08-26 11:00:48'),
(33, 'Dr. Shabeel', 1, 'shabeeb@apexiondental.com', NULL, '12345678', NULL, NULL, NULL),
(34, 'Hakeem', 1, 'hakeem@apexiondental.com', NULL, '12345678', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
