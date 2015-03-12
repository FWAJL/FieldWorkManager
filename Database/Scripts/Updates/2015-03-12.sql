-- Update for 2015-03-12
-- > New table form and form_usage
-- > New column added to table document

USE baiken_fwm_1;

-- Table structure for table `form`
CREATE TABLE `form_usage` (
    `form_usage_id` int(11) NOT NULL AUTO_INCREMENT,
    `form_usage_type` varchar(25) NOT NULL COMMENT 'A string representation of the specific usage type. Possible values: task, location',
    `form_usage_value` varchar(25) NOT NULL DEFAULT 0 COMMENT 'A string representation of the specific usage. Possible values: coc, well.',
    `form_usage_desc` varchar(100) NULL COMMENT 'Describes the meaning of the form_usage',
    PRIMARY KEY (`form_usage_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

-- Table structure for table `form`
CREATE TABLE `form` (
    `form_id` int(11) NOT NULL AUTO_INCREMENT,
    `document_id` int(11) NOT NULL COMMENT 'Cannot set a foreign key as the document cannot be until the form is created so default value tells where it is: -1 -> being created ; any value above 0 -> look at document table',
    `form_category` varchar(25) NOT NULL DEFAULT 0 COMMENT 'Will store the id column name of the object that relates to the form',
    `form_category_id_value` int(11) NULL COMMENT 'Will store the id value of the object that relates to the form',
    `form_usage_id` int(11) NOT NULL COMMENT 'Look up the table form_usage for the meaning of id set',
    CONSTRAINT `fk_form_usage` FOREIGN KEY (`form_usage_id`)
        REFERENCES `form_usage` (`form_usage_id`),
    PRIMARY KEY (`form_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;

ALTER TABLE `document`
	ADD COLUMN `document_title` varchar(250) NULL DEFAULT 'Caption goes here' COMMENT 'This is the value for a document that is displayed in the HTML as a caption'
;
