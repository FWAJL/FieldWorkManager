<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ProjectFormDal extends \Library\DAL\BaseManager {

  //  public function add($item) {  }

  //  public function edit($object, $where_filter_id) {  }

  public function deleteByFilters($object, $filters) {
    $tableName = \Applications\PMTool\Helpers\CommonHelper::GetShortClassName($object);
    $queryArray = array();
    $query = "";
    foreach($filters as $filterName=>$filterValue) {
      $queryArray[]= "`$filterName` = '$filterValue'";
    }
    $query = implode(" AND ",$queryArray);
    try {
      $sql = $this->dao->query("DELETE FROM `$tableName` WHERE $query;");
      $sql->closeCursor();
      return true;
    } catch (Exception $exc) {
      return false;
    }
  }

}
