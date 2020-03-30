-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 30, 2019 at 08:19 AM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `map`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('Eleutherios Mavris', '1111'),
('Charikeia Pylarinou', '2222');

-- --------------------------------------------------------

--
-- Table structure for table `coordinates`
--

DROP TABLE IF EXISTS `coordinates`;
CREATE TABLE IF NOT EXISTS `coordinates` (
  `coord_id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `coord_x` double(17,15) DEFAULT NULL,
  `coord_y` double(17,15) DEFAULT NULL,
  PRIMARY KEY (`coord_id`),
  KEY `c_constraint` (`p_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33740 DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `hourly_parking_demand`
--

DROP TABLE IF EXISTS `hourly_parking_demand`;
CREATE TABLE IF NOT EXISTS `hourly_parking_demand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hour` varchar(11) NOT NULL,
  `kind_id` int(11) NOT NULL,
  `demand` float(3,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `kind_const` (`kind_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hourly_parking_demand`
--

INSERT INTO `hourly_parking_demand` (`id`, `hour`, `kind_id`, `demand`) VALUES
(1, '00', 1, 0.75),
(2, '00', 2, 0.69),
(3, '00', 3, 0.18),
(4, '01', 1, 0.55),
(5, '01', 2, 0.71),
(6, '01', 3, 0.17),
(7, '02', 1, 0.46),
(8, '02', 2, 0.73),
(9, '02', 3, 0.21),
(10, '03', 1, 0.19),
(11, '03', 2, 0.68),
(12, '03', 3, 0.25),
(13, '04', 1, 0.20),
(14, '04', 2, 0.69),
(15, '04', 3, 0.22),
(16, '05', 1, 0.20),
(17, '05', 2, 0.70),
(18, '05', 3, 0.17),
(19, '06', 1, 0.39),
(20, '06', 2, 0.67),
(21, '06', 3, 0.16),
(22, '07', 1, 0.55),
(23, '07', 2, 0.55),
(24, '07', 3, 0.39),
(25, '08', 1, 0.67),
(26, '08', 2, 0.49),
(27, '08', 3, 0.54),
(28, '09', 1, 0.80),
(29, '09', 2, 0.43),
(30, '09', 3, 0.77),
(31, '10', 1, 0.95),
(32, '10', 2, 0.34),
(33, '10', 3, 0.78),
(34, '11', 1, 0.90),
(35, '11', 2, 0.45),
(36, '11', 3, 0.83),
(37, '12', 1, 0.95),
(38, '12', 2, 0.48),
(39, '12', 3, 0.78),
(40, '13', 1, 0.90);

-- --------------------------------------------------------

--
-- Table structure for table `polygon`
--

DROP TABLE IF EXISTS `polygon`;
CREATE TABLE IF NOT EXISTS `polygon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `population` int(11) DEFAULT NULL,
  `center_x` double(17,15) DEFAULT NULL,
  `center_y` double(17,15) DEFAULT NULL,
  `total_spots` int(11) DEFAULT NULL,
  `available_spots` int(11) DEFAULT NULL,
  `kind_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `kind_constraint` (`kind_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3165 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `polygon_kind`
--

DROP TABLE IF EXISTS `polygon_kind`;
CREATE TABLE IF NOT EXISTS `polygon_kind` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `polygon_kind`
--

INSERT INTO `polygon_kind` (`id`, `descr`) VALUES
(1, 'Centro'),
(2, 'Lugar que vive gente'),
(3, 'Lugar de estable demanda');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coordinates`
--
ALTER TABLE `coordinates`
  ADD CONSTRAINT `c_constraint` FOREIGN KEY (`p_id`) REFERENCES `polygon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hourly_parking_demand`
--
ALTER TABLE `hourly_parking_demand`
  ADD CONSTRAINT `kind_const` FOREIGN KEY (`kind_id`) REFERENCES `polygon_kind` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `polygon`
--
ALTER TABLE `polygon`
  ADD CONSTRAINT `kind_constraint` FOREIGN KEY (`kind_id`) REFERENCES `polygon_kind` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
