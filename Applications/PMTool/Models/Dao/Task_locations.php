<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Task_locations extends \Library\Entity{  public     $task_id,    $location_id;
  const     TASK_ID_ERR = 0,    LOCATION_ID_ERR = 1;
  // SETTERS //  public function setTask_id($task_id) {    if (empty($task_id)) {      $this->erreurs[] = self::TASK_ID_ERR;    } else {      $this->task_id = $task_id;    }  }
  public function setLocation_id($location_id) {    if (empty($location_id)) {      $this->erreurs[] = self::LOCATION_ID_ERR;    } else {      $this->location_id = $location_id;    }  }
  // GETTERS //  public function task_id() {    return $this->task_id;  }
  public function location_id() {    return $this->location_id;  }
}