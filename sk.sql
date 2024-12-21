-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 21, 2024 at 03:05 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `master_content_data`
--

CREATE TABLE `master_content_data` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `content` varchar(250) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_content_data`
--

INSERT INTO `master_content_data` (`id`, `code`, `content`, `status`, `created_date`) VALUES
(1, 'dev_owner', 'SparkleWavesTech', 1, '2024-12-06 22:27:58'),
(2, 'site_owner', 'SK Fabrications', 1, '2024-12-06 22:27:58'),
(3, 'site_num', '+91 9597159138', 1, '2024-12-06 22:30:43'),
(4, 'site_email', 'skconstruction@gmail.com', 1, '2024-12-06 22:30:43'),
(5, 'site_address', '5/70 sikkampatty, seeraikadi <br>Periyakadampatty (PO), Omalur Taluk, Tharamangalam , salem, TN', 1, '2024-12-06 22:32:20'),
(6, 'web_url', 'https://skfabrication.co.in/', 1, '2024-12-12 22:32:20'),
(7, 'contact_page_content', 'Contact', 1, '2024-12-16 22:32:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `master_content_data`
--
ALTER TABLE `master_content_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `master_content_data`
--
ALTER TABLE `master_content_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
