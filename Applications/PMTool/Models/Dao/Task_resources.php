<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Task_resources extends \Library\Entity{  public     $task_id,    $resource_id;
  const     TASK_ID_ERR = 0,    RESOURCE_ID_ERR = 1;
  // SETTERS //  public function setTask_id($task_id) {    if (empty($task_id)) {      $this->erreurs[] = self::TASK_ID_ERR;    } else {      $this->task_id = $task_id;    }  }
  public function setResource_id($resource_id) {    if (empty($resource_id)) {      $this->erreurs[] = self::RESOURCE_ID_ERR;    } else {      $this->resource_id = $resource_id;    }  }
  // GETTERS //  public function task_id() {    return $this->task_id;  }
  public function resource_id() {    return $this->resource_id;  }
}