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
   * @param iint $id
   */
  abstract public function countById($id);


//  abstract public function add($identifier);
//  abstract public function delete($identifier);
}
