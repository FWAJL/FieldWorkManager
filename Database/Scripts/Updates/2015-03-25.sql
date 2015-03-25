-- Update for 2015-03-25
-- > Alter user_is_logged field in user table to user_session_id
-- > Copy the project_manager credentials to the user table.

USE baiken_fwm_1;

ALTER TABLE `user` CHANGE `user_is_logged` `user_session_id` VARCHAR(50) NULL COMMENT 'Hashed session ID';

INSERT INTO `baiken_fwm_1`.`user`
(`user_login`,
`user_password`,
`user_hint`,
`user_type`,
`user_value`,
`user_role_id`,
`user_session_id`)
SELECT 
`username`, 
`password`, 
`hint`,
'pm',
pm_id,
2 'user_role_id',
NULL 'user_session_id'
FROM `project_manager`;

SELECT *
FROM `user`;