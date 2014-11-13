<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class FacilityDal extends \Library\DAL\BaseManager {

  /**
   * Select a Facility from its id and pm_id
   * 
   * @param Facility $p
   * @return array the selected row in the db
   */
  public function selectOne($facility_in) {
    return NULL;
  }

  /**
   * Select a PM from its username or password
   * 
   * @param FacilityManager $pm
   * @return array the selected row in the db
   */
  public function update($facility) {
    return null;
  }

  /**
   * Returns list of facilitys for PM
   * 
   * @param \Applications\PMTool\Models\Dao\Facility $facility
   * @return array of \Applications\PMTool\Models\Dao\Facility
   */
  public function selectMany($facility) {
    $sql = 'SELECT * FROM `facility` f inner join `project` p on f.project_id = p.project_id';
    $sql .= ' where p.`pm_id` = \'' . $facility->pm_id() . '\';'; //AND `active` = 1  AND `visible` = 1;';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Facility');

    $facility_list = $query->fetchAll();
    $query->closeCursor();

    return $facility_list;
  }

  public function countById($pm_id) {
    $sql = 'SELECT COUNT(*) FROM facility where `pm_id` = \'' . $pm_id . '\';'; // AND `active` = 1  AND `visible` = 1;';
    $query = $this->dao->query($sql);
    $num_rows = $query->fetch(\PDO::FETCH_NUM);
    $query->closeCursor();

    return intval($num_rows[0]);
  }

  public function add($facility) {
    $columns = "";
    $values = "";
    foreach ($facility as $key => $value) {
      $columns .= "`" . $key . "`,";
      $values .= "'" . addslashes($value) . "',";
    }
    $columns = rtrim($columns, ", ");
    $values = rtrim($values, ", ");
    $sql = "INSERT INTO `facility` (" . $columns . ") VALUES (" . $values . ");";
    $query = $this->dao->query($sql);
    $result;
    if (!$query) {
      $result = $query->errorCode();
    } else {
      $result = $this->dao->lastInsertId();
    }
    $query->closeCursor();
    return $result;
  }

  public function edit($facility) {
    $set_clause = "";
    $where_clause = "";
    foreach ($facility as $key => $value) {
      if ($key === "project_id") {
        //skip
      }
      elseif ($key === "facility_id") {
        $where_clause = "$key = $value";
      } else {
        $set_clause .= "`" . $key . "` = '" . addslashes($value) ."',"; 
      }
    }
    $set_clause = rtrim($set_clause, ",");
    $sql = "UPDATE `facility` SET $set_clause  WHERE $where_clause;";
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
    $sql = "DELETE from `facility` WHERE facility_id = " . $identifier . ";";
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
