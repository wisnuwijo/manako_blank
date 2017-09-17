-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 16, 2015 at 05:39 
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gt_manako32s`
--

-- --------------------------------------------------------

--
-- Table structure for table `project_hotel`
--

CREATE TABLE IF NOT EXISTS `project_hotel` (
  `hotelId` bigint(20) unsigned NOT NULL,
  `hotelNama` varchar(200) NOT NULL,
  `hotelAlamat` varchar(255) NOT NULL,
  `hotelKodeProv` bigint(20) NOT NULL,
  `hotelPhone` varchar(255) NOT NULL,
  `hotelHarga` varchar(100) NOT NULL,
  `hotelFasilitas` varchar(255) NOT NULL,
  `hotelKeterangan` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `project_hotel`
--

INSERT INTO `project_hotel` (`hotelId`, `hotelNama`, `hotelAlamat`, `hotelKodeProv`, `hotelPhone`, `hotelHarga`, `hotelFasilitas`, `hotelKeterangan`) VALUES
(1, 'Hotelku 1', 'sleman', 340, '08181818181', '200000', 'ac, tv, kamar mandi', 'hotelku bla bla bla'),
(2, 'Hotelku 2', 'yogyakarta', 340, '123123123', '123000000', 'ac, tv', 'hotelku 2 bla bla bla'),
(4, 'hotel 6', 'aceh', 110, '123123', '20000', 'aklshd', 'lkakndlwaw'),
(9, 'ini hotel SUMBAR', 'sumbar', 130, '1233123', '12312313', 'ini fasilitas', 'ini keterangan'),
(17, 'asdasd', 'dasd', 100, '123', '3123', 'asdasd', 'asdasd'),
(20, 'qwe', 'qwe', 110, '123123', '200000', 'asdasd', 'asdasd'),
(22, 'Hotel Baru', 'lalala', 120, '0810181', '100000', 'fasil', 'lahsdlask'),
(32, 'Hotel Baru', 'Jalan Mangkubumi', 340, '0812812718', '1000000', 'lengkap', 'lengkap hjkhjfgty'),
(33, 'Hotel Soechi', 'sumut', 120, '021678909', '528', 'tgg', 'hyhyyj'),
(34, 'testing hotel baru', 'sleman', 340, '091823', '1000000', 'lengkap', 'lengkap'),
(35, 'mamaaaa', 'aceeeh', 110, '12012101212', '1000000', 'LALALAL', 'LALALALA');

-- --------------------------------------------------------

--
-- Table structure for table `project_hotel_client`
--

CREATE TABLE IF NOT EXISTS `project_hotel_client` (
  `hotelClientHotelId` bigint(20) NOT NULL COMMENT 'Ref to project_hotel.hotelId',
  `hotelClientClientId` bigint(20) NOT NULL COMMENT 'ref to project_contact.contactId'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `project_hotel_client`
--

INSERT INTO `project_hotel_client` (`hotelClientHotelId`, `hotelClientClientId`) VALUES
(4, 4),
(4, 5),
(4, 6),
(9, 26),
(9, 39),
(20, 3),
(20, 28),
(22, 6),
(22, 9),
(1, 1),
(1, 2),
(1, 5),
(1, 39),
(2, 1),
(2, 4),
(2, 5),
(32, 2),
(32, 34),
(32, 36),
(33, 1),
(35, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project_hotel`
--
ALTER TABLE `project_hotel`
  ADD PRIMARY KEY (`hotelId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project_hotel`
--
ALTER TABLE `project_hotel`
  MODIFY `hotelId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
