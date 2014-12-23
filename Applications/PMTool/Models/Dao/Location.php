<?php
/**
*
* @package    Basic MVC framework
* @author     Jeremie Litzler
* @copyright  Copyright (c) 2014
* @license
* @link
* @since
* @filesource
*/
// ------------------------------------------------------------------------
/**
*
* Location Dao Class
*
* @package     Application/PMTool
* @subpackage  Models/Dao
* @category    Location
* @author      FWM DEV Team
* @link
*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Location extends \Library\Entity{  public     $location_id,    $location_name,    $location_desc,    $location_lat,    $location_long,    $location_active,    $location_visible,    $project_id,
    $location_address,
    $location_category;
  const     LOCATION_ID_ERR = 0,    LOCATION_NAME_ERR = 1,    LOCATION_DESC_ERR = 2,    LOCATION_LAT_ERR = 3,    LOCATION_LONG_ERR = 4,    LOCATION_ACTIVE_ERR = 5,    LOCATION_VISIBLE_ERR = 6,    PROJECT_ID_ERR = 7,
    LOCATION_ADDRESS_ERR = 8,
    LOCATION_CATEGORY_ERR = 9;
  // SETTERS //  public function setLocation_id($location_id) {      $this->location_id = $location_id;  }
  public function setLocation_name($location_name) {      $this->location_name = $location_name;  }
  public function setLocation_desc($location_desc) {      $this->location_desc = $location_desc;  }
  public function setLocation_lat($location_lat) {      $this->location_lat = $location_lat;  }
  public function setLocation_long($location_long) {      $this->location_long = $location_long;  }
  public function setLocation_active($location_active) {      $this->location_active = $location_active;  }
  public function setLocation_visible($location_visible) {      $this->location_visible = $location_visible;  }
  public function setProject_id($project_id) {      $this->project_id = $project_id;  }
  public function setLocation_address($location_address) {
      $this->location_address = $location_address;
  }
  public function setLocation_category($location_category) {
      $this->location_category = $location_category;
  }
  // GETTERS //  public function location_id() {    return $this->location_id;  }
  public function location_name() {    return $this->location_name;  }
  public function location_desc() {    return $this->location_desc;  }
  public function location_lat() {    return $this->location_lat;  }
  public function location_long() {    return $this->location_long;  }
  public function location_active() {    return $this->location_active;  }
  public function location_visible() {    return $this->location_visible;  }
  public function project_id() {    return $this->project_id;  }
  public function location_address() {
    return $this->location_address;
  }
  public function location_category() {
    return $this->location_category;
  }
}