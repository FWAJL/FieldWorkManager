<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ProjectServiceDal extends \Library\DAL\BaseManager {

  public function selectOne($object) {
    return NULL;
  }

  public function update($object) {
    return NULL;
  }

  /**
   * Returns list of Project_service for Project
   * 
   * @param \Applications\PMTool\Models\Dao\Project_service $object
   * @return array of \Applications\PMTool\Models\Dao\Project_service
   */
  public function selectMany($object) {
    $sql = 'SELECT project_id, service_id FROM project_service where `project_id` = \'' . $object->project_id() . '\';';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Project_service');

    $project_service_list = $query->fetchAll();
    $query->closeCursor();

    return count($project_service_list) > 0 ? $project_service_list : array();
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
