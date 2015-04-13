/*Table structure for table `master_form` */

USE baiken_fwm_1;

CREATE TABLE `master_form` (
  `form_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`form_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `project_form` */

CREATE TABLE `project_form` (
  `project_id` int(11) NOT NULL,
  `master_form_id` int(11) DEFAULT NULL,
  `user_form_id` int(11) DEFAULT NULL,
  UNIQUE KEY `un_pf_p_uf_mf` (`project_id`,`user_form_id`,`master_form_id`),
  CONSTRAINT `fk_pf_project` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*Table structure for table `user_form` */

CREATE TABLE `user_form` (
  `form_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` int(10) DEFAULT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pm_id` int(11) NOT NULL,
  PRIMARY KEY (`form_id`),
  KEY `fk_uf_project_manager` (`pm_id`),
  CONSTRAINT `fk_uf_project_manager` FOREIGN KEY (`pm_id`) REFERENCES `project_manager` (`pm_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `master_form` */

insert  into `master_form`(`form_id`,`content_type`,`category`,`value`,`size`,`title`) values (1,'pdf',NULL,'FWM_T-ChainofCustody.pdf',45,'Chain Of Custody'),(2,'pdf',NULL,'FWM_L-WellPurge.pdf',152,'Well Purge'),(3,'pdf',NULL,'FWM_T-FieldSampling.pdf',100,'Field Sampling');