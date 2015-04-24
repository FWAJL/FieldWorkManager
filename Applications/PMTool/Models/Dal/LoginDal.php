<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class LoginDal extends \Library\DAL\BaseManager {

  /**
   * Select User from its username or password
   * 
   * @param User $user
   * @return array the selected row in the db
   */
  public function selectOne($user) {
    if ($user->user_login() !== "") {//Check if the user is giving his username and that there is a value
      $sql = 'SELECT * FROM user where `user_login` = :user_login AND `user_password` = :user_password LIMIT 0, 1;';
    }else {
      return NULL;
    }
    $sth = $this->dao->prepare($sql);
    $sth->bindValue(':user_login',$user->user_login(),\PDO::PARAM_STR);
    $sth->bindValue(':user_password',$user->user_password(),\PDO::PARAM_STR);
    $sth->execute();
    $sth->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\User');

    $user_out = $sth->fetchAll();
    $sth->closeCursor();

    return $user_out;
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

  public function selectUserType($user) {
    $table = null;
    switch ($user->user_type()){
      case 'technician_id':
        $table = 'technician';
        $field = 'technician_id';
        $sql = "SELECT * FROM `$table` WHERE `$field` = '".$user->user_value()."' LIMIT 0,1";
        $dao = '\Applications\PMTool\Models\Dao\Technician';
      break;
      case 'pm_id':
        $table = 'project_manager';
        $field = 'pm_id';
        $sql = "SELECT * FROM `$table` WHERE `$field`  ='".$user->user_value()."' LIMIT 0,1";
        $dao = '\Applications\PMTool\Models\Dao\Project_manager';
      break;
      default:
        $table = null;
    }

    if($table !== null) {
      $query = $this->dao->query($sql);
      $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $dao);
      $user_type_out = $query->fetchAll();
      $query->closeCursor();
      return $user_type_out;
    } else {
      return null;
    }

  }
}
