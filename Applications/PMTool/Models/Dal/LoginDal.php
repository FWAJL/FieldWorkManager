<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class LoginDal extends \Library\DAL\BaseManager {

  /**
   * Select a PM from its username or password
   * 
   * @param ProjectManager $pm
   * @return array the selected row in the db
   */
  public function selectOne($pm_in) {
    if ($pm_in->username() !== "") {//Check if the user is giving his username and that there is a value
      $sql = 'SELECT * FROM project_manager where `username` = \'' . $pm_in->username() . '\' AND `password` = \'' . $pm_in->password() . '\' LIMIT 0, 1;';
    } else if ($pm_in->pm_email() !== "") {//Check if the user is giving an email
      $sql = 'SELECT * FROM project_manager where `email` = \'' . $pm_in->pm_email() . '\' AND `password` = \'' . $pm_in->password() . '\' LIMIT 0, 1;';
    } else {
      return NULL;
    }
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Project_manager');

    $pm_out = $query->fetchAll();
    $query->closeCursor();

    return $pm_out;
  }

  /**
   * Select a PM from its username or password
   * 
   * @param ProjectManager $pm
   * @return array the selected row in the db
   */
  public function update($pm) {
    $sql = $this->dao->prepare("UPDATE project_manager set `password` = :password WHERE `pm_id` = :pm_id;");
    $sql->bindValue(":pm_id", $pm->pm_id(), \PDO::PARAM_INT);
    $sql->bindValue(":password", $pm->password(), \PDO::PARAM_STR);

    try {
      return $sql->execute();
    } catch (Exception $exc) {
      echo $exc->getTraceAsString();
    }
  }

  public function countById($item) {
    
  }

//  public function add($item) {  }

//  public function edit($object, $where_filter_id) {  }

//  public function delete($object, $where_filter_id) {  }

}
