-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2026 at 04:06 AM
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
-- Database: `studentss`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `Date_Added` date NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `course`, `year`, `Date_Added`, `email`, `phone`, `address`) VALUES
(1, 'Juan Dela Cruz', 'BSCS', 3, '2026-06-10', NULL, NULL, NULL),
(2, 'Maria Clara', 'BSIT', 2, '2026-06-10', NULL, NULL, NULL),
(3, 'Pedro Penduko', 'BSCS', 1, '2026-06-10', NULL, NULL, NULL),
(4, 'Jose Rizal', 'BSIS', 4, '2026-06-10', NULL, NULL, NULL),
(5, 'Gabriela Silang', 'BSIT', 3, '2026-06-10', NULL, NULL, NULL),
(6, 'Earl Lumusad', 'BSIT', 2, '2026-06-10', NULL, NULL, NULL),
(7, 'Earl Lumusad', 'BSIT', 2, '2026-06-10', NULL, NULL, NULL),
(8, 'Earl Lumusad', 'BSIT', 2, '2026-06-10', NULL, NULL, NULL),
(9, 'Earl Lumusad', 'BSIT', 2, '2026-06-10', NULL, NULL, NULL),
(10, 'w', 'w', 2, '2026-06-10', NULL, NULL, NULL),
(11, 'w', 'w', 2, '2026-06-10', NULL, NULL, NULL),
(12, 'Godfred Dublin', 'BSIT', 2, '2026-06-10', NULL, NULL, NULL),
(13, 'Godfred Dublin', 'BSIT', 2, '2026-06-10', NULL, NULL, NULL),
(14, 'Badingong', 'BSIT', 2, '2026-06-10', NULL, NULL, NULL),
(15, 'Ag Gasal', 'BSIT', 2, '2026-06-10', NULL, NULL, NULL),
(16, 'Ag Gasal', 'BSIT', 2, '2026-06-10', NULL, NULL, NULL),
(17, 'Ducati Panigale v4', 'BSIT', 1, '2026-06-10', NULL, NULL, NULL),
(18, 'Ducati Panigale v4', 'BSIT', 1, '2026-06-10', NULL, NULL, NULL),
(19, 'Ag', 'BSIT', 1, '2026-06-10', NULL, NULL, NULL),
(20, 'Ag Gasal', 'BSIT', 2, '2026-06-10', 'ag.gasal2006@gmail.com', '9655486419', 'iligan city'),
(21, 'Ag Gasal', 'BSIT', 2, '2026-06-10', 'ag.gasal2006@gmail.com', '9655486419', 'iligan city'),
(22, 'Ag Gasal', 'BSIT', 2, '2026-06-10', 'ag.gasal2006@gmail.com', '9655486419', 'iligan city'),
(23, 'earl lumusad', 'BSIT', 2, '2026-06-10', 'ag.gasal2006@gmail.com', '9655486419', 'iligan city'),
(24, 'earl lumusad', 'BSIT', 2, '2026-06-10', 'ag.gasal2006@gmail.com', '9655486419', 'iligan city');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
