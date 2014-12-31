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

  public static function StoreListsData($caller) {
    $sessionPm = PmHelper::GetCurrentSessionPm($caller->user());
    $loopParams = array("field", "lab");
    for ($index = 0; $index < count($loopParams); $index++) {
      switch ($loopParams[$index]) {
        case "field":
          $sessionPm = self::_StoreAnalytes(
                  $caller, $sessionPm, \Library\Enums\SessionKeys::PmFieldAnalytes, new \Applications\PMTool\Models\Dao\Field_analyte());
          break;
        case "lab":
          $sessionPm = self::_StoreAnalytes(
                  $caller, $sessionPm, \Library\Enums\SessionKeys::PmLabAnalytes, new \Applications\PMTool\Models\Dao\Lab_analyte());
          break;
      }
      PmHelper::SetSessionPm($caller->user(), $sessionPm);
    }
  }

  private static function _StoreAnalytes($caller, $sessionPm, $sessionKey, $analyteObj) {
    if (count($sessionPm[$sessionKey]) === 0) {
//      \Library\Utility\DebugHelper::LogAsHtmlComment(__CLASS__ . '->' . __FUNCTION__ . ' ==> ' . $sessionKey);
//      \Library\Utility\DebugHelper::LogAsHtmlComment(__CLASS__ . '->' . __FUNCTION__ . ' ==> Analytes not in session');
      $analyteObj->setPm_id($sessionPm[\Library\Enums\SessionKeys::PmObject]->pm_id());
      $dal = $caller->managers()->getManagerOf($caller->module());
      $analytes = $dal->selectMany($analyteObj, "pm_id");
      $sessionPm[$sessionKey] = $analytes;
    }
    return $sessionPm;
  }

  public static function GetListPropertiesForFieldAnalyte() {
    return array("name" => "name_unit", "id" => "id");
  }

  public static function AddAnalyte($caller, $result, $isFieldType = TRUE) {
    $pm = PmHelper::GetCurrentSessionPm($caller->user());

    $manager = $caller->managers()->getManagerOf($caller->module());
    $data_sent = $caller->dataPost();
    $data_sent["pm_id"] = $pm[\Library\Enums\SessionKeys::PmObject]->pm_id();

    $analytes = array();
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
        array_push($pm[\Library\Enums\SessionKeys::PmFieldAnalytes], $analyte);
      } else {
        $analyte->setLab_analyte_id($result["dataId"]);
        array_push($pm[\Library\Enums\SessionKeys::PmLabAnalytes], $analyte);
      }
    }
    if ($result["dataId"] > 0) {
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

  public static function UpdateProjectAnalytes($caller) {
    $result = $caller->InitResponseWS(); // Init result
    $dataPost = $caller->dataPost();
    $result["rows_affected"] = 0;
    if ($dataPost["isFieldType"]) {
      $result = self::ProcessListAnalytes(
              $caller, array(
            "sessionKey" => \Library\Enums\SessionKeys::PmFieldAnalytes,
            "dataPost" => $dataPost,
            "analyteObj" => new \Applications\PMTool\Models\Dao\Project_field_analyte(),
            "objPropId" => "field_analyte_id"));
    } else {
      $result = self::ProcessListAnalytes(
              $caller, array(
            "sessionKey" => \Library\Enums\SessionKeys::PmLabAnalytes,
            "dataPost" => $dataPost,
            "analyteObj" => new \Applications\PMTool\Models\Dao\Project_lab_analyte(),
            "objPropId" => "lab_analyte_id"));
    }
    return $result;
  }

  private static function ProcessListAnalytes($caller, $params) {
    $result["arrayOfIds"] = str_getcsv($params["dataPost"]["arrayOfIds"], ',');
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($caller->user());
    $project_analytes = array();
    foreach ($result["arrayOfIds"] as $id) {
      $setMethodObjId = "set" . ucfirst($params["objPropId"]);
      $params["obj"]->$setMethodObjId($id);
      $params["obj"]->setProject_id($sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id());
      $dal = $caller->managers()->getManagerOf($caller->module());
      if ($params["dataPost"]["action"] === "add") {
        $result["rows_affected"] += $dal->add($params["obj"]) >= 0 ? 1 : 0;
      } else {
        $result["rows_affected"] += $dal->delete($params["obj"], $params["objPropId"]) ? 1 : 0;
      }
      array_push($project_analytes, $params["obj"]);
    }
    $sessionProject[$params["sessionKey"]] = $project_analytes;
    \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($caller->user(), $sessionProject);
  }

}
