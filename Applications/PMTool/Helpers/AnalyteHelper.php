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

  public static function FilterAnalytesByProjectAnalytesList($caller, $getFieldType = TRUE) {
    $pm = PmHelper::GetCurrentSessionPm($caller->user());
    $project_analytes = self::GetProjectAnalytes($caller, $getFieldType);
    $sessionKey = $getFieldType ? \Library\Enums\SessionKeys::PmFieldAnalytes : \Library\Enums\SessionKeys::PmLabAnalytes;
    $analytePropId = $getFieldType ? "field_analyte_id" : "lab_analyte_id";
    $matches = array();
    foreach ($project_analytes as $project_analyte) {
      foreach ($pm[$sessionKey] as $analyte) {
        if (intval($analyte->$analytePropId()) === intval($project_analyte->$analytePropId())) {
          array_push($matches, $analyte);
          break;
        }
      }
    }
    return $matches;
  }

  public static function GetProjectAnalytes($caller, $getFieldType = TRUE) {
    $pm = PmHelper::GetCurrentSessionPm($caller->user());
    $project = ProjectHelper::GetCurrentSessionProject($caller->user());
    $sessionKey = $getFieldType ? \Library\Enums\SessionKeys::ProjectFieldAnalytes : \Library\Enums\SessionKeys::ProjectLabAnalytes;
    if (count($project[$sessionKey]) === 0) {
      \Library\Utility\DebugHelper::LogAsHtmlComment($getFieldType);
      $type = $getFieldType ? "field" : "lab";
      $className = "\Applications\PMTool\Models\Dao\Project_" . $type . "_analyte";
      \Library\Utility\DebugHelper::LogAsHtmlComment($className);
      $project_analyte = new $className();
      $project_analyte->setProject_id($project[\Library\Enums\SessionKeys::ProjectObject]->project_id());
      $dal = $caller->managers()->getManagerOf($caller->module());
      $project_analytes = $dal->selectMany($project_analyte, "project_id");
      $project[$sessionKey] = $project_analytes;
      ProjectHelper::SetUserSessionProject($caller->user(), $project);
    }
    return $project[$sessionKey];
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
                      $caller, $result, array(
                  "sessionKey" => \Library\Enums\SessionKeys::ProjectFieldAnalytes,
                  "dataPost" => $dataPost,
                  "object" => new \Applications\PMTool\Models\Dao\Project_field_analyte(),
                  "objPropId" => "field_analyte_id"));
    } else {
      $result = self::ProcessListAnalytes(
                      $caller, $result, array(
                  "sessionKey" => \Library\Enums\SessionKeys::ProjectLabAnalytes,
                  "dataPost" => $dataPost,
                  "object" => new \Applications\PMTool\Models\Dao\Project_lab_analyte(),
                  "objPropId" => "lab_analyte_id"));
    }
    return $result;
  }

  private static function ProcessListAnalytes($caller, $result, $params) {
    $result["arrayOfValues"] = str_getcsv($params["dataPost"]["arrayOfValues"], ',');
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($caller->user());
    foreach ($result["arrayOfValues"] as $id) {
      $setMethodObjId = "set" . ucfirst($params["objPropId"]);
      $params["object"]->$setMethodObjId($id);
      $params["object"]->setProject_id($sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id());
      $dal = $caller->managers()->getManagerOf($caller->module());
      if ($params["dataPost"]["action"] === "add") {
        $analyte = $params["objPropId"] === "field_analyte_id" ?
                new \Applications\PMTool\Models\Dao\Project_field_analyte() : new \Applications\PMTool\Models\Dao\Project_lab_analyte();
        $analyte->setProject_id($sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id());
        $setMethodObjId = "set" . ucfirst($params["objPropId"]);
        $analyte->$setMethodObjId($id);
        $newId = $dal->add($analyte);
        $result["rows_affected"] += 1;
        $sessionProjectAnalytes = $sessionProject[$params["sessionKey"]];
        array_push($sessionProjectAnalytes, $analyte);
        $sessionProject[$params["sessionKey"]] = $sessionProjectAnalytes;
      } else {
        $result["rows_affected"] += $dal->delete($params["object"], $params["objPropId"]) ? 1 : 0;
        //TODO: remove object deleted from array list
        $propId = $params["objPropId"];
        $match = CommonHelper::FindIndexInObjectListById($params["object"]->$propId(), $params["objPropId"], $sessionProject, $params["sessionKey"]);
        unset($sessionProject[$params["sessionKey"]][$match["key"]]);
      }
    }
    \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($caller->user(), $sessionProject);
    return $result;
  }
    public static function AddTabsStatus(\Library\User $user) {
    $tabs = array(
    \Applications\PMTool\Resources\Enums\AnalyteTabKeys::FieldTab => "active",
    \Applications\PMTool\Resources\Enums\AnalyteTabKeys::LabTab=> ""
    );
    $user->setAttribute(\Library\Enums\SessionKeys::TabActiveAnalyte, $tabs);
  }


}
