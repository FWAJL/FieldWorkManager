-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: 198.23.51.222:3306
-- Generation Time: Jul 29, 2014 at 05:10 AM
-- Server version: 5.5.38
-- PHP Version: 5.4.4-14+deb7u12

DROP SCHEMA `baiken_fwm_1`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `baiken_fwm_1`
--
 CREATE DATABASE IF NOT EXISTS `baiken_fwm_1` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
 USE `baiken_fwm_1`;

-- --------------------------------------------------------

--
-- Table structure for table `facility`
--

CREATE TABLE IF NOT EXISTS `facility` (
  `facility_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `facility_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `facility_address` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `facility_lat` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `facility_long` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `facility_contact_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facility_contact_phone` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `facility_contact_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `facility_id_num` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facility_sector` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facility_sic` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `boundary` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`facility_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `facility`
--

INSERT INTO `facility` (`facility_id`, `project_id`, `facility_name`, `facility_address`, `facility_lat`, `facility_long`, `facility_contact_name`, `facility_contact_phone`, `facility_contact_email`, `facility_id_num`, `facility_sector`, `facility_sic`, `boundary`) VALUES
(3, 14, 'Project FWA 240714 facility', 'address 240714', '', '', '', '', '', '', '', '', ''),
(4, 15, 'The Fantastic Facility', 'Mesa Rd,\n85201 Tempe, AZ\n', '', '', '', '', '', '', '', '', ''),
(5, 18, 'Facility of 290714', 'New address:\n1 Chemin d''Andéol\n26600 Tain l''Hermitage\nFrance', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `project_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_desc` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` smallint(6) DEFAULT NULL,
  `visible` smallint(6) DEFAULT NULL,
  `pm_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`),
  KEY `pm_id` (`pm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`project_id`, `project_name`, `project_number`, `project_desc`, `active`, `visible`, `pm_id`) VALUES
(14, 'Project FWA 240714 edit', 'p24071455', 'desc 240714', 0, 0, 1),
(15, 'Project FWA 2407-2', 'p2407-2', 'desc 2407-2', 0, 0, 1),
(18, 'Project add 290714 (edited)', 'p290714', 'Description', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `project_manager`
--

CREATE TABLE IF NOT EXISTS `project_manager` (
  `pm_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `hint` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `pm_comp_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pm_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pm_address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pm_phone` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `pm_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`pm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `project_manager`
--

INSERT INTO `project_manager` (`pm_id`, `username`, `password`, `hint`, `pm_comp_name`, `pm_name`, `pm_address`, `pm_phone`, `pm_email`) VALUES
(1, 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3g496lJL683', 'hint', 'comp name', 'John', 'Doe', '1234567890', 'test@fwa.net');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `facility`
--
ALTER TABLE `facility`
  ADD CONSTRAINT `facility_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`pm_id`) REFERENCES `project_manager` (`pm_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
