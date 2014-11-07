<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Task_technicians extends \Library\Entity{  public     $task_id,    $technician_id,    $lead_tech;
  const     TASK_ID_ERR = 0,    TECHNICIAN_ID_ERR = 1,    LEAD_TECH_ERR = 2;
  // SETTERS //  public function setTask_id($task_id) {    if (empty($task_id)) {      $this->erreurs[] = self::TASK_ID_ERR;    } else {      $this->task_id = $task_id;    }  }
  public function setTechnician_id($technician_id) {    if (empty($technician_id)) {      $this->erreurs[] = self::TECHNICIAN_ID_ERR;    } else {      $this->technician_id = $technician_id;    }  }
  public function setLead_tech($lead_tech) {    if (empty($lead_tech)) {      $this->erreurs[] = self::LEAD_TECH_ERR;    } else {      $this->lead_tech = $lead_tech;    }  }
  // GETTERS //  public function task_id() {    return $this->task_id;  }
  public function technician_id() {    return $this->technician_id;  }
  public function lead_tech() {    return $this->lead_tech;  }
}