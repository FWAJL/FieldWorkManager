<?php

namespace Library\BO;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class News extends \Library\Entity {

  protected $pm_id,
          $username,
          $password,
          $hint,
          $pm_comp_name,
          $pm_name,
          $pm_address,
          $pm_phone,
          $pm_email;
  
  const PM_ID_ERR = 1;
  const USERNAME_ERR = 2;
  const PWD_ERR = 3;
  const PM_COMP_NAME_ERR = 4;
  const PM_NAME_ERR = 5;
  const PM_ADDRESS_ERR = 6;
  const PM_PHONE_ERR = 7;
  const PM_EMAIL_ERR = 8;

  public function isValid() {
    return !(empty($this->username) || empty($this->password));
  }

  // SETTERS //

  public function setUserName($username) {
    if (!is_string($username) || empty($username)) {
      $this->erreurs[] = self::USERNAME_ERR;
    } else {
      $this->username = $username;
    }
  }
  
  public function setPassword($pwd) {
    if (!is_string($pwd) || empty($pwd)) {
      $this->erreurs[] = self::PWD_ERR;
    } else {
      $this->password = $pwd;
    }
  }
  
  public function setPmCompName($pm_comp_name) {
    if (!is_string($pm_comp_name) || empty($pm_comp_name)) {
      $this->erreurs[] = self::PM_COMP_NAME_ERR;
    } else {
      $this->pm_comp_name = $pm_comp_name;
    }
  }
  
  public function setPmName($pm_name) {
    if (!is_string($pm_name) || empty($pm_name)) {
      $this->erreurs[] = self::PM_NAME_ERR;
    } else {
      $this->pm_name = $pm_name;
    }
  }
  
  public function setPmAddress($pm_address) {
    if (!is_string($pm_address) || empty($pm_address)) {
      $this->erreurs[] = self::PM_ADDRESS_ERR;
    } else {
      $this->pm_address = $pm_address;
    }
  }
  
  public function setPmPhone($pm_phone) {
    if (!is_string($pm_phone) || empty($pm_phone)) {
      $this->erreurs[] = self::PM_PHONE_ERR;
    } else {
      $this->pm_phone = $pm_phone;
    }
  }
  
  public function setPmEmail($pm_email) {
    if (!is_string($pm_email) || empty($pm_email)) {
      $this->erreurs[] = self::PM_EMAIL_ERR;
    } else {
      $this->pm_email = $pm_email;
    }
  }
  // GETTERS //

  public function pm_id() {
    return $this->pm_id;
  }

  public function username() {
    return $this->username;
  }

  public function password() {
    return $this->password;
  }

  public function pm_comp_name() {
    return $this->pm_comp_name;
  }

  public function pm_name() {
    return $this->pm_name;
  }
  
  public function pm_address() {
    return $this->pm_address;
  }
  
  public function pm_phone() {
    return $this->pm_phone;
  }
  
  public function pm_email() {
    return $this->pm_email;
  }

}