<?php

namespace Library\DAL;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class BaseManager extends \Library\Manager {
  public function __construct($dao, $filters) {
    parent::__construct($dao, $filters);
  }

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
   * @param object <p> 
   * $object: Dao object
   * @param mixed <p>
   * $where_filter_id: a string or integer value
   * representing the column name to filter the data on. It is used in the WHERE clause.
   * @param bool <p>
   * $filter_as_string: TRUE or FALSE to know if a where filter is a string or a integer </p>
   * @return mixed <p>
   * Can be a bool (TRUE,FALSE), a integer or a list of Dao objects (of type  $dao_class) </p>
   */
  public function selectMany($object, $where_filter_id, $filter_as_string = false) {
    $params = array("type" => "SELECT", "dao_class" => \Applications\PMTool\Helpers\CommonHelper::GetFullClassName($object));
    if ($where_filter_id !== "") {
        $where_clause = " WHERE " . $where_filter_id . " = :where_filter_id";
    } else {
      $where_clause = "";
    }

    $order_by = "";
    if($object->getOrderByField() !== FALSE) {
      $order_by = "ORDER BY ".$object->getOrderByField();
    }
    $select_clause = "SELECT ";
    foreach ($object as $key => $value) {
      $select_clause .= $key . ", ";
    }
    $select_clause = rtrim($select_clause, ", ");
    $select_clause .= " FROM ".$this->GetTableName($object).$where_clause." ".$order_by;
    $sth = $this->dao->prepare($select_clause);
    if($where_filter_id !== "") {
      if($filter_as_string) {
        $sth->bindValue(':where_filter_id',$object->$where_filter_id(),\PDO::PARAM_STR);
      } else {
        $sth->bindValue(':where_filter_id',$object->$where_filter_id(),\PDO::PARAM_INT);
      }
    }

    return $this->ExecuteQuery($sth, $params);
  }
  /**
   * Select method for many items
   * 
   * @param object <p> 
   * $object: Dao object
   * @param array <p>
   * $where_filters: an array following structure:
   * 
   * representing the column name to filter the data on. It is used in the WHERE clause.
   * @param bool <p>
   * $filter_as_string: TRUE or FALSE to know if a where filter is a string or a integer </p>
   * @return mixed <p>
   * Can be a bool (TRUE,FALSE), a integer or a list of Dao objects (of type  $dao_class) </p>
   */
  public function selectManyComplex($object, $where_filters) {
    $params = array("type" => "SELECT", "dao_class" => \Applications\PMTool\Helpers\CommonHelper::GetFullClassName($object));
    $select_clause = "SELECT ";
    //TODO: implement building the where clause with one or many filters
    $where_clause = "";//$this->BuildWhereClause($where_filters);
    foreach ($object as $key => $value) {
      $select_clause .= $key . ", ";
    }
    $select_clause = rtrim($select_clause, ", ");
    $select_clause.=" FROM ".$this->GetTableName($object);
    $order_by = "";
    if($object->getOrderByField() !== FALSE) {
      $order_by = "ORDER BY ".$object->getOrderByField();
    }
    $select_clause .= $where_clause." ".$order_by;

    $sth = $this->dao->prepare($select_clause);
    return $this->ExecuteQuery($sth, $params);
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
    $params = array("type" => "INSERT");
    $columns = "";
    $values = "";
    foreach ($object as $key => $value) {
      $columns .= "`" . $key . "`,";
      $values .= ":$key,";
    }
    $columns = rtrim($columns, ", ");
    $values = rtrim($values, ", ");
    $insert_clause = "INSERT INTO `".$this->GetTableName($object)."` ($columns) VALUES ($values);";
    $sth = $this->dao->prepare($insert_clause);
    foreach($object as $key=>$value) {
      $sth->bindValue(":$key",$value,\PDO::PARAM_STR);
    }
    return $this->ExecuteQuery($sth, $params);
  }

  /**
   * Edit method to update a item into DB
   *
   * @param object $item
   */
  public function edit($object, $where_filter_id) {
    $params = array("type" => "UPDATE");
    $where_clause = $set_clause = "";
    foreach ($object as $key => $value) {
      if ($key === $where_filter_id) {
        $where_clause = "$key = :$key";
      } else {
        $set_clause .= "`$key` = :$key,";
      }
    }
    $set_clause = rtrim($set_clause, ",");
    $update_clause = "UPDATE `" . $this->GetTableName($object) . "` SET $set_clause  WHERE $where_clause;";
    $sth = $this->dao->prepare($update_clause);
    foreach ($object as $key => $value) {
      $sth->bindValue("$key",$value,\PDO::PARAM_STR);
    }
    return $this->ExecuteQuery($sth, $params);
  }

  /**
   * Add method to delete a item to DB
   *
   * @param int $identifier
   */
  public function delete($object, $where_filter_id) {
    $params = array("type" => "DELETE");
    $delete_clause = "DELETE from `" . $this->GetTableName($object) . "` WHERE $where_filter_id = " . $object->$where_filter_id() . ";";
    $sth = $this->dao->prepare($delete_clause);
    return $this->ExecuteQuery($sth, $params);
  }

  private function GetTableName($object) {
    return \Applications\PMTool\Helpers\CommonHelper::GetShortClassName($object);
  }

  private function ExecuteQuery($sth, $params) {
    $result = -1;
    try {
      //\Library\Utility\DebugHelper::LogAsHtmlComment($sql_query);
      $query = $sth->execute();
      if (!$query) {
        $result = $query->errorCode();
      } else {
        switch ($params["type"]) {
          case "SELECT":
            $sth->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $params["dao_class"]);
            $list = $sth->fetchAll();
            $sth->closeCursor();
            return count($list) > 0 ? $list : array();
            break;
          case "UPDATE":
          case "DELETE":
            $result = TRUE;
            break;
          case "INSERT":
            $result = $this->dao->lastInsertId();
            break;
          default:
            break;
        }
        
      }
      $sth->closeCursor();
    } catch (\PDOException $pdo_ex) {
      json_encode($pdo_ex);
      //echo "<!--" . $pdo_ex->getMessage() . "-->";
      $result *=  $pdo_ex->getCode();
    }
    return $result;
  }

}
