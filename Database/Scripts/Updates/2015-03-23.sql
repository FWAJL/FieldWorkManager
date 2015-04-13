-- Update for 2015-03-23
-- > New table lab_analyte_location

USE baiken_fwm_1;

DROP TABLE IF EXISTS `lab_analyte_location`;
CREATE TABLE `lab_analyte_location` (
  `task_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `lab_analyte_id` int(11) NOT NULL,
    CONSTRAINT `fk_lal_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_lal_location` FOREIGN KEY (`location_id`)
        REFERENCES `location` (`location_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_lal_lab_analyte` FOREIGN KEY (`lab_analyte_id`)
        REFERENCES `lab_analyte` (`lab_analyte_id`) ON DELETE CASCADE,
    UNIQUE INDEX `un_lal` (`task_id` ASC, `location_id` ASC, `lab_analyte_id`ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;