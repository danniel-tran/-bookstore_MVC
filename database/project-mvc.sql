-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2021 at 01:19 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project-mvc`
--

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE `group` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `group_acp` tinyint(1) DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `created_by` varchar(45) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modified_by` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`id`, `name`, `group_acp`, `created`, `created_by`, `modified`, `modified_by`, `status`) VALUES
(1, 'Admin', 1, '2013-11-11 00:00:00', 'admin', '1970-01-01 08:00:00', 'admin', 'active'),
(2, 'Manager', 1, '2013-11-07 00:00:00', 'admin', '2021-06-12 20:42:27', 'admin', 'inactive'),
(3, 'Member', 0, '2020-09-07 04:41:34', 'admin', '2021-06-12 20:42:28', 'admin', 'inactive'),
(4, 'Register', 1, '2021-07-25 20:11:24', 'admin', '2021-06-12 20:42:25', 'admin', 'active'),
(12, 'Register', 0, '2021-07-25 20:11:24', 'admin', '2021-11-27 12:48:43', 'admin', 'inactive'),
(13, 'Admin', 0, '2013-11-11 00:00:00', 'admin', '2021-08-12 10:52:30', 'admin', 'inactive'),
(14, 'Manager', 1, '2013-11-07 00:00:00', 'admin', '2020-09-05 06:06:03', 'admin', 'active'),
(15, 'Member', 0, '2020-09-07 04:41:34', 'admin', '2020-09-07 11:00:57', 'admin', 'active'),
(16, 'Register', 0, '2021-07-25 20:11:24', 'admin', '2021-11-27 12:48:47', 'admin', 'inactive'),
(17, 'Admin', 1, '2013-11-11 00:00:00', 'admin', '2021-07-25 20:48:12', 'admin', 'active'),
(18, 'Manager', 1, '2013-11-07 00:00:00', 'admin', '2020-09-05 06:06:03', 'admin', 'active'),
(19, 'Member', 0, '2020-09-07 04:41:34', 'admin', '2020-09-07 11:00:57', 'admin', 'active'),
(21, 'Register', 1, '2021-07-25 20:11:24', 'admin', '2021-06-12 20:42:25', 'admin', 'active'),
(22, 'Register', 0, '2021-07-25 20:11:24', 'admin', '2021-11-27 12:48:43', 'admin', 'inactive'),
(23, 'Admin', 0, '2013-11-11 00:00:00', 'admin', '2021-08-12 10:52:30', 'admin', 'inactive'),
(24, 'Manager', 1, '2013-11-07 00:00:00', 'admin', '2020-09-05 06:06:03', 'admin', 'active'),
(25, 'Member', 0, '2020-09-07 04:41:34', 'admin', '2020-09-07 11:00:57', 'admin', 'active'),
(26, 'Register', 0, '2021-07-25 20:11:24', 'admin', '2021-11-27 12:48:47', 'admin', 'inactive'),
(27, 'Admin', 1, '2013-11-11 00:00:00', 'admin', '2021-07-25 20:48:12', 'admin', 'active'),
(28, 'Manager', 1, '2013-11-07 00:00:00', 'admin', '2020-09-05 06:06:03', 'admin', 'active'),
(29, 'Member', 0, '2020-09-07 04:41:34', 'admin', '2020-09-07 11:00:57', 'admin', 'active'),
(30, 'NEw', 0, '2021-12-08 13:52:41', 'admin', '2021-12-08 14:08:29', 'admin', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
