-- Update for 205-04-07
-- > Update to table user to prevent the same login added twice

USE baiken_fwm_1;

ALTER TABLE `user`
	ADD UNIQUE INDEX `un_user_login` (`user_login` ASC);