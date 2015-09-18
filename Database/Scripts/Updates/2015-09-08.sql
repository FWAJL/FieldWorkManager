-- Update for task table
-- > Added new column task_type and task_reference_id

USE `baiken_fwm_1`;

ALTER TABLE `task`
	ADD COLUMN `task_type` VARCHAR(6) NOT NULL DEFAULT 'parent' COMMENT 'Possible values: parent, child',
	ADD COLUMN `task_reference_id` int(11) NOT NULL COMMENT 'The id of the parent task when the type is child';