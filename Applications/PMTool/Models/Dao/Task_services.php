<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Task_services extends \Library\Entity{  public     $task_id,    $service_id;
  const     TASK_ID_ERR = 0,    SERVICE_ID_ERR = 1;
  // SETTERS //  public function setTask_id($task_id) {      $this->task_id = $task_id;  }
  public function setService_id($service_id) {      $this->service_id = $service_id;  }
  // GETTERS //  public function task_id() {    return $this->task_id;  }
  public function service_id() {    return $this->service_id;  }
}