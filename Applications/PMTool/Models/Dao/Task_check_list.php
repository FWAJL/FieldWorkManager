<?php
/**** @package    Basic MVC framework* @author     Jeremie Litzler* @copyright  Copyright (c) 2014* @license* @link* @since* @filesource*/// ------------------------------------------------------------------------
/**** Task_check_list Dao Class** @package     Application/PMTool* @subpackage  Models/Dao* @category    Task_check_list* @author      FWM DEV Team* @link*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Task_check_list extends \Library\Entity{  public     $task_check_list_id,    $task_id,    $task_check_list_complete,    $task_check_list_detail;
  const     TASK_CHECK_LIST_ID_ERR = 0,    TASK_ID_ERR = 1,    TASK_CHECK_LIST_COMPLETE_ERR = 2,    TASK_CHECK_LIST_DETAIL_ERR = 3;
  // SETTERS //  public function setTask_check_list_id($task_check_list_id) {      $this->task_check_list_id = $task_check_list_id;  }
  public function setTask_id($task_id) {      $this->task_id = $task_id;  }
  public function setTask_check_list_complete($task_check_list_complete) {      $this->task_check_list_complete = $task_check_list_complete;  }
  public function setTask_check_list_detail($task_check_list_detail) {      $this->task_check_list_detail = $task_check_list_detail;  }
  // GETTERS //  public function task_check_list_id() {    return $this->task_check_list_id;  }
  public function task_id() {    return $this->task_id;  }
  public function task_check_list_complete() {    return $this->task_check_list_complete;  }
  public function task_check_list_detail() {    return $this->task_check_list_detail;  }
}