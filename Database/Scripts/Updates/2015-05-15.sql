-- Update for 2015-05-15
-- > added new columns to lab_analyte and filed_analyte

USE `baiken_fwm_1`;

ALTER TABLE `lab_analyte` ADD `analyte_abbrev` VARCHAR( 13 ) NULL DEFAULT NULL AFTER `lab_analyte_name`;

ALTER TABLE `field_analyte` ADD `analyte_abbrev` VARCHAR( 13 ) NULL DEFAULT NULL AFTER `field_analyte_name_unit`;