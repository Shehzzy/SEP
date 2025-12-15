-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2025 at 05:13 AM
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
-- Database: `vaccination_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `vaccine_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `status` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `child_id`, `hospital_id`, `vaccine_id`, `booking_date`, `status`, `created_at`) VALUES
(11, 4, NULL, 1, '2025-12-15', 0, '2025-12-15 08:53:12'),
(12, 4, NULL, 2, '2026-01-14', 0, '2025-12-15 08:53:12'),
(13, 4, NULL, 3, '2026-02-13', 0, '2025-12-15 08:53:12'),
(14, 4, NULL, 4, '2026-03-15', 0, '2025-12-15 08:53:12'),
(15, 4, NULL, 5, '2026-04-14', 0, '2025-12-15 08:53:12'),
(16, 4, NULL, 6, '2026-05-14', 0, '2025-12-15 08:53:12'),
(17, 4, NULL, 7, '2026-06-13', 0, '2025-12-15 08:53:12'),
(18, 4, NULL, 8, '2026-07-13', 0, '2025-12-15 08:53:12'),
(19, 4, NULL, 9, '2026-08-12', 0, '2025-12-15 08:53:12'),
(20, 4, NULL, 10, '2026-09-11', 0, '2025-12-15 08:53:12'),
(21, 4, NULL, 11, '2026-10-11', 0, '2025-12-15 08:53:12'),
(22, 4, NULL, 12, '2026-11-10', 0, '2025-12-15 08:53:12'),
(23, 4, NULL, 13, '2026-12-10', 0, '2025-12-15 08:53:12'),
(24, 4, NULL, 14, '2027-01-09', 0, '2025-12-15 08:53:12'),
(25, 4, NULL, 15, '2027-02-08', 0, '2025-12-15 08:53:12'),
(26, 4, NULL, 16, '2027-03-10', 0, '2025-12-15 08:53:12'),
(27, 4, NULL, 17, '2027-04-09', 0, '2025-12-15 08:53:12'),
(28, 4, NULL, 18, '2027-05-09', 0, '2025-12-15 08:53:12'),
(29, 4, NULL, 19, '2027-06-08', 0, '2025-12-15 08:53:12'),
(30, 4, NULL, 20, '2027-07-08', 0, '2025-12-15 08:53:12'),
(31, 4, NULL, 21, '2027-08-07', 0, '2025-12-15 08:53:12'),
(32, 4, NULL, 22, '2027-09-06', 0, '2025-12-15 08:53:12'),
(33, 4, NULL, 23, '2027-10-06', 0, '2025-12-15 08:53:12'),
(34, 4, NULL, 24, '2027-11-05', 0, '2025-12-15 08:53:12'),
(35, 4, NULL, 25, '2027-12-05', 0, '2025-12-15 08:53:12'),
(36, 4, NULL, 26, '2028-01-04', 0, '2025-12-15 08:53:12'),
(37, 4, NULL, 27, '2028-02-03', 0, '2025-12-15 08:53:12'),
(38, 4, NULL, 28, '2028-03-04', 0, '2025-12-15 08:53:12'),
(39, 4, NULL, 29, '2028-04-03', 0, '2025-12-15 08:53:12'),
(40, 4, NULL, 30, '2028-05-03', 0, '2025-12-15 08:53:12'),
(41, 4, NULL, 31, '2028-06-02', 0, '2025-12-15 08:53:12');

-- --------------------------------------------------------

--
-- Table structure for table `children`
--

CREATE TABLE `children` (
  `child_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `child_name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `weight` decimal(5,2) DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `children`
--

INSERT INTO `children` (`child_id`, `parent_id`, `child_name`, `dob`, `gender`, `created_at`, `weight`, `blood_group`) VALUES
(1, 2, 'Amna', '2025-11-01', 'Female', '2025-12-15 08:45:07', 5.00, 'A+'),
(4, 2, 'Ali', '2025-12-15', 'Male', '2025-12-15 08:53:12', 2.60, 'B+');

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `hospital_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hospital_name` varchar(150) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'parent', 'Anusha Khan', 'ansh@gmail.com', '$2y$10$2tsFYrVhzM0Yj7FwlxQmM.W3cpGKKpd4CieNbs/pc96/q1h72AT4S', '2025-12-15 08:00:48'),
(2, 'parent', 'Anushaaa noman', 'ansh09@gmail.com', 'anusha1234', '2025-12-15 08:05:40');

-- --------------------------------------------------------

--
-- Table structure for table `vaccination_reports`
--

CREATE TABLE `vaccination_reports` (
  `report_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `vaccination_date` date DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccines`
--

CREATE TABLE `vaccines` (
  `vaccine_id` int(11) NOT NULL,
  `vaccine_name` varchar(100) NOT NULL,
  `availability` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccines`
--

INSERT INTO `vaccines` (`vaccine_id`, `vaccine_name`, `availability`) VALUES
(1, 'BCG', 1),
(2, 'Polio', 1),
(3, 'Hepatitis B', 1),
(4, 'DPT', 1),
(5, 'Measles', 1),
(6, 'MMR', 1),
(7, 'Typhoid', 1),
(8, 'Chickenpox', 1),
(9, 'BCG (At Birth)', 1),
(10, 'Hepatitis B (Birth Dose)', 1),
(11, 'OPV (6 weeks)', 1),
(12, 'Pentavalent 1 (6 weeks)', 1),
(13, 'PCV 1 (6 weeks)', 1),
(14, 'Rotavirus 1 (6 weeks)', 1),
(15, 'OPV (10 weeks)', 1),
(16, 'Pentavalent 2 (10 weeks)', 1),
(17, 'PCV 2 (10 weeks)', 1),
(18, 'Rotavirus 2 (10 weeks)', 1),
(19, 'OPV (14 weeks)', 1),
(20, 'Pentavalent 3 (14 weeks)', 1),
(21, 'PCV 3 (14 weeks)', 1),
(22, 'Rotavirus 3 (14 weeks)', 1),
(23, 'IPV (14 weeks)', 1),
(24, 'Measles 1 (9 months)', 1),
(25, 'Vitamin A (9 months)', 1),
(26, 'MMR (15 months)', 1),
(27, 'Booster DPT (16-24 months)', 1),
(28, 'OPV Booster (16-24 months)', 1),
(29, 'Vitamin A (16-24 months)', 1),
(30, 'Typhoid Conjugate Vaccine (2 years)', 1),
(31, 'Hepatitis A (18 months)', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `child_id` (`child_id`),
  ADD KEY `vaccine_id` (`vaccine_id`),
  ADD KEY `bookings_ibfk_2` (`hospital_id`);

--
-- Indexes for table `children`
--
ALTER TABLE `children`
  ADD PRIMARY KEY (`child_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`hospital_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vaccination_reports`
--
ALTER TABLE `vaccination_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `vaccines`
--
ALTER TABLE `vaccines`
  ADD PRIMARY KEY (`vaccine_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `children`
--
ALTER TABLE `children`
  MODIFY `child_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `hospital_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vaccination_reports`
--
ALTER TABLE `vaccination_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccines`
--
ALTER TABLE `vaccines`
  MODIFY `vaccine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`child_id`) REFERENCES `children` (`child_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`hospital_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`vaccine_id`) REFERENCES `vaccines` (`vaccine_id`) ON DELETE CASCADE;

--
-- Constraints for table `children`
--
ALTER TABLE `children`
  ADD CONSTRAINT `children_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD CONSTRAINT `hospitals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `vaccination_reports`
--
ALTER TABLE `vaccination_reports`
  ADD CONSTRAINT `vaccination_reports_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
