<?php
/**** @package    Basic MVC framework* @author     Jeremie Litzler* @copyright  Copyright (c) 2014* @license* @link* @since* @filesource*/// ------------------------------------------------------------------------
/**** Location_document Dao Class** @package     Application/PMTool* @subpackage  Models/Dao* @category    Location_document* @author      FWM DEV Team* @link*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Location_document extends \Library\Entity{  public     $location_id,    $location_document_value;
  const     LOCATION_ID_ERR = 0,    LOCATION_DOCUMENT_VALUE_ERR = 1;
  // SETTERS //  public function setLocation_id($location_id) {      $this->location_id = $location_id;  }
  public function setLocation_document_value($location_document_value) {      $this->location_document_value = $location_document_value;  }
  // GETTERS //  public function location_id() {    return $this->location_id;  }
  public function location_document_value() {    return $this->location_document_value;  }
}