-- Update for `project` table, default project
-- >

USE baiken_fwm_1;
ALTER TABLE `project` ADD COLUMN `project_is_default` TINYINT(1) DEFAULT 0 NULL AFTER `pm_id`;