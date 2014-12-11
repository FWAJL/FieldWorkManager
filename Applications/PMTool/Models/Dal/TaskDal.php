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

  /**
   * Returns list of objects for PM
   * 
   * @param \Applications\PMTool\Models\Dao\Project $object
   * @return array of \Applications\PMTool\Models\Dao\Project
   */
  public function selectMany($object) {
    $sql = 'SELECT * FROM task where `project_id` = \'' . $object->project_id() . '\';'; //AND `active` = 1  AND `visible` = 1;';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Task');

    $tasks_list = $query->fetchAll();
    $query->closeCursor();

    return count($tasks_list) > 0 ? $tasks_list : array();
  }

  public function countById($pm_id) {
    return NULL;
  }

//  public function add($object) {    }

//  public function edit($object) {  }

//  public function delete($identifier) {  }

}
