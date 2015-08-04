<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class FieldAnalyteLocationDal extends \Library\DAL\BaseManager {
	/**
	*	Update Field_analyte_location based on location_id
	* and analyte_id
	*/
	public function updateFieldDataMatrixResult($location_id, $field_analyte_id, $result_val) {
		$query = "UPDATE field_analyte_location set `field_analyte_location_result` = :result_val WHERE `location_id` = :location_id and `field_analyte_id` = :field_analyte_id;";
		$sql = $this->dao->prepare($query);
    $sql->bindValue(":result_val", $result_val, \PDO::PARAM_STR);
    $sql->bindValue(":location_id", $location_id, \PDO::PARAM_STR);
    $sql->bindValue(":field_analyte_id", $field_analyte_id, \PDO::PARAM_STR);

    try {
      return $sql->execute();
    } catch (Exception $exc) {
      echo $exc->getTraceAsString();
    }
	}
}
