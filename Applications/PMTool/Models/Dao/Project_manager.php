<?php
/**
*
* @package    Basic MVC framework
* @author     Jeremie Litzler
* @copyright  Copyright (c) 2015
* @license
* @link
* @since
* @filesource
*/
// ------------------------------------------------------------------------
/**
*
* Project_manager Dao Class
*
* @package     Application/PMTool
* @subpackage  Models/Dao
* @category    Project_manager
* @author      FWM DEV Team
* @link
*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Project_manager extends \Library\Entity{  public     $pm_id,    $username,    $password,    $hint,    $pm_comp_name,    $pm_name,    $pm_address,    $pm_phone,    $pm_email;
  const     PM_ID_ERR = 0,    USERNAME_ERR = 1,    PASSWORD_ERR = 2,    HINT_ERR = 3,    PM_COMP_NAME_ERR = 4,    PM_NAME_ERR = 5,    PM_ADDRESS_ERR = 6,    PM_PHONE_ERR = 7,    PM_EMAIL_ERR = 8;
  // SETTERS //  public function setPm_id($pm_id) {      $this->pm_id = $pm_id;  }
  public function setUsername($username) {      $this->username = $username;  }
  public function setPassword($password) {      $this->password = $password;  }
  public function setHint($hint) {      $this->hint = $hint;  }
  public function setPm_comp_name($pm_comp_name) {      $this->pm_comp_name = $pm_comp_name;  }
  public function setPm_name($pm_name) {      $this->pm_name = $pm_name;  }
  public function setPm_address($pm_address) {      $this->pm_address = $pm_address;  }
  public function setPm_phone($pm_phone) {      $this->pm_phone = $pm_phone;  }
  public function setPm_email($pm_email) {      $this->pm_email = $pm_email;  }
  // GETTERS //  public function pm_id() {    return $this->pm_id;  }
  public function username() {    return $this->username;  }
  public function password() {    return $this->password;  }
  public function hint() {    return $this->hint;  }
  public function pm_comp_name() {    return $this->pm_comp_name;  }
  public function pm_name() {    return $this->pm_name;  }
  public function pm_address() {    return $this->pm_address;  }
  public function pm_phone() {    return $this->pm_phone;  }
  public function pm_email() {    return $this->pm_email;  }
}