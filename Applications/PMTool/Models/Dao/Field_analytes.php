<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Field_analytes extends \Library\Entity{  public     $field_analyte_id,    $field_analyte_name_unit;
  const     FIELD_ANALYTE_ID_ERR = 0,    FIELD_ANALYTE_NAME_UNIT_ERR = 1;
  // SETTERS //  public function setField_analyte_id($field_analyte_id) {    if (empty($field_analyte_id)) {      $this->erreurs[] = self::FIELD_ANALYTE_ID_ERR;    } else {      $this->field_analyte_id = $field_analyte_id;    }  }
  public function setField_analyte_name_unit($field_analyte_name_unit) {    if (empty($field_analyte_name_unit)) {      $this->erreurs[] = self::FIELD_ANALYTE_NAME_UNIT_ERR;    } else {      $this->field_analyte_name_unit = $field_analyte_name_unit;    }  }
  // GETTERS //  public function field_analyte_id() {    return $this->field_analyte_id;  }
  public function field_analyte_name_unit() {    return $this->field_analyte_name_unit;  }
}