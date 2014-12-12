<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskLocationDal extends \Library\DAL\BaseManager {

  public function selectOne($object) {
    return NULL;
  }

  public function update($object) {
    return NULL;
  }

  /**
   * Returns list of Task_location for Task
   * 
   * @param \Applications\PMTool\Models\Dao\Task_location $object
   * @return array of \Applications\PMTool\Models\Dao\Task_location
   */
  public function selectMany($object) {
    $sql = 'SELECT task_id, location_id FROM task_location where `task_id` = \'' . $object->task_id() . '\';';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Task_location');

    $task_location_list = $query->fetchAll();
    $query->closeCursor();

    return count($task_location_list) > 0 ? $task_location_list : array();
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
