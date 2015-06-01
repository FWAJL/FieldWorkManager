<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class MasterLabAnalyteDal extends \Library\DAL\BaseManager {

  public function getMatchingMasterLabAnalytes($match_on) {
    $sql = 'select * from master_lab_analyte where master_lab_analyte_name LIKE :search_str';
    $dao = $this->dao->prepare($sql);
    $dao->bindValue(':search_str', '%' . $match_on . '%', \PDO::PARAM_STR);
    $dao->execute();
    $dao->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Master_lab_analyte');
    $search_res = $dao->fetchAll();
    $dao->closeCursor();
    return $search_res;
  }
}
