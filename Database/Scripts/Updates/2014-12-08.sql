ALTER TABLE `baiken_fwm_1`.`task_location` 
ADD UNIQUE INDEX `un_t_l` (`task_id` ASC, `location_id` ASC);

ALTER TABLE `baiken_fwm_1`.`task_service` 
ADD UNIQUE INDEX `un_t_s` (`task_id` ASC, `service_id` ASC);

ALTER TABLE `baiken_fwm_1`.`task_technician`
ADD UNIQUE INDEX `un_t_r` (`task_id` ASC, `technician_id` ASC);