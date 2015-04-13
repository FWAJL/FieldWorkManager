<?php

namespace Library;

if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
session_start();

class User extends ApplicationComponent {

  public function getAttribute($attr) {
    return isset($_SESSION[$attr]) ? $_SESSION[$attr] : null;
  }

  public function getFlash() {
    $flash = $_SESSION[Enums\SessionKeys::UserFlash];
    unset($_SESSION[Enums\SessionKeys::UserFlash]);

    return $flash;
  }

  public function hasFlash() {
    return isset($_SESSION[Enums\SessionKeys::UserFlash]);
  }

  public function isAuthenticated() {
    return isset($_SESSION[Enums\SessionKeys::UserAuthenticated]) && $_SESSION[Enums\SessionKeys::UserAuthenticated] === true;
  }

  public function setRole($role) {
    $_SESSION[Enums\SessionKeys::UserRole] = $role;
  }

  public function getRole() {
    return isset($_SESSION[Enums\SessionKeys::UserRole])?$_SESSION[Enums\SessionKeys::UserRole]:null;
  }

  public function setUserType($type) {
    $_SESSION[Enums\SessionKeys::UserType] = $type;
  }

  public function getUserType() {
    return isset($_SESSION[Enums\SessionKeys::UserType])?$_SESSION[Enums\SessionKeys::UserType]:null;
  }

  public function setUserTypeId($userTypeId) {
    $_SESSION[Enums\SessionKeys::UserTypeId] = $userTypeId;
  }

  public function getUserTypeId() {
    return isset($_SESSION[Enums\SessionKeys::UserTypeId])?$_SESSION[Enums\SessionKeys::UserTypeId]:null;
  }

  public function setAttribute($attr, $value) {
    $_SESSION[$attr] = $value;
  }

  public function setAuthenticated($authenticated = true) {
    if (!is_bool($authenticated)) {
      throw new \InvalidArgumentException('Value of method User::setAuthenticated() must be a boolean');
    }

    $_SESSION[Enums\SessionKeys::UserAuthenticated] = $authenticated;
  }

  public function setFlash($value) {
    $_SESSION[Enums\SessionKeys::UserFlash] = $value;
  }
  /**
   * Unset a session item of given key
   * 
   * @param string $key (Enums\SessionKeys)
   */
  public function unsetAttribute($key) {
    unset($_SESSION[$key]);
  }

  public function keyExistInSession($key) {
    return isset($_SESSION[$key]);
  }
}