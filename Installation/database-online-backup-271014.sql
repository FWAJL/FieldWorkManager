-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: 10.123.0.99:3306
-- Generation Time: Oct 27, 2014 at 06:03 AM
-- Server version: 5.5.38
-- PHP Version: 5.4.4-14+deb7u14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `baiken_fwm_1`
--
-- CREATE DATABASE IF NOT EXISTS `baiken_fwm_1` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
-- USE `baiken_fwm_1`;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `client_company_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_contact_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_contact_phone` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_contact_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `facility`
--

CREATE TABLE IF NOT EXISTS `facility` (
  `facility_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `facility_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `facility_address` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `facility_lat` float(10,6) NOT NULL,
  `facility_long` float(10,6) NOT NULL,
  `facility_contact_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facility_contact_phone` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `facility_contact_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `facility_id_num` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facility_sector` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facility_sic` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `boundary` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`facility_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `facility`
--

INSERT INTO `facility` (`facility_id`, `project_id`, `facility_name`, `facility_address`, `facility_lat`, `facility_long`, `facility_contact_name`, `facility_contact_phone`, `facility_contact_email`, `facility_id_num`, `facility_sector`, `facility_sic`, `boundary`) VALUES
(1, 1, 'Facility 25', '25 St of Somewhere\nCity\nCountry', 0.000000, 0.000000, '', '', '', '', '', '', ''),
(2, 2, 'Facility 42', '42 St of Somewhere\nCity\nCountry', 0.000000, 0.000000, '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `field_analytes`
--

CREATE TABLE IF NOT EXISTS `field_analytes` (
  `field_analyte_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_analyte_name_unit` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`field_analyte_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `field_sample_matrix`
--

CREATE TABLE IF NOT EXISTS `field_sample_matrix` (
  `task_id` int(11) NOT NULL,
  `field_analyte_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inspection_questions`
--

CREATE TABLE IF NOT EXISTS `inspection_questions` (
  `task_insp_form_id` int(11) NOT NULL,
  `inspection_form_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `insp_quest_id` int(11) NOT NULL AUTO_INCREMENT,
  `insp_question` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`insp_quest_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lab_analytes`
--

CREATE TABLE IF NOT EXISTS `lab_analytes` (
  `lab_analyte_id` int(11) NOT NULL AUTO_INCREMENT,
  `lab_analyte_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`lab_analyte_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lab_sample_matrix`
--

CREATE TABLE IF NOT EXISTS `lab_sample_matrix` (
  `task_id` int(11) NOT NULL,
  `lab_analyte_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location_desc` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location_document` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location_lat` float(10,6) DEFAULT NULL,
  `location_long` float(10,6) DEFAULT NULL,
  `location_active` smallint(6) NOT NULL DEFAULT '1',
  `location_visible` smallint(6) NOT NULL DEFAULT '1',
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`location_id`),
  UNIQUE KEY `idlocation_UNIQUE` (`location_id`),
  KEY `FK_LOC_PROJECT_idx` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`location_id`, `location_name`, `location_desc`, `location_document`, `location_lat`, `location_long`, `location_active`, `location_visible`, `project_id`) VALUES
(1, '25-1a', '', '', 0.000000, 0.000000, 1, 0, 1),
(2, '25-2a', '', '', 0.000000, 0.000000, 1, 0, 1),
(3, '25-3', '', '', 0.000000, 0.000000, 1, 0, 1),
(4, '42-1a', '', '', 0.000000, 0.000000, 1, 0, 2),
(5, '42-2', '', '', 0.000000, 0.000000, 0, 0, 2),
(6, '42-3', '', '', 0.000000, 0.000000, 1, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `project_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_desc` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_active` smallint(6) DEFAULT NULL,
  `project_visible` smallint(6) DEFAULT NULL,
  `pm_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`),
  KEY `pm_id` (`pm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`project_id`, `project_name`, `project_number`, `project_desc`, `project_active`, `project_visible`, `pm_id`) VALUES
(1, 'Project 25c-abc', 'n-25a', 'Description 25a', 1, 0, 1),
(2, 'Project 42a', 'n-42', 'Description 42', 1, 0, 1);

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

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE IF NOT EXISTS `resources` (
  `resource_id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'contractor or equipment',
  `resource_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `resource_url` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `resource_address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `resource_contact_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `resource_contact_phone` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `resource_contact_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `resource_active` tinyint(1) NOT NULL,
  `pm_id` int(11) NOT NULL,
  PRIMARY KEY (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `task_coc_info`
--

CREATE TABLE IF NOT EXISTS `task_coc_info` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL COMMENT 'Lab ID number',
  `po_number` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `lab_instructions` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `lab_sample_type` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `lab_sample_tat` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `project_id_num` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `results_to_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `results_to_company` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `results_to_address` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `results_to_phone` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `results_to_email` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `task_field_analytes`
--

CREATE TABLE IF NOT EXISTS `task_field_analytes` (
  `task_id` int(11) NOT NULL,
  `field_analyte_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_field_data_locations`
--

CREATE TABLE IF NOT EXISTS `task_field_data_locations` (
  `task_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_info`
--

CREATE TABLE IF NOT EXISTS `task_info` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `task_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `task_deadline` date NOT NULL,
  `task_instructions` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `task_trigger_cal` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `task_trigger_pm` tinyint(1) DEFAULT NULL,
  `task_trigger_ext` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`task_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `task_insp_form`
--

CREATE TABLE IF NOT EXISTS `task_insp_form` (
  `task_insp_form_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  PRIMARY KEY (`task_insp_form_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `task_lab_analytes`
--

CREATE TABLE IF NOT EXISTS `task_lab_analytes` (
  `task_id` int(11) NOT NULL,
  `lab_analyte_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_lab_data_locations`
--

CREATE TABLE IF NOT EXISTS `task_lab_data_locations` (
  `task_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_locations`
--

CREATE TABLE IF NOT EXISTS `task_locations` (
  `task_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_resources`
--

CREATE TABLE IF NOT EXISTS `task_resources` (
  `task_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_technicians`
--

CREATE TABLE IF NOT EXISTS `task_technicians` (
  `task_id` int(11) NOT NULL,
  `technician_id` int(11) NOT NULL,
  `lead_tech` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `technician`
--

CREATE TABLE IF NOT EXISTS `technician` (
  `technician_id` int(11) NOT NULL AUTO_INCREMENT,
  `technician_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `technician_phone` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `technician_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `technician_document` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `technician_active` smallint(6) NOT NULL DEFAULT '1',
  `pm_id` int(11) NOT NULL,
  PRIMARY KEY (`technician_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `facility`
--
ALTER TABLE `facility`
  ADD CONSTRAINT `facility_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `FK_LOC_PROJECT` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`pm_id`) REFERENCES `project_manager` (`pm_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
