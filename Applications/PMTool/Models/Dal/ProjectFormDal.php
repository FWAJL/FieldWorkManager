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
    $deleteQuery = "DELETE FROM `$tableName` WHERE ";
    foreach($filters as $filterName=>$filterValue) {
      $queryArray[]= "`$filterName` = :$filterName";
    }
    $query = implode(" AND ",$queryArray);
    $deleteQuery .= $query;
    $sth = $this->dao->prepare($deleteQuery);
    foreach($filters as $filterName=>$filterValue) {
      $sth->bindValue(":$filterName",$filterValue,\PDO::PARAM_STR);
    }
    try {
      $sth->execute();
      $sth->closeCursor();
      return true;
    } catch (Exception $exc) {
      return false;
    }
  }

}
