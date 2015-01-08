-- Update for Jan-08 2015
-- > added new tables common_field_analyte and common_lab_analyte

USE baiken_fwm_1;

-- Table structure for table `common_lab_analyte`
CREATE TABLE `common_lab_analyte` (
    `common_lab_analyte_id` int(11) NOT NULL AUTO_INCREMENT,
    `common_lab_analyte_category_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
    `common_lab_analyte_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`common_lab_analyte_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `common_field_analyte`
CREATE TABLE `common_field_analyte` (
    `common_field_analyte_id` int(11) NOT NULL AUTO_INCREMENT,
    `common_field_analyte_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`common_field_analyte_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;
