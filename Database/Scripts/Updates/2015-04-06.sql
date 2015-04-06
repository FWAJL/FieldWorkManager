-- Update for 2015-04-06
-- > Insert for an admin user 

USE baiken_fwm_1;

INSERT INTO `user` 
(`user_login`,`user_password`,`user_hint`,`user_type`,`user_value`,`user_role_id`,`user_session_id`) 
VALUES 
('admintest','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3g496lJL683','','administrator_id',0,1,NULL);