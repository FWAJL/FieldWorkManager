<?php
namespace Library\DAL;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

abstract class BaseManager extends \Library\Manager
{
//  abstract public function selectMany($debut = -1, $limite = -1);

  abstract public function selectOne($identifier);
  
//  abstract public function update($identifier);
  
//  abstract public function add($identifier);
  
//  abstract public function delete($identifier);
}