<?php
/**** @package    Basic MVC framework* @author     Jeremie Litzler* @copyright  Copyright (c) 2015* @license* @link* @since* @filesource*/// ------------------------------------------------------------------------
/**** Project_lab_analyte Dao Class** @package     Application/PMTool* @subpackage  Models/Dao* @category    Project_lab_analyte* @author      FWM DEV Team* @link*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Project_lab_analyte extends \Library\Entity{  public     $project_id,    $lab_analyte_id;
  const     PROJECT_ID_ERR = 0,    LAB_ANALYTE_ID_ERR = 1;
  // SETTERS //  public function setProject_id($project_id) {      $this->project_id = $project_id;  }
  public function setLab_analyte_id($lab_analyte_id) {      $this->lab_analyte_id = $lab_analyte_id;  }
  // GETTERS //  public function project_id() {    return $this->project_id;  }
  public function lab_analyte_id() {    return $this->lab_analyte_id;  }
}