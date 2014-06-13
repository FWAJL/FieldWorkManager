<?php

namespace Library\BO;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Facility extends \Library\Entity{
  public 
    $facility_id,
    $project_id,
    $facility_name,
    $facility_address,
    $facility_lat,
    $facility_long,
    $facility_contact_name,
    $facility_contact_phone,
    $facility_contact_email,
    $facility_id_num,
    $facility_sector,
    $faciliy_sic,
    $boundary;

  const 
    FACILITY_ID_ERR = 0,
    PROJECT_ID_ERR = 1,
    FACILITY_NAME_ERR = 2,
    FACILITY_ADDRESS_ERR = 3,
    FACILITY_LAT_ERR = 4,
    FACILITY_LONG_ERR = 5,
    FACILITY_CONTACT_NAME_ERR = 6,
    FACILITY_CONTACT_PHONE_ERR = 7,
    FACILITY_CONTACT_EMAIL_ERR = 8,
    FACILITY_ID_NUM_ERR = 9,
    FACILITY_SECTOR_ERR = 10,
    FACILIY_SIC_ERR = 11,
    BOUNDARY_ERR = 12;

  // SETTERS //
  public function setFacility_id($facility_id) {
    if (empty($facility_id)) {
      $this->erreurs[] = self::FACILITY_ID_ERR;
    } else {
      $this->facility_id = $facility_id;
    }
  }

  public function setProject_id($project_id) {
    if (empty($project_id)) {
      $this->erreurs[] = self::PROJECT_ID_ERR;
    } else {
      $this->project_id = $project_id;
    }
  }

  public function setFacility_name($facility_name) {
    if (empty($facility_name)) {
      $this->erreurs[] = self::FACILITY_NAME_ERR;
    } else {
      $this->facility_name = $facility_name;
    }
  }

  public function setFacility_address($facility_address) {
    if (empty($facility_address)) {
      $this->erreurs[] = self::FACILITY_ADDRESS_ERR;
    } else {
      $this->facility_address = $facility_address;
    }
  }

  public function setFacility_lat($facility_lat) {
    if (empty($facility_lat)) {
      $this->erreurs[] = self::FACILITY_LAT_ERR;
    } else {
      $this->facility_lat = $facility_lat;
    }
  }

  public function setFacility_long($facility_long) {
    if (empty($facility_long)) {
      $this->erreurs[] = self::FACILITY_LONG_ERR;
    } else {
      $this->facility_long = $facility_long;
    }
  }

  public function setFacility_contact_name($facility_contact_name) {
    if (empty($facility_contact_name)) {
      $this->erreurs[] = self::FACILITY_CONTACT_NAME_ERR;
    } else {
      $this->facility_contact_name = $facility_contact_name;
    }
  }

  public function setFacility_contact_phone($facility_contact_phone) {
    if (empty($facility_contact_phone)) {
      $this->erreurs[] = self::FACILITY_CONTACT_PHONE_ERR;
    } else {
      $this->facility_contact_phone = $facility_contact_phone;
    }
  }

  public function setFacility_contact_email($facility_contact_email) {
    if (empty($facility_contact_email)) {
      $this->erreurs[] = self::FACILITY_CONTACT_EMAIL_ERR;
    } else {
      $this->facility_contact_email = $facility_contact_email;
    }
  }

  public function setFacility_id_num($facility_id_num) {
    if (empty($facility_id_num)) {
      $this->erreurs[] = self::FACILITY_ID_NUM_ERR;
    } else {
      $this->facility_id_num = $facility_id_num;
    }
  }

  public function setFacility_sector($facility_sector) {
    if (empty($facility_sector)) {
      $this->erreurs[] = self::FACILITY_SECTOR_ERR;
    } else {
      $this->facility_sector = $facility_sector;
    }
  }

  public function setFaciliy_sic($faciliy_sic) {
    if (empty($faciliy_sic)) {
      $this->erreurs[] = self::FACILIY_SIC_ERR;
    } else {
      $this->faciliy_sic = $faciliy_sic;
    }
  }

  public function setBoundary($boundary) {
    if (empty($boundary)) {
      $this->erreurs[] = self::BOUNDARY_ERR;
    } else {
      $this->boundary = $boundary;
    }
  }

  // GETTERS //
  public function facility_id() {
    return $this->facility_id;
  }

  public function project_id() {
    return $this->project_id;
  }

  public function facility_name() {
    return $this->facility_name;
  }

  public function facility_address() {
    return $this->facility_address;
  }

  public function facility_lat() {
    return $this->facility_lat;
  }

  public function facility_long() {
    return $this->facility_long;
  }

  public function facility_contact_name() {
    return $this->facility_contact_name;
  }

  public function facility_contact_phone() {
    return $this->facility_contact_phone;
  }

  public function facility_contact_email() {
    return $this->facility_contact_email;
  }

  public function facility_id_num() {
    return $this->facility_id_num;
  }

  public function facility_sector() {
    return $this->facility_sector;
  }

  public function faciliy_sic() {
    return $this->faciliy_sic;
  }

  public function boundary() {
    return $this->boundary;
  }


}