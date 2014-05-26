<?php

namespace Library\DAL\Models;

class LoginManager_PDO extends \Library\DAL\BaseManager {

  public function selectOne($user_obj) {
    $sql = 'SELECT * FROM project_manager where username = `'. $user_obj["username"] . '` LIMIT 0, 1;';
    error_log($sql);
    $requete = $this->dao->query($sql);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\BO\ProjectManager');
    
    $pm = $requete->fetchAll();
    $requete->closeCursor();
    
    return $pm;
    
  }

}

?>
