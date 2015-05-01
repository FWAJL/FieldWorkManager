-- Update for 2015-04-16
-- > Table user: increase size of user_login

USE `baiken_fwm_1`;

ALTER TABLE `user` 
CHANGE COLUMN `user_login` `user_login` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ;
