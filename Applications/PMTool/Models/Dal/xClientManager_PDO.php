<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ClientManager_PDO extends \Library\DAL\BaseManager {

  /**
   * Select a Client from its id and pm_id
   * 
   * @param Client $p
   * @return array the selected row in the db
   */
  public function selectOne($client_in) {
    return NULL;
  }

  /**
   * Select a PM from its username or password
   * 
   * @param ClientManager $pm
   * @return array the selected row in the db
   */
//  public function update($client) {
//    $sql = $this->dao->prepare("UPDATE client set `password` = :password WHERE `pm_id` = :pm_id;");
//    $sql->bindValue(":pm_id", $pm->pm_id(), \PDO::PARAM_INT);
//    $sql->bindValue(":password", $pm->password(), \PDO::PARAM_STR);
//
//    try {
//      return $sql->execute();
//    } catch (Exception $exc) {
//      echo $exc->getTraceAsString();
//    }
//  }

  /**
   * Returns list of clients for PM
   * 
   * @param \Applications\PMTool\Models\Dao\Client $client
   * @return array of \Applications\PMTool\Models\Dao\Client
   */
  public function selectMany($client) {
    $sql = 'SELECT * FROM `client` c inner join `project` p on c.project_id = p.project_id';
    $sql .= ' where p.`pm_id` = \'' . $client->pm_id() . '\';'; //AND `active` = 1  AND `visible` = 1;';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Client');

    $client_list = $query->fetchAll();
    $query->closeCursor();

    return $client_list;
  }

  public function countById($pm_id) {
    $sql = 'SELECT COUNT(*) FROM client where `pm_id` = \'' . $pm_id . '\';'; // AND `active` = 1  AND `visible` = 1;';
    $query = $this->dao->query($sql);
    $num_rows = $query->fetch(\PDO::FETCH_NUM);
    $query->closeCursor();

    return intval($num_rows[0]);
  }

  public function add($client) {
    $columns = "";
    $values = "";
    foreach ($client as $key => $value) {
      $columns .= "`" . $key . "`,";
      $values .= "'" . addslashes($value) . "',";
    }
    $columns = rtrim($columns, ", ");
    $values = rtrim($values, ", ");
    $sql = "INSERT INTO `client` (" . $columns . ") VALUES (" . $values . ");";
    $query = $this->dao->query($sql);
    $result;
    if (!$query) {
      $result = $query->errorCode();
    } else {
      $result = TRUE;
    }
    $query->closeCursor();
    return $result;
  }

  public function edit($client) {
    $set_clause = "";
    $where_clause = "";
    foreach ($client as $key => $value) {
      if ($key === "project_id") {
        //skip
      }
      elseif ($key === "client_id") {
        $where_clause = "$key = $value";
      } else {
        $set_clause .= "`" . $key . "` = '" . addslashes($value) ."',"; 
      }
    }
    $set_clause = rtrim($set_clause, ",");
    $sql = "UPDATE `client` SET $set_clause  WHERE $where_clause;";
    $query = $this->dao->query($sql);
    $result;
    if (!$query) {
      $result = $query->errorCode();
    } else {
      $result = TRUE;
    }
    $query->closeCursor();
    return $result;
  }

  public function delete($identifier) {
    $sql = "DELETE from `client` WHERE client_id = " . $identifier . ";";
    $query = $this->dao->query($sql);
    $result;
    if (!$query) {
      $result = $query->errorCode();
    } else {
      $result = TRUE;
    }
    $query->closeCursor();
    return $result;
  }

}
