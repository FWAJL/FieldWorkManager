-- Update for 2015-04-17
-- > table discussion_content updates
-- > new table discussion_person

USE `baiken_fwm_1`;

DROP TABLE IF EXISTS `discussion_person`;
CREATE TABLE `discussion_person` (
  	`discussion_person_id` int(11) NOT NULL AUTO_INCREMENT,
  	`discussion_id` int(11) NOT NULL,
  	`user_id` int(11) NOT NULL,
  	`discussion_person_is_author` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Set to 1 when the person is the one who created the discussion',
   	CONSTRAINT `fk_dp_discussion` FOREIGN KEY (`discussion_id`)
        REFERENCES `discussion` (`discussion_id`) ON DELETE CASCADE,
   	CONSTRAINT `fk_dp_user` FOREIGN KEY (`user_id`)
        REFERENCES `user` (`user_id`) ON DELETE CASCADE,
    PRIMARY KEY (`discussion_person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

ALTER TABLE `discussion_content`
 DROP COLUMN `discussion_content_category_type`, 
 DROP COLUMN `discussion_content_category_value`,
 DROP FOREIGN KEY `fk_dc_discussion`,
 DROP COLUMN `discussion_id`,
 DROP INDEX `fk_dc_discussion`,
 CHANGE `discussion_content_value` `discussion_content_message` varchar(500) NOT NULL COMMENT 'The message sent',
 ADD COLUMN `discussion_person_id` int(11) NOT NULL,
 ADD CONSTRAINT `fk_dc_discussion_person` FOREIGN KEY (`discussion_person_id`)
        REFERENCES `discussion_person` (`discussion_person_id`) ON DELETE CASCADE;
