<?php

namespace Library\DAL\Models;

class LoginManager_PDO extends \Library\DAL\BaseManager {

  public function selectOne($id) {
    $sql = 'SELECT * FROM project_manager where pm_id = '. $id . 'LIMIT 0, 1;';
    
    $requete = $this->dao->query($sql);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Library\BO\ProjectManager');
    
    $pm = $requete->fetchAll();
    $requete->closeCursor();
    
    return $pm;
    
  }

}

?>
