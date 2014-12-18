<?php

/**
 *
 * @package		Basic MVC framework
 * @author		Jeremie Litzler
 * @copyright	Copyright (c) 2014
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * AnalyteHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	AnalyteHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class AnalyteHelper {

  public static function GetListData($caller) {
    $sessionPm = PmHelper::GetCurrentSessionPm($caller->user());
    if (!count($sessionPm[\Library\Enums\SessionKeys::PmFieldAnalytes]) > 0) {
      $analyte = new \Applications\PMTool\Models\Dao\Field_analyte();
      $analyte->setPm_id($sessionPm[\Library\Enums\SessionKeys::PmObject]->pm_id());
      $dal = $caller->managers()->getManagerOf($caller->module());
      $analytes = $dal->selectMany($analyte, "pm_id");
    }
    return $sessionPm;
  }

  public static function AddAnalyte($caller, $result) {
    $pm = PmHelper::GetCurrentSessionPm($caller->user());

    $manager = $caller->managers()->getManagerOf($caller->module());
    $data_sent = $caller->dataPost();
    $data_sent["pm_id"] = $pm[\Library\Enums\SessionKeys::PmObject]->pm_id();

    $analytes = array();
    $isFieldType = TRUE;//TODO: set value according to type being added.
    $analyteObj = $isFieldType ? new \Applications\PMTool\Models\Dao\Field_analyte() : new \Applications\PMTool\Models\Dao\Lab_analyte();
    if (array_key_exists("names", $caller->dataPost())) {
      $analytes = self::_PrepareManyAnalyteObjects($data_sent, $isFieldType);
    } else {
      array_push($analytes, CommonHelper::PrepareUserObject($data_sent, $analyteObj));
    }
    $result["dataIn"] = $analytes;

    $result["dataId"] = 0;
    foreach ($analytes as $analyte) {
      $result["dataId"] = $manager->add($analyte);
      if ($isFieldType) {
        $analyte->setField_analyte_id($result["dataId"]);
      } else {
        $analyte->setLab_analyte_id($result["dataId"]);
      }
      array_push($pm[\Library\Enums\SessionKeys::PmFieldAnalytes], $analyte);
    }
    if ($result["dataId"]) {
      PmHelper::SetSessionPm($caller->user(), $pm);
    }
    return $result;
  }
    private static function _PrepareManyAnalyteObjects($dataPost, $isFieldType = TRUE) {
    $analytes = array();
    $analyte_names = \Applications\PMTool\Helpers\CommonHelper::StringToArray("\n", $dataPost["names"]);
    foreach ($analyte_names as $name) {
      $analyte = $isFieldType ? new \Applications\PMTool\Models\Dao\Field_analyte() : new \Applications\PMTool\Models\Dao\Lab_analyte();
      $analyte->setPm_id($dataPost["pm_id"]);
      if ($isFieldType) {
        $analyte->setField_analyte_name_unit($name);
      } else {
        $analyte->setLab_analyte_name($name);
      }
      array_push($analytes, $analyte);
    }
    return $analytes;
  }


}
