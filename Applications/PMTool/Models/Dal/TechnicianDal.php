<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

/**
 * Replace '_Template' by your custom name
 */
class TechnicianDal extends \Library\DAL\BaseManager {

  public function selectOne($object) {
    return NULL;
  }

  public function update($object) {
    return NULL;
  }

   public function selectMany($object) {
    $sql = 'SELECT * FROM technician where `pm_id` = \'' . $object->pm_id() . '\';'; //AND `active` = 1  AND `visible` = 1;';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Technician');

    $technicians_list = $query->fetchAll();
    $query->closeCursor();

    return count($technicians_list) > 0 ? $technicians_list : array();
  }

  public function countById($pm_id) {
    return NULL;
  }

//  public function add($object) {  }

//  public function edit($object) {  }

//  public function delete($identifier) {  }

}
