-- Update for Feb-06 2015
-- > update to tables field_analyte, lab_analyte, common_field_analyte and common_lab_analyte
-- > IMPORTANT: before updating check that there is no duplicates

USE baiken_fwm_1;

ALTER TABLE `field_analyte`
	ADD CONSTRAINT UNIQUE INDEX `un_fa` (`field_analyte_name_unit` ASC);
ALTER TABLE `lab_analyte`
	ADD CONSTRAINT UNIQUE INDEX `un_la` (`lab_analyte_name` ASC);
ALTER TABLE `common_field_analyte`
	ADD CONSTRAINT UNIQUE INDEX `un_cfa` (`common_field_analyte_name` ASC);
ALTER TABLE `common_lab_analyte`
	ADD CONSTRAINT UNIQUE INDEX `un_cla` (`common_lab_analyte_name` ASC);
