-- Update for Feb-03 2015
-- > update to table document

USE baiken_fwm_1;

ALTER TABLE `document`
	ADD `document_size` int(11) NOT NULL COMMENT 'File size in Kb',
	CHANGE COLUMN `document_id` `document_id` INT(11) NOT NULL AUTO_INCREMENT,
	CHANGE COLUMN `document_value` `document_value` VARCHAR(100) NOT NULL;
