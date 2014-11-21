-- FWM TEAM
--
-- History:
-- 10-09-14: added location table
-- 03-10-14: added technician and client tables
-- 19-11-14: tidy up of database 

DROP SCHEMA IF EXISTS `baiken_fwm_1`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- Database: `baiken_fwm_1`
CREATE DATABASE IF NOT EXISTS `baiken_fwm_1` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `baiken_fwm_1`;

-- Table structure for table `project_manager`
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
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=2;

-- Table structure for table `project`
CREATE TABLE IF NOT EXISTS `project` (
    `project_id` int(11) NOT NULL AUTO_INCREMENT,
    `project_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
    `project_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `project_desc` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
    `project_active` tinyint(1) DEFAULT NULL,
    `project_visible` tinyint(1) DEFAULT NULL,
    `pm_id` int(11) NOT NULL COMMENT 'Foreign key => project_manager',
    PRIMARY KEY (`project_id`),
    CONSTRAINT `fk_project_pm` FOREIGN KEY (`pm_id`)
        REFERENCES `project_manager` (`pm_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `technician`
CREATE TABLE `baiken_fwm_1`.`technician` (
    `technician_id` INT(11) NOT NULL AUTO_INCREMENT,
    `technician_name` VARCHAR(50) COLLATE utf8_unicode_ci NULL,
    `technician_phone` VARCHAR(12) COLLATE utf8_unicode_ci NULL,
    `technician_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
    `technician_document` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
    `technician_active` smallint(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 1,
    `pm_id` int(11) NOT NULL COMMENT 'Foreign key => project_manager',
    PRIMARY KEY (`technician_id`),
    CONSTRAINT `fk_tech_pm` FOREIGN KEY (`pm_id`)
        REFERENCES `project_manager` (`pm_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `client`
CREATE TABLE `baiken_fwm_1`.`client` (
    `client_id` INT(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) NOT NULL COMMENT 'Foreign key => project',
    `client_company_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
    `client_address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
    `client_contact_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
    `client_contact_phone` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
    `client_contact_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`client_id`),
    CONSTRAINT `fk_client_project` FOREIGN KEY (`project_id`)
        REFERENCES `project` (`project_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `facility`
CREATE TABLE IF NOT EXISTS `facility` (
    `facility_id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) NOT NULL COMMENT 'Foreign key => project',
    `facility_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
    `facility_address` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
    `facility_lat` FLOAT(10 , 6 ) COLLATE utf8_unicode_ci NOT NULL,
    `facility_long` FLOAT(10 , 6 ) COLLATE utf8_unicode_ci NOT NULL,
    `facility_contact_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
    `facility_contact_phone` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
    `facility_contact_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `facility_id_num` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
    `facility_sector` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
    `facility_sic` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
    `boundary` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`facility_id`),
    CONSTRAINT `fk_facility_project` FOREIGN KEY (`project_id`)
        REFERENCES `project` (`project_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `location`
CREATE TABLE `baiken_fwm_1`.`location` (
    `location_id` INT(11) COLLATE utf8_unicode_ci NOT NULL AUTO_INCREMENT,
    `location_name` VARCHAR(50) COLLATE utf8_unicode_ci NULL,
    `location_desc` VARCHAR(250) COLLATE utf8_unicode_ci NULL,
    `location_document` VARCHAR(500) COLLATE utf8_unicode_ci NULL,
    `location_lat` FLOAT(10 , 6 ) COLLATE utf8_unicode_ci NULL,
    `location_long` FLOAT(10 , 6 ) COLLATE utf8_unicode_ci NULL,
    `location_active` tinyint(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 1,
    `location_visible` tinyint(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 1,
    `project_id` INT(11) NOT NULL COMMENT 'Foreign key => project',
    PRIMARY KEY (`location_id`),
    CONSTRAINT `fk_loc_project` FOREIGN KEY (`project_id`)
        REFERENCES `project` (`project_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `task`
CREATE TABLE `task` (
    `task_id` int(11) NOT NULL AUTO_INCREMENT,
    `project_id` int(11) NOT NULL COMMENT 'Foreign key => project',
    `task_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `task_deadline` date NOT NULL,
    `task_instructions` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
    `task_trigger_cal` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
    `task_trigger_pm` tinyint(1) DEFAULT NULL,
    `task_trigger_ext` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
    `active` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`task_id`),
    CONSTRAINT `fk_task_project` FOREIGN KEY (`project_id`)
        REFERENCES `project` (`project_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `service`
CREATE TABLE `service` (
    `service_id` int(11) NOT NULL AUTO_INCREMENT,
    `pm_id` int(11) NOT NULL COMMENT 'Foreign key => project_manager',
    `service_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'contractor or equipment',
    `service_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
    `service_url` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `service_address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
    `service_contact_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
    `service_contact_phone` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
    `service_contact_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
    `service_active` tinyint(1) NOT NULL,
    PRIMARY KEY (`service_id`),
    CONSTRAINT `fk_service_pm` FOREIGN KEY (`pm_id`)
        REFERENCES `project_manager` (`pm_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `field_analyte`
CREATE TABLE `field_analyte` (
    `field_analyte_id` int(11) NOT NULL AUTO_INCREMENT,
    `field_analyte_name_unit` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
    `pm_id` int(11) NOT NULL COMMENT 'Foreign key => project_manager',
    PRIMARY KEY (`field_analyte_id`),
    CONSTRAINT `fk_field_analyte_pm` FOREIGN KEY (`pm_id`)
        REFERENCES `project_manager` (`pm_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `field_sample_matrix`
CREATE TABLE `field_sample_matrix` (
    `task_id` int(11) NOT NULL,
    `field_analyte_id` int(11) NOT NULL,
    `location_id` int(11) NOT NULL,
    CONSTRAINT `fk_fsm_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`),
    CONSTRAINT `fk_fsm_field_analyte` FOREIGN KEY (`field_analyte_id`)
        REFERENCES `field_analyte` (`field_analyte_id`),
    CONSTRAINT `fk_fsm_location` FOREIGN KEY (`location_id`)
        REFERENCES `location` (`location_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci;

-- Table structure for table `inspection_question`
CREATE TABLE `inspection_question` (
    `inspection_question_id` int(11) NOT NULL AUTO_INCREMENT,
    `inspection_question_form_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `inspection_question_data` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
    `pm_id` int(11) NOT NULL COMMENT 'Foreign key => project_manager',
    PRIMARY KEY (`inspection_question_id`),
    CONSTRAINT `fk_inspection_question_pm` FOREIGN KEY (`pm_id`)
        REFERENCES `project_manager` (`pm_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `lab_analyte`
CREATE TABLE `lab_analyte` (
    `lab_analyte_id` int(11) NOT NULL AUTO_INCREMENT,
    `lab_analyte_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `pm_id` int(11) NOT NULL COMMENT 'Foreign key => project_manager',
    PRIMARY KEY (`lab_analyte_id`),
    CONSTRAINT `fk_lab_analyte_pm` FOREIGN KEY (`pm_id`)
        REFERENCES `project_manager` (`pm_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `lab_sample_matrix`
CREATE TABLE `lab_sample_matrix` (
    `task_id` int(11) NOT NULL,
    `lab_analyte_id` int(11) NOT NULL,
    `location_id` int(11) NOT NULL,
    CONSTRAINT `fk_lsm_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`),
    CONSTRAINT `fk_lsm_lab_analyte` FOREIGN KEY (`lab_analyte_id`)
        REFERENCES `lab_analyte` (`lab_analyte_id`),
    CONSTRAINT `fk_lsm_location` FOREIGN KEY (`location_id`)
        REFERENCES `location` (`location_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `task_coc_info`
CREATE TABLE `task_coc_info` (
    `task_coc_id` int(11) NOT NULL AUTO_INCREMENT,
    `task_id` int(11) NOT NULL,
    `service_id` int(11) NOT NULL COMMENT 'Lab ID number',
    `po_number` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
    `lab_instructions` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
    `lab_sample_type` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
    `lab_sample_tat` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
    `project_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Is project.project_number by DEFAULT but deverges from it if the PM wants to change it',
    `results_to_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Is pm.pm_name by DEFAULT but can changed',
    `results_to_company` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Is pm.pm_comp_name by DEFAULT but can changed',
    `results_to_address` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Is pm.pm_address by DEFAULT but can changed',
    `results_to_phone` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Is pm.pm_phone by DEFAULT but can changed',
    `results_to_email` varchar(35) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Is pm.pm_email by DEFAULT but can changed',
    PRIMARY KEY (`task_coc_id`),
    CONSTRAINT `fk_tci_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`),
    CONSTRAINT `fk_tci_service` FOREIGN KEY (`service_id`)
        REFERENCES `service` (`service_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `task_field_analytes`
CREATE TABLE `task_field_analytes` (
    `task_id` int(11) NOT NULL,
    `field_analyte_id` int(11) NOT NULL,
    CONSTRAINT `fk_tfa_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`),
    CONSTRAINT `fk_tfa_field_analyte` FOREIGN KEY (`field_analyte_id`)
        REFERENCES `field_analyte` (`field_analyte_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci;

-- Table structure for table `task_field_data_locations`
CREATE TABLE `task_field_data_locations` (
    `task_id` int(11) NOT NULL,
    `location_id` int(11) NOT NULL,
    CONSTRAINT `fk_tfdl_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`),
    CONSTRAINT `fk_tfdl_location` FOREIGN KEY (`location_id`)
        REFERENCES `location` (`location_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci;

-- Table structure for table `task_insp_form`
CREATE TABLE `task_insp_form` (
    `task_id` int(11) NOT NULL,
    `inspection_question_id` int(11) NOT NULL,
    CONSTRAINT `fk_tif_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`),
    CONSTRAINT `fk_tif_inspection` FOREIGN KEY (`inspection_question_id`)
        REFERENCES `inspection_question` (`inspection_question_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci;

-- Table structure for table `task_lab_analytes`
CREATE TABLE `task_lab_analytes` (
    `task_id` int(11) NOT NULL,
    `lab_analyte_id` int(11) NOT NULL,
    CONSTRAINT `fk_tla_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`),
    CONSTRAINT `fk_tla_lab_analyte` FOREIGN KEY (`lab_analyte_id`)
        REFERENCES `lab_analyte` (`lab_analyte_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci;

-- Table structure for table `task_lab_data_locations`
CREATE TABLE `task_lab_data_locations` (
    `task_id` int(11) NOT NULL,
    `location_id` int(11) NOT NULL,
    CONSTRAINT `fk_tldl_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`),
    CONSTRAINT `fk_tldl_location` FOREIGN KEY (`location_id`)
        REFERENCES `location` (`location_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci;

-- Table structure for table `task_locations`
CREATE TABLE `task_locations` (
    `task_id` int(11) NOT NULL,
    `location_id` int(11) NOT NULL,
    CONSTRAINT `fk_tl_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`),
    CONSTRAINT `fk_tl_location` FOREIGN KEY (`location_id`)
        REFERENCES `location` (`location_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci;

-- Table structure for table `task_services`
CREATE TABLE `task_services` (
    `task_id` int(11) NOT NULL,
    `service_id` int(11) NOT NULL,
    CONSTRAINT `fk_ts_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`),
    CONSTRAINT `fk_ts_service` FOREIGN KEY (`service_id`)
        REFERENCES `service` (`service_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci;

-- Table structure for table `task_technicians`
CREATE TABLE `task_technicians` (
    `task_id` int(11) NOT NULL,
    `technician_id` int(11) NOT NULL,
    `is_lead_tech` tinyint(1) DEFAULT 0,
    CONSTRAINT `fk_tt_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`),
    CONSTRAINT `fk_tt_technician` FOREIGN KEY (`technician_id`)
        REFERENCES `technician` (`technician_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci;

-- Dumping data for table `project_manager`
INSERT INTO `project_manager` (`pm_id`, `username`, `password`, `hint`, `pm_comp_name`, `pm_name`, `pm_address`, `pm_phone`, `pm_email`) VALUES
(1, 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3g496lJL683', 'hint', 'comp name', 'John', 'Doe', '1234567890', 'test@fwa.net');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
