-- Update for 2015-04-15
-- > new table discussion
-- > new table discussion_content

USE baiken_fwm_1;

DROP TABLE IF EXISTS `discussion`;
CREATE TABLE `discussion` (
  `discussion_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
    CONSTRAINT `fk_discussion_task` FOREIGN KEY (`task_id`)
        REFERENCES `task` (`task_id`) ON DELETE CASCADE,
    PRIMARY KEY (`discussion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `discussion_content`;
CREATE TABLE `discussion_content` (
  `discussion_content_id` int(11) NOT NULL AUTO_INCREMENT,
  `discussion_id` int(11) NOT NULL,
  `discussion_content_category_type` varchar(25) NOT NULL COMMENT 'Values: pm_id or technician_id or service_id or client_id',
  `discussion_content_category_value` int(11) NOT NULL COMMENT 'Integer value of the set discussion_content_category_type',
  `discussion_content_value` varchar(500) NOT NULL COMMENT 'The message sent',
    CONSTRAINT `fk_dc_discussion` FOREIGN KEY (`discussion_id`)
        REFERENCES `discussion` (`discussion_id`) ON DELETE CASCADE,
    PRIMARY KEY (`discussion_content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
