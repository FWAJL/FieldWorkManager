-- Update for field_analyte_location table
-- > Added new column field_analyte_location_id and its constraints

USE `baiken_fwm_1`;

ALTER TABLE `field_analyte_location`
	ADD COLUMN `field_analyte_location_id` INT(11) NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT PRIMARY KEY (`field_analyte_location_id`),
    ADD CONSTRAINT UNIQUE INDEX `un_fa` (`field_analyte_location_id` ASC);