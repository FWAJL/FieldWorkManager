-- Update for 2015-03-26 
-- > new table task_note

USE baiken_fwm_1;

DROP TABLE IF EXISTS `task_note`;
CREATE TABLE `task_note` (
  `task_note_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `task_note_category_type` varchar(25) NOT NULL COMMENT 'Possible values: pm_id, technician_id',
  `task_note_category_value` int(11) NOT NULL COMMENT 'Represents the value of the object property set in the task_note_category_type',
  `task_note_value`varchar(500) NULL COMMENT 'The value of the note typed by the user',
    CONSTRAINT `fk_tn_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`) ON DELETE CASCADE,
    PRIMARY KEY (`task_note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;