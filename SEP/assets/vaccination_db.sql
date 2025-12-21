-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2025 at 06:23 AM
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
(11, 4, 1, 1, '2025-12-23', 2, '2025-12-15 08:53:12'),
(12, 4, 1, 2, '2025-12-26', 2, '2025-12-15 08:53:12'),
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
(41, 4, NULL, 31, '2028-06-02', 0, '2025-12-15 08:53:12'),
(42, 5, 1, 1, '2025-12-23', 1, '2025-12-21 09:12:02'),
(43, 5, NULL, 2, '2026-01-19', 0, '2025-12-21 09:12:02'),
(44, 5, NULL, 3, '2026-02-18', 0, '2025-12-21 09:12:02'),
(45, 5, NULL, 4, '2026-03-20', 0, '2025-12-21 09:12:02'),
(46, 5, NULL, 5, '2026-04-19', 0, '2025-12-21 09:12:02'),
(47, 5, NULL, 6, '2026-05-19', 0, '2025-12-21 09:12:02'),
(48, 5, NULL, 7, '2026-06-18', 0, '2025-12-21 09:12:02'),
(49, 5, NULL, 8, '2026-07-18', 0, '2025-12-21 09:12:02'),
(50, 5, NULL, 9, '2026-08-17', 0, '2025-12-21 09:12:02'),
(51, 5, NULL, 10, '2026-09-16', 0, '2025-12-21 09:12:02'),
(52, 5, NULL, 11, '2026-10-16', 0, '2025-12-21 09:12:02'),
(53, 5, NULL, 12, '2026-11-15', 0, '2025-12-21 09:12:02'),
(54, 5, NULL, 13, '2026-12-15', 0, '2025-12-21 09:12:02'),
(55, 5, NULL, 14, '2027-01-14', 0, '2025-12-21 09:12:02'),
(56, 5, NULL, 15, '2027-02-13', 0, '2025-12-21 09:12:02'),
(57, 5, NULL, 16, '2027-03-15', 0, '2025-12-21 09:12:02'),
(58, 5, NULL, 17, '2027-04-14', 0, '2025-12-21 09:12:02'),
(59, 5, NULL, 18, '2027-05-14', 0, '2025-12-21 09:12:02'),
(60, 5, NULL, 19, '2027-06-13', 0, '2025-12-21 09:12:02'),
(61, 5, NULL, 20, '2027-07-13', 0, '2025-12-21 09:12:02'),
(62, 5, NULL, 21, '2027-08-12', 0, '2025-12-21 09:12:02'),
(63, 5, NULL, 22, '2027-09-11', 0, '2025-12-21 09:12:02'),
(64, 5, NULL, 23, '2027-10-11', 0, '2025-12-21 09:12:02'),
(65, 5, NULL, 24, '2027-11-10', 0, '2025-12-21 09:12:02'),
(66, 5, NULL, 25, '2027-12-10', 0, '2025-12-21 09:12:02'),
(67, 5, NULL, 26, '2028-01-09', 0, '2025-12-21 09:12:02'),
(68, 5, NULL, 27, '2028-02-08', 0, '2025-12-21 09:12:02'),
(69, 5, NULL, 28, '2028-03-09', 0, '2025-12-21 09:12:02'),
(70, 5, NULL, 29, '2028-04-08', 0, '2025-12-21 09:12:02'),
(71, 5, NULL, 30, '2028-05-08', 0, '2025-12-21 09:12:02'),
(72, 5, NULL, 31, '2028-06-07', 0, '2025-12-21 09:12:02');

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
(4, 2, 'Ali', '2025-12-15', 'Male', '2025-12-15 08:53:12', 2.60, 'B+'),
(5, 2, 'Zoya', '2025-12-20', 'Female', '2025-12-21 09:12:02', 5.50, 'O+');

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
  `status` tinyint(4) DEFAULT 1,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`hospital_id`, `user_id`, `hospital_name`, `address`, `location`, `status`, `contact_phone`, `contact_email`) VALUES
(1, 4, 'Ziauddin Hospital Karachi', 'karachi', 'karachi', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hospital_vaccines`
--

CREATE TABLE `hospital_vaccines` (
  `hospital_vaccine_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `vaccine_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `last_updated` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital_vaccines`
--

INSERT INTO `hospital_vaccines` (`hospital_vaccine_id`, `hospital_id`, `vaccine_id`, `quantity`, `last_updated`) VALUES
(1, 1, 1, 50, '2025-12-21 09:08:20');

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
(2, 'parent', 'Anushaaa Ali', 'ansh09@gmail.com', 'anusha1234', '2025-12-15 08:05:40'),
(3, 'admin', 'Superadmin', 'admin@gmail.com', 'admin@1234', '2025-12-21 07:34:44'),
(4, 'hospital', 'ZiaAdmin', 'ziaadmin@gmail.com', 'zia1234', '2025-12-21 07:50:11');

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

--
-- Dumping data for table `vaccination_reports`
--

INSERT INTO `vaccination_reports` (`report_id`, `booking_id`, `vaccination_date`, `remarks`) VALUES
(1, 11, '2025-12-21', 'VaccinateD!'),
(2, 12, '2025-12-21', ''),
(3, 42, '2025-12-21', 'Done!');

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
-- Indexes for table `hospital_vaccines`
--
ALTER TABLE `hospital_vaccines`
  ADD PRIMARY KEY (`hospital_vaccine_id`),
  ADD UNIQUE KEY `unique_hospital_vaccine` (`hospital_id`,`vaccine_id`),
  ADD KEY `vaccine_id` (`vaccine_id`);

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
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `children`
--
ALTER TABLE `children`
  MODIFY `child_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `hospital_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hospital_vaccines`
--
ALTER TABLE `hospital_vaccines`
  MODIFY `hospital_vaccine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vaccination_reports`
--
ALTER TABLE `vaccination_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- Constraints for table `hospital_vaccines`
--
ALTER TABLE `hospital_vaccines`
  ADD CONSTRAINT `hospital_vaccines_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`hospital_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hospital_vaccines_ibfk_2` FOREIGN KEY (`vaccine_id`) REFERENCES `vaccines` (`vaccine_id`) ON DELETE CASCADE;

--
-- Constraints for table `vaccination_reports`
--
ALTER TABLE `vaccination_reports`
  ADD CONSTRAINT `vaccination_reports_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
