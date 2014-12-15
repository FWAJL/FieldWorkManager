<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskTechnicianDal extends \Library\DAL\BaseManager {

  public function selectOne($object) {
    return NULL;
  }

  public function update($object) {
    return NULL;
  }

  /**
   * Returns list of Task_technician for Task
   * 
   * @param \Applications\PMTool\Models\Dao\Task_technician $object
   * @return array of \Applications\PMTool\Models\Dao\Task_technician
   */
  public function selectMany($object) {
    $sql = 'SELECT task_id, technician_id, is_lead_tech FROM task_technician where `task_id` = \'' . $object->task_id() . '\';';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Task_technician');

    $task_technician_list = $query->fetchAll();
    $query->closeCursor();

    return count($task_technician_list) > 0 ? $task_technician_list : array();
  }

  public function countById($pm_id) {
    return NULL;
  }

  //public function add($object) {
  //}

  //public function edit($object) {
  //}

  //public function delete($object) {
  //}

}
