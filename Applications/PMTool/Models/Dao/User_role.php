<?php
/**** @package    Basic MVC framework* @author     Jeremie Litzler* @copyright  Copyright (c) 2014* @license* @link* @since* @filesource*/// ------------------------------------------------------------------------
/**** User_role Dao Class** @package     Application/PMTool* @subpackage  Models/Dao* @category    User_role* @author      FWM DEV Team* @link*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class User_role extends \Library\Entity{  public     $user_role_id,    $user_role_desc;
  const     USER_ROLE_ID_ERR = 0,    USER_ROLE_DESC_ERR = 1;
  // SETTERS //  public function setUser_role_id($user_role_id) {      $this->user_role_id = $user_role_id;  }
  public function setUser_role_desc($user_role_desc) {      $this->user_role_desc = $user_role_desc;  }
  // GETTERS //  public function user_role_id() {    return $this->user_role_id;  }
  public function user_role_desc() {    return $this->user_role_desc;  }
}