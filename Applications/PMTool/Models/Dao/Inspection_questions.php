<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Inspection_questions extends \Library\Entity{  public     $task_insp_form_id,    $inspection_form_name,    $insp_quest_id,    $insp_question;
  const     TASK_INSP_FORM_ID_ERR = 0,    INSPECTION_FORM_NAME_ERR = 1,    INSP_QUEST_ID_ERR = 2,    INSP_QUESTION_ERR = 3;
  // SETTERS //  public function setTask_insp_form_id($task_insp_form_id) {    if (empty($task_insp_form_id)) {      $this->erreurs[] = self::TASK_INSP_FORM_ID_ERR;    } else {      $this->task_insp_form_id = $task_insp_form_id;    }  }
  public function setInspection_form_name($inspection_form_name) {    if (empty($inspection_form_name)) {      $this->erreurs[] = self::INSPECTION_FORM_NAME_ERR;    } else {      $this->inspection_form_name = $inspection_form_name;    }  }
  public function setInsp_quest_id($insp_quest_id) {    if (empty($insp_quest_id)) {      $this->erreurs[] = self::INSP_QUEST_ID_ERR;    } else {      $this->insp_quest_id = $insp_quest_id;    }  }
  public function setInsp_question($insp_question) {    if (empty($insp_question)) {      $this->erreurs[] = self::INSP_QUESTION_ERR;    } else {      $this->insp_question = $insp_question;    }  }
  // GETTERS //  public function task_insp_form_id() {    return $this->task_insp_form_id;  }
  public function inspection_form_name() {    return $this->inspection_form_name;  }
  public function insp_quest_id() {    return $this->insp_quest_id;  }
  public function insp_question() {    return $this->insp_question;  }
}