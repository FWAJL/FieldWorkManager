-- Update for 2015-05-13
-- > added new columns to task table

USE `baiken_fwm_1`;

ALTER TABLE `task`
ADD COLUMN `task_start_date` varchar(50) NULL,
ADD COLUMN `task_activated` tinyint(1) NOT NULL DEFAULT '0';