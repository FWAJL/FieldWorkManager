-- Update for 2015-03-17-1
-- > New table for task forms

USE `baiken_fwm_1`;

CREATE TABLE `task_form`(
`task_id` INT(11) NOT NULL,
`master_form_id` INT(11),
`user_form_id` INT(11),
UNIQUE INDEX `un_tf_t_uf_mf` (`task_id`, `master_form_id`, `user_form_id`),
CONSTRAINT `fk_tf_task` FOREIGN KEY (`task_id`) REFERENCES `task`(`task_id`) ON DELETE CASCADE );