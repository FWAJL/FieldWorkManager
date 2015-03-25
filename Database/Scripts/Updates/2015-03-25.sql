-- Update for 2015-03-25
-- > Alter user_is_logged field in user table to user_session_id
USE baiken_fwm_1;

ALTER TABLE `user` CHANGE `user_is_logged` `user_session_id` VARCHAR(50) NULL COMMENT 'Hashed session ID';

INSERT INTO `user`(`user_id`,`user_login`,`user_password`,`user_hint`,`user_type`,`user_value`,`user_role_id`,`user_session_id`) values (1,'mapping','821b7db1bf69055d3819db82de2c55389a73409bg496lJL683','test','pm_id',4,2,NULL),(3,'test','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3g496lJL683',NULL,'pm_id',1,2,NULL),(4,'demo','89e495e7941cf9e40e6980d14a16bf023ccd4c91g496lJL683',NULL,'pm_id',3,2,NULL),(5,'new_test','11f4eda6444723f5a74d26c3d6f14817b0aa1ea4g496lJL683',NULL,'pm_id',6,2,NULL),(6,'Popup','3b6fb9033a8302fc168ca0199caaba142dbc5530g496lJL683',NULL,'pm_id',7,2,NULL),(7,'pdf','ce9f44bc3d348133b47226685a8f75bbf17e757bg496lJL683',NULL,'pm_id',8,2,NULL);
