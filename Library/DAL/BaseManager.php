<?php

namespace Library\DAL;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

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

//  abstract public function add($identifier);
//  abstract public function delete($identifier);
}
