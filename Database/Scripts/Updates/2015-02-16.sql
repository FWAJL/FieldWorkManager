-- Update for field_analyte and lab_analyte
-- > Modified the unique constraint to take the pm_id into account

USE baiken_fwm_1;

ALTER TABLE `field_analyte`
	DROP INDEX `un_fa`,
	ADD CONSTRAINT UNIQUE INDEX `un_fa` (`pm_id` ASC, `field_analyte_name_unit` ASC);

ALTER TABLE `lab_analyte`
	DROP INDEX `un_la`,
	ADD CONSTRAINT UNIQUE INDEX `un_la` (`pm_id` ASC, `lab_analyte_name` ASC);