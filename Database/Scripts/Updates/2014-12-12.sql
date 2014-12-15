-- Table structure for table `project_service`
USE baiken_fwm_1;
CREATE TABLE `project_service` (
  `project_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
    CONSTRAINT `fk_ps_p` FOREIGN KEY (`project_id`)
        REFERENCES `project` (`project_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_ps_s` FOREIGN KEY (`service_id`)
        REFERENCES `service` (`service_id`) ON DELETE CASCADE,
    UNIQUE INDEX `un_p_s` (`project_id` ASC, `service_id` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

