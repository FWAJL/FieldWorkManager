-- Update for 2015-04-01
-- > Update to the table task_location

USE baiken_fwm_1;

ALTER TABLE `task_location`
	ADD COLUMN `task_location_id` INT(11) NOT NULL AUTO_INCREMENT,
	ADD PRIMARY KEY (`task_location_id`);