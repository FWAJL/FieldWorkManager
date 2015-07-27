-- Update for field_analyte_location table
-- > Added new column field_analyte_location_date

USE `baiken_fwm_1`;

ALTER TABLE `field_analyte_location`
ADD COLUMN `field_analyte_location_date` VARCHAR(50) NULL;