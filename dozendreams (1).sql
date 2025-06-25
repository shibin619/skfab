-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 25, 2025 at 04:04 AM
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
-- Database: `dozendreams`
--

-- --------------------------------------------------------

--
-- Table structure for table `callback_requests`
--

CREATE TABLE `callback_requests` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `callback_date` date NOT NULL,
  `callback_time` time NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contests`
--

CREATE TABLE `contests` (
  `id` int(11) NOT NULL,
  `match_id` int(11) DEFAULT NULL,
  `prize_pool` float DEFAULT 0,
  `prize_type` varchar(10) DEFAULT 'Lakhs',
  `entry_fee` float DEFAULT 0,
  `first_prize` float DEFAULT 0,
  `total_spots` int(11) DEFAULT 0,
  `spots_left` int(11) DEFAULT 0,
  `winning_percent` int(11) DEFAULT 0,
  `max_teams` int(11) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `contest_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contests`
--

INSERT INTO `contests` (`id`, `match_id`, `prize_pool`, `prize_type`, `entry_fee`, `first_prize`, `total_spots`, `spots_left`, `winning_percent`, `max_teams`, `created_at`, `updated_at`, `contest_type_id`) VALUES
(1, 1, 200000, 'Lakhs', 20, 20000, 100000, 9999, 75, 20, NULL, NULL, 1),
(2, 1, 1000000, 'Lakhs', 100, 500000, 200000, 200000, 75, 20, NULL, NULL, 1),
(3, 2, 0.5, 'Lakhs', 5, 10000, 10000, 10000, 75, 20, NULL, NULL, 1),
(4, 2, 1.8, 'Lakhs', 10, 20000, 20000, 20000, 75, 20, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contest_types`
--

CREATE TABLE `contest_types` (
  `id` int(11) NOT NULL,
  `short_name` varchar(100) NOT NULL,
  `short_code` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status_id` int(11) DEFAULT 6,
  `entry_limit` int(11) DEFAULT 1,
  `total_teams_limit` int(11) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contest_types`
--

INSERT INTO `contest_types` (`id`, `short_name`, `short_code`, `description`, `status_id`, `entry_limit`, `total_teams_limit`, `created_at`, `updated_at`) VALUES
(1, 'Mega Contest', 'MEGA', 'High-reward contest with large entry pool', 6, 20, 1, '2025-06-22 21:31:10', '2025-06-22 21:34:16'),
(2, 'Winner Takes All', 'WTA', 'Only one person wins the prize', 6, 1, 3, '2025-06-22 21:31:10', '2025-06-22 21:34:16'),
(3, 'Head to Head', 'H2H', 'Two participants, winner takes all', 6, 1, 2, '2025-06-22 21:31:10', '2025-06-22 21:34:16'),
(4, 'Top 50%', 'TOP50', 'Half of participants win something', 6, 10, 1, '2025-06-22 21:31:10', '2025-06-22 21:34:16');

-- --------------------------------------------------------

--
-- Table structure for table `crons`
--

CREATE TABLE `crons` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status_id` int(11) DEFAULT 1 COMMENT 'From masters (Pending, Completed, Failed)',
  `last_run_by` int(11) DEFAULT NULL,
  `last_run_role` varchar(50) DEFAULT NULL,
  `last_run_time` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crons`
--

INSERT INTO `crons` (`id`, `name`, `description`, `status_id`, `last_run_by`, `last_run_role`, `last_run_time`, `created_at`) VALUES
(1, 'Sync Match Stats', 'Cron to sync latest player match stats.', 2, 4, 'commando', '2025-06-16 15:49:54', '2025-06-16 15:35:55'),
(2, 'Generate Suggested Teams', 'AI-based team generation.', 2, 4, 'commando', '2025-06-16 15:51:19', '2025-06-16 15:35:55');

-- --------------------------------------------------------

--
-- Table structure for table `dream_tree`
--

CREATE TABLE `dream_tree` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL COMMENT 'Refers to upline user in tree (can be null for root)',
  `position` enum('left','right') DEFAULT NULL COMMENT 'Position under parent',
  `level` int(11) DEFAULT 0 COMMENT 'Tree level, 0 = root',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dream_tree`
--

INSERT INTO `dream_tree` (`id`, `user_id`, `parent_id`, `position`, `level`, `created_at`) VALUES
(1, 559, 16, 'left', 1, '2025-06-24 18:06:59');

-- --------------------------------------------------------

--
-- Table structure for table `masters`
--

CREATE TABLE `masters` (
  `id` int(11) NOT NULL,
  `master_type_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `short_code` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `masters`
--

INSERT INTO `masters` (`id`, `master_type_id`, `name`, `short_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Super Admin', 'superadmin', 1, '2025-06-06 16:48:23', '2025-06-06 16:48:23'),
(2, 1, 'Admin', 'admin', 1, '2025-06-06 16:48:23', '2025-06-06 16:48:23'),
(3, 1, 'Commando', 'commando', 1, '2025-06-06 16:48:23', '2025-06-06 16:48:23'),
(4, 1, 'User', 'user', 1, '2025-06-06 16:48:23', '2025-06-06 16:48:23'),
(5, 1, 'Developer', 'developer', 1, '2025-06-06 16:48:23', '2025-06-06 16:48:23'),
(6, 2, 'Active', '1', 1, '2025-06-06 16:48:23', '2025-06-06 16:48:23'),
(7, 2, 'Inactive', '0', 1, '2025-06-06 16:48:23', '2025-06-06 16:48:23'),
(8, 1, 'Tester', 'tester', 1, '2025-06-06 16:48:23', '2025-06-11 21:35:29'),
(9, 3, 'Upcoming', 'upcoming', 1, '2025-06-16 12:08:49', '2025-06-16 12:10:37'),
(10, 3, 'Completed', 'completed', 1, '2025-06-16 12:08:49', '2025-06-16 12:10:42'),
(11, 3, 'Cancelled', 'cancelled', 1, '2025-06-16 12:08:49', '2025-06-16 12:10:48'),
(12, 4, 'Lineup Not Announced', '0', 1, '2025-06-16 15:15:21', '2025-06-16 15:19:29'),
(13, 4, 'Lineup Out', '1', 1, '2025-06-16 15:15:21', '2025-06-16 15:15:21'),
(17, 5, 'Pending', 'P', 1, '2025-06-16 15:33:10', '2025-06-16 15:33:10'),
(18, 5, 'Completed', 'C', 1, '2025-06-16 15:33:10', '2025-06-16 15:33:10'),
(19, 5, 'Failed', 'F', 1, '2025-06-16 15:33:10', '2025-06-16 15:33:10'),
(20, 6, 'Batsman', 'BAT', 1, '2025-06-16 17:31:56', '2025-06-16 17:31:56'),
(21, 6, 'Bowler', 'BOW', 1, '2025-06-16 17:31:56', '2025-06-16 17:31:56'),
(22, 6, 'All-Rounder', 'AR', 1, '2025-06-16 17:31:56', '2025-06-16 17:31:56'),
(23, 6, 'Wicket-Keeper', 'WK', 1, '2025-06-16 17:31:56', '2025-06-16 17:31:56'),
(24, 7, 'Upcoming', 'upcoming', 1, '2025-06-19 12:03:23', '2025-06-19 12:03:23'),
(25, 7, 'Ongoing', 'ongoing', 1, '2025-06-19 12:03:23', '2025-06-19 12:03:23'),
(26, 7, 'Completed', 'completed', 1, '2025-06-19 12:03:23', '2025-06-19 12:03:23'),
(27, 7, 'Abandoned', 'abandoned', 1, '2025-06-19 12:03:23', '2025-06-19 12:03:23'),
(28, 7, 'Cancelled', 'cancelled', 1, '2025-06-19 12:03:23', '2025-06-19 12:03:23'),
(29, 8, 'Batting Friendly', 'bat', 1, '2025-06-20 12:51:12', '2025-06-20 12:51:12'),
(30, 8, 'Bowling Friendly', 'bowl', 1, '2025-06-20 12:51:12', '2025-06-20 12:51:12'),
(31, 8, 'Balanced', 'bal', 1, '2025-06-20 12:51:12', '2025-06-20 12:51:12'),
(32, 9, 'Right-Hand Bat', 'RHB', 1, '2025-06-20 15:34:14', '2025-06-20 15:34:14'),
(33, 9, 'Left-Hand Bat', 'LHB', 1, '2025-06-20 15:34:14', '2025-06-20 15:34:14'),
(34, 9, 'Right-Arm Bowl', 'RAB', 1, '2025-06-20 15:34:14', '2025-06-20 15:34:14'),
(35, 9, 'Left-Arm Bowl', 'LAB', 1, '2025-06-20 15:34:14', '2025-06-20 15:34:14'),
(36, 9, 'Both-Hand Bat', 'BOTHB', 1, '2025-06-20 15:34:14', '2025-06-20 15:34:14'),
(37, 9, 'Both-Arm Bowl', 'BOTHR', 1, '2025-06-20 15:34:14', '2025-06-20 15:34:14');

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
(2, 'site_owner', 'SK Fabrication Construction And Promoters', 1, '2024-12-06 22:27:58'),
(3, 'site_num', '+91 9597159138', 1, '2024-12-06 22:30:43'),
(4, 'site_email', 'skconstruction@gmail.com', 1, '2024-12-06 22:30:43'),
(5, 'site_address', '5/70 sikkampatty, seeraikadi <br>Periyakadampatty (PO), Omalur Taluk, Tharamangalam , salem, TN', 1, '2024-12-06 22:32:20'),
(6, 'web_url', 'https://skfabrication.co.in/', 1, '2024-12-12 22:32:20'),
(7, 'contact_page_content', 'Contact', 1, '2024-12-16 22:32:20'),
(8, 'logo_url', 'https://skfabrication.co.in/assets/img/logo.jpg', 1, '2025-01-15 22:32:20'),
(9, 'fav_url', 'https://skfabrication.co.in/assets/img/logo.png', 1, '2025-01-15 22:32:20');

-- --------------------------------------------------------

--
-- Table structure for table `master_types`
--

CREATE TABLE `master_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'Defines the type: Role, Status, PitchType, etc.',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_types`
--

INSERT INTO `master_types` (`id`, `name`, `created_at`) VALUES
(1, 'Role', '2025-06-06 16:48:23'),
(2, 'Status', '2025-06-06 16:48:23'),
(3, 'Shedule Status', '2025-06-06 16:48:23'),
(4, 'Lineup Status', '2025-06-16 15:15:06'),
(5, 'Cron Status', '2025-06-16 15:15:06'),
(6, 'Player Role', '2025-06-06 16:48:23'),
(7, 'Match Status', '2025-06-06 16:48:23'),
(8, 'Pitch Type', '2025-06-20 12:51:12'),
(9, 'Hand Type', '2025-06-20 15:34:14');

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `lineup_status_id` int(11) DEFAULT NULL COMMENT 'FK from masters (Lineup Status)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `schedule_id`, `lineup_status_id`) VALUES
(1, 1, 12),
(2, 2, 12);

-- --------------------------------------------------------

--
-- Table structure for table `match_types`
--

CREATE TABLE `match_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `status_id` int(11) DEFAULT 6 COMMENT 'References masters(id) where master_type_id = 2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `match_types`
--

INSERT INTO `match_types` (`id`, `type_name`, `status_id`) VALUES
(1, 'T10', 6),
(2, 'T20', 6),
(3, 'ODI', 6),
(4, 'Test', 6);

-- --------------------------------------------------------

--
-- Table structure for table `pair_income_history`
--

CREATE TABLE `pair_income_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pair_count` int(11) NOT NULL,
  `income_amount` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pair_income_slabs`
--

CREATE TABLE `pair_income_slabs` (
  `id` int(11) NOT NULL,
  `pair_count` int(11) DEFAULT NULL,
  `income_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pair_income_slabs`
--

INSERT INTO `pair_income_slabs` (`id`, `pair_count`, `income_amount`) VALUES
(1, 5, '50.00'),
(2, 10, '100.00'),
(3, 25, '1000.00');

-- --------------------------------------------------------

--
-- Table structure for table `pitch_types`
--

CREATE TABLE `pitch_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pitch_types`
--

INSERT INTO `pitch_types` (`id`, `type_name`, `description`) VALUES
(1, 'Spin-friendly', NULL),
(2, 'Pace-friendly', NULL),
(3, 'Good for Batting', NULL),
(4, 'Difficult for Batting', NULL),
(5, 'High Bounce', NULL),
(6, 'Low Bounce', NULL),
(7, 'Good for Chasing', NULL),
(8, 'Favors Swing', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pitch_type_ability_map`
--

CREATE TABLE `pitch_type_ability_map` (
  `id` int(11) NOT NULL,
  `pitch_type_id` int(11) NOT NULL,
  `special_ability` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pitch_type_ability_map`
--

INSERT INTO `pitch_type_ability_map` (`id`, `pitch_type_id`, `special_ability`) VALUES
(1, 1, 'Spinner'),
(2, 1, 'All Rounder'),
(3, 2, 'Fast Bowler'),
(4, 2, 'All Rounder'),
(5, 3, 'Opener Batsman'),
(6, 3, 'Top Order Batsman'),
(7, 3, 'Middle Order Batsman'),
(8, 3, 'Wicketkeeper Batsman'),
(9, 4, 'Fast Bowler'),
(10, 4, 'Spinner'),
(11, 5, 'Fast Bowler'),
(12, 6, 'Spinner'),
(13, 7, 'Middle Order Batsman'),
(14, 7, 'All Rounder'),
(15, 8, 'Fast Bowler');

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `special_ability_id` int(11) DEFAULT NULL,
  `credit_points` float DEFAULT 0,
  `fitness_status` enum('Fit','Injured','Doubtful') DEFAULT 'Fit',
  `status_id` int(11) DEFAULT 6,
  `cap_number` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `batting_hand_id` int(11) DEFAULT NULL,
  `bowling_hand_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`id`, `name`, `image`, `special_ability_id`, `credit_points`, `fitness_status`, `status_id`, `cap_number`, `created_at`, `updated_at`, `batting_hand_id`, `bowling_hand_id`) VALUES
(1, 'MS Dhoni', NULL, 0, 9.5, 'Fit', 6, 7, '2025-06-20 11:15:32', '2025-06-20 15:36:59', 32, 34),
(2, 'Ruturaj Gaikwad', NULL, 0, 9, 'Fit', 6, 31, '2025-06-20 11:15:32', '2025-06-20 15:37:15', 32, 34),
(3, 'Moeen Ali', NULL, 0, 9, 'Fit', 6, 18, '2025-06-20 11:15:32', '2025-06-20 15:39:04', 33, 34),
(4, 'Ravindra Jadeja', NULL, 0, 9.5, 'Fit', 6, 8, '2025-06-20 11:15:32', '2025-06-20 15:37:15', 32, 34),
(5, 'Deepak Chahar', NULL, 0, 8, 'Fit', 6, 90, '2025-06-20 11:15:32', '2025-06-20 15:37:15', 32, 34),
(6, 'Rohit Sharma', NULL, 0, 9.5, 'Fit', 6, 45, '2025-06-20 11:15:32', '2025-06-20 15:37:15', 32, 34),
(7, 'Ishan Kishan', NULL, 0, 9, 'Fit', 6, 32, '2025-06-20 11:15:32', '2025-06-20 15:37:15', 32, 34),
(8, 'Suryakumar Yadav', NULL, 0, 9.5, 'Fit', 6, 63, '2025-06-20 11:15:32', '2025-06-20 15:37:15', 32, 34),
(9, 'Hardik Pandya', NULL, 0, 9.5, 'Fit', 6, 33, '2025-06-20 11:15:32', '2025-06-20 15:37:15', 32, 34),
(10, 'Jasprit Bumrah', NULL, 0, 9, 'Fit', 6, 93, '2025-06-20 11:15:32', '2025-06-20 15:37:15', 32, 34),
(11, 'Shivam Dube', NULL, NULL, 8.5, 'Fit', 6, 70, '2025-06-20 11:18:28', '2025-06-20 15:37:15', 32, 34),
(12, 'Devon Conway', NULL, NULL, 9, 'Fit', 6, 88, '2025-06-20 11:18:28', '2025-06-20 15:37:15', 32, 34),
(13, 'Tushar Deshpande', NULL, NULL, 8, 'Fit', 6, 52, '2025-06-20 11:18:28', '2025-06-20 15:37:15', 32, 34),
(14, 'Maheesh Theekshana', NULL, NULL, 8.5, 'Fit', 6, 55, '2025-06-20 11:18:28', '2025-06-20 15:37:15', 32, 34),
(15, 'Matheesha Pathirana', NULL, NULL, 8.5, 'Fit', 6, 63, '2025-06-20 11:18:28', '2025-06-20 15:37:15', 32, 34),
(16, 'Ben Stokes', NULL, NULL, 9, 'Fit', 6, 55, '2025-06-20 11:18:28', '2025-06-20 15:37:15', 32, 34),
(17, 'Ajinkya Rahane', NULL, NULL, 8.5, 'Fit', 6, 27, '2025-06-20 11:18:28', '2025-06-20 15:37:15', 32, 34),
(18, 'Ambati Rayudu', NULL, NULL, 8, 'Fit', 6, 5, '2025-06-20 11:18:28', '2025-06-20 15:37:15', 32, 34),
(19, 'Mitchell Santner', NULL, NULL, 8, 'Fit', 6, 74, '2025-06-20 11:18:28', '2025-06-20 15:37:15', 32, 34),
(20, 'Dwaine Pretorius', NULL, NULL, 8, 'Fit', 6, 77, '2025-06-20 11:18:28', '2025-06-20 15:37:15', 32, 34),
(21, 'Tilak Varma', NULL, 0, 8.5, 'Fit', 6, 29, '2025-06-20 11:22:43', '2025-06-20 15:37:15', 32, 34),
(22, 'Tim David', NULL, 0, 8.5, 'Fit', 6, 85, '2025-06-20 11:22:43', '2025-06-20 15:37:15', 32, 34),
(23, 'Nehal Wadhera', NULL, 0, 7.5, 'Fit', 6, 24, '2025-06-20 11:22:43', '2025-06-20 15:37:15', 32, 34),
(24, 'Dewald Brevis', NULL, 0, 7.5, 'Fit', 6, 17, '2025-06-20 11:22:43', '2025-06-20 15:37:15', 32, 34),
(25, 'Gerald Coetzee', NULL, 0, 8, 'Fit', 6, 52, '2025-06-20 11:22:43', '2025-06-20 15:37:15', 32, 34),
(26, 'Shreyas Gopal', NULL, 0, 7, 'Fit', 6, 13, '2025-06-20 11:22:43', '2025-06-20 15:37:15', 32, 34),
(27, 'Romario Shepherd', NULL, 0, 7.5, 'Fit', 6, 77, '2025-06-20 11:22:43', '2025-06-20 15:37:15', 32, 34),
(28, 'Mohammad Nabi', NULL, 0, 8, 'Fit', 6, 7, '2025-06-20 11:22:43', '2025-06-20 15:37:15', 32, 34),
(29, 'Kumar Kartikeya', NULL, 0, 7, 'Fit', 6, 36, '2025-06-20 11:22:43', '2025-06-20 15:37:15', 32, 34),
(30, 'Piyush Chawla', NULL, 0, 7.5, 'Fit', 6, 11, '2025-06-20 11:22:43', '2025-06-20 15:37:15', 32, 34),
(31, 'Abhishek Sharma', NULL, NULL, 8.5, 'Fit', 6, 23, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(32, 'Travis Head', NULL, NULL, 9, 'Fit', 6, 62, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(33, 'Aiden Markram', NULL, NULL, 8.5, 'Fit', 6, 46, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(34, 'Heinrich Klaasen', NULL, NULL, 9, 'Fit', 6, 24, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(35, 'Rahul Tripathi', NULL, NULL, 8, 'Fit', 6, 12, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(36, 'Washington Sundar', NULL, NULL, 8, 'Fit', 6, 5, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(37, 'Marco Jansen', NULL, NULL, 8.5, 'Fit', 6, 70, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(38, 'Pat Cummins', NULL, NULL, 9, 'Fit', 6, 30, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(39, 'T Natarajan', NULL, NULL, 8, 'Fit', 6, 44, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(40, 'Bhuvneshwar Kumar', NULL, NULL, 8.5, 'Fit', 6, 15, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(41, 'Mayank Agarwal', NULL, NULL, 8, 'Fit', 6, 16, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(42, 'Nitish Reddy', NULL, NULL, 8.5, 'Fit', 6, 58, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(43, 'Anmolpreet Singh', NULL, NULL, 7.5, 'Fit', 6, 61, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(44, 'Sanvir Singh', NULL, NULL, 7.5, 'Fit', 6, 91, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34),
(45, 'Fazalhaq Farooqi', NULL, NULL, 7.5, 'Fit', 6, 19, '2025-06-21 13:09:40', '2025-06-21 13:09:40', 32, 34);

-- --------------------------------------------------------

--
-- Table structure for table `player_statistics`
--

CREATE TABLE `player_statistics` (
  `id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `matches` int(11) DEFAULT 0,
  `runs` int(11) DEFAULT 0,
  `balls_faced` int(11) DEFAULT 0,
  `strike_rate` float DEFAULT NULL,
  `fours` int(11) DEFAULT 0,
  `sixes` int(11) DEFAULT 0,
  `wickets` int(11) DEFAULT 0,
  `overs_bowled` decimal(5,2) DEFAULT 0.00,
  `economy_rate` float DEFAULT NULL,
  `runs_given` int(11) DEFAULT 0,
  `catches` int(11) DEFAULT 0,
  `stumpings` int(11) DEFAULT 0,
  `status_id` int(11) DEFAULT 6,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player_statistics`
--

INSERT INTO `player_statistics` (`id`, `player_id`, `matches`, `runs`, `balls_faced`, `strike_rate`, `fours`, `sixes`, `wickets`, `overs_bowled`, `economy_rate`, `runs_given`, `catches`, `stumpings`, `status_id`, `created_at`, `updated_at`) VALUES
(11, 1, 250, 5000, 4000, 125, 400, 150, 0, '0.00', NULL, 0, 100, 50, 6, '2025-06-20 11:15:55', '2025-06-20 11:15:55'),
(12, 2, 100, 3200, 2600, 123, 350, 90, 0, '0.00', NULL, 0, 40, 0, 6, '2025-06-20 11:15:55', '2025-06-20 11:15:55'),
(13, 3, 150, 3000, 2400, 120, 280, 100, 50, '180.20', 7.8, 1400, 30, 0, 6, '2025-06-20 11:15:55', '2025-06-20 11:15:55'),
(14, 4, 220, 2800, 2200, 127, 200, 80, 85, '200.40', 6.2, 1300, 75, 0, 6, '2025-06-20 11:15:55', '2025-06-20 11:15:55'),
(15, 5, 95, 300, 250, 110, 40, 5, 85, '180.00', 7.1, 1100, 15, 0, 6, '2025-06-20 11:15:55', '2025-06-20 11:15:55'),
(16, 6, 270, 5800, 4700, 123.4, 480, 190, 2, '20.00', 8.2, 150, 90, 0, 6, '2025-06-20 11:15:55', '2025-06-20 11:15:55'),
(17, 7, 90, 2300, 1900, 121, 210, 80, 0, '0.00', NULL, 0, 25, 25, 6, '2025-06-20 11:15:55', '2025-06-20 11:15:55'),
(18, 8, 120, 4000, 3400, 117.6, 410, 130, 5, '12.20', 9.4, 112, 35, 0, 6, '2025-06-20 11:15:55', '2025-06-20 11:15:55'),
(19, 9, 160, 2700, 2000, 135, 190, 110, 70, '150.30', 8.1, 1200, 50, 0, 6, '2025-06-20 11:15:55', '2025-06-20 11:15:55'),
(20, 10, 100, 100, 80, 125, 15, 3, 120, '230.00', 6.7, 1500, 20, 0, 6, '2025-06-20 11:15:55', '2025-06-20 11:15:55'),
(21, 11, 90, 2100, 1700, 123.5, 180, 60, 0, '0.00', NULL, 0, 35, 0, 6, '2025-06-20 11:19:59', '2025-06-20 11:19:59'),
(22, 12, 50, 900, 750, 120, 85, 25, 0, '0.00', NULL, 0, 18, 0, 6, '2025-06-20 11:19:59', '2025-06-20 11:19:59'),
(23, 13, 60, 400, 350, 114.2, 40, 10, 35, '110.00', 7.1, 780, 12, 0, 6, '2025-06-20 11:19:59', '2025-06-20 11:19:59'),
(24, 14, 35, 150, 120, 125, 18, 4, 30, '90.00', 6.7, 600, 10, 0, 6, '2025-06-20 11:19:59', '2025-06-20 11:19:59'),
(25, 15, 80, 1000, 820, 121.9, 90, 30, 10, '50.00', 7, 350, 20, 0, 6, '2025-06-20 11:19:59', '2025-06-20 11:19:59'),
(26, 16, 45, 800, 670, 119.4, 75, 18, 5, '25.00', 7.2, 180, 15, 0, 6, '2025-06-20 11:19:59', '2025-06-20 11:19:59'),
(27, 17, 55, 1400, 1100, 127.2, 130, 45, 0, '0.00', NULL, 0, 22, 0, 6, '2025-06-20 11:19:59', '2025-06-20 11:19:59'),
(28, 18, 38, 130, 95, 136.8, 14, 3, 42, '150.00', 8.3, 1120, 6, 0, 6, '2025-06-20 11:19:59', '2025-06-20 11:19:59'),
(29, 19, 33, 90, 72, 125, 10, 2, 28, '110.00', 6.4, 700, 4, 0, 6, '2025-06-20 11:19:59', '2025-06-20 11:19:59'),
(30, 20, 30, 70, 60, 116.7, 8, 1, 25, '100.00', 7.5, 750, 3, 0, 6, '2025-06-20 11:19:59', '2025-06-20 11:19:59'),
(31, 21, 180, 2700, 2300, 117.39, 250, 60, 2, '10.00', 6.5, 65, 70, 0, 6, '2025-06-20 11:24:24', '2025-06-20 11:24:24'),
(32, 22, 145, 3200, 2600, 123.07, 300, 90, 0, '0.00', NULL, 0, 40, 0, 6, '2025-06-20 11:24:24', '2025-06-20 11:24:24'),
(33, 23, 75, 800, 640, 125, 80, 22, 20, '55.00', 7.2, 396, 18, 0, 6, '2025-06-20 11:24:24', '2025-06-20 11:24:24'),
(34, 24, 60, 550, 500, 110, 50, 10, 35, '72.00', 6.75, 486, 10, 0, 6, '2025-06-20 11:24:24', '2025-06-20 11:24:24'),
(35, 25, 30, 150, 140, 107.14, 10, 4, 25, '44.00', 8.5, 374, 6, 0, 6, '2025-06-20 11:24:24', '2025-06-20 11:24:24'),
(36, 26, 95, 1700, 1500, 113.33, 150, 40, 12, '18.00', 6.8, 122, 25, 0, 6, '2025-06-20 11:24:24', '2025-06-20 11:24:24'),
(37, 27, 100, 1000, 900, 111.11, 90, 20, 50, '85.00', 7, 595, 23, 0, 6, '2025-06-20 11:24:24', '2025-06-20 11:24:24'),
(38, 28, 40, 300, 250, 120, 20, 12, 18, '36.00', 6.9, 248, 9, 0, 6, '2025-06-20 11:24:24', '2025-06-20 11:24:24'),
(39, 29, 20, 140, 110, 127.27, 12, 6, 10, '24.00', 7.4, 178, 4, 0, 6, '2025-06-20 11:24:24', '2025-06-20 11:24:24'),
(40, 30, 12, 70, 60, 116.67, 8, 3, 7, '18.00', 7.1, 128, 2, 0, 6, '2025-06-20 11:24:24', '2025-06-20 11:24:24');

-- --------------------------------------------------------

--
-- Table structure for table `player_team`
--

CREATE TABLE `player_team` (
  `id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player_team`
--

INSERT INTO `player_team` (`id`, `player_id`, `team_id`, `role_id`, `start_date`, `end_date`) VALUES
(1, 1, 1, 4, '2025-01-01', '2026-12-31'),
(2, 2, 1, 1, '2025-01-01', '2026-12-31'),
(3, 3, 1, 3, '2025-01-01', '2026-12-31'),
(4, 4, 1, 3, '2025-01-01', '2026-12-31'),
(5, 5, 1, 2, '2025-01-01', '2026-12-31'),
(6, 11, 1, 1, '2025-01-01', '2026-12-31'),
(7, 12, 1, 4, '2025-01-01', '2026-12-31'),
(8, 13, 1, 2, '2025-01-01', '2026-12-31'),
(9, 14, 1, 2, '2025-01-01', '2026-12-31'),
(10, 15, 1, 2, '2025-01-01', '2026-12-31'),
(11, 16, 1, 3, '2025-01-01', '2026-12-31'),
(12, 17, 1, 1, '2025-01-01', '2026-12-31'),
(13, 18, 1, 1, '2025-01-01', '2026-12-31'),
(14, 19, 1, 2, '2025-01-01', '2026-12-31'),
(15, 20, 1, 2, '2025-01-01', '2026-12-31'),
(16, 6, 2, 3, '2025-01-01', '2026-12-31'),
(17, 7, 2, 4, '2025-01-01', '2026-12-31'),
(18, 8, 2, 1, '2025-01-01', '2026-12-31'),
(19, 9, 2, 3, '2025-01-01', '2026-12-31'),
(20, 10, 2, 3, '2025-01-01', '2026-12-31'),
(21, 21, 2, 1, '2025-01-01', '2026-12-31'),
(22, 22, 2, 3, '2025-01-01', '2026-12-31'),
(23, 23, 2, 1, '2025-01-01', '2026-12-31'),
(24, 24, 2, 1, '2025-01-01', '2026-12-31'),
(25, 25, 2, 3, '2025-01-01', '2026-12-31'),
(26, 26, 2, 2, '2025-01-01', '2026-12-31'),
(27, 27, 2, 3, '2025-01-01', '2026-12-31'),
(28, 28, 2, 3, '2025-01-01', '2026-12-31'),
(29, 29, 2, 2, '2025-01-01', '2026-12-31'),
(30, 30, 2, 2, '2025-01-01', '2026-12-31'),
(31, 31, 4, 3, '2025-01-01', '2026-12-31'),
(32, 32, 4, 1, '2025-01-01', '2026-12-31'),
(33, 33, 4, 1, '2025-01-01', '2026-12-31'),
(34, 34, 4, 4, '2025-01-01', '2026-12-31'),
(35, 35, 4, 1, '2025-01-01', '2026-12-31'),
(36, 36, 4, 3, '2025-01-01', '2026-12-31'),
(37, 37, 4, 2, '2025-01-01', '2026-12-31'),
(38, 38, 4, 2, '2025-01-01', '2026-12-31'),
(39, 39, 4, 2, '2025-01-01', '2026-12-31'),
(40, 40, 4, 2, '2025-01-01', '2026-12-31'),
(41, 41, 4, 1, '2025-01-01', '2026-12-31'),
(42, 42, 4, 3, '2025-01-01', '2026-12-31'),
(43, 43, 4, 1, '2025-01-01', '2026-12-31'),
(44, 44, 4, 3, '2025-01-01', '2026-12-31'),
(45, 45, 4, 2, '2025-01-01', '2026-12-31');

-- --------------------------------------------------------

--
-- Stand-in structure for view `player_tournament`
-- (See below for the actual view)
--
CREATE TABLE `player_tournament` (
);

-- --------------------------------------------------------

--
-- Table structure for table `points_system`
--

CREATE TABLE `points_system` (
  `id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `role_id` tinyint(4) NOT NULL,
  `points` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `type_id` int(11) DEFAULT NULL COMMENT 'References match_types(id)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `points_system`
--

INSERT INTO `points_system` (`id`, `action`, `role_id`, `points`, `created_at`, `type_id`) VALUES
(1, 'Wicket (Excluding Run Out)', 2, 30, '2025-06-20 05:09:39', 2),
(2, 'Run', 1, 1, '2025-06-20 05:09:39', 2),
(3, 'Dot Ball', 2, 1, '2025-06-20 05:09:39', 2),
(4, 'Captain Points', 7, 2, '2025-06-20 05:09:39', 2),
(5, 'Vice-Captain Points', 8, 1.5, '2025-06-20 05:09:39', 2),
(6, 'In Announced Lineups', 9, 4, '2025-06-20 05:09:39', 2),
(7, 'Playing Substitute', 5, 4, '2025-06-20 05:09:39', 2),
(8, '3 Wicket Bonus', 2, 4, '2025-06-20 05:09:39', 2),
(9, '4 Wicket Bonus', 2, 8, '2025-06-20 05:09:39', 2),
(10, '5 Wicket Bonus', 2, 12, '2025-06-20 05:09:39', 2),
(11, 'Maiden Over', 2, 12, '2025-06-20 05:09:39', 2),
(12, 'Economy Rate: Below 5 runs per over', 2, 6, '2025-06-20 05:09:39', 2),
(13, 'Economy Rate: 5 - 5.99 runs per over', 2, 4, '2025-06-20 05:09:39', 2),
(14, 'Economy Rate: 6 - 7 runs per over', 2, 2, '2025-06-20 05:09:39', 2),
(15, 'Economy Rate: 10 - 11 runs per over', 2, -2, '2025-06-20 05:09:39', 2),
(16, 'Economy Rate: 11.01 - 12 runs per over', 2, -4, '2025-06-20 05:09:39', 2),
(17, 'Economy Rate: Above 12 runs per over', 2, -6, '2025-06-20 05:09:39', 2),
(18, 'Catch', 6, 8, '2025-06-20 05:18:40', 2),
(19, '3 Catch Bonus', 6, 4, '2025-06-20 05:21:23', 2),
(20, 'Stumping', 4, 12, '2025-06-20 05:21:53', 2),
(21, 'Run Out(Direct Hit)', 6, 12, '2025-06-20 05:22:43', 2),
(22, 'Run Out(Non Direct Hit)', 6, 6, '2025-06-20 05:23:05', 2),
(23, 'Strike rate : Below 50 runs per 100 balls', 1, -6, '2025-06-20 05:25:36', 2),
(24, 'Strike rate : Between 50-50.99 runs per 100 balls', 1, -4, '2025-06-20 05:26:45', 2),
(25, 'Strike rate : Between 60-70 runs per 100 balls', 1, -2, '2025-06-20 05:27:16', 2),
(26, 'Strike rate : Between 130-150 runs per 100 balls', 1, 2, '2025-06-20 05:28:13', 2),
(27, 'Strike rate : Between 150.01-170 runs per 100 balls', 1, 4, '2025-06-20 05:29:02', 2),
(28, 'Strike rate : Above 170 runs per 100 balls', 1, 6, '2025-06-20 05:30:11', 2),
(29, 'Dismissal For a Duck', 1, -2, '2025-06-20 05:31:40', 2),
(30, '100 Run Bonus', 1, 16, '2025-06-20 05:32:10', 2),
(31, '75 Run Bonus', 1, 12, '2025-06-20 05:32:42', 2),
(32, '50 Run Bonus', 1, 8, '2025-06-20 05:33:00', 2),
(33, 'Six Bonus', 1, 6, '2025-06-20 05:33:33', 2),
(34, 'Boundary Bonus', 1, 4, '2025-06-20 05:33:57', 2),
(35, 'Wicket Bonus(LBW/Bowled)', 2, 8, '2025-06-20 05:38:06', 2);

-- --------------------------------------------------------

--
-- Table structure for table `prize_breakup_levels`
--

CREATE TABLE `prize_breakup_levels` (
  `id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `percentage` float NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status_id` int(11) DEFAULT 6,
  `calculation_type` enum('entry','pool') NOT NULL DEFAULT 'entry',
  `winner_percent_range` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prize_breakup_levels`
--

INSERT INTO `prize_breakup_levels` (`id`, `level`, `description`, `percentage`, `created_at`, `status_id`, `calculation_type`, `winner_percent_range`) VALUES
(1, 1, 'Rank 1 - 20% of prize pool', 20, '2025-06-23 10:37:15', 6, 'pool', '0-0.01'),
(2, 2, 'Rank 2 - 2.5% of prize pool', 2.5, '2025-06-23 10:37:15', 6, 'pool', '0.01-0.02'),
(3, 3, 'Rank 3 - 2% of prize pool', 2, '2025-06-23 10:37:15', 6, 'pool', '0.02-0.03'),
(4, 4, 'Rank 4 - 1.5% of prize pool', 1.5, '2025-06-23 10:37:15', 6, 'pool', '0.03-0.04'),
(5, 5, 'Rank 5 - 1.3% of prize pool', 1.3, '2025-06-23 10:37:15', 6, 'pool', '0.04-0.05'),
(6, 6, 'Rank 6 to Top 2% - 550% of entry', 550, '2025-06-23 10:37:15', 6, 'entry', '0.05-2'),
(7, 7, 'Ranks 3% to 7% - 300% of entry', 300, '2025-06-23 10:37:15', 6, 'entry', '2-6'),
(8, 8, 'Ranks 10% to 20% - 150% of entry', 150, '2025-06-23 10:37:15', 6, 'entry', '10-20'),
(9, 9, 'Ranks 20% to 30% - 125% of entry', 125, '2025-06-23 10:37:15', 6, 'entry', '20-30'),
(10, 10, 'Ranks 30% to 55% - 100% of entry', 100, '2025-06-23 10:37:15', 6, 'entry', '30-55'),
(11, 11, '20% of 55% to 75% range - 40% of entry', 40, '2025-06-23 10:37:15', 6, 'entry', '55-75');

-- --------------------------------------------------------

--
-- Table structure for table `referral_bonus_earnings`
--

CREATE TABLE `referral_bonus_earnings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User who receives the bonus',
  `slab_id` int(11) NOT NULL COMMENT 'References referral_bonus_slabs.id',
  `level` int(11) DEFAULT 1 COMMENT 'Referral level (1 for direct, 2 for second-level, etc.)',
  `bonus_amount` decimal(10,2) NOT NULL COMMENT 'Bonus given at the time of record',
  `created_by` int(11) DEFAULT NULL COMMENT 'Admin or system user who created the record',
  `updated_by` int(11) DEFAULT NULL COMMENT 'Admin or system user who last updated the record',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_bonus_slabs`
--

CREATE TABLE `referral_bonus_slabs` (
  `id` int(11) NOT NULL,
  `direct_referrals` int(11) DEFAULT NULL,
  `bonus_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referral_bonus_slabs`
--

INSERT INTO `referral_bonus_slabs` (`id`, `direct_referrals`, `bonus_amount`) VALUES
(1, 5, '10.00'),
(2, 10, '25.00'),
(3, 25, '50.00'),
(4, 50, '200.00');

-- --------------------------------------------------------

--
-- Table structure for table `referral_earnings`
--

CREATE TABLE `referral_earnings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `referred_user_id` int(11) NOT NULL,
  `level` tinyint(4) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_income_levels`
--

CREATE TABLE `referral_income_levels` (
  `id` int(11) NOT NULL,
  `referral_case` enum('direct','one_upline','two_uplines') NOT NULL COMMENT 'Refers to how many uplines exist',
  `level` int(11) NOT NULL COMMENT 'Level 0 = self, 1 = direct referrer, 2 = first upline, 3 = second upline',
  `receiver_role` varchar(50) NOT NULL COMMENT 'Who receives the income at this level (self/referrer/upline)',
  `income_amount` decimal(10,2) NOT NULL COMMENT 'Fixed income in INR',
  `description` varchar(255) DEFAULT NULL COMMENT 'Explanation of the logic',
  `status_id` int(11) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referral_income_levels`
--

INSERT INTO `referral_income_levels` (`id`, `referral_case`, `level`, `receiver_role`, `income_amount`, `description`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 'direct', 0, 'self', '100.00', 'User has no referrer, gets ₹100', 6, '2025-06-18 11:02:32', '2025-06-18 11:19:04'),
(2, 'one_upline', 1, 'referrer', '75.00', 'Direct referrer gets ₹75', 6, '2025-06-18 11:02:32', '2025-06-18 11:19:04'),
(3, 'one_upline', 2, 'first upline', '25.00', '1st upline gets ₹25', 6, '2025-06-18 11:02:32', '2025-06-18 11:19:04'),
(4, 'two_uplines', 1, 'referrer', '60.00', 'Direct referrer gets ₹60', 6, '2025-06-18 11:02:32', '2025-06-18 11:19:04'),
(5, 'two_uplines', 2, 'first upline', '25.00', '1st upline gets ₹25', 6, '2025-06-18 11:02:32', '2025-06-18 11:19:04'),
(6, 'two_uplines', 3, 'second upline', '10.00', '2nd upline gets ₹10', 6, '2025-06-18 11:02:32', '2025-06-18 11:19:04');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `short_name` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `short_name`) VALUES
(1, 'Batsman', 'BAT'),
(2, 'Bowler', 'BOWL'),
(3, 'All-Rounder', 'AR'),
(4, 'Wicket-Keeper', 'WK'),
(5, 'Impact Player', 'IMP'),
(6, 'Fielder', 'FR'),
(7, 'Captain', 'Cap'),
(8, 'Vice Captain', 'VC'),
(9, 'Player', 'PL');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `match_date` datetime DEFAULT NULL,
  `tournament_id` int(11) DEFAULT NULL,
  `team_a_id` int(11) DEFAULT NULL,
  `team_b_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `match_date`, `tournament_id`, `team_a_id`, `team_b_id`, `venue_id`, `status_id`) VALUES
(1, '2025-06-27 19:00:00', 1, 1, 2, 22, 9),
(2, '2025-06-28 00:00:00', 1, 2, 4, 1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `short_name` varchar(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `short_name`, `status`) VALUES
(1, 'Chennai Super Kings', 'CSK', 1),
(2, 'Mumbai Indians', 'MI', 1),
(3, 'Gujrat lions', 'GT', 1),
(4, 'Sunrises Hyderabad', 'SRH', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE `tournaments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `short_name` varchar(20) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`id`, `name`, `short_name`, `start_date`, `end_date`, `status`) VALUES
(1, 'Indian Premier League', 'IPL', '2025-06-20', '1025-10-20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL COMMENT 'References masters.id where master_type = Role',
  `status_id` int(11) DEFAULT NULL COMMENT 'References masters.id where master_type = Status',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `referred_by` int(11) DEFAULT NULL,
  `details_id` int(11) DEFAULT NULL COMMENT 'References user_details.id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `status_id`, `created_at`, `updated_at`, `referred_by`, `details_id`) VALUES
(1, 'Shibin Robin', 'superadmin@example.com', '$2y$10$N4e1pMueG09VzV.uC2x0Qelp4KbETQJeGDB714cAho4lmWjg/zGJO', 1, 6, '2025-06-06 16:48:23', '2025-06-12 10:29:58', NULL, NULL),
(2, 'Admin One', 'admin@example.com', '$2y$10$h2u0edU26x0nVZ0Y0ijZ5umVSGpT1aNl4TEbPKmlpxB39U2JrU8T2', 2, 6, '2025-06-06 16:48:23', '2025-06-11 21:39:44', 1, NULL),
(3, 'Admin Two', 'admin2@example.com', '$2y$10$k7Z23yTzvZ8kn2aVJkq5XOTRZqHuQw81L9AVdNq1y8V0uy82rOn/y', 2, 6, '2025-06-12 09:14:50', '2025-06-19 18:24:49', 1, NULL),
(4, 'Commando A', 'commando@example.com', '$2y$10$N4e1pMueG09VzV.uC2x0Qelp4KbETQJeGDB714cAho4lmWjg/zGJO', 3, 6, '2025-06-06 16:48:23', '2025-06-19 18:24:53', 1, NULL),
(5, 'Commando B', 'commando2@example.com', '$2y$10$A7ZxKusD/fJKPxqFRepl9uNjwqVUMz9Wa6Xa/ghV3fUn3g4XYDd16', 3, 6, '2025-06-12 09:14:50', '2025-06-19 18:25:05', 2, NULL),
(6, 'Commando C', 'commando3@example.com', '$2y$10$D8XawTLZ6sQXkPtWapbnEOEftvApN0leph8sWk7It6/WzHjSYas9a', 3, 6, '2025-06-12 09:14:50', '2025-06-19 18:25:12', 2, NULL),
(7, 'Dev One', 'dev1@example.com', '$2y$10$k3Oq8vTQAzDl6Re6zxmpjO0kpG4Un1yR76FAsfdnC0RkeLdyBAa72', 5, 6, '2025-06-12 09:14:50', '2025-06-19 18:25:31', 3, NULL),
(8, 'Dev Two', 'dev2@example.com', '$2y$10$k3Oq8vTQAzDl6Re6zxmpjO0kpG4Un1yR76FAsfdnC0RkeLdyBAa72', 5, 6, '2025-06-12 09:14:50', '2025-06-19 18:25:35', 3, NULL),
(9, 'Dev Three', 'dev3@example.com', '$2y$10$k3Oq8vTQAzDl6Re6zxmpjO0kpG4Un1yR76FAsfdnC0RkeLdyBAa72', 5, 6, '2025-06-12 09:14:50', '2025-06-19 18:25:40', 3, NULL),
(10, 'Dev Team', 'developer@example.com', '$2y$10$h2u0edU26x0nVZ0Y0ijZ5umVSGpT1aNl4TEbPKmlpxB39U2JrU8T2\n', 5, 6, '2025-06-06 16:48:23', '2025-06-19 18:25:47', 2, NULL),
(11, 'Tester One', 'tester1@example.com', '$2y$10$z3Wc9XRYGpCoh2VP6WhbUOl8kK.DMCv6c1EkSAdZYDJdZQzD3ynrO', 8, 6, '2025-06-12 09:14:50', '2025-06-12 09:15:58', 5, NULL),
(12, 'Tester Two', 'tester2@example.com', '$2y$10$z3Wc9XRYGpCoh2VP6WhbUOl8kK.DMCv6c1EkSAdZYDJdZQzD3ynrO', 8, 6, '2025-06-12 09:14:50', '2025-06-12 09:16:31', 5, NULL),
(13, 'Tester Three', 'tester3@example.com', '$2y$10$z3Wc9XRYGpCoh2VP6WhbUOl8kK.DMCv6c1EkSAdZYDJdZQzD3ynrO', 8, 6, '2025-06-12 09:14:50', '2025-06-12 09:16:31', 5, NULL),
(14, 'Tester Four', 'tester4@example.com', '$2y$10$z3Wc9XRYGpCoh2VP6WhbUOl8kK.DMCv6c1EkSAdZYDJdZQzD3ynrO', 8, 6, '2025-06-12 09:14:50', '2025-06-12 09:16:31', 5, NULL),
(15, 'Tester Five', 'tester5@example.com', '$2y$10$z3Wc9XRYGpCoh2VP6WhbUOl8kK.DMCv6c1EkSAdZYDJdZQzD3ynrO', 8, 6, '2025-06-12 09:14:50', '2025-06-12 09:16:31', 5, NULL),
(16, 'Regular User', 'user@example.com', '$2y$10$N4e1pMueG09VzV.uC2x0Qelp4KbETQJeGDB714cAho4lmWjg/zGJO', 4, 6, '2025-06-06 16:48:23', '2025-06-19 18:16:40', 2, NULL),
(17, 'test user', 'testuser@gmail.com', '$2y$10$N4e1pMueG09VzV.uC2x0Qelp4KbETQJeGDB714cAho4lmWjg/zGJO', 4, 6, '2025-06-07 15:13:40', '2025-06-24 07:30:53', 16, NULL),
(559, 'user1', 'uset@test.com', '$2y$10$LaDzzKQPbtLdV.vqZISNj.3muPPNeUjKtd1z7Gw0/vylYnEl30UYO', 4, 6, '2025-06-24 18:06:59', '2025-06-24 18:06:59', 16, NULL),
(560, 'direct user', 'user2@test.com', '$2y$10$RW7JHQP6GRNmWW6IT6L4qumxwYFvN/2b.i2Jn9RhHj/UplpArNmGO', 4, 6, '2025-06-25 06:15:56', '2025-06-25 06:15:56', 14, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_contest_teams`
--

CREATE TABLE `user_contest_teams` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `contest_id` int(11) NOT NULL,
  `joined_teams` varchar(255) DEFAULT NULL COMMENT 'Comma-separated team IDs like "1,2,4"',
  `teams_count` int(11) DEFAULT 1 COMMENT 'How many teams the user joined with',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_contest_teams`
--

INSERT INTO `user_contest_teams` (`id`, `user_id`, `match_id`, `contest_id`, `joined_teams`, `teams_count`, `created_at`, `updated_at`) VALUES
(1, 16, 1, 1, '2,3,1', 3, '2025-06-23 22:32:49', '2025-06-23 22:45:52');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `unique_id` varchar(50) NOT NULL,
  `fullname` varchar(150) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `aadhaar_number` varchar(20) DEFAULT NULL,
  `pan_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `referral_code` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `user_id`, `unique_id`, `fullname`, `first_name`, `last_name`, `mobile_number`, `aadhaar_number`, `pan_number`, `address`, `profile_image`, `referral_code`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 16, 'DZNDR735291AB', 'Shibin Robin', 'Shibin', 'Robin', '9876543210', '123456789012', 'ABCDE1234F', 'TC-02-02, Scarlet Homes, Trivandrum', 'profile.png', 'ABCD1234REF', '2025-06-24 07:16:38', '2025-06-24 07:16:38', '2025-06-24 07:16:38'),
(2, 17, 'DZNDR823719AB', 'Arjun Mehra', 'Arjun', 'Mehra', '9876000001', '123456789017', 'XYZPM1234K', 'Block 17, Smart Towers, Technopark Phase 2, Trivandrum', 'profile.png', 'REF17ARJUNMEH', '2025-06-24 07:30:22', '2025-06-24 07:30:22', '2025-06-24 07:30:22'),
(3, 559, 'Z94DND4R050', 'user1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4C582AAFC544', NULL, '2025-06-24 18:06:59', '2025-06-24 18:06:59'),
(4, 560, '4D04RNZD661', 'direct user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '11434BB1BC71', NULL, '2025-06-25 06:15:56', '2025-06-25 06:15:56');

-- --------------------------------------------------------

--
-- Table structure for table `user_generated_teams`
--

CREATE TABLE `user_generated_teams` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `players` text NOT NULL,
  `captain_id` int(11) NOT NULL,
  `vice_captain_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT 1,
  `gen_team_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gen_team_data`)),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_generated_teams`
--

INSERT INTO `user_generated_teams` (`id`, `user_id`, `match_id`, `players`, `captain_id`, `vice_captain_id`, `status_id`, `gen_team_data`, `created_at`) VALUES
(1, 1, 101, '[\"12\",\"23\",\"34\",\"45\",\"56\",\"67\",\"78\",\"89\",\"90\",\"10\",\"11\"]', 45, 67, 1, '{\r\n    \"team1\": {\"captain\": 11, \"vice_captain\": 13, \"team_players\": [1,2,3,9,11,12,13,15,17,18,19]},\r\n    \"team2\": {\"captain\": 13, \"vice_captain\": 11, \"team_players\": [1,3,6,8,11,13,14,16,17,18,21]},\r\n    \"team3\": {\"captain\": 16, \"vice_captain\": 2, \"team_players\": [1,2,8,9,11,15,16,19,21,22,23]},\r\n    \"team4\": {\"captain\": 8, \"vice_captain\": 7, \"team_players\": [1,3,6,7,8,9,11,13,15,17,18]}\r\n  }', '2025-06-11 13:19:18'),
(2, 16, 1, '[\"3\",\"4\",\"5\",\"6\",\"7\",\"11\",\"2\",\"8\",\"18\",\"10\",\"14\"]', 2, 8, 1, '{\"team1\":{\"captain\":\"2\",\"vice_captain\":\"8\",\"team_players\":[\"3\",\"4\",\"5\",\"6\",\"7\",\"11\",\"2\",\"8\",\"18\",\"10\",\"14\"]},\"team2\":{\"captain\":\"2\",\"vice_captain\":\"8\",\"team_players\":[\"3\",\"4\",\"5\",\"6\",\"7\",\"11\",\"2\",\"8\",\"17\",\"18\",\"10\"]},\"team3\":{\"captain\":\"2\",\"vice_captain\":\"8\",\"team_players\":[\"3\",\"4\",\"5\",\"6\",\"7\",\"11\",\"2\",\"8\",\"18\",\"10\",\"19\"]},\"team4\":{\"captain\":\"2\",\"vice_captain\":\"8\",\"team_players\":[\"3\",\"4\",\"5\",\"6\",\"7\",\"11\",\"2\",\"8\",\"18\",\"9\",\"10\"]},\"team5\":{\"captain\":\"2\",\"vice_captain\":\"8\",\"team_players\":[\"3\",\"4\",\"5\",\"6\",\"7\",\"11\",\"2\",\"8\",\"18\",\"10\",\"22\"]}}', '2025-06-24 16:27:33'),
(3, 16, 1, '[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]', 16, 10, 1, '{\"team1\":{\"captain\":\"16\",\"vice_captain\":\"10\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team2\":{\"captain\":\"12\",\"vice_captain\":\"17\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team3\":{\"captain\":\"16\",\"vice_captain\":\"2\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team4\":{\"captain\":\"5\",\"vice_captain\":\"17\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team5\":{\"captain\":\"5\",\"vice_captain\":\"18\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team6\":{\"captain\":\"5\",\"vice_captain\":\"16\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team7\":{\"captain\":\"5\",\"vice_captain\":\"12\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team8\":{\"captain\":\"5\",\"vice_captain\":\"24\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team9\":{\"captain\":\"5\",\"vice_captain\":\"20\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team10\":{\"captain\":\"5\",\"vice_captain\":\"25\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team11\":{\"captain\":\"5\",\"vice_captain\":\"9\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team12\":{\"captain\":\"5\",\"vice_captain\":\"10\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team13\":{\"captain\":\"5\",\"vice_captain\":\"2\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team14\":{\"captain\":\"12\",\"vice_captain\":\"18\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team15\":{\"captain\":\"16\",\"vice_captain\":\"25\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team16\":{\"captain\":\"12\",\"vice_captain\":\"16\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team17\":{\"captain\":\"12\",\"vice_captain\":\"24\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team18\":{\"captain\":\"12\",\"vice_captain\":\"20\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team19\":{\"captain\":\"12\",\"vice_captain\":\"25\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team20\":{\"captain\":\"12\",\"vice_captain\":\"9\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team21\":{\"captain\":\"12\",\"vice_captain\":\"10\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team22\":{\"captain\":\"12\",\"vice_captain\":\"2\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team23\":{\"captain\":\"24\",\"vice_captain\":\"17\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team24\":{\"captain\":\"24\",\"vice_captain\":\"18\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team25\":{\"captain\":\"24\",\"vice_captain\":\"16\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team26\":{\"captain\":\"24\",\"vice_captain\":\"5\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team27\":{\"captain\":\"16\",\"vice_captain\":\"9\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team28\":{\"captain\":\"16\",\"vice_captain\":\"20\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team29\":{\"captain\":\"24\",\"vice_captain\":\"20\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team30\":{\"captain\":\"18\",\"vice_captain\":\"16\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team31\":{\"captain\":\"17\",\"vice_captain\":\"18\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team32\":{\"captain\":\"17\",\"vice_captain\":\"16\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team33\":{\"captain\":\"17\",\"vice_captain\":\"5\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team34\":{\"captain\":\"17\",\"vice_captain\":\"12\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team35\":{\"captain\":\"17\",\"vice_captain\":\"24\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team36\":{\"captain\":\"17\",\"vice_captain\":\"20\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team37\":{\"captain\":\"17\",\"vice_captain\":\"25\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team38\":{\"captain\":\"17\",\"vice_captain\":\"9\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team39\":{\"captain\":\"17\",\"vice_captain\":\"10\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]},\"team40\":{\"captain\":\"17\",\"vice_captain\":\"2\",\"team_players\":[\"17\",\"18\",\"16\",\"5\",\"12\",\"24\",\"20\",\"25\",\"9\",\"10\",\"2\"]}}', '2025-06-25 06:29:36'),
(4, 16, 1, '[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]', 2, 6, 1, '{\"team1\":{\"captain\":\"2\",\"vice_captain\":\"6\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team2\":{\"captain\":\"16\",\"vice_captain\":\"2\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team3\":{\"captain\":\"5\",\"vice_captain\":\"4\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team4\":{\"captain\":\"5\",\"vice_captain\":\"2\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team5\":{\"captain\":\"16\",\"vice_captain\":\"14\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team6\":{\"captain\":\"16\",\"vice_captain\":\"7\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team7\":{\"captain\":\"16\",\"vice_captain\":\"5\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team8\":{\"captain\":\"16\",\"vice_captain\":\"18\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team9\":{\"captain\":\"16\",\"vice_captain\":\"17\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team10\":{\"captain\":\"16\",\"vice_captain\":\"10\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team11\":{\"captain\":\"16\",\"vice_captain\":\"8\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team12\":{\"captain\":\"16\",\"vice_captain\":\"6\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team13\":{\"captain\":\"16\",\"vice_captain\":\"4\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team14\":{\"captain\":\"18\",\"vice_captain\":\"14\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team15\":{\"captain\":\"5\",\"vice_captain\":\"10\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team16\":{\"captain\":\"18\",\"vice_captain\":\"7\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team17\":{\"captain\":\"18\",\"vice_captain\":\"5\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team18\":{\"captain\":\"18\",\"vice_captain\":\"16\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team19\":{\"captain\":\"18\",\"vice_captain\":\"17\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team20\":{\"captain\":\"18\",\"vice_captain\":\"10\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team21\":{\"captain\":\"18\",\"vice_captain\":\"8\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team22\":{\"captain\":\"18\",\"vice_captain\":\"6\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team23\":{\"captain\":\"18\",\"vice_captain\":\"4\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team24\":{\"captain\":\"18\",\"vice_captain\":\"2\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team25\":{\"captain\":\"17\",\"vice_captain\":\"14\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team26\":{\"captain\":\"17\",\"vice_captain\":\"7\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team27\":{\"captain\":\"5\",\"vice_captain\":\"8\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team28\":{\"captain\":\"5\",\"vice_captain\":\"17\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team29\":{\"captain\":\"17\",\"vice_captain\":\"16\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team30\":{\"captain\":\"7\",\"vice_captain\":\"14\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team31\":{\"captain\":\"2\",\"vice_captain\":\"4\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team32\":{\"captain\":\"14\",\"vice_captain\":\"7\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team33\":{\"captain\":\"14\",\"vice_captain\":\"5\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team34\":{\"captain\":\"14\",\"vice_captain\":\"16\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team35\":{\"captain\":\"14\",\"vice_captain\":\"18\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team36\":{\"captain\":\"14\",\"vice_captain\":\"17\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team37\":{\"captain\":\"14\",\"vice_captain\":\"10\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team38\":{\"captain\":\"14\",\"vice_captain\":\"8\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team39\":{\"captain\":\"14\",\"vice_captain\":\"6\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team40\":{\"captain\":\"14\",\"vice_captain\":\"4\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]}}', '2025-06-25 06:31:32'),
(5, 16, 1, '[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]', 2, 6, 1, '{\"team1\":{\"captain\":\"2\",\"vice_captain\":\"6\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team2\":{\"captain\":\"16\",\"vice_captain\":\"2\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team3\":{\"captain\":\"5\",\"vice_captain\":\"4\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team4\":{\"captain\":\"5\",\"vice_captain\":\"2\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team5\":{\"captain\":\"16\",\"vice_captain\":\"14\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team6\":{\"captain\":\"16\",\"vice_captain\":\"7\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team7\":{\"captain\":\"16\",\"vice_captain\":\"5\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team8\":{\"captain\":\"16\",\"vice_captain\":\"18\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team9\":{\"captain\":\"16\",\"vice_captain\":\"17\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team10\":{\"captain\":\"16\",\"vice_captain\":\"10\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team11\":{\"captain\":\"16\",\"vice_captain\":\"8\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team12\":{\"captain\":\"16\",\"vice_captain\":\"6\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team13\":{\"captain\":\"16\",\"vice_captain\":\"4\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team14\":{\"captain\":\"18\",\"vice_captain\":\"14\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team15\":{\"captain\":\"5\",\"vice_captain\":\"10\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team16\":{\"captain\":\"18\",\"vice_captain\":\"7\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team17\":{\"captain\":\"18\",\"vice_captain\":\"5\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team18\":{\"captain\":\"18\",\"vice_captain\":\"16\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team19\":{\"captain\":\"18\",\"vice_captain\":\"17\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team20\":{\"captain\":\"18\",\"vice_captain\":\"10\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team21\":{\"captain\":\"18\",\"vice_captain\":\"8\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team22\":{\"captain\":\"18\",\"vice_captain\":\"6\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team23\":{\"captain\":\"18\",\"vice_captain\":\"4\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team24\":{\"captain\":\"18\",\"vice_captain\":\"2\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team25\":{\"captain\":\"17\",\"vice_captain\":\"14\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team26\":{\"captain\":\"17\",\"vice_captain\":\"7\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team27\":{\"captain\":\"5\",\"vice_captain\":\"8\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team28\":{\"captain\":\"5\",\"vice_captain\":\"17\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team29\":{\"captain\":\"17\",\"vice_captain\":\"16\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team30\":{\"captain\":\"7\",\"vice_captain\":\"14\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team31\":{\"captain\":\"2\",\"vice_captain\":\"4\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team32\":{\"captain\":\"14\",\"vice_captain\":\"7\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team33\":{\"captain\":\"14\",\"vice_captain\":\"5\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team34\":{\"captain\":\"14\",\"vice_captain\":\"16\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team35\":{\"captain\":\"14\",\"vice_captain\":\"18\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team36\":{\"captain\":\"14\",\"vice_captain\":\"17\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team37\":{\"captain\":\"14\",\"vice_captain\":\"10\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team38\":{\"captain\":\"14\",\"vice_captain\":\"8\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team39\":{\"captain\":\"14\",\"vice_captain\":\"6\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]},\"team40\":{\"captain\":\"14\",\"vice_captain\":\"4\",\"team_players\":[\"2\",\"4\",\"6\",\"8\",\"10\",\"17\",\"18\",\"16\",\"5\",\"7\",\"14\"]}}', '2025-06-25 06:34:34'),
(6, 16, 1, '[\"2\",\"4\",\"8\",\"10\",\"30\",\"7\",\"6\",\"17\",\"18\",\"14\",\"19\"]', 7, 6, 1, '{\"team1\":{\"captain\":\"7\",\"vice_captain\":\"6\",\"team_players\":[\"2\",\"4\",\"8\",\"10\",\"30\",\"7\",\"6\",\"17\",\"18\",\"14\",\"19\"]}}', '2025-06-25 06:35:06');

-- --------------------------------------------------------

--
-- Table structure for table `user_points`
--

CREATE TABLE `user_points` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_teams`
--

CREATE TABLE `user_teams` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `team_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Format: {"team1": {"captain": player_id, "vice_captain": player_id, "team_players": [player_ids...]}, ...}' CHECK (json_valid(`team_data`)),
  `status_id` int(11) DEFAULT NULL COMMENT 'References masters.id where master_type_id = 7 (Team Status)',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_teams`
--

INSERT INTO `user_teams` (`id`, `user_id`, `match_id`, `team_data`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 16, 1, '{\"team1\":{\"captain\":11,\"vice_captain\":13,\"team_players\":[1,2,3,9,11,12,13,15,17,18,19]},\"team2\":{\"captain\":13,\"vice_captain\":11,\"team_players\":[1,3,6,8,11,13,14,16,17,18,21]},\"team3\":{\"captain\":16,\"vice_captain\":2,\"team_players\":[1,2,8,9,11,15,16,19,21,22,23]},\"team4\":{\"captain\":8,\"vice_captain\":7,\"team_players\":[1,3,6,7,8,9,11,13,15,17,18]}}', 24, '2025-06-21 09:35:14', '2025-06-21 15:38:16'),
(3, 16, 2, '{\"team1\":{\"captain\":6,\"vice_captain\":10,\"team_players\":[6,7,8,9,10,21,24,25,32,34,38]}}', 24, '2025-06-21 13:10:58', '2025-06-21 13:10:58');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(150) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id`, `name`, `location`, `capacity`) VALUES
(1, 'Eden Gardens', 'Kolkata, India', 68000),
(2, 'Melbourne Cricket Ground', 'Melbourne, Australia', 100024),
(3, 'Lord\'s Cricket Ground', 'London, England', 30000),
(4, 'The Oval', 'London, England', 25000),
(5, 'Old Trafford', 'Manchester, England', 26000),
(6, 'Sydney Cricket Ground', 'Sydney, Australia', 48000),
(7, 'Adelaide Oval', 'Adelaide, Australia', 53583),
(8, 'WACA Ground', 'Perth, Australia', 20000),
(9, 'Newlands', 'Cape Town, South Africa', 25000),
(10, 'SuperSport Park', 'Centurion, South Africa', 22000),
(11, 'Wanderers Stadium', 'Johannesburg, South Africa', 34000),
(12, 'Gaddafi Stadium', 'Lahore, Pakistan', 27000),
(13, 'National Stadium', 'Karachi, Pakistan', 34000),
(14, 'Sher-e-Bangla Stadium', 'Dhaka, Bangladesh', 25000),
(15, 'R. Premadasa Stadium', 'Colombo, Sri Lanka', 35000),
(16, 'Pallekele International Cricket Stadium', 'Kandy, Sri Lanka', 35000),
(17, 'Zahur Ahmed Chowdhury Stadium', 'Chittagong, Bangladesh', 20000),
(18, 'Dubai International Stadium', 'Dubai, UAE', 25000),
(19, 'Sharjah Cricket Stadium', 'Sharjah, UAE', 16000),
(20, 'Sheikh Zayed Stadium', 'Abu Dhabi, UAE', 20000),
(21, 'Sardar Patel Stadium (Narendra Modi Stadium)', 'Ahmedabad, India', 132000),
(22, 'M. A. Chidambaram Stadium', 'Chennai, India', 50000),
(23, 'Wankhede Stadium', 'Mumbai, India', 33000),
(24, 'Rajiv Gandhi International Stadium', 'Hyderabad, India', 55000),
(25, 'Arun Jaitley Stadium', 'Delhi, India', 41000),
(26, 'Holkar Cricket Stadium', 'Indore, India', 30000),
(27, 'Punjab Cricket Association Stadium', 'Mohali, India', 26000),
(28, 'Green Park Stadium', 'Kanpur, India', 32000),
(29, 'Barsapara Stadium', 'Guwahati, India', 40000),
(30, 'Vidarbha Cricket Association Stadium', 'Nagpur, India', 45000),
(31, 'M. Chinnaswamy Stadium', 'Bangalore, India', 40000),
(32, 'Bay Oval', 'Mount Maunganui, New Zealand', 10000),
(33, 'Hagley Oval', 'Christchurch, New Zealand', 18000),
(34, 'Eden Park', 'Auckland, New Zealand', 50000),
(35, 'Bellerive Oval', 'Hobart, Australia', 20000),
(36, 'Queen\'s Park Oval', 'Port of Spain, Trinidad', 20000),
(37, 'Kensington Oval', 'Bridgetown, Barbados', 28000),
(38, 'Providence Stadium', 'Georgetown, Guyana', 20000),
(39, 'Brian Lara Stadium', 'Tarouba, Trinidad & Tobago', 15000),
(40, 'National Cricket Stadium', 'St. George\'s, Grenada', 20000);

-- --------------------------------------------------------

--
-- Table structure for table `venue_pitch_types`
--

CREATE TABLE `venue_pitch_types` (
  `id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `pitch_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `wallet_type_id` int(11) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `wallet_type_id`, `balance`, `updated_at`) VALUES
(1, 16, 1, '90.00', '2025-06-23 22:45:52'),
(2, 16, 2, '0.00', '2025-06-23 12:03:05'),
(3, 16, 3, '60.00', '2025-06-24 18:06:59'),
(4, 16, 4, '0.00', '2025-06-23 12:03:05'),
(5, 16, 5, '0.00', '2025-06-23 12:03:05'),
(6, 16, 6, '0.00', '2025-06-23 12:03:05'),
(8, 17, 1, '0.00', '2025-06-23 12:03:05'),
(9, 17, 2, '0.00', '2025-06-23 12:03:05'),
(10, 17, 3, '0.00', '2025-06-23 12:03:05'),
(11, 17, 4, '0.00', '2025-06-23 12:03:05'),
(12, 17, 5, '0.00', '2025-06-23 12:03:05'),
(13, 17, 6, '0.00', '2025-06-23 12:03:05'),
(14, 559, 1, '0.00', '2025-06-24 18:06:59'),
(15, 559, 2, '0.00', '2025-06-24 18:06:59'),
(16, 559, 3, '0.00', '2025-06-24 18:06:59'),
(17, 559, 4, '0.00', '2025-06-24 18:06:59'),
(18, 559, 5, '0.00', '2025-06-24 18:06:59'),
(19, 560, 1, '0.00', '2025-06-25 06:15:56'),
(20, 560, 2, '0.00', '2025-06-25 06:15:56'),
(21, 560, 3, '100.00', '2025-06-25 06:15:56'),
(22, 560, 4, '0.00', '2025-06-25 06:15:56'),
(23, 560, 5, '0.00', '2025-06-25 06:15:56'),
(24, 560, 6, '0.00', '2025-06-25 06:15:56');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `wallet_type_id` int(11) DEFAULT NULL,
  `type` enum('credit','debit') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `source` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallet_transactions`
--

INSERT INTO `wallet_transactions` (`id`, `user_id`, `wallet_type_id`, `type`, `amount`, `source`, `created_at`) VALUES
(1, 16, 1, 'debit', '40.00', 'Contest #1 - Joined with 2 team(s)', '2025-06-23 22:32:49'),
(2, 16, 1, 'debit', '20.00', 'Contest Join #1', '2025-06-23 22:45:52'),
(3, 560, 3, 'credit', '100.00', 'Self Signup Bonus', '2025-06-25 06:15:56');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_types`
--

CREATE TABLE `wallet_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `status_id` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallet_types`
--

INSERT INTO `wallet_types` (`id`, `type_name`, `description`, `status_id`) VALUES
(1, 'Main', 'Main Wallet for regular usage', 6),
(2, 'Referral', 'Wallet for direct referral earnings', 6),
(3, 'Indirect Referral', 'Earnings from indirect referrals', 6),
(4, 'Pair', 'Pair commission wallet', 6),
(5, 'Dream', 'Dream wallet earnings', 6),
(6, 'Bonus', 'Bonus wallet used for rewards/promotions', 6);

-- --------------------------------------------------------

--
-- Structure for view `player_tournament`
--
DROP TABLE IF EXISTS `player_tournament`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `player_tournament`  AS SELECT DISTINCT `pt`.`player_id` AS `player_id`, `tt`.`tournament_id` AS `tournament_id` FROM (`player_team` `pt` join `team_tournament` `tt` on(`pt`.`team_id` = `tt`.`team_id`))  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `callback_requests`
--
ALTER TABLE `callback_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contests`
--
ALTER TABLE `contests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contest_type_id` (`contest_type_id`);

--
-- Indexes for table `contest_types`
--
ALTER TABLE `contest_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `crons`
--
ALTER TABLE `crons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `last_run_by` (`last_run_by`);

--
-- Indexes for table `dream_tree`
--
ALTER TABLE `dream_tree`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `masters`
--
ALTER TABLE `masters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_type_id` (`master_type_id`);

--
-- Indexes for table `master_content_data`
--
ALTER TABLE `master_content_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_types`
--
ALTER TABLE `master_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `matches_lineup_status_fk` (`lineup_status_id`);

--
-- Indexes for table `match_types`
--
ALTER TABLE `match_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `pair_income_history`
--
ALTER TABLE `pair_income_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pair_income_slabs`
--
ALTER TABLE `pair_income_slabs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pitch_types`
--
ALTER TABLE `pitch_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pitch_type_ability_map`
--
ALTER TABLE `pitch_type_ability_map`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pitch_type_id` (`pitch_type_id`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_players_status` (`status_id`),
  ADD KEY `fk_players_batting_hand` (`batting_hand_id`),
  ADD KEY `fk_players_bowling_hand` (`bowling_hand_id`);

--
-- Indexes for table `player_statistics`
--
ALTER TABLE `player_statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `player_id` (`player_id`),
  ADD KEY `fk_status_id` (`status_id`);

--
-- Indexes for table `player_team`
--
ALTER TABLE `player_team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `player_id` (`player_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `player_team_ibfk_2` (`team_id`);

--
-- Indexes for table `points_system`
--
ALTER TABLE `points_system`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_points_system_match_type` (`type_id`);

--
-- Indexes for table `prize_breakup_levels`
--
ALTER TABLE `prize_breakup_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_bonus_earnings`
--
ALTER TABLE `referral_bonus_earnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `slab_id` (`slab_id`);

--
-- Indexes for table `referral_bonus_slabs`
--
ALTER TABLE `referral_bonus_slabs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_earnings`
--
ALTER TABLE `referral_earnings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_income_levels`
--
ALTER TABLE `referral_income_levels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_referral_status` (`status_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `schedules_ibfk_2` (`team_a_id`),
  ADD KEY `schedules_ibfk_3` (`team_b_id`),
  ADD KEY `fk_schedule_status` (`status_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `details_id` (`details_id`);

--
-- Indexes for table `user_contest_teams`
--
ALTER TABLE `user_contest_teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ucj_user` (`user_id`),
  ADD KEY `fk_ucj_match` (`match_id`),
  ADD KEY `fk_ucj_contest` (`contest_id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_unique` (`user_id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `user_generated_teams`
--
ALTER TABLE `user_generated_teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_points`
--
ALTER TABLE `user_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_teams`
--
ALTER TABLE `user_teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_team_user` (`user_id`),
  ADD KEY `fk_user_team_match` (`match_id`),
  ADD KEY `fk_user_team_status` (`status_id`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `venue_pitch_types`
--
ALTER TABLE `venue_pitch_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `pitch_type_id` (`pitch_type_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_wallet_type` (`user_id`,`wallet_type_id`),
  ADD KEY `fk_wallets_type` (`wallet_type_id`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wallet_type` (`wallet_type_id`);

--
-- Indexes for table `wallet_types`
--
ALTER TABLE `wallet_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wallet_types_status` (`status_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `callback_requests`
--
ALTER TABLE `callback_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contests`
--
ALTER TABLE `contests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contest_types`
--
ALTER TABLE `contest_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `crons`
--
ALTER TABLE `crons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dream_tree`
--
ALTER TABLE `dream_tree`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `masters`
--
ALTER TABLE `masters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `master_content_data`
--
ALTER TABLE `master_content_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `master_types`
--
ALTER TABLE `master_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `match_types`
--
ALTER TABLE `match_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pair_income_history`
--
ALTER TABLE `pair_income_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pair_income_slabs`
--
ALTER TABLE `pair_income_slabs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pitch_type_ability_map`
--
ALTER TABLE `pitch_type_ability_map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `player_statistics`
--
ALTER TABLE `player_statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `player_team`
--
ALTER TABLE `player_team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `points_system`
--
ALTER TABLE `points_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `prize_breakup_levels`
--
ALTER TABLE `prize_breakup_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `referral_bonus_earnings`
--
ALTER TABLE `referral_bonus_earnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_bonus_slabs`
--
ALTER TABLE `referral_bonus_slabs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `referral_earnings`
--
ALTER TABLE `referral_earnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_income_levels`
--
ALTER TABLE `referral_income_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=561;

--
-- AUTO_INCREMENT for table `user_contest_teams`
--
ALTER TABLE `user_contest_teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_generated_teams`
--
ALTER TABLE `user_generated_teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_points`
--
ALTER TABLE `user_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_teams`
--
ALTER TABLE `user_teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `venue_pitch_types`
--
ALTER TABLE `venue_pitch_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wallet_types`
--
ALTER TABLE `wallet_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contests`
--
ALTER TABLE `contests`
  ADD CONSTRAINT `fk_contest_type` FOREIGN KEY (`contest_type_id`) REFERENCES `contest_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `contest_types`
--
ALTER TABLE `contest_types`
  ADD CONSTRAINT `fk_contest_type_status` FOREIGN KEY (`status_id`) REFERENCES `masters` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `crons`
--
ALTER TABLE `crons`
  ADD CONSTRAINT `crons_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `masters` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `crons_ibfk_2` FOREIGN KEY (`last_run_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dream_tree`
--
ALTER TABLE `dream_tree`
  ADD CONSTRAINT `fk_user_tree_parent` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_user_tree_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `masters`
--
ALTER TABLE `masters`
  ADD CONSTRAINT `masters_ibfk_1` FOREIGN KEY (`master_type_id`) REFERENCES `master_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_lineup_status_fk` FOREIGN KEY (`lineup_status_id`) REFERENCES `masters` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `matches_schedule_fk` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `match_types`
--
ALTER TABLE `match_types`
  ADD CONSTRAINT `fk_match_types_status` FOREIGN KEY (`status_id`) REFERENCES `masters` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pitch_type_ability_map`
--
ALTER TABLE `pitch_type_ability_map`
  ADD CONSTRAINT `pitch_type_ability_map_ibfk_1` FOREIGN KEY (`pitch_type_id`) REFERENCES `pitch_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `fk_players_batting_hand` FOREIGN KEY (`batting_hand_id`) REFERENCES `masters` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_players_bowling_hand` FOREIGN KEY (`bowling_hand_id`) REFERENCES `masters` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_players_status` FOREIGN KEY (`status_id`) REFERENCES `masters` (`id`);

--
-- Constraints for table `player_statistics`
--
ALTER TABLE `player_statistics`
  ADD CONSTRAINT `fk_status_id` FOREIGN KEY (`status_id`) REFERENCES `masters` (`id`),
  ADD CONSTRAINT `player_statistics_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `player_team`
--
ALTER TABLE `player_team`
  ADD CONSTRAINT `player_team_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`),
  ADD CONSTRAINT `player_team_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `player_team_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `points_system`
--
ALTER TABLE `points_system`
  ADD CONSTRAINT `fk_points_system_match_type` FOREIGN KEY (`type_id`) REFERENCES `match_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `referral_bonus_earnings`
--
ALTER TABLE `referral_bonus_earnings`
  ADD CONSTRAINT `fk_referral_bonus_earnings_slab` FOREIGN KEY (`slab_id`) REFERENCES `referral_bonus_slabs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_referral_bonus_earnings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referral_income_levels`
--
ALTER TABLE `referral_income_levels`
  ADD CONSTRAINT `fk_referral_status` FOREIGN KEY (`status_id`) REFERENCES `masters` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `fk_schedule_status` FOREIGN KEY (`status_id`) REFERENCES `masters` (`id`),
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`),
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`team_a_id`) REFERENCES `teams` (`id`),
  ADD CONSTRAINT `schedules_ibfk_3` FOREIGN KEY (`team_b_id`) REFERENCES `teams` (`id`),
  ADD CONSTRAINT `schedules_ibfk_4` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `masters` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `masters` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_contest_teams`
--
ALTER TABLE `user_contest_teams`
  ADD CONSTRAINT `fk_ucj_contest` FOREIGN KEY (`contest_id`) REFERENCES `contests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ucj_match` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ucj_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_details`
--
ALTER TABLE `user_details`
  ADD CONSTRAINT `user_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_points`
--
ALTER TABLE `user_points`
  ADD CONSTRAINT `user_points_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `venue_pitch_types`
--
ALTER TABLE `venue_pitch_types`
  ADD CONSTRAINT `venue_pitch_types_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `venue_pitch_types_ibfk_2` FOREIGN KEY (`pitch_type_id`) REFERENCES `pitch_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `fk_wallets_type` FOREIGN KEY (`wallet_type_id`) REFERENCES `wallet_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_wallets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `fk_wallet_type` FOREIGN KEY (`wallet_type_id`) REFERENCES `wallet_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `wallet_types`
--
ALTER TABLE `wallet_types`
  ADD CONSTRAINT `fk_wallet_types_status` FOREIGN KEY (`status_id`) REFERENCES `masters` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
