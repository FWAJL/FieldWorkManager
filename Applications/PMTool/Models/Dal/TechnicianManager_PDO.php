<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

/**
 * Replace '_Template' by your custom name
 */
class TechnicianManager_PDO extends \Library\DAL\BaseManager {

  public function selectOne($object) {
    return NULL;
  }

  public function update($object) {
    return NULL;
  }

   public function selectMany($object) {
    $sql = 'SELECT * FROM technician where `pm_id` = \'' . $object->pm_id() . '\';'; //AND `active` = 1  AND `visible` = 1;';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Technician');

    $technicians_list = $query->fetchAll();
    $query->closeCursor();

    return count($technicians_list) > 0 ? $technicians_list : array();
  }

  public function countById($pm_id) {
    return NULL;
  }

  public function add($object) {
    $columns = "";
    $values = "";
    foreach ($object as $key => $value) {
      $columns .= "`" . $key . "`,";
      $values .= "'" . $value . "',";
    }
    $columns = rtrim($columns, ", ");
    $values = rtrim($values, ", ");
    $sql = "INSERT INTO `technician` (" . $columns . ") VALUES (" . $values . ");";
    $query = $this->dao->query($sql);
    $result;
    if (!$query) {
      $result = $query->errorCode();
      $query->closeCursor();
    } else {
      $result = $this->dao->lastInsertId();
    }
    
    return $result;
  }

  public function edit($object) {
    $set_clause = "";
    $where_clause = "";
    foreach ($object as $key => $value) {
      if ($key === "technician_id") {
        $where_clause = "$key = $value";
      } else {
        $set_clause .= "`" . $key . "` = '" . $value ."',"; 
      }
    }
    $set_clause = rtrim($set_clause, ",");
    $sql = "UPDATE `technician` SET $set_clause  WHERE $where_clause;";
    $query = $this->dao->query($sql);
    $result;
    if (!$query) {
      $result = $query->errorCode();
    } else {
      $result = TRUE;
    }
    $query->closeCursor();
    return $result;
  }

  public function delete($identifier) {
    $sql = "DELETE from `technician` WHERE technician_id = " . $identifier . ";";
    $query = $this->dao->query($sql);
    $result;
    if (!$query) {
      $result = $query->errorCode();
    } else {
      $result = TRUE;
    }
    $query->closeCursor();
    return $result;
  }

}
