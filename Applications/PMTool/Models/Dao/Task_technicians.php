<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Task_technicians extends \Library\Entity{  public     $task_id,    $technician_id,    $is_lead_tech;
  const     TASK_ID_ERR = 0,    TECHNICIAN_ID_ERR = 1,    IS_LEAD_TECH_ERR = 2;
  // SETTERS //  public function setTask_id($task_id) {      $this->task_id = $task_id;  }
  public function setTechnician_id($technician_id) {      $this->technician_id = $technician_id;  }
  public function setIs_lead_tech($is_lead_tech) {      $this->is_lead_tech = $is_lead_tech;  }
  // GETTERS //  public function task_id() {    return $this->task_id;  }
  public function technician_id() {    return $this->technician_id;  }
  public function is_lead_tech() {    return $this->is_lead_tech;  }
}