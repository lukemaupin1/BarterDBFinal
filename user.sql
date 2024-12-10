-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 02:05 AM
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
-- Database: `user`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `itemid` int(11) NOT NULL,
  `itemName` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `value` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`itemid`, `itemName`, `description`, `value`) VALUES
(12, 'Wrench', 'daa', 34.00),
(14, 'stick', 'fire', 10.21),
(15, 'Nails', 'box of 120 nails', 20.00),
(16, 'f', '3', 53.00),
(17, 'Hammer', 'hits good', 12.00),
(20, 'ball', 'game', 10.00),
(21, 'ad', 'its an ad', 120.32),
(22, 'Computer', 'brand new, unopened', 365.99),
(23, 'Pizza', 'tastes good', 3.99),
(24, 'Bat', 'hits good', 27.99);

-- --------------------------------------------------------

--
-- Table structure for table `owns`
--

CREATE TABLE `owns` (
  `userid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owns`
--

INSERT INTO `owns` (`userid`, `itemid`, `quantity`) VALUES
(1, 12, 1),
(1, 15, 3),
(1, 17, 1),
(1, 20, 1),
(1, 21, 2),
(2, 16, 2),
(2, 22, 1),
(27, 14, 6),
(27, 23, 1),
(27, 24, 1);

-- --------------------------------------------------------

--
-- Table structure for table `trade`
--

CREATE TABLE `trade` (
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `itemWant` varchar(255) NOT NULL,
  `itemOffer` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `forPartner` tinyint(1) NOT NULL DEFAULT 0,
  `date_posted` timestamp NOT NULL DEFAULT current_timestamp(),
  `tradeStatus` varchar(255) NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trade`
--

INSERT INTO `trade` (`postid`, `userid`, `itemWant`, `itemOffer`, `quantity`, `forPartner`, `date_posted`, `tradeStatus`) VALUES
(32, 1, 'Table', 'f', 1, 0, '2024-11-14 19:08:36', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `partnerid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `first_name`, `last_name`, `email`, `password`, `create_datetime`, `partnerid`) VALUES
(1, 'ItsLukeM', 'Luke', 'Maupin', 'lukemaupin1@gmail.com', 'bob', '2024-10-17 06:33:10', 30),
(2, 'Dan123', 'dan', 'danny', 'dan@gmail.com', 'bob2', '2024-10-17 07:34:46', 1),
(3, 'Mark123', 'mark', 'marky', 'mark@gmail.com', 'mars', '2024-10-17 07:35:13', 27),
(27, 'test', 'test', 'test', 'test@gmail.com', 'b', '2024-10-24 23:08:55', 3),
(28, 'Luke123', 'Luke', 'Maupin', 'luke@gmail.com', 'bob1', '2024-11-12 08:44:32', 0),
(30, 'Jim123', 'Jim', 'Bob', 'jim@gmail.com', 'bob1', '2024-11-14 21:28:12', 1),
(31, 'jesus', 'Jesus', 'PEralta', 'jesusemail', '123', '2024-11-14 23:15:58', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`itemid`);

--
-- Indexes for table `owns`
--
ALTER TABLE `owns`
  ADD PRIMARY KEY (`userid`,`itemid`),
  ADD KEY `itemID` (`itemid`);

--
-- Indexes for table `trade`
--
ALTER TABLE `trade`
  ADD PRIMARY KEY (`postid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `itemid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `trade`
--
ALTER TABLE `trade`
  MODIFY `postid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `owns`
--
ALTER TABLE `owns`
  ADD CONSTRAINT `owns_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `owns_ibfk_2` FOREIGN KEY (`itemID`) REFERENCES `items` (`itemID`) ON DELETE CASCADE;

--
-- Constraints for table `trade`
--
ALTER TABLE `trade`
  ADD CONSTRAINT `trade_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
