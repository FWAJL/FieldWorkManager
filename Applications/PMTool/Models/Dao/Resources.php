<?php

namespace Applications\PMTool\Models\Dao;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Resources extends \Library\Entity{
  public 
    $resource_id,
    $resource_type,
    $resource_name,
    $resource_url,
    $resource_address,
    $resource_contact_name,
    $resource_contact_phone,
    $resource_contact_email,
    $resource_active,
    $pm_id;

  const 
    RESOURCE_ID_ERR = 0,
    RESOURCE_TYPE_ERR = 1,
    RESOURCE_NAME_ERR = 2,
    RESOURCE_URL_ERR = 3,
    RESOURCE_ADDRESS_ERR = 4,
    RESOURCE_CONTACT_NAME_ERR = 5,
    RESOURCE_CONTACT_PHONE_ERR = 6,
    RESOURCE_CONTACT_EMAIL_ERR = 7,
    RESOURCE_ACTIVE_ERR = 8,
    PM_ID_ERR = 9;

  // SETTERS //
  public function setResource_id($resource_id) {
    if (empty($resource_id)) {
      $this->erreurs[] = self::RESOURCE_ID_ERR;
    } else {
      $this->resource_id = $resource_id;
    }
  }

  public function setResource_type($resource_type) {
    if (empty($resource_type)) {
      $this->erreurs[] = self::RESOURCE_TYPE_ERR;
    } else {
      $this->resource_type = $resource_type;
    }
  }

  public function setResource_name($resource_name) {
    if (empty($resource_name)) {
      $this->erreurs[] = self::RESOURCE_NAME_ERR;
    } else {
      $this->resource_name = $resource_name;
    }
  }

  public function setResource_url($resource_url) {
    if (empty($resource_url)) {
      $this->erreurs[] = self::RESOURCE_URL_ERR;
    } else {
      $this->resource_url = $resource_url;
    }
  }

  public function setResource_address($resource_address) {
    if (empty($resource_address)) {
      $this->erreurs[] = self::RESOURCE_ADDRESS_ERR;
    } else {
      $this->resource_address = $resource_address;
    }
  }

  public function setResource_contact_name($resource_contact_name) {
    if (empty($resource_contact_name)) {
      $this->erreurs[] = self::RESOURCE_CONTACT_NAME_ERR;
    } else {
      $this->resource_contact_name = $resource_contact_name;
    }
  }

  public function setResource_contact_phone($resource_contact_phone) {
    if (empty($resource_contact_phone)) {
      $this->erreurs[] = self::RESOURCE_CONTACT_PHONE_ERR;
    } else {
      $this->resource_contact_phone = $resource_contact_phone;
    }
  }

  public function setResource_contact_email($resource_contact_email) {
    if (empty($resource_contact_email)) {
      $this->erreurs[] = self::RESOURCE_CONTACT_EMAIL_ERR;
    } else {
      $this->resource_contact_email = $resource_contact_email;
    }
  }

  public function setResource_active($resource_active) {
    if (empty($resource_active)) {
      $this->erreurs[] = self::RESOURCE_ACTIVE_ERR;
    } else {
      $this->resource_active = $resource_active;
    }
  }

  public function setPm_id($pm_id) {
    if (empty($pm_id)) {
      $this->erreurs[] = self::PM_ID_ERR;
    } else {
      $this->pm_id = $pm_id;
    }
  }

  // GETTERS //
  public function resource_id() {
    return $this->resource_id;
  }

  public function resource_type() {
    return $this->resource_type;
  }

  public function resource_name() {
    return $this->resource_name;
  }

  public function resource_url() {
    return $this->resource_url;
  }

  public function resource_address() {
    return $this->resource_address;
  }

  public function resource_contact_name() {
    return $this->resource_contact_name;
  }

  public function resource_contact_phone() {
    return $this->resource_contact_phone;
  }

  public function resource_contact_email() {
    return $this->resource_contact_email;
  }

  public function resource_active() {
    return $this->resource_active;
  }

  public function pm_id() {
    return $this->pm_id;
  }


}