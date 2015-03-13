-- Update for Mar-13, 2015
-- > New column for project_manager table

USE baiken_fwm_1;

ALTER TABLE `project_manager`
	ADD COLUMN `project_manager_is_logged` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Specifies if a given user is logged in'
;