<?php

namespace Library\DAL\Models;

class LoginManager_PDO extends \Library\DAL\BaseManager {

  public function selectOne($user_obj) {
    $sql = 'SELECT * FROM project_manager where username = \''. $user_obj["username"] . '\' LIMIT 0, 1;';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\BO\ProjectManager');
    
    $pm = $query->fetchAll();
    $query->closeCursor();
    
    return $pm;    
  }
}
