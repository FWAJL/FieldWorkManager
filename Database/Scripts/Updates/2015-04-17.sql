-- Update for 2015-04-17
-- > table discussion and discussion_content updates

USE `baiken_fwm_1`;

ALTER TABLE `discussion` 
ADD COLUMN `discussion_start_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `discussion_content`
ADD COLUMN `discussion_content_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
