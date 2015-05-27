-- Update for 2015-05-22
-- > added new column to task_note -- task_note_time current timestamp

USE `baiken_fwm_1`;

ALTER TABLE `task_note` ADD COLUMN `task_note_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL AFTER `task_note_value`;