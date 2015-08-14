<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskCocDal extends \Library\DAL\BaseManager {

  public function selectOne($object, $where_filter_id = "", $filter_as_string = false) {
    return NULL;
  }

  public function update($object) {
    return NULL;
  }

  public function countById($pm_id) {
    return NULL;
  }
}
