-- Update for 2015-05-19
-- > added new column to task_location -- task_location_status, 0 = not started; 1 = in process; 2 = finished

USE `baiken_fwm_1`;
ALTER TABLE `task_location` ADD COLUMN `task_location_status` TINYINT(1) DEFAULT 0 NOT NULL AFTER `task_location_id`;