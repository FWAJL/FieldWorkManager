-- Update for 2015-03-12
-- > New table form
-- > New column added to table document

USE baiken_fwm_1;

-- Table structure for table `form`
CREATE TABLE `form` (
    `form_id` int(11) NOT NULL AUTO_INCREMENT,
    `document_id` int(11) NOT NULL COMMENT 'Cannot set a foreign key as the document cannot be until the form is created so default value tells where it is: -1 -> being created ; any value above 0 -> look at document table',
    `form_category` varchar(25) NOT NULL DEFAULT 0 COMMENT 'Will store the id column name of the object that relates to the form',
    `form_category_id_value` int(11) NULL COMMENT 'Will store the id value of the object that relates to the form',
    PRIMARY KEY (`form_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

ALTER TABLE `document`
	ADD COLUMN `document_title` varchar(250) NULL DEFAULT 'Caption goes here' COMMENT 'This is the value for a document that is displayed in the HTML as a caption',
;