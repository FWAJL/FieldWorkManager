<?php
/**** @package    Basic MVC framework* @author     Jeremie Litzler* @copyright  Copyright (c) 2015* @license* @link* @since* @filesource*/// ------------------------------------------------------------------------
/**** Project_field_analyte Dao Class** @package     Application/PMTool* @subpackage  Models/Dao* @category    Project_field_analyte* @author      FWM DEV Team* @link*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Project_field_analyte extends \Library\Entity{  public     $project_id,    $field_analyte_id;
  const     PROJECT_ID_ERR = 0,    FIELD_ANALYTE_ID_ERR = 1;
  // SETTERS //  public function setProject_id($project_id) {      $this->project_id = $project_id;  }
  public function setField_analyte_id($field_analyte_id) {      $this->field_analyte_id = $field_analyte_id;  }
  // GETTERS //  public function project_id() {    return $this->project_id;  }
  public function field_analyte_id() {    return $this->field_analyte_id;  }
}