USE `baiken_fwm_1`;
-- Table structure for `task_check_list`
CREATE TABLE IF NOT EXISTS `task_check_list` (
  `task_check_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `task_check_list_complete` TINYINT(1) NOT NULL DEFAULT 0,
  `task_check_list_detail` varchar(150) NOT NULL,
    CONSTRAINT `fk_task_cl_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`) ON DELETE CASCADE,
    PRIMARY KEY (`task_check_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
