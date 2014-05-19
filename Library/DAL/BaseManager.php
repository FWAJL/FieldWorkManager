<?php
namespace Library\DAL;

abstract class BaseManager extends \Library\BL\Core\Manager
{
//  abstract public function selectMany($debut = -1, $limite = -1);

  abstract public function selectOne($identifier);
  
//  abstract public function update($identifier);
  
//  abstract public function add($identifier);
  
//  abstract public function delete($identifier);
}