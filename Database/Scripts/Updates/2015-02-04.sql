-- Update for Feb-03 2015
-- > update to table document

USE baiken_fwm_1;

ALTER TABLE `document`
	CHANGE COLUMN `document_size` `document_size` FLOAT(6,2) NOT NULL;
