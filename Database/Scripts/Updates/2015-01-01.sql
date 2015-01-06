-- Update for Jan-01 2015
-- > added new tables project_field_analyte and project_lab_analyte

-- Table structure for table `project_field_analyte`
USE baiken_fwm_1;

CREATE TABLE `project_field_analyte` (
    `project_id` int(11) NOT NULL,
    `field_analyte_id` int(11) NOT NULL,
    CONSTRAINT `fk_pfa_project` FOREIGN KEY (`project_id`)
        REFERENCES `project` (`project_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_pfa_field_analyte` FOREIGN KEY (`field_analyte_id`)
        REFERENCES `field_analyte` (`field_analyte_id`) ON DELETE CASCADE,
	UNIQUE INDEX `un_pfa_p_fa` (`project_id` ASC, `field_analyte_id` ASC)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci;

-- Table structure for table `project_lab_analyte`
CREATE TABLE `project_lab_analyte` (
    `project_id` int(11) NOT NULL,
    `lab_analyte_id` int(11) NOT NULL,
    CONSTRAINT `fk_pla_project` FOREIGN KEY (`project_id`)
        REFERENCES `project` (`project_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_pla_field_analyte` FOREIGN KEY (`lab_analyte_id`)
        REFERENCES `lab_analyte` (`lab_analyte_id`) ON DELETE CASCADE,
    UNIQUE INDEX `un_pla_l_la` (`project_id` ASC, `lab_analyte_id` ASC)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci;
