<?php
/**** @package    Basic MVC framework* @author     Jeremie Litzler* @copyright  Copyright (c) 2014* @license* @link* @since* @filesource*/// ------------------------------------------------------------------------
/**** Lab_analyte_location Dao Class** @package     Application/PMTool* @subpackage  Models/Dao* @category    Lab_analyte_location* @author      FWM DEV Team* @link*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Lab_analyte_location extends \Library\Entity{  public     $task_id,    $location_id,    $lab_analyte_id;
  const     TASK_ID_ERR = 0,    LOCATION_ID_ERR = 1,    LAB_ANALYTE_ID_ERR = 2;
  // SETTERS //  public function setTask_id($task_id) {      $this->task_id = $task_id;  }
  public function setLocation_id($location_id) {      $this->location_id = $location_id;  }
  public function setLab_analyte_id($lab_analyte_id) {      $this->lab_analyte_id = $lab_analyte_id;  }
  // GETTERS //  public function task_id() {    return $this->task_id;  }
  public function location_id() {    return $this->location_id;  }
  public function lab_analyte_id() {    return $this->lab_analyte_id;  }
}