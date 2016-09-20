-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2016 at 02:45 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `a2361141_ebank`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `accountNo` int(11) NOT NULL,
  `idNo` varchar(255) NOT NULL,
  `DOB` varchar(255) NOT NULL,
  `password` varchar(500) NOT NULL,
  `Balance` float NOT NULL,
  `Status` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`accountNo`, `idNo`, `DOB`, `password`, `Balance`, `Status`) VALUES
(5787, '28', '2015-05-04', '12', 1700, 'A'),
(4321, '4321', '101091', '9876', 5419, 'I'),
(2147483647, '0123456789', '101112', '1234', 0, 'A'),
(23456, '23456', '101091', '23456', 1356, 'A'),
(1, '12', '03091991', '12', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `Type` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `Price` varchar(255) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `Type`, `Price`) VALUES
(1, 'Centrisafe-L', '10000'),
(2, 'Centrisafe-S', '6000'),
(3, 'Centrisafe-M', '10000'),
(4, 'Centrisafe-Sl', '6000');

-- --------------------------------------------------------

--
-- Table structure for table `purchased_products`
--

CREATE TABLE `purchased_products` (
  `id` int(11) NOT NULL,
  `user_id` int(100) NOT NULL,
  `product_id` int(100) NOT NULL,
  `status` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `date` datetime NOT NULL,
  `quantity` int(50) NOT NULL,
  `amount` varchar(255) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `purchased_products`
--

INSERT INTO `purchased_products` (`id`, `user_id`, `product_id`, `status`, `date`, `quantity`, `amount`) VALUES
(1, 2, 3, 'R', '2016-09-05 21:09:49', 4, '40000'),
(2, 2, 4, 'P', '2016-09-05 21:23:53', 4, '24000');

-- --------------------------------------------------------

--
-- Table structure for table `retrieval_request`
--

CREATE TABLE `retrieval_request` (
  `id` int(11) NOT NULL,
  `subscribed_service_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `date` datetime NOT NULL,
  `clear_date` datetime DEFAULT NULL,
  `sales_agent` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `retrieval_request`
--

INSERT INTO `retrieval_request` (`id`, `subscribed_service_id`, `user_id`, `status`, `date`, `clear_date`, `sales_agent`) VALUES
(1, 1, 2, 'P', '2016-09-12 14:56:21', '2016-09-20 10:22:54', '001'),
(2, 2, 2, 'P', '2016-09-12 15:18:00', '2016-09-20 11:37:20', '001'),
(3, 4, 2, 'R', '2016-09-19 16:10:57', '2016-09-20 08:17:05', '001'),
(4, 1, 2, 'R', '2016-09-20 10:24:55', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_agent`
--

CREATE TABLE `sales_agent` (
  `id` int(11) NOT NULL,
  `sales_code` varchar(50) DEFAULT NULL,
  `agent_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales_agent`
--

INSERT INTO `sales_agent` (`id`, `sales_code`, `agent_name`) VALUES
(1, '001', 'Agnes Kirubi'),
(2, '002', 'Johnstone Kivuva'),
(3, '003', 'Vivian Aluoch'),
(4, '004', 'Duncan Mwiti');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `type` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `price` varchar(255) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `type`, `price`) VALUES
(1, '3 Year Service Contract', '5000'),
(2, '5 Year Service Contract', '9000'),
(3, '10 Year Service Contract', '13000');

-- --------------------------------------------------------

--
-- Table structure for table `service_request`
--

CREATE TABLE `service_request` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `service_request`
--

INSERT INTO `service_request` (`id`, `service_id`, `user_id`, `status`, `date`) VALUES
(1, 2, 2, 'P', '2016-09-12 15:47:11'),
(2, 2, 1, 'R', '2016-09-12 16:54:11'),
(3, 2, 2, 'R', '2016-09-12 16:55:01'),
(4, 2, 1, 'R', '2016-09-12 16:55:03'),
(5, 5, 3, 'R', '2016-09-20 11:39:21');

-- --------------------------------------------------------

--
-- Table structure for table `subscribed_services`
--

CREATE TABLE `subscribed_services` (
  `id` int(11) NOT NULL,
  `service_no` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_type` int(11) DEFAULT NULL,
  `status` varchar(10) NOT NULL,
  `open_date` datetime NOT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `sales_agent` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subscribed_services`
--

INSERT INTO `subscribed_services` (`id`, `service_no`, `user_id`, `service_type`, `status`, `open_date`, `expiry_date`, `sales_agent`) VALUES
(1, 'SRV03001', 2, 1, 'A', '2016-09-01 00:00:00', NULL, '004'),
(2, 'SRV05001', 2, 2, 'A', '2016-09-09 00:00:00', NULL, '002'),
(3, 'SRV10001', 2, 3, 'A', '2016-09-08 00:00:00', NULL, '003'),
(4, 'SRV10283', 2, 2, 'A', '2016-09-19 12:30:38', NULL, '001');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transID` int(11) NOT NULL,
  `transCode` varchar(10) NOT NULL,
  `amount` float NOT NULL,
  `accountNo` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transID`, `transCode`, `amount`, `accountNo`) VALUES
(1, 'D', 2000, '5787'),
(2, 'W', 300, '5787'),
(3, 'D', 10000, '4321'),
(4, 'W', 4581, '4321'),
(5, 'D', 1556, '23456'),
(6, 'W', 200, '23456');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `Name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `PhoneNumber` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `IdNo` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `Password` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `Status` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `Date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `Name`, `PhoneNumber`, `IdNo`, `Password`, `Status`, `Date`) VALUES
(1, 'James', '0722451039', '28470515', '1234', 'A', '2016-08-17 00:00:00'),
(2, 'Agnes', '+254720391860', '28470512', '12345', 'A', '2016-08-17 10:55:24'),
(3, 'Michael Muriithi', '+254722451032', '12345678', '122', 'I', '2016-09-19 16:31:49'),
(4, 'ernest', '+254720391834', '12345', '12345', 'A', '2016-09-20 10:16:48'),
(5, 'P', '+254725567229', '25252525', '1234', 'A', '2016-09-20 11:35:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accountNo`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchased_products`
--
ALTER TABLE `purchased_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `retrieval_request`
--
ALTER TABLE `retrieval_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_agent`
--
ALTER TABLE `sales_agent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_request`
--
ALTER TABLE `service_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribed_services`
--
ALTER TABLE `subscribed_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `purchased_products`
--
ALTER TABLE `purchased_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `retrieval_request`
--
ALTER TABLE `retrieval_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sales_agent`
--
ALTER TABLE `sales_agent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `service_request`
--
ALTER TABLE `service_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `subscribed_services`
--
ALTER TABLE `subscribed_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
