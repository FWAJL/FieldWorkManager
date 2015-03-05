-- Update for 05-03-15
-- > new table master_lab_analyte

USE baiken_fwm_1;

-- Table structure for table `master_lab_analyte`
CREATE TABLE `master_lab_analyte` (
    `master_lab_analyte_id` int(11) NOT NULL AUTO_INCREMENT,
    `master_lab_analyte_category_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
    `master_lab_analyte_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    UNIQUE INDEX `un_cla` (`master_lab_analyte_name` ASC),
    PRIMARY KEY (`master_lab_analyte_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;
