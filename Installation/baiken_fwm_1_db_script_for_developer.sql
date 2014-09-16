-- FWM TEAM
--
-- History:
-- 10-09-14: add location table

DROP SCHEMA IF EXISTS `baiken_fwm_1`;

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
  `facility_lat` FLOAT(10,6) COLLATE utf8_unicode_ci NOT NULL,
  `facility_long` FLOAT(10,6) COLLATE utf8_unicode_ci NOT NULL,
  `facility_contact_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facility_contact_phone` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `facility_contact_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `facility_id_num` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facility_sector` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facility_sic` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `boundary` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`facility_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
-- Table structure for table `location`
--

CREATE TABLE `baiken_fwm_1`.`location` (
  `location_id` INT(11) COLLATE utf8_unicode_ci NOT NULL AUTO_INCREMENT,
  `location_name` VARCHAR(50) COLLATE utf8_unicode_ci NULL,
  `location_desc` VARCHAR(250) COLLATE utf8_unicode_ci NULL,
  `location_document` VARCHAR(500) COLLATE utf8_unicode_ci NULL,
  `location_lat` FLOAT(10,6) COLLATE utf8_unicode_ci NULL,
  `location_long` FLOAT(10,6) COLLATE utf8_unicode_ci NULL,
  `location_active` SMALLINT(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 1,
  `location_visible` SMALLINT(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 1,
  `project_id` INT(11) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `project`
--
ALTER TABLE `location`
  ADD UNIQUE INDEX `idlocation_UNIQUE` (`location_id` ASC);
ALTER TABLE `location`
  ADD INDEX `FK_LOC_PROJECT_idx` (`project_id` ASC);
ALTER TABLE `location`
  ADD CONSTRAINT `FK_LOC_PROJECT` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);

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
