-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2025 at 08:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'hari', 'mukilan@gmail.com', '$2y$10$7SjyA/ObVK0QDSPE/piTS.ONdDtXpxYDM.OoIwW471Lcssex0aFLi'),
(2, 'sundar', 'sundar@gmail.com', '$2y$10$mZZeJoLdL82ti/KNo9dDjeSbtTnFIjLf94UUgTWOvc6/QpO10yFHi'),
(3, 'tamizh@123', 'abc@gmail.com', '$2y$10$5DlIpTV/9Wjf9h84S7GXyukKUs9ArD14iSlD85Rf6PZ0UZxr3qWQ2'),
(4, 'abcd', 'abcd@gmail.com', '$2y$10$tvK.8583Q0OQ4ijt8h.m/OZPXWjMqSUZBJu/QavVzdXcVgZYmsEu6'),
(5, 'dhoni', '123@gmail.com', '$2y$10$fHADSUf.Kwrsn9iU3AkITOGixlP3i4Dq0Rx6mrZqXZqpcx6n8Ddoi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
