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

  /**
  * Gets all records based on tak_id and loc_id from Field_analyte_location
  */
  public function getAllMatrixDataForTaskLocation($task_id, $location_id) {
    $sql = 'select * from field_analyte_location where task_id = :task_id and location_id = :location_id';
    $dao = $this->dao->prepare($sql);
    $dao->bindValue(':task_id', $task_id, \PDO::PARAM_STR);
    $dao->bindValue(':location_id', $location_id, \PDO::PARAM_STR);
    
    try {
      $dao->execute();
      $dao->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Field_analyte_location');
      $search_res = $dao->fetchAll();
      $dao->closeCursor();
      return $search_res;
    } catch (Exception $exc) {
      echo $exc->getTraceAsString();
    }
  }

  /**
  * Check if matrix data is already inserted for this task id, location_id and FA id
  */
  public function ifMatrixDataExistsFor($task_id, $location_id, $fa_id) {
    $sql = 'select * from field_analyte_location where task_id = :task_id and location_id = :location_id and field_analyte_id = :field_analyte_id';
    $dao = $this->dao->prepare($sql);
    $dao->bindValue(':task_id', $task_id, \PDO::PARAM_STR);
    $dao->bindValue(':location_id', $location_id, \PDO::PARAM_STR);
    $dao->bindValue(':field_analyte_id', $fa_id, \PDO::PARAM_STR);
    
    $ret_val = false;
    try {
      $dao->execute();
      $dao->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Field_analyte_location');
      $search_res = $dao->fetchAll();
      $dao->closeCursor();
      if(count($search_res) > 0) {
        $ret_val = true;
      }
    } catch (Exception $exc) {
      echo $exc->getTraceAsString();
    } 
    return $ret_val;
  }
}
