<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Lab_analyte extends \Library\Entity{  public     $lab_analyte_id,    $lab_analyte_name,    $pm_id;
  const     LAB_ANALYTE_ID_ERR = 0,    LAB_ANALYTE_NAME_ERR = 1,    PM_ID_ERR = 2;
  // SETTERS //  public function setLab_analyte_id($lab_analyte_id) {      $this->lab_analyte_id = $lab_analyte_id;  }
  public function setLab_analyte_name($lab_analyte_name) {      $this->lab_analyte_name = $lab_analyte_name;  }
  public function setPm_id($pm_id) {      $this->pm_id = $pm_id;  }
  // GETTERS //  public function lab_analyte_id() {    return $this->lab_analyte_id;  }
  public function lab_analyte_name() {    return $this->lab_analyte_name;  }
  public function pm_id() {    return $this->pm_id;  }
}