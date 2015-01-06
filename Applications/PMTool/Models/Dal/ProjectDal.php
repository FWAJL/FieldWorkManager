<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ProjectDal extends \Library\DAL\BaseManager {

  public function selectOne($project) {
    return NULL;
  }

  public function update($project) {
    return NULL;
  }

  public function countById($pm_id) {
    $sql = 'SELECT COUNT(*) FROM project where `pm_id` = \'' . $pm_id . '\';'; // AND `project_active` = 1  AND `visible` = 1;';
    $query = $this->dao->query($sql);
    $num_rows = $query->fetch(\PDO::FETCH_NUM);
    $query->closeCursor();

    return intval($num_rows[0]);
  }
}
