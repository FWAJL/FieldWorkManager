-- Update for 2015-05-04
-- > Remove the foreign on service_id in task_coc_info table

USE `baiken_fwm_1`;

ALTER TABLE `task_coc_info`
MODIFY COLUMN `service_id` int(11) NULL COMMENT 'Lab ID number',
DROP FOREIGN KEY `fk_tci_service`,
DROP INDEX `fk_tci_service`;