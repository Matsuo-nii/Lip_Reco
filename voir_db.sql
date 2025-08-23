-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2025 at 05:10 PM
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
-- Database: `voir_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `guards`
--

CREATE TABLE `guards` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` enum('available','unavailable') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guards`
--

INSERT INTO `guards` (`id`, `name`, `status`) VALUES
(1, 'Carlos Mendoza', 'available'),
(2, 'Antonio Salsanez', 'unavailable'),
(3, 'Ramon Villanueva', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` int(11) DEFAULT NULL,
  `agreed_to_terms` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `otp`, `otp_expiry`, `agreed_to_terms`) VALUES
(1, 'mat', 'matsuor8@gmail.com', '$2y$10$vSdDQ2OCOQDJKQP9g5QMiODZSM3xqA72c/HOhUOubYEX1a/lbhK5q', '855677', 1753756565, 0),
(3, 'nat', 'matthewrosales234@gmail.com', '$2y$10$yxFYnkVWLF93wjXY59hGqOgJ2rG0UVoDAJTh0zOETjjVMvxXeEBcq', NULL, NULL, 0),
(4, 'JOHN', 'mashudesu03@gmail.com', '$2y$10$aWxOBXhqgYYcbUlTduyLlOcP1Ll07rHVIm5OT/WCVwinZVAtTeDD2', '369548', 1753712388, 0),
(9, 'Andeng', 'zaraandrea57@gmail.com', '$2y$10$98ITkwxAXNXuqB5ELedQLeDPUYBkElcpMiFNisWymXp1oGdXZrcjC', '106124', 1753757251, 0),
(10, 'kim', 'kimrigodon7@gmail.com', '$2y$10$G/n6h5lK8FuasX2rdlApR.Nf/jIINeyRAZdQunQXGTXvQ0Im2yJwW', '572589', 1753757478, 0);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `type` enum('car','motorcycle','van') NOT NULL,
  `license_plate` varchar(20) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `sr_code` varchar(50) DEFAULT NULL,
  `registered_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `type`, `license_plate`, `owner_name`, `category`, `sr_code`, `registered_at`) VALUES
(1, 'car', 'ABC123', 'Juan Dela Cruz', 'student', 'SR2023001', '2025-07-20 17:39:59'),
(2, 'motorcycle', 'XYZ987', 'Maria Clara', 'professor', 'SR2023010', '2025-07-20 17:39:59'),
(3, 'van', 'VAN456', 'Jose Rizal', 'faculty', 'SR2023025', '2025-07-20 17:39:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guards`
--
ALTER TABLE `guards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_plate` (`license_plate`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `guards`
--
ALTER TABLE `guards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
