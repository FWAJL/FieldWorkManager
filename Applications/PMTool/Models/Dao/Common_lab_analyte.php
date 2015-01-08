<?php
/**** @package    Basic MVC framework* @author     Jeremie Litzler* @copyright  Copyright (c) 2014* @license* @link* @since* @filesource*/// ------------------------------------------------------------------------
/**** Common_lab_analyte Dao Class** @package     Application/PMTool* @subpackage  Models/Dao* @category    Common_lab_analyte* @author      FWM DEV Team* @link*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Common_lab_analyte extends \Library\Entity{  public     $common_lab_analyte_id,    $common_lab_analyte_category_name,    $common_lab_analyte_name;
  const     COMMON_LAB_ANALYTE_ID_ERR = 0,    COMMON_LAB_ANALYTE_CATEGORY_NAME_ERR = 1,    COMMON_LAB_ANALYTE_NAME_ERR = 2;
  // SETTERS //  public function setCommon_lab_analyte_id($common_lab_analyte_id) {      $this->common_lab_analyte_id = $common_lab_analyte_id;  }
  public function setCommon_lab_analyte_category_name($common_lab_analyte_category_name) {      $this->common_lab_analyte_category_name = $common_lab_analyte_category_name;  }
  public function setCommon_lab_analyte_name($common_lab_analyte_name) {      $this->common_lab_analyte_name = $common_lab_analyte_name;  }
  // GETTERS //  public function common_lab_analyte_id() {    return $this->common_lab_analyte_id;  }
  public function common_lab_analyte_category_name() {    return $this->common_lab_analyte_category_name;  }
  public function common_lab_analyte_name() {    return $this->common_lab_analyte_name;  }
}