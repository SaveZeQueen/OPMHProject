-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2022 at 11:48 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `opmh`
--
DROP DATABASE IF EXISTS `opmh`;
CREATE DATABASE IF NOT EXISTS `opmh` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `opmh`;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `ID` int(11) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `hashKey` char(60) DEFAULT NULL,
  `email` text,
  `logginAttempts` int(11) NOT NULL DEFAULT '0',
  `streetAddress` text,
  `cityName` text,
  `stateName` varchar(3) DEFAULT '',
  `zipCode` int(5) NOT NULL DEFAULT '0',
  `firstName` tinytext NOT NULL,
  `lastName` tinytext NOT NULL,
  `companyName` tinytext NOT NULL,
  `accountVerified` tinytext,
  `accountVerID` varchar(10) NOT NULL,
  `dateEST` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `birthDate` date NOT NULL DEFAULT '0000-00-00',
  `phoneNumber` bigint(10) NOT NULL DEFAULT '0',
  `faxNumber` bigint(10) NOT NULL DEFAULT '0',
  `accountType` varchar(3) NOT NULL DEFAULT 'DEF',
  `lastLogin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`ID`, `username`, `hashKey`, `email`, `logginAttempts`, `streetAddress`, `cityName`, `stateName`, `zipCode`, `firstName`, `lastName`, `companyName`, `accountVerified`, `accountVerID`, `dateEST`, `birthDate`, `phoneNumber`, `faxNumber`, `accountType`, `lastLogin`) VALUES
(1, 'Admin', '$2y$11$BZZJr3sb0FG4pvmQQ0H8cO9uShhQnCzeDTvowlQaDYxkLrCQbxCbi', 'admin@opmhproject.com', 0, '11400 Decimal Dr', 'Louisville', 'KY', 40299, 'Admin', 'User', 'OPMH', 'T', 'sA3YvO5OJU', '2017-08-11 16:26:39', '1900-01-01', 5025929992, 5023459950, 'ADM', '2021-11-03');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands` (
  `uID` int(11) NOT NULL,
  `brandName` tinytext,
  `brandID` varchar(15) DEFAULT NULL,
  `brandGCC` text NOT NULL,
  `brandImageURL` mediumtext NOT NULL,
  `brandProdDir` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`uID`, `brandName`, `brandID`, `brandGCC`, `brandImageURL`, `brandProdDir`) VALUES
(1, 'Test001', 'Y4EaE2ENYDuMaHE', 'GCC/BakeNShake.pdf', 'images/brandLogos/primal-logo.png', 'images/ProductPictures/Test001/'),
(2, 'Bake Up Test', 'UEOPUJUHY5AHoTO', 'GCC/BakeNShake.pdf', 'images/brandLogos/bakeUpBros.png', 'images/ProductPictures/Bake Up Test/');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `uID` int(11) NOT NULL,
  `invoiceNumber` varchar(15) NOT NULL,
  `dateIssued` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dueDate` date NOT NULL,
  `invoiceStatus` varchar(15) NOT NULL DEFAULT 'PENDING',
  `invoiceUser` varchar(30) NOT NULL,
  `invoiceUserID` int(11) DEFAULT NULL,
  `invoiceUserVerID` varchar(15) NOT NULL,
  `invoiceTotalQTY` int(11) NOT NULL DEFAULT '0',
  `invoiceTotalCost` float(11,2) NOT NULL DEFAULT '0.00',
  `invoiceProducts` longtext,
  `customerComments` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`uID`, `invoiceNumber`, `dateIssued`, `dueDate`, `invoiceStatus`, `invoiceUser`, `invoiceUserID`, `invoiceUserVerID`, `invoiceTotalQTY`, `invoiceTotalCost`, `invoiceProducts`, `customerComments`) VALUES
(1, '830744690760543', '2017-10-17 08:01:55', '2017-11-17', 'PAID', 'admin', 1, 'sA3YvO5OJU', 30, 2160.00, '24385701,10|24385702,10|49833500,10', 'Test Order 1'),
(5, '981483609167529', '2017-11-26 22:29:13', '2017-12-26', 'PENDING', 'admin', 1, 'sA3YvO5OJU', 98, 11067.00, '24385700,1|24385701,6|24385702,15|49833500,12|49833501,27|49833502,5|49833503,1|84004200,22|84004201,6|84004202,1|84004203,2', 'Test Comment 001'),
(9, '796996771576361', '2017-11-27 22:05:19', '2017-12-27', 'PENDING', 'admin', 1, 'sA3YvO5OJU', 50, 250.00, '96282100,5|96282101,10|96282102,15|96282103,20', 'Another Test Order'),
(10, '582054403461388', '2017-12-03 23:12:42', '2018-01-02', 'PAID', 'admin', 1, 'sA3YvO5OJU', 2, 101.00, '24385700,2', 'Just those'),
(11, '367105161387635', '2019-02-28 01:36:57', '2019-03-29', 'PENDING', 'admin', 1, 'sA3YvO5OJU', 15, 1530.00, '24385700,1|24385701,1|24385702,1|49833500,1|49833501,1|49833502,1|49833503,1|96282100,1|96282101,1|96282102,1|96282103,1|84004200,1|84004201,1|84004202,1|84004203,1', 'Please use code 123 to get in the door.'),
(12, '092375235277381', '2019-03-22 02:48:37', '2019-04-20', 'PENDING', 'admin', 1, 'sA3YvO5OJU', 8, 606.00, '24385700,1|24385701,2|24385702,5', 'Test 1'),
(13, '002220214248319', '2020-02-24 09:10:26', '2020-03-25', 'PENDING', 'admin', 1, 'sA3YvO5OJU', 180, 9570.00, '24385700,20|24385701,20|49833502,20|49833503,20|96282100,20|96282101,20|96282102,20|96282103,20|84004200,20', 'This is a Test :)');

-- --------------------------------------------------------

--
-- Table structure for table `loginlog`
--

DROP TABLE IF EXISTS `loginlog`;
CREATE TABLE `loginlog` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `uID` int(11) NOT NULL,
  `brandID` varchar(15) NOT NULL,
  `productName` text,
  `productSKU` varchar(8) DEFAULT NULL,
  `productNicLVL` int(11) NOT NULL DEFAULT '0',
  `productDescription` mediumtext,
  `productFlavors` text,
  `productCost` double(11,2) NOT NULL,
  `productWholeSaleCost` double(11,2) NOT NULL,
  `productBotSize` int(11) NOT NULL,
  `productPG` int(11) NOT NULL,
  `productVG` int(11) NOT NULL,
  `productImageURL` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`uID`, `brandID`, `productName`, `productSKU`, `productNicLVL`, `productDescription`, `productFlavors`, `productCost`, `productWholeSaleCost`, `productBotSize`, `productPG`, `productVG`, `productImageURL`) VALUES
(10, 'Y4EaE2ENYDuMaHE', 'Test001', '84004200', 0, 'testtest', 'test', 25.99, 12.00, 60, 70, 30, 'images/ProductPictures/Test001/RED small wbst.jpg'),
(11, 'Y4EaE2ENYDuMaHE', 'Test001', '84004201', 4, 'testtest', 'test', 25.99, 12.00, 60, 70, 30, 'images/ProductPictures/Test001/RED small wbst.jpg'),
(12, 'Y4EaE2ENYDuMaHE', 'Test001', '84004202', 6, 'testtest', 'test', 25.99, 12.00, 60, 70, 30, 'images/ProductPictures/Test001/RED small wbst.jpg'),
(13, 'Y4EaE2ENYDuMaHE', 'Test001', '84004203', 8, 'testtest', 'test', 25.99, 12.00, 60, 70, 30, 'images/ProductPictures/Test001/RED small wbst.jpg'),
(14, 'Y4EaE2ENYDuMaHE', 'Prod 2', '49833500', 0, 'adsasdasd', '', 56.00, 22.00, 60, 70, 30, 'images/ProductPictures/Test001/Strawberry_Cheesecake_Milkshake.jpg'),
(15, 'Y4EaE2ENYDuMaHE', 'Prod 2', '49833501', 2, 'adsasdasd', '', 56.00, 22.00, 60, 70, 30, 'images/ProductPictures/Test001/Strawberry_Cheesecake_Milkshake.jpg'),
(16, 'Y4EaE2ENYDuMaHE', 'Prod 2', '49833502', 3, 'adsasdasd', '', 56.00, 22.00, 60, 70, 30, 'images/ProductPictures/Test001/Strawberry_Cheesecake_Milkshake.jpg'),
(17, 'Y4EaE2ENYDuMaHE', 'Prod 2', '49833503', 4, 'adsasdasd', '', 56.00, 22.00, 60, 70, 30, 'images/ProductPictures/Test001/Strawberry_Cheesecake_Milkshake.jpg'),
(21, 'UEOPUJUHY5AHoTO', 'test 3', '96282100', 0, 'Test Product', 'Stuff, more stuff', 5.00, 2.50, 10, 30, 70, 'images/ProductPictures/Bake Up Test/Blueberry_Donut_Milkshake.jpg'),
(22, 'UEOPUJUHY5AHoTO', 'test 3', '96282101', 8, 'Test Product', 'Stuff, more stuff', 5.00, 2.50, 10, 30, 70, 'images/ProductPictures/Bake Up Test/Blueberry_Donut_Milkshake.jpg'),
(23, 'UEOPUJUHY5AHoTO', 'test 3', '96282102', 12, 'Test Product', 'Stuff, more stuff', 5.00, 2.50, 10, 30, 70, 'images/ProductPictures/Bake Up Test/Blueberry_Donut_Milkshake.jpg'),
(24, 'UEOPUJUHY5AHoTO', 'test 3', '96282103', 16, 'Test Product', 'Stuff, more stuff', 5.00, 2.50, 10, 30, 70, 'images/ProductPictures/Bake Up Test/Blueberry_Donut_Milkshake.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
CREATE TABLE `states` (
  `stateID` varchar(2) NOT NULL,
  `stateName` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`stateID`, `stateName`) VALUES
('AK', 'Alaska'),
('AL', 'Alabama'),
('AR', 'Arkansas'),
('AZ', 'Arizona'),
('CA', 'California'),
('CO', 'Colorado'),
('CT', 'Connecticut'),
('DE', 'Delaware'),
('FL', 'Florida'),
('GA', 'Georgia'),
('HI', 'Hawaii'),
('IA', 'Iowa'),
('ID', 'Idaho'),
('IL', 'Illinois'),
('IN', 'Indiana'),
('KS', 'Kansas'),
('KY', 'Kentucky'),
('LA', 'Louisiana'),
('MA', 'Massachusetts'),
('MD', 'Maryland'),
('ME', 'Maine'),
('MI', 'Michigan'),
('MN', 'Minnesota'),
('MO', 'Missouri'),
('MS', 'Mississippi'),
('MT', 'Montana'),
('NC', 'North Carolina'),
('ND', 'North Dakota'),
('NE', 'Nebraska'),
('NH', 'New Hampshire'),
('NJ', 'New Jersey'),
('NM', 'New Mexico'),
('NV', 'Nevada'),
('NY', 'New York'),
('OH', 'Ohio'),
('OK', 'Oklahoma'),
('OR', 'Oregon'),
('PA', 'Pennsylvania'),
('RI', 'Rhode Island'),
('SC', 'South Carolina'),
('SD', 'South Dakota'),
('TN', 'Tennessee'),
('TX', 'Texas'),
('UT', 'Utah'),
('VA', 'Virginia'),
('VT', 'Vermont'),
('WA', 'Washington'),
('WI', 'Wisconsin'),
('WV', 'West Virginia'),
('WY', 'Wyoming');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `hashKey` (`hashKey`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`uID`),
  ADD UNIQUE KEY `brandID` (`brandID`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`uID`),
  ADD UNIQUE KEY `invoiceNumber` (`invoiceNumber`);

--
-- Indexes for table `loginlog`
--
ALTER TABLE `loginlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`uID`),
  ADD UNIQUE KEY `productSKU` (`productSKU`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD UNIQUE KEY `stateID` (`stateID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `uID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `uID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `loginlog`
--
ALTER TABLE `loginlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `uID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
