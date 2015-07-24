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

  public function countById($pm_id) {
    return NULL;
  }

  //public function add($object) {
  //}

  //public function edit($object) {
  //}

  //public function delete($object) {
  //}

  /**
  * Returns form from the Document table
  * matching on task_id and location_id
  */
  public function GetTaskLocations($loc_id, $task_id) {
    $sql = 'select * from task_location where task_id = :task_id and location_id = :location_id';
    $dao = $this->dao->prepare($sql);
    $dao->bindValue(':task_id', $task_id, \PDO::PARAM_INT);
    $dao->bindValue(':location_id', $loc_id, \PDO::PARAM_INT);
    $dao->execute();
    $dao->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Task_location');
    $search_res = $dao->fetchAll();
    $dao->closeCursor();
    return $search_res;
  }

}
