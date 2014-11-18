<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Task_insp_form extends \Library\Entity{  public     $task_insp_form_id,    $task_id;
  const     TASK_INSP_FORM_ID_ERR = 0,    TASK_ID_ERR = 1;
  // SETTERS //  public function setTask_insp_form_id($task_insp_form_id) {    if (empty($task_insp_form_id)) {      $this->erreurs[] = self::TASK_INSP_FORM_ID_ERR;    } else {      $this->task_insp_form_id = $task_insp_form_id;    }  }
  public function setTask_id($task_id) {    if (empty($task_id)) {      $this->erreurs[] = self::TASK_ID_ERR;    } else {      $this->task_id = $task_id;    }  }
  // GETTERS //  public function task_insp_form_id() {    return $this->task_insp_form_id;  }
  public function task_id() {    return $this->task_id;  }
}