<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ClientDal extends \Library\DAL\BaseManager {

  /**
   * Select a Client from its id and pm_id
   * 
   * @param Client $p
   * @return array the selected row in the db
   */
  public function selectOne($client_in, $where_filter_id = "", $filter_as_string = false) {
    return NULL;
  }

  /**
   * Select a PM from its username or password
   * 
   * @param ClientManager $pm
   * @return array the selected row in the db
   */
  public function update($client) {
    $sql = $this->dao->prepare("UPDATE client set `password` = :password WHERE `pm_id` = :pm_id;");
    $sql->bindValue(":pm_id", $pm->pm_id(), \PDO::PARAM_INT);
    $sql->bindValue(":password", $pm->password(), \PDO::PARAM_STR);

    try {
      return $sql->execute();
    } catch (Exception $exc) {
      echo $exc->getTraceAsString();
    }
  }

  /**
   * Returns list of clients for PM
   * 
   * @param \Applications\PMTool\Models\Dao\Client $client
   * @return array of \Applications\PMTool\Models\Dao\Client
   */
  public function selectMany($client, $where_filter_id, $filter_as_string = false) {
    $sql = 'SELECT c.* FROM `client` c inner join `project` p on c.project_id = p.project_id';
    $sql .= ' where p.`'. $where_filter_id.'` = :where_filter_id;'; //AND `active` = 1  AND `visible` = 1;';
    $sth = $this->dao->prepare($sql);
    $sth->bindValue(':where_filter_id',$client->$where_filter_id(),\PDO::PARAM_INT);
    $query = $sth->execute();
    if($query) {
      $sth->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Client');

      $client_list = $sth->fetchAll();
      $sth->closeCursor();
      return $client_list;
    } else {
      $sth->closeCursor();
      return -1;
    }



  }

  public function countById($pm_id) {
    $sql = 'SELECT COUNT(*) FROM client where `pm_id` = :pm_id;'; // AND `active` = 1  AND `visible` = 1;';
    $sth = $this->dao->prepare($sql);
    $sth->bindValue(':pm_id',$pm_id,\PDO::PARAM_INT);
    $num_rows = $sth->fetch(\PDO::FETCH_NUM);
    $sth->closeCursor();

    return intval($num_rows[0]);
  }

//  public function add($client) {  }

//  public function edit($client) {  }

//  public function delete($identifier) {  }

}
