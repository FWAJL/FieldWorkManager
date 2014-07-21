<?php

namespace Library\DAL\Models;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ProjectManager_PDO extends \Library\DAL\BaseManager {

  /**
   * Select a Project from its id and pm_id
   * 
   * @param Project $p
   * @return array the selected row in the db
   */
  public function selectOne($project_in) {
    return NULL;
  }

  /**
   * Select a PM from its username or password
   * 
   * @param ProjectManager $pm
   * @return array the selected row in the db
   */
  public function update($project) {
    $sql = $this->dao->prepare("UPDATE project set `password` = :password WHERE `pm_id` = :pm_id;");
    $sql->bindValue(":pm_id", $pm->pm_id(), \PDO::PARAM_INT);
    $sql->bindValue(":password", $pm->password(), \PDO::PARAM_STR);

    try {
      return $sql->execute();
    } catch (Exception $exc) {
      echo $exc->getTraceAsString();
    }
  }

  /**
   * Returns list of projects for PM
   * 
   * @param \Library\BO\Project $project
   * @return array of \Library\BO\Project
   */
  public function selectMany($project) {
    $sql = 'SELECT * FROM project where `pm_id` = \'' . $project->pm_id() . '\';'; //AND `active` = 1  AND `visible` = 1;';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\BO\Project');

    $project_list = $query->fetchAll();
    $query->closeCursor();

    return $project_list;
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
    } else {
      $result = TRUE;
    }
    $query->closeCursor();
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
    
  }

}
