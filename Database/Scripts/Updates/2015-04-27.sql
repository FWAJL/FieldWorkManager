-- Update for 2015-04-27
-- > updates to tables to make the email value unique

USE baiken_fwm_1;

ALTER TABLE `user`
ADD COLUMN `user_email` VARCHAR(50) NOT NULL COMMENT 'User email that is unique and must be set';

ALTER TABLE `user`
ADD UNIQUE INDEX `un_user_email_val` (`user_email` ASC);