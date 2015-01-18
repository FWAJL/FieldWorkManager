<?php

/**
 *
 * @package		Basic MVC framework
 * @author		FWM DEV Team
 * @copyright	Copyright (c) 2015
 * @license
 * @link
 * @since
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Authenticate controller Class
 *
 * @package		Application/PMTool
 * @subpackage	Models\Dao
 * @category	WebUser
 * @author		FWM DEV Team
 * @link
 */

namespace Applications\PMTool\Models\Dao;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class WebUser extends \Library\Entity{
  public 
    $user_type,
    $user_object;
  
  // SETTERS //
  public function setUser_type($user_type) {
      $this->user_type = $user_type;
  }

  public function setUser_object($user_object) {
      $this->user_object = $user_object;
  }

  // GETTERS //
  public function user_type() {
    return $this->user_type;
  }

  public function user_object() {
    return $this->user_object;
  }
}