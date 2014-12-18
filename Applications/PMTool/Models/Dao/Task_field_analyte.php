<?php
/**
*
* @package    Basic MVC framework
* @author     Jeremie Litzler
* @copyright  Copyright (c) 2014
* @license
* @link
* @since
* @filesource
*/
// ------------------------------------------------------------------------
/**
*
* Task_field_analyte Dao Class
*
* @package     Application/PMTool
* @subpackage  Models/Dao
* @category    Task_field_analyte
* @author      FWM DEV Team
* @link
*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Task_field_analyte extends \Library\Entity{  public     $task_id,    $field_analyte_id;
  const     TASK_ID_ERR = 0,    FIELD_ANALYTE_ID_ERR = 1;
  // SETTERS //  public function setTask_id($task_id) {      $this->task_id = $task_id;  }
  public function setField_analyte_id($field_analyte_id) {      $this->field_analyte_id = $field_analyte_id;  }
  // GETTERS //  public function task_id() {    return $this->task_id;  }
  public function field_analyte_id() {    return $this->field_analyte_id;  }
}