<?php

namespace Library\BO;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Project_manager extends \Library\Entity{
  public 
    $pm_id,
    $username,
    $password,
    $hint,
    $pm_comp_name,
    $pm_name,
    $pm_address,
    $pm_phone,
    $pm_email;

  const 
    PM_ID_ERR = 0,
    USERNAME_ERR = 1,
    PASSWORD_ERR = 2,
    HINT_ERR = 3,
    PM_COMP_NAME_ERR = 4,
    PM_NAME_ERR = 5,
    PM_ADDRESS_ERR = 6,
    PM_PHONE_ERR = 7,
    PM_EMAIL_ERR = 8;

  // SETTERS //
  public function setPm_id($pm_id) {
    if (empty($pm_id)) {
      $this->erreurs[] = self::PM_ID_ERR;
    } else {
      $this->pm_id = $pm_id;
    }
  }

  public function setUsername($username) {
    if (empty($username)) {
      $this->erreurs[] = self::USERNAME_ERR;
    } else {
      $this->username = $username;
    }
  }

  public function setPassword($password) {
    if (empty($password)) {
      $this->erreurs[] = self::PASSWORD_ERR;
    } else {
      $this->password = $password;
    }
  }

  public function setHint($hint) {
    if (empty($hint)) {
      $this->erreurs[] = self::HINT_ERR;
    } else {
      $this->hint = $hint;
    }
  }

  public function setPm_comp_name($pm_comp_name) {
    if (empty($pm_comp_name)) {
      $this->erreurs[] = self::PM_COMP_NAME_ERR;
    } else {
      $this->pm_comp_name = $pm_comp_name;
    }
  }

  public function setPm_name($pm_name) {
    if (empty($pm_name)) {
      $this->erreurs[] = self::PM_NAME_ERR;
    } else {
      $this->pm_name = $pm_name;
    }
  }

  public function setPm_address($pm_address) {
    if (empty($pm_address)) {
      $this->erreurs[] = self::PM_ADDRESS_ERR;
    } else {
      $this->pm_address = $pm_address;
    }
  }

  public function setPm_phone($pm_phone) {
    if (empty($pm_phone)) {
      $this->erreurs[] = self::PM_PHONE_ERR;
    } else {
      $this->pm_phone = $pm_phone;
    }
  }

  public function setPm_email($pm_email) {
    if (empty($pm_email)) {
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

  public function hint() {
    return $this->hint;
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