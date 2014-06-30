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

  public function selectMany($item) {
    return NULL;
  }
  
  public function countById($pm_id) {
    $sql = 'SELECT COUNT(*) FROM project where `pm_id` = \'' . $pm_id . '\' AND `active` = 1  AND `visible` = 1;';
    $query = $this->dao->query($sql);
    $num_rows = $query->fetch(\PDO::FETCH_NUM);
    $query->closeCursor();

    return intval($num_rows[0]);
  }

}
