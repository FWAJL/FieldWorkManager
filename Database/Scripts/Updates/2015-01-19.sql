-- Update for Jan-19 2015
-- > removed table inspection_question

USE baiken_fwm_1;

DROP TABLE IF EXISTS `inspection_question`;
-- -- Table structure for table `inspection_question`
-- CREATE TABLE `inspection_question` (
--     `inspection_question_id` int(11) NOT NULL AUTO_INCREMENT,
--     `inspection_question_form_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
--     `inspection_question_data` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
--     `pm_id` int(11) NOT NULL COMMENT 'Foreign key => project_manager',
--     PRIMARY KEY (`inspection_question_id`),
--     CONSTRAINT `fk_inspection_question_pm` FOREIGN KEY (`pm_id`)
--         REFERENCES `project_manager` (`pm_id`) ON DELETE CASCADE
-- )  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;
