<?php
/**** @author     Jeremie Litzler* @copyright  Copyright (c) 2015* @license* @link* @since* @filesource*/// ------------------------------------------------------------------------
/**** Field_analyte_location Dao Class** @package     Application/PMTool* @subpackage  Models/Dao* @category    Field_analyte_location* @author      FWM DEV Team* @link*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Field_analyte_location extends \Library\Entity{  public     $task_id,    $field_analyte_id,    $location_id,    $field_analyte_location_date,
    $field_analyte_location_result;
  const     TASK_ID_ERR = 0,    FIELD_ANALYTE_ID_ERR = 1,    LOCATION_ID_ERR = 2,    FIELD_ANALYTE_LOCATION_DATE_ERR = 3,
    FIELD_ANALYTE_LOCATION_RESULT_ERR = 4;
  // SETTERS //  public function setTask_id($task_id) {      $this->task_id = $task_id;  }
  public function setField_analyte_id($field_analyte_id) {      $this->field_analyte_id = $field_analyte_id;  }
  public function setLocation_id($location_id) {      $this->location_id = $location_id;  }

  public function setField_analyte_location_date($field_analyte_location_date) {
      $this->field_analyte_location_date = $field_analyte_location_date;
  }
  public function setField_analyte_location_result($field_analyte_location_result) {      $this->field_analyte_location_result = $field_analyte_location_result;  }
  // GETTERS //  public function task_id() {    return $this->task_id;  }
  public function field_analyte_id() {    return $this->field_analyte_id;  }
  public function location_id() {    return $this->location_id;  }
  public function field_analyte_location_date() {
    return $this->field_analyte_location_date;
  }

  public function field_analyte_location_result() {    return $this->field_analyte_location_result;  }
}