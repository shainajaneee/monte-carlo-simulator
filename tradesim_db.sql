-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2025 at 06:25 AM
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
-- Database: `tradesim_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Shaina Jane Tanguan', 'shayna@gmail.com', '$2y$10$j7Rfzo1zHTNKdQ0PlqxzkeLWdPU7vWy/zIrPlTeq.B9nRwW2BjkPS', '2025-11-25 05:39:39'),
(2, 'hasfjhe', 'okayed@gmail.com', '$2y$10$uWC4qc0CbLn48gy3l2tjjejZJ1Xlw5nymlvlRFjAXJ9e66oc6Rksq', '2025-11-25 07:13:54'),
(3, 'RJ Lorejo', 'rj@gmail.com', '$2y$10$2PNT/ib0CpP6ZE8.P3DAtOtGNu3av2P7DXAWPjyuiHfLhzCMOqiq2', '2025-11-27 01:33:41'),
(4, 'Shaina Jane Tanguan', 'shainajane0102@gmail.com', '$2y$10$a7tzwkzoeSb1jlwKqttsSeX4tXwoYQJW7cnJ009p9LET/fK7dUo7S', '2025-11-29 06:08:06'),
(5, 'Mario Norca', 'mario@gmail.com', '$2y$10$M0SjvgzD4LKRAUgrcyWxl.mdelmo7.IuHNQOHBC/2.ZHBSGecHzb2', '2025-12-02 03:18:05'),
(6, 'Shaina Jane Tanguan', 'shaina@gmail.com', '$2y$10$rgXf36L3q0cYwiLjFqvk..TadI.nGYbUx7zMMYUyyVHfxUjnMaqYm', '2025-12-08 05:15:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
