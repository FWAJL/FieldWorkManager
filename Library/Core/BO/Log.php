<?php

/**
 *
 * @package     Basic MVC framework
 * @author      Jeremie Litzler
 * @copyright   Copyright (c) 2015
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Log Class
 *
 * @package       Library
 * @subpackage    Core\BO
 * @category      Log
 * @author        Jeremie Litzler
 * @link		
 */

namespace Library\Core\BO;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class Log {

  public
      $type,
      $id,
      $url;

  //Getters
  public function type() {
    return $this->type;
  }
  public function id() {
    return $this->id;
  }
  public function url() {
    return $this->url;
  }
  
  //Setters
  public function setType($type) {
    $this->type = $type;
  }
  public function setId($id) {
    $this->id = $id;
  }
  public function setUrl($url) {
    $this->url = $url;
  }
}
