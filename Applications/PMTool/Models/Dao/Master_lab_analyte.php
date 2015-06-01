<?php
/**
*
* @package    Basic MVC framework
* @author     Souvik Ghosh
* @copyright  Copyright (c) 2015
* @license
* @link
* @since
* @filesource
*/
// ------------------------------------------------------------------------
/**
*
* Master_lab_analyte Dao Class
*
* @package     Application/PMTool
* @subpackage  Models/Dao
* @category    Master_lab_analyte
* @author      FWM DEV Team
* @link
*/
namespace Applications\PMTool\Models\Dao;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Master_lab_analyte extends \Library\Entity{
  public 
    $master_lab_analyte_id,
    $master_lab_analyte_category_name,
    $master_lab_analyte_name;
  const 
    MASTER_LAB_ANALYTE_ERR = 0,
    MASTER_LAB_ANALYTE_CATEGORY_NAME_ERR = 1,
    MASTER_LAB_ANALYTE_NAME_ERR = 2;
  // SETTERS //
  public function setMaster_lab_analyte_id($master_lab_analyte_id) {
      $this->master_lab_analyte_id = $master_lab_analyte_id;
  }
  public function setMaster_lab_analyte_category_name($master_lab_analyte_category_name) {
      $this->master_lab_analyte_category_name = $master_lab_analyte_category_name;
  }
  public function setMaster_lab_analyte_name($master_lab_analyte_name) {
      $this->master_lab_analyte_name = $master_lab_analyte_name;
  }
  // GETTERS //
  public function master_lab_analyte_id() {
    return $this->master_lab_analyte_id;
  }
  public function master_lab_analyte_category_name() {
    return $this->master_lab_analyte_category_name;
  }
  public function master_lab_analyte_name() {
    return $this->master_lab_analyte_name;
  }

}