<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

/**
 * Replace '_Template' by your custom name
 */
class ServiceDal extends \Library\DAL\BaseManager {

  public function selectOne($object) {
    return NULL;
  }

  public function update($object) {
    return NULL;
  }

   public function selectMany($object) {
    $sql = 'SELECT * FROM service where `pm_id` = \'' . $object->pm_id() . '\';'; //AND `active` = 1  AND `visible` = 1;';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Service');

    $services_list = $query->fetchAll();
    $query->closeCursor();

    return count($services_list) > 0 ? $services_list : array();
  }

  public function countById($pm_id) {
    return NULL;
  }

//  public function add($object) {  }

//  public function edit($object) {  }

//  public function delete($identifier) {  }

}
