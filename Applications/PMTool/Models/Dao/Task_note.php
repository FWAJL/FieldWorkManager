<?php
/**** @package    Basic MVC framework* @author     Jeremie Litzler* @copyright  Copyright (c) 2014* @license* @link* @since* @filesource*/// ------------------------------------------------------------------------
/**** Task_note Dao Class** @package     Application/PMTool* @subpackage  Models/Dao* @category    Task_note* @author      FWM DEV Team* @link*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Task_note extends \Library\Entity{  public     $task_note_id,    $task_id,    $task_note_category_type,    $task_note_category_value,    $task_note_value;
  const     TASK_NOTE_ID_ERR = 0,    TASK_ID_ERR = 1,    TASK_NOTE_CATEGORY_TYPE_ERR = 2,    TASK_NOTE_CATEGORY_VALUE_ERR = 3,    TASK_NOTE_VALUE_ERR = 4;
  // SETTERS //  public function setTask_note_id($task_note_id) {      $this->task_note_id = $task_note_id;  }
  public function setTask_id($task_id) {      $this->task_id = $task_id;  }
  public function setTask_note_category_type($task_note_category_type) {      $this->task_note_category_type = $task_note_category_type;  }
  public function setTask_note_category_value($task_note_category_value) {      $this->task_note_category_value = $task_note_category_value;  }
  public function setTask_note_value($task_note_value) {      $this->task_note_value = $task_note_value;  }
  // GETTERS //  public function task_note_id() {    return $this->task_note_id;  }
  public function task_id() {    return $this->task_id;  }
  public function task_note_category_type() {    return $this->task_note_category_type;  }
  public function task_note_category_value() {    return $this->task_note_category_value;  }
  public function task_note_value() {    return $this->task_note_value;  }
}