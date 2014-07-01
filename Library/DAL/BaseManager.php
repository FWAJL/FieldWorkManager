<?php

namespace Library\DAL;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

abstract class BaseManager extends \Library\Manager {
//  abstract public function selectMany($debut = -1, $limite = -1);

  /**
   * Select method for one item
   * 
   * @param array $item array containing the data to use to build the SQL statement
   */
  abstract public function selectOne($item);

  /**
   * Update method for one item
   * 
   * @param array $item array containing the data to use to build the SQL statement
   */
  abstract public function update($item);

  /**
   * Select method for many items
   * 
   * @param array $item array containing the data to use to build the SQL statement
   */
  abstract public function selectMany($item);
  /**
   * Select method to get a count by id
   * 
   * @param int $id
   */
  abstract public function countById($id);

  /**
   * Add method to add a item to DB
   * 
   * @param object $item
   */
  abstract public function add($item);
  
//  abstract public function delete($identifier);
}
