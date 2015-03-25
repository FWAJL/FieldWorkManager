-- Update for 2015-03-19
-- > new table user
-- > new table user_role
-- > data init for table user_role

USE baiken_fwm_1;

-- Table structure for `user_role`
CREATE TABLE `user_role` (
    `user_role_id` smallint(2) NOT NULL,
    `user_role_desc` varchar(100),
    PRIMARY KEY (`user_role_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci;

INSERT INTO `user_role` (`user_role_id`,`user_role_desc`) VALUES
(1,'Administrator'),
(2,'Project Manager'),
(3,'Field Technician'),
(4,'Visitor'),
(5,'None');

-- Table structure for `web_user`
CREATE TABLE `user` (
    `user_id` int(11) NOT NULL AUTO_INCREMENT,
    `user_login` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
    `user_password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `user_hint` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
    `user_type` varchar(50) NOT NULL COMMENT 'Possible values: pm_id, technician_id, service_id',
    `user_value` int(11) NOT NULL COMMENT 'ID value corresponding to the user_type',
    `user_role_id` smallint(2) NOT NULL COMMENT 'Look up the table user_role for details about the roles',
    `user_is_logged` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Flag to set to 1 when a user logs in successful. It will prevent multiple connection per single user',
    CONSTRAINT `fk_user_role_user` FOREIGN KEY (`user_role_id`)
        REFERENCES `user_role` (`user_role_id`),
    PRIMARY KEY (`user_id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT=1;