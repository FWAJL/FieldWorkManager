<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskDal extends \Library\DAL\BaseManager {

  public function selectOne($object) {
    return NULL;
  }

  public function update($object) {
    return NULL;
  }

  public function countById($pm_id) {
    return NULL;
  }

  public function selectTasksByTechnician($technician) {
    $sql = "SELECT t.* FROM task AS t JOIN task_technician AS tt ON tt.task_id = t.task_id WHERE tt.technician_id = :technician_id AND t.task_active = 1";
    $sth = $this->dao->prepare($sql);
    $sth->bindValue(':technician_id',$technician->technician_id(),\PDO::PARAM_INT);
    $sth->execute();
    $sth->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Task');
    $tasks = $sth->fetchAll();
    $sth->closeCursor();
    return $tasks;
  }
}
