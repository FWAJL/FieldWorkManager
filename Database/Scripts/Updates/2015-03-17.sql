-- Update for 2015-03-17
-- > Modifications for task tab fields

USE `baiken_fwm_1`;


ALTER TABLE `task`
ADD COLUMN `task_req_form` TINYINT(1) DEFAULT 0 NULL,
ADD COLUMN `task_req_field_analyte` TINYINT(1) DEFAULT 0 NULL,
ADD COLUMN `task_req_lab_analyte` TINYINT(1) DEFAULT 0 NULL,
ADD COLUMN `task_req_service` TINYINT(1) DEFAULT 0 NULL;