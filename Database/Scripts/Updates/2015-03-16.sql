-- Update for 2015-03-16
-- > Modifications for task trigger fields

USE `baiken_fwm_1`;

ALTER TABLE `task`
CHANGE `task_trigger_cal` `task_trigger_cal` TINYINT(1) DEFAULT 0 NULL,
CHANGE `task_trigger_pm` `task_trigger_pm` TINYINT(1) DEFAULT 0 NULL,
ADD COLUMN `task_trigger_cal_value` VARCHAR(50) NULL AFTER `task_trigger_cal`,
CHANGE `task_trigger_ext` `task_trigger_ext` TINYINT(1) DEFAULT 0 NULL;