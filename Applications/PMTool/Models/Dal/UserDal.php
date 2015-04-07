<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class UserDal extends \Library\DAL\BaseManager {

  public function selectAllUsers() {
    $sql = 'SELECT u.* FROM `user` u WHERE u.`user_role_id` <> 1';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\User');

    $list = $query->fetchAll();
    $query->closeCursor();

    return $list;
  }

}
