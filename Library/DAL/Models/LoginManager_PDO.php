<?php

namespace Library\DAL\Models;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class LoginManager_PDO extends \Library\DAL\BaseManager {

  public function selectOne($user_obj) {
    if (isset($user_obj["username"])) {//Check if the user is giving his username and that there is a value
          $sql = 'SELECT * FROM project_manager where `username` = \''. $user_obj["username"] . '\' LIMIT 0, 1;';

    } else if (isset ($user_obj["email"])) {//Check if the user is giving an email
          $sql = 'SELECT * FROM project_manager where `email` = \''. $user_obj["email"] . '\' LIMIT 0, 1;';

    } else {
      return NULL;
    }
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\BO\ProjectManager');
    
    $pm = $query->fetchAll();
    $query->closeCursor();
    
    return $pm;    
  }
}
