<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

/**
 * Replace '_Template' by your custom name
 */
class TechnicianDal extends \Library\DAL\BaseManager {

  public function selectOne($object, $where_filter_id = "", $filter_as_string = false) {
    return NULL;
  }

  public function update($object) {
    return NULL;
  }

//  public function add($object) {  }

//  public function edit($object) {  }

//  public function delete($identifier) {  }

}
