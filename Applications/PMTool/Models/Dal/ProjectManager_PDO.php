<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ProjectManager_PDO extends \Library\DAL\BaseManager {

  public function selectOne($project) {
    return NULL;
  }

  public function update($project) {
    return NULL;
  }

  /**
   * Returns list of projects for PM
   * 
   * @param \Applications\PMTool\Models\Dao\Project $project
   * @return array of \Applications\PMTool\Models\Dao\Project
   */
  public function selectMany($project) {
    $sql = 'SELECT * FROM project where `pm_id` = \'' . $project->pm_id() . '\';'; //AND `active` = 1  AND `visible` = 1;';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Project');

    $project_list = $query->fetchAll();
    $query->closeCursor();

    return count($project_list) > 0 ? $project_list : array();
  }

  public function countById($pm_id) {
    $sql = 'SELECT COUNT(*) FROM project where `pm_id` = \'' . $pm_id . '\';'; // AND `active` = 1  AND `visible` = 1;';
    $query = $this->dao->query($sql);
    $num_rows = $query->fetch(\PDO::FETCH_NUM);
    $query->closeCursor();

    return intval($num_rows[0]);
  }

  public function add($project) {
    $columns = "";
    $values = "";
    foreach ($project as $key => $value) {
      $columns .= "`" . $key . "`,";
      $values .= "'" . $value . "',";
    }
    $columns = rtrim($columns, ", ");
    $values = rtrim($values, ", ");
    $sql = "INSERT INTO `project` (" . $columns . ") VALUES (" . $values . ");";
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

  public function edit($project) {
    $set_clause = "";
    $where_clause = "";
    foreach ($project as $key => $value) {
      if ($key === "project_id") {
        $where_clause = "$key = $value";
      } else {
        $set_clause .= "`" . $key . "` = '" . $value ."',"; 
      }
    }
    $set_clause = rtrim($set_clause, ",");
    $sql = "UPDATE `project` SET $set_clause  WHERE $where_clause;";
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
    $sql = "DELETE from `facility` WHERE project_id = " . $identifier . ";";
    $sql .= "DELETE from `project` WHERE project_id = " . $identifier . ";";
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
