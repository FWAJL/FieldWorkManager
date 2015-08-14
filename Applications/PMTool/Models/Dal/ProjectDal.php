<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ProjectDal extends \Library\DAL\BaseManager {

  public function selectOne($project, $where_filter_id = "", $filter_as_string = false) {
    return NULL;
  }

  public function update($project) {
    return NULL;
  }

  public function countById($pm_id) {
    $sql = 'SELECT COUNT(*) FROM project where `pm_id` = :pm_id;'; // AND `project_active` = 1  AND `visible` = 1;';
    $sth = $this->dao->prepare($sql);
    $sth->bindValue(':pm_id',$pm_id,\PDO::PARAM_INT);
    $sth->execute();
    $num_rows = $sth->fetch(\PDO::FETCH_NUM);
    $sth->closeCursor();

    return intval($num_rows[0]);
  }
}
