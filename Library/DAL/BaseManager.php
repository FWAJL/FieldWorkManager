<?php

namespace Library\DAL;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class BaseManager extends \Library\Manager {
//  abstract public function selectMany($debut = -1, $limite = -1);

  /**
   * Select method for one item
   *
   * @param array $item array containing the data to use to build the SQL statement
   */
  public function selectOne($item) {
    
  }

  /**
   * Update method for one item
   *
   * @param array $item array containing the data to use to build the SQL statement
   */
  public function update($item) {
    
  }

  /**
   * Select method for many items
   *
   * @param array $item array containing the data to use to build the SQL statement
   */
  public function selectMany($item) {
    
  }

  /**
   * Select method to get a count by id
   *
   * @param int $id
   */
  public function countById($id) {
    
  }

  /**
   * Add method to add a item to DB
   *
   * @param object $item
   */
  public function add($object) {
    $columns = "";
    $values = "";
    foreach ($object as $key => $value) {
      $columns .= "`" . $key . "`,";
      $values .= "'" . $value . "',";
    }
    $columns = rtrim($columns, ", ");
    $values = rtrim($values, ", ");
    return $this->ExecuteQuery("INSERT INTO `" . $this->GetTableName($object) . "` ($columns) VALUES ($values);", TRUE);
  }

  /**
   * Edit method to update a item into DB
   *
   * @param object $item
   */
  public function edit($object, $where_filter_id) {
    $set_clause = "";
    $where_clause = "";
    foreach ($object as $key => $value) {
      if ($key === $where_filter_id) {
        $where_clause = "$key = $value";
      } else {
        $set_clause .= "`$key` = '$value',";
      }
    }
    $set_clause = rtrim($set_clause, ",");
    return $this->ExecuteQuery(
            "UPDATE `" . $this->GetTableName($object) . "` SET $set_clause  WHERE $where_clause;");
  }

  /**
   * Add method to delete a item to DB
   *
   * @param int $identifier
   */
  public function delete($object, $where_filter_id) {
    return $this->ExecuteQuery(
            "DELETE from `" . $this->GetTableName($object) . "` WHERE $where_filter_id = " . $object->$where_filter_id() . ";");
  }

  private function GetTableName($object) {
    return \Applications\PMTool\Helpers\CommonHelper::GetClassName($object);
  }

  private function ExecuteQuery($sql_query, $is_insert = FALSE) {
    try {
      $query = $this->dao->query($sql_query);
      $result;
      if (!$query) {
        $result = $query->errorCode();
      } else {
        $result = $is_insert ? $this->dao->lastInsertId() : TRUE;
      }
      $query->closeCursor();
    } catch (\PDOException $pdo_ex) {
      json_encode($pdo_ex);
      //echo $pdo_ex->getTraceAsString();
      $result = FALSE;
    }
    return $result;
  }

}
