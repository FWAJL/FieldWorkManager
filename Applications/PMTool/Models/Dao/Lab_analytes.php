<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Lab_analytes extends \Library\Entity{  public     $lab_analyte_id,    $lab_analyte_name;
  const     LAB_ANALYTE_ID_ERR = 0,    LAB_ANALYTE_NAME_ERR = 1;
  // SETTERS //  public function setLab_analyte_id($lab_analyte_id) {    if (empty($lab_analyte_id)) {      $this->erreurs[] = self::LAB_ANALYTE_ID_ERR;    } else {      $this->lab_analyte_id = $lab_analyte_id;    }  }
  public function setLab_analyte_name($lab_analyte_name) {    if (empty($lab_analyte_name)) {      $this->erreurs[] = self::LAB_ANALYTE_NAME_ERR;    } else {      $this->lab_analyte_name = $lab_analyte_name;    }  }
  // GETTERS //  public function lab_analyte_id() {    return $this->lab_analyte_id;  }
  public function lab_analyte_name() {    return $this->lab_analyte_name;  }
}