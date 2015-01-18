-- Update for Jan-13 2015
-- > removed table locatio_document
-- > added new table document

USE baiken_fwm_1;

DROP TABLE IF EXISTS `location_document`;

-- DROP TABLE IF EXISTS `document`;
-- Table structure for table `document`
CREATE TABLE `document` (
    `document_id` int(11) NOT NULL,
    `document_content_type` varchar(50) NOT NULL COMMENT 'Store the content type of the document (https://docs.google.com/spreadsheets/d/1NZTuQ974YlxW5u1huzFjvFCpFFWSU08wFNF3AcgWRa8)',
    `document_category` varchar(50) NOT NULL COMMENT 'Is the name of the table/class for which we want a document. Possible values (13-01-14): location, technician',
    `document_value` int(11) NOT NULL COMMENT 'This is the path to the document. A unique constraint prevents adding duplicate value/category',
    PRIMARY KEY (`document_id`),
    UNIQUE INDEX `un_doc_cat_val` (`document_id` ASC, `document_category` ASC, `document_value` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
