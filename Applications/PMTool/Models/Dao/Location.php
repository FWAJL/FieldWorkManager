<?php
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Location extends \Library\Entity{  public     $location_id,    $location_name,    $location_desc,    $location_document,    $location_lat,    $location_long,    $location_active,    $location_visible,    $project_id;
  const     LOCATION_ID_ERR = 0,    LOCATION_NAME_ERR = 1,    LOCATION_DESC_ERR = 2,    LOCATION_DOCUMENT_ERR = 3,    LOCATION_LAT_ERR = 4,    LOCATION_LONG_ERR = 5,    LOCATION_ACTIVE_ERR = 6,    LOCATION_VISIBLE_ERR = 7,    PROJECT_ID_ERR = 8;
  // SETTERS //  public function setLocation_id($location_id) {    if (empty($location_id)) {      $this->erreurs[] = self::LOCATION_ID_ERR;    } else {      $this->location_id = $location_id;    }  }
  public function setLocation_name($location_name) {    if (empty($location_name)) {      $this->erreurs[] = self::LOCATION_NAME_ERR;    } else {      $this->location_name = $location_name;    }  }
  public function setLocation_desc($location_desc) {    if (empty($location_desc)) {      $this->erreurs[] = self::LOCATION_DESC_ERR;    } else {      $this->location_desc = $location_desc;    }  }
  public function setLocation_document($location_document) {    if (empty($location_document)) {      $this->erreurs[] = self::LOCATION_DOCUMENT_ERR;    } else {      $this->location_document = $location_document;    }  }
  public function setLocation_lat($location_lat) {    if (empty($location_lat)) {      $this->erreurs[] = self::LOCATION_LAT_ERR;    } else {      $this->location_lat = $location_lat;    }  }
  public function setLocation_long($location_long) {    if (empty($location_long)) {      $this->erreurs[] = self::LOCATION_LONG_ERR;    } else {      $this->location_long = $location_long;    }  }
  public function setLocation_active($location_active) {    if (empty($location_active)) {      $this->erreurs[] = self::LOCATION_ACTIVE_ERR;    } else {      $this->location_active = $location_active;    }  }
  public function setLocation_visible($location_visible) {    if (empty($location_visible)) {      $this->erreurs[] = self::LOCATION_VISIBLE_ERR;    } else {      $this->location_visible = $location_visible;    }  }
  public function setProject_id($project_id) {    if (empty($project_id)) {      $this->erreurs[] = self::PROJECT_ID_ERR;    } else {      $this->project_id = $project_id;    }  }
  // GETTERS //  public function location_id() {    return $this->location_id;  }
  public function location_name() {    return $this->location_name;  }
  public function location_desc() {    return $this->location_desc;  }
  public function location_document() {    return $this->location_document;  }
  public function location_lat() {    return $this->location_lat;  }
  public function location_long() {    return $this->location_long;  }
  public function location_active() {    return $this->location_active;  }
  public function location_visible() {    return $this->location_visible;  }
  public function project_id() {    return $this->project_id;  }
}