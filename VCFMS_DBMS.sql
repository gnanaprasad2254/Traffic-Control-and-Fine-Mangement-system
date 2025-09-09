-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2025 at 06:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'Admin',
  `designation` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'passive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `password_hash`, `name`, `email`, `phone`, `role`, `designation`, `address`, `status`, `created_at`, `updated_at`) VALUES
(9, '$2y$10$xlaLlQV5kOcsztj0xSRryOxN0H.FrkiGuYj3ruAESHmqWko5CnsvO', 'Rajesh', 'b230065@nitsikkim.ac.in', '8522840158', 'Admin', 'yyyyyy of ts', 'Plot - no - 167, Seetha Homes, Telangana, India', 'active', '2025-05-31 16:05:52', '2025-05-31 16:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `officer`
--

CREATE TABLE `officer` (
  `officer_id` int(11) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `badge_number` varchar(50) NOT NULL,
  `station_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'passive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officer`
--

INSERT INTO `officer` (`officer_id`, `password_hash`, `name`, `badge_number`, `station_name`, `email`, `phone`, `status`, `created_at`, `updated_at`, `address`) VALUES
(11, '$2y$10$7Eih71akWill6DTk0xAsVOPjvH8bT7/8GkcMbwEAkZoH0Oo7xWE52', 'Rajesh', 'yyyyyy', 'ts', 'b230065@nitsikkim.ac.in', '8522840158', 'active', '2025-05-31 16:09:52', '2025-06-01 16:14:41', 'Plot - no - 167, Seetha Homes, Telangana, India');

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE `rules` (
  `rule_id` int(11) NOT NULL,
  `violation_type` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `rule_number` varchar(50) NOT NULL,
  `fine_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rules`
--

INSERT INTO `rules` (`rule_id`, `violation_type`, `description`, `rule_number`, `fine_amount`, `created_at`) VALUES
(7, 'Over Speeding (Light Vehicles)', 'Exceeding speed limits for LMV (Car/Bike)', 'Section 183 (1)', 2000.00, '2025-05-02 19:08:22'),
(8, 'Over Speeding (Heavy Vehicles)', 'Exceeding speed limits for Heavy Vehicles', 'Section 183 (2)', 4000.00, '2025-05-02 19:08:22'),
(9, 'Drunk Driving', 'Driving under influence of alcohol or drugs', 'Section 185', 10000.00, '2025-05-02 19:08:22'),
(10, 'Driving Without License', 'Operating a vehicle without a valid license', 'Section 181', 5000.00, '2025-05-02 19:08:22'),
(11, 'Driving Without Insurance', 'Driving without third-party insurance', 'Section 196', 2000.00, '2025-05-02 19:08:22'),
(12, 'Seatbelt Violation', 'Not wearing a seatbelt while driving', 'Section 194B', 1000.00, '2025-05-02 19:08:22'),
(13, 'Helmet Violation', 'Riding a two-wheeler without wearing a helmet', 'Section 194D', 1000.00, '2025-05-02 19:08:22'),
(14, 'Red Light Jumping', 'Violating traffic signals at intersections', 'Section 184 (F)', 5000.00, '2025-05-02 19:08:22'),
(15, 'Using Mobile While Driving', 'Using mobile phone while driving', 'Section 184 (C)', 5000.00, '2025-05-02 19:08:22'),
(16, 'Overloading (Passenger Vehicle)', 'Carrying more passengers than allowed', 'Section 194A', 2000.00, '2025-05-02 19:08:22'),
(37, 'Driving Without Registration', 'Driving an unregistered vehicle on public roads', 'Section 192', 5000.00, '2025-05-02 19:13:38'),
(38, 'Driving Without Permit', 'Operating a transport vehicle without a valid permit', 'Section 192A', 10000.00, '2025-05-02 19:13:38'),
(39, 'Obstructing Emergency Vehicle', 'Failure to give way to ambulance, fire service, or police vehicle', 'Section 194E', 10000.00, '2025-05-02 19:13:38'),
(40, 'Overloading Goods Vehicle', 'Carrying goods exceeding permissible limit', 'Section 194', 2000.00, '2025-05-02 19:13:38'),
(41, 'Unauthorized Use of Vehicle', 'Using vehicle for a purpose other than permitted', 'Section 192', 5000.00, '2025-05-02 19:13:38');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `license` varchar(255) DEFAULT NULL,
  `rc` varchar(255) NOT NULL,
  `vehicle_no` varchar(255) NOT NULL,
  `chassis` varchar(255) NOT NULL,
  `engine no` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'passive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `phone`, `address`, `license`, `rc`, `vehicle_no`, `chassis`, `engine no`, `status`) VALUES
(2, 'Priya Sharma', 'priya.sharma@example.com', '9876543211', 'Pune', 'MH1234567890', 'RC5678PUN', 'MH12XY7890', 'CHASSIS5678', 'ENGINE5678', 'active'),
(3, 'Arjun Singh', 'arjun.singh@example.com', '9876543212', 'Bangalore', 'KA9876543210', 'RC4321BAN', 'KA05MN4321', 'CHASSIS4321', 'ENGINE4321', 'active'),
(4, 'Nisha Patel', 'nisha.patel@example.com', '9876543213', 'Ahmedabad', 'GJ6543210987', 'RC8765AMD', 'GJ18PQ6789', 'CHASSIS8765', 'ENGINE8765', 'active'),
(5, 'Vikram Verma', 'vikram.verma@example.com', '9876543214', 'Chennai', 'TN1234987654', 'RC2468CHE', 'TN10UV5678', 'CHASSIS2468', 'ENGINE2468', 'active'),
(6, 'Sneha Reddy', 'sneha.reddy@example.com', '9876543215', 'Hyderabad', 'AP5678123456', 'RC1357HYD', 'AP09KL4321', 'CHASSIS1357', 'ENGINE1357', 'active'),
(7, 'Ravi Mehta', 'ravi.mehta@example.com', '9876543216', 'Jaipur', 'RJ4321123456', 'RC9753JPR', 'RJ14ZX7890', 'CHASSIS9753', 'ENGINE9753', 'active'),
(8, 'Ankita Das', 'ankita.das@example.com', '9876543217', 'Kolkata', 'WB0987123456', 'RC8642KOL', 'WB02BC1234', 'CHASSIS8642', 'ENGINE8642', 'active'),
(9, 'Sahil Yadav', 'sahil.yadav@example.com', '9876543218', 'Gurgaon', 'HR5678098765', 'RC7531GUR', 'HR26DF9999', 'CHASSIS7531', 'ENGINE7531', 'active'),
(10, 'Pooja Bhatt', 'pooja.bhatt@example.com', '9876543219', 'Lucknow', 'UP6789054321', 'RC6420LKO', 'UP32GH8765', 'CHASSIS6420', 'ENGINE6420', 'active'),
(24, 'Rajesh Reddy Siddareddy', 'b230065@nitsikkim.ac.in', '8522840158', 'Plot - no - 167, Seetha Homes, Telangana, India', '', 'RT-34664', 'hhhhhhhuiukj', 'EX123456ER', 'TYU890', 'email verified');

-- --------------------------------------------------------

--
-- Table structure for table `violation`
--

CREATE TABLE `violation` (
  `violation_id` int(11) NOT NULL,
  `officer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_number` varchar(20) NOT NULL,
  `violation_type` int(11) NOT NULL,
  `violation_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `fine_amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','Paid','Appealed','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `chassis_number` varchar(50) DEFAULT NULL,
  `engine_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `violation`
--

INSERT INTO `violation` (`violation_id`, `officer_id`, `user_id`, `vehicle_number`, `violation_type`, `violation_date`, `location`, `fine_amount`, `status`, `created_at`, `chassis_number`, `engine_number`) VALUES
(30, 11, 2, 'MH12XY7890', 38, '2025-05-31 00:00:00', 'hyd', 10000.00, 'Paid', '2025-05-31 16:13:10', 'CHASSIS5678', 'ENGINE5678'),
(31, 11, 2, 'MH12XY7890', 8, '2025-05-31 00:00:00', 'hyd', 4000.00, 'Pending', '2025-05-31 16:22:46', 'CHASSIS5678', 'ENGINE5678'),
(32, 11, 6, 'AP09KL4321', 16, '2025-05-31 00:00:00', 'BLR', 2000.00, 'Paid', '2025-05-31 18:13:45', 'CHASSIS1357', 'ENGINE1357'),
(33, 11, 6, 'AP09KL4321', 14, '2025-06-01 00:00:00', 'hyd', 5000.00, 'Paid', '2025-06-01 05:24:14', 'CHASSIS1357', 'ENGINE1357'),
(34, 11, 6, 'AP09KL4321', 40, '2025-06-01 00:00:00', 'BLR', 2000.00, 'Pending', '2025-06-01 05:25:20', 'CHASSIS1357', 'ENGINE1357'),
(35, 11, 24, 'hhhhhhhuiukj', 8, '2025-06-01 00:00:00', 'BLR', 4000.00, 'Pending', '2025-06-01 14:35:53', 'EX123456ER', 'TYU890'),
(36, 11, 24, 'hhhhhhhuiukj', 39, '2025-06-01 00:00:00', 'hyd', 10000.00, 'Pending', '2025-06-01 14:43:24', 'EX123456ER', 'TYU890'),
(37, 11, 24, 'hhhhhhhuiukj', 39, '2025-06-01 00:00:00', 'hyd', 10000.00, 'Pending', '2025-06-01 14:44:30', 'EX123456ER', 'TYU890'),
(38, 11, 24, 'hhhhhhhuiukj', 39, '2025-06-01 00:00:00', 'hyd', 0.00, 'Pending', '2025-06-01 14:45:06', 'EX123456ER', 'TYU890'),
(39, 11, 24, 'hhhhhhhuiukj', 39, '2025-06-01 00:00:00', 'BLR', 10000.00, 'Pending', '2025-06-01 14:45:53', 'EX123456ER', 'TYU890');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `officer`
--
ALTER TABLE `officer`
  ADD PRIMARY KEY (`officer_id`),
  ADD UNIQUE KEY `badge_number` (`badge_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`rule_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `rc` (`rc`),
  ADD UNIQUE KEY `vehicle_no` (`vehicle_no`),
  ADD UNIQUE KEY `chassis` (`chassis`),
  ADD UNIQUE KEY `engine no` (`engine no`),
  ADD UNIQUE KEY `license` (`license`);

--
-- Indexes for table `violation`
--
ALTER TABLE `violation`
  ADD PRIMARY KEY (`violation_id`),
  ADD KEY `violation_ibfk_1` (`officer_id`),
  ADD KEY `violation_ibfk_2` (`user_id`),
  ADD KEY `violation_ibfk_3` (`violation_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `officer`
--
ALTER TABLE `officer`
  MODIFY `officer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rules`
--
ALTER TABLE `rules`
  MODIFY `rule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `violation`
--
ALTER TABLE `violation`
  MODIFY `violation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `violation`
--
ALTER TABLE `violation`
  ADD CONSTRAINT `violation_ibfk_3` FOREIGN KEY (`violation_type`) REFERENCES `rules` (`rule_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
