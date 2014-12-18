-- Update for Dec-18 2014
-- > removed the column location_document from table location
-- > added the column location_address in table location
-- > added the column location_category in table location
-- > added the new table location_document

USE baiken_fwm_1;

ALTER TABLE `location`
	DROP COLUMN `location_document`,
	ADD COLUMN `location_address` VARCHAR(500) COLLATE utf8_unicode_ci NULL,
    ADD COLUMN `location_category` VARCHAR(50) COLLATE utf8_unicode_ci NULL;

-- Table structure for table `location_document`
CREATE TABLE `location_document` (
  `location_id` int(11) NOT NULL,
  `location_document_value` int(11) NOT NULL COMMENT 'A unique constraint prevent adding the same document as a given location',
    CONSTRAINT `fk_ld_l` FOREIGN KEY (`location_id`)
        REFERENCES `location` (`location_id`) ON DELETE CASCADE,
    UNIQUE INDEX `un_ld_l` (`location_id` ASC, `location_document_value` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
