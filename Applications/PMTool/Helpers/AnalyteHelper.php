<?php

/**
 *
 * @package		Basic MVC framework
 * @author		Jeremie Litzler
 * @copyright	Copyright (c) 2015
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

  public static function StoreListsData($caller, $processCommonAnalytes = FALSE) {
    $sessionPm = PmHelper::GetCurrentSessionPm($caller->user());
    $COMMON = "common_";
    $FIELD = "field";
    $LAB = "lab";
    /*
     * creates an array depending on the value of $processCommonAnalytes
     * 
     * array(
     *  "common_field",
     *  "common_lab"
     * )
     * 
     * or 
     * 
     * array(
     *  "field",
     *  "lab"
     * )
     * 
     */
    $loopParams = array($processCommonAnalytes ? $COMMON . $FIELD : $FIELD, $processCommonAnalytes ? $COMMON . $LAB : $LAB);
    for ($index = 0; $index < count($loopParams); $index++) {
      switch ($loopParams[$index]) {
        case $FIELD:
          $sessionPm = self::_StoreAnalytes(
                          $caller, $sessionPm, \Library\Enums\SessionKeys::PmFieldAnalytes, new \Applications\PMTool\Models\Dao\Field_analyte());
          break;
        case $LAB:
          $sessionPm = self::_StoreAnalytes(
                          $caller, $sessionPm, \Library\Enums\SessionKeys::PmLabAnalytes, new \Applications\PMTool\Models\Dao\Lab_analyte());
          break;
        case $COMMON . $FIELD:
          self::_StoreCommonAnalytes(
                  $caller, \Library\Enums\SessionKeys::CommonFieldAnalytes, new \Applications\PMTool\Models\Dao\Common_field_analyte);
          break;

        case $COMMON . $LAB:
          self::_StoreCommonAnalytes(
                  $caller, \Library\Enums\SessionKeys::CommonLabAnalytes, new \Applications\PMTool\Models\Dao\Common_lab_analyte);
          break;
      }
      PmHelper::SetSessionPm($caller->user(), $sessionPm);
    }
  }

  public static function StoreCommonListData($caller) {
    self::_StoreCommonAnalytes($caller, \Library\Enums\SessionKeys::CommonFieldAnalytes, new \Applications\PMTool\Models\Dao\Common_field_analyte);
    self::_StoreCommonAnalytes($caller, \Library\Enums\SessionKeys::CommonLabAnalytes, new \Applications\PMTool\Models\Dao\Common_lab_analyte);
  }

  private static function _StoreAnalytes($caller, $sessionPm, $sessionKey, $analyteObj) {
    if (count($sessionPm[$sessionKey]) === 0) {
      $analyteObj->setPm_id($sessionPm[\Library\Enums\SessionKeys::PmObject]->pm_id());
      $dal = $caller->managers()->getManagerOf("Analyte");
      $analytes = $dal->selectMany($analyteObj, "pm_id");
      $sessionPm[$sessionKey] = $analytes;
    }
    return $sessionPm;
  }

  private static function _StoreCommonAnalytes($caller, $sessionKey, $analyteObj) {
    if (count(CommonHelper::GetValueInSession($caller->user(), $sessionKey)) === 0) {
      $dal = $caller->managers()->getManagerOf("Analyte");
      $analytes = $dal->selectMany($analyteObj, "");
      CommonHelper::SetValueInSession($caller->user(), $sessionKey, $analytes);
    }
  }

  public static function GetListPropertiesForFieldAnalyte() {
    return array("name" => "name_unit", "id" => "id");
  }
  
  public static function GetListPropertiesForLabAnalyte() {
    return array("name" => "name", "id" => "id");
  }

  public static function FilterAnalytesByProjectAnalytesList($caller, $getFieldType = TRUE) {
    $pm = PmHelper::GetCurrentSessionPm($caller->user());
    $project_analytes = self::GetProjectAnalytes($caller, $getFieldType);
    $sessionKey = $getFieldType ? \Library\Enums\SessionKeys::PmFieldAnalytes : \Library\Enums\SessionKeys::PmLabAnalytes;
    $analytePropId = $getFieldType ? "field_analyte_id" : "lab_analyte_id";
    $matches = array();

    foreach ($pm[$sessionKey] as $analyte) {
      foreach ($project_analytes as $project_analyte) {
        if (intval($analyte->$analytePropId()) === intval($project_analyte->$analytePropId())) {
          array_push($matches, $analyte);
          break;
        }
      }
    }
    return $matches;
  }

  public static function GetProjectAnalytes($caller, $getFieldType = TRUE, $sessionProject = NULL) {
    $pm = PmHelper::GetCurrentSessionPm($caller->user());
    $project = $sessionProject == NULL ? ProjectHelper::GetCurrentSessionProject($caller->user()) : $sessionProject;
    $sessionKey = $getFieldType ? \Library\Enums\SessionKeys::ProjectFieldAnalytes : \Library\Enums\SessionKeys::ProjectLabAnalytes;
    if (count($project[$sessionKey]) === 0) {
      $type = $getFieldType ? "field" : "lab";
      $className = "\Applications\PMTool\Models\Dao\Project_" . $type . "_analyte";
      $project_analyte = new $className();
      $project_analyte->setProject_id($project[\Library\Enums\SessionKeys::ProjectObject]->project_id());
      $dal = $caller->managers()->getManagerOf($caller->module());
      $project_analytes = $dal->selectMany($project_analyte, "project_id");
      $project[$sessionKey] = $project_analytes;
      ProjectHelper::SetUserSessionProject($caller->user(), $project);
    }
    return $project[$sessionKey];
  }
  
  /**
  * Gets all TaskFieldAnalytes from the relevant table 
  * and sets the same to Session
  */
  public static function GetAndStoreTaskFieldAnalytes($caller, $sessionTask) {
    $sessionTasks = $caller->user()->getAttribute(\Library\Enums\SessionKeys::SessionTasks);
    $taskFieldAnalyte = new \Applications\PMTool\Models\Dao\Task_field_analyte();
    $taskFieldAnalyte->setTask_id($sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
//    if (!(count($sessionProject[\Library\Enums\SessionKeys::ProjectServices]) > 0)) {
    $dal = $caller->managers()->getManagerOf("TaskFieldAnalyte");
    $sessionTask[\Library\Enums\SessionKeys::TaskFieldAnalytes] = $dal->selectMany($taskFieldAnalyte, "task_id");
//    }
    TaskHelper::SetSessionTask($caller->user(), $sessionTask);
    TaskHelper::SetCurrentSessionTask($caller->user(), $sessionTask);
    return self::GetFieldAnalytesFromTaskFieldAnalytes($caller->user(), $sessionTask);
  }
  
  /**
  * Gets all TaskLabAnalytes from the relevant table 
  * and sets the same to Session
  */
  public static function GetAndStoreTaskLabAnalytes($caller, $sessionTask) {
    $sessionTasks = $caller->user()->getAttribute(\Library\Enums\SessionKeys::SessionTasks);
    $taskLabAnalyte = new \Applications\PMTool\Models\Dao\Task_lab_analyte();
    $taskLabAnalyte->setTask_id($sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
//    if (!(count($sessionProject[\Library\Enums\SessionKeys::ProjectServices]) > 0)) {
    $dal = $caller->managers()->getManagerOf("TaskLabAnalyte");
    $sessionTask[\Library\Enums\SessionKeys::TaskLabAnalytes] = $dal->selectMany($taskLabAnalyte, "task_id");
//    }
    TaskHelper::SetSessionTask($caller->user(), $sessionTask);
    TaskHelper::SetCurrentSessionTask($caller->user(), $sessionTask);
    return self::GetLabAnalytesFromTaskLabAnalytes($caller->user(), $sessionTask);
  }
  
  public static function GetFieldAnalytesFromTaskFieldAnalytes(\Library\User $user, $sessionTask) {
    $matches = array();
    $sessionPm = PmHelper::GetCurrentSessionPm($user);
    foreach ($sessionPm[\Library\Enums\SessionKeys::PmFieldAnalytes] as $field_analyte) {
      foreach ($sessionTask[\Library\Enums\SessionKeys::TaskFieldAnalytes] as $task_field_analyte) {
        if (intval($field_analyte->field_analyte_id()) === intval($task_field_analyte->field_analyte_id())) {
          array_push($matches, $field_analyte);
          break;
        }
      }
    }
    return $matches;
  }
  
  public static function GetLabAnalytesFromTaskLabAnalytes(\Library\User $user, $sessionTask) {
    $matches = array();
    $sessionPm = PmHelper::GetCurrentSessionPm($user);
    foreach ($sessionPm[\Library\Enums\SessionKeys::PmLabAnalytes] as $lab_analyte) {
      foreach ($sessionTask[\Library\Enums\SessionKeys::TaskLabAnalytes] as $task_lab_analyte) {
        if (intval($lab_analyte->lab_analyte_id()) === intval($task_lab_analyte->lab_analyte_id())) {
          array_push($matches, $lab_analyte);
          break;
        }
      }
    }
    return $matches;
  }
 

  public static function AddAnalyte($caller, $result, $isFieldType, $isCommon) {

    $manager = $caller->managers()->getManagerOf($caller->module());
    $dataPost = $caller->dataPost();
    if(!$isCommon) {
      $pm = PmHelper::GetCurrentSessionPm($caller->user());
      $dataPost["pm_id"] = $pm[\Library\Enums\SessionKeys::PmObject]->pm_id();
    }
    $analytes = array();
    $analyteObj = null;
    if ($isCommon) {
      $analyteObj = $isFieldType ? new \Applications\PMTool\Models\Dao\Common_field_analyte() : new \Applications\PMTool\Models\Dao\Common_lab_analyte();
    } else {
      $analyteObj = $isFieldType ? new \Applications\PMTool\Models\Dao\Field_analyte() : new \Applications\PMTool\Models\Dao\Lab_analyte();
    }

    if (array_key_exists("names", $dataPost)) {
      $analytes = !$isCommon ?
              self::_PrepareManyAnalyteObjects($dataPost, $isFieldType) :
              self::_PrepareManyCommonAnalyteObjects($dataPost, $isFieldType);
    } else {
      array_push($analytes, CommonHelper::PrepareUserObject($dataPost, $analyteObj));
    }
    $result["dataIn"] = $analytes;

    $result["dataId"] = 0;
    $result["error"] = array("hasError" => FALSE, "code" => 0, "message" => "");
    $commonSessiongKey = $isFieldType ? \Library\Enums\SessionKeys::CommonFieldAnalytes : \Library\Enums\SessionKeys::CommonLabAnalytes;
    $commonAnalytes = self::GetCommonAnalytes($caller->user(), $commonSessiongKey);
    foreach ($analytes as $analyte) {
      $result["dataId"] = 0;
      $result["dataId"] = $manager->add($analyte);
      if ($result["dataId"] < 0) {
        $result["error"]["hasError"] = TRUE;
        $result["error"]["code"] = $result["dataId"];
        $result["error"]["message"] = "Some items couldn't be added to the database";
      } elseif ($isFieldType && !$isCommon) {
        $analyte->setField_analyte_id($result["dataId"]);
        array_push($pm[\Library\Enums\SessionKeys::PmFieldAnalytes], $analyte);
      } elseif (!$isFieldType && !$isCommon) {
        $analyte->setLab_analyte_id($result["dataId"]);
        array_push($pm[\Library\Enums\SessionKeys::PmLabAnalytes], $analyte);
      } elseif ($isFieldType && $isCommon) {
        $analyte->setCommon_field_analyte_id($result["dataId"]);
        array_push($commonAnalytes, $analyte);
      } else {
        $commonSessiongKey = \Library\Enums\SessionKeys::CommonLabAnalytes;
        $analyte->setCommon_lab_analyte_id($result["dataId"]);
        array_push($commonAnalytes, $analyte);
      }
    }
    if ($isCommon) {
      self::SetCommonAnalytes($caller->user(), $commonSessiongKey, $commonAnalytes);
    } else {
      PmHelper::SetSessionPm($caller->user(), $pm);
    }
    return $result;
  }

  private static function _PrepareManyAnalyteObjects($dataPost, $isFieldType = TRUE) {
    $analytes = array();
    if (preg_match("`^.*,*$`", $dataPost["names"])) {
      $analyte_names = \Applications\PMTool\Helpers\CommonHelper::StringToArray(",", $dataPost["names"]);
    } else {
      $analyte_names = \Applications\PMTool\Helpers\CommonHelper::StringToArray("\n", $dataPost["names"]);
    }
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

  private static function _PrepareManyCommonAnalyteObjects($dataPost, $isFieldType = TRUE) {
    $analytes = array();
    if (preg_match("`^.*,*$`", $dataPost["names"])) {
      $analyte_names = \Applications\PMTool\Helpers\CommonHelper::StringToArray(",", $dataPost["names"]);
    } else {
      $analyte_names = \Applications\PMTool\Helpers\CommonHelper::StringToArray("\n", $dataPost["names"]);
    }
    foreach ($analyte_names as $name) {
      $analyte = $isFieldType ? new \Applications\PMTool\Models\Dao\Common_field_analyte() : new \Applications\PMTool\Models\Dao\Common_lab_analyte();
      if ($isFieldType) {
        $analyte->setCommon_field_analyte_name($name);
      } else {
        $analyte->setCommon_lab_analyte_name($name);
        $analyte->setCommon_lab_analyte_category_name($name);
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
  
  /**
  *	Update method for Task specific analytes
  */
  public static function UpdateTaskAnalytes($caller) {
    $result = $caller->InitResponseWS(); // Init result
    $dataPost = $caller->dataPost();
    $result["rows_affected"] = 0;
    if ($dataPost["isFieldType"]) {
      $result = self::ProcessListAnalytesTasks(
                      $caller, $result, array(
                  "sessionKey" => \Library\Enums\SessionKeys::TaskFieldAnalytes,
                  "dataPost" => $dataPost,
                  "object" => new \Applications\PMTool\Models\Dao\Task_field_analyte(),
                  "objPropId" => "field_analyte_id"));
    } else {
      $result = self::ProcessListAnalytesTasks(
                      $caller, $result, array(
                  "sessionKey" => \Library\Enums\SessionKeys::TaskLabAnalytes,
                  "dataPost" => $dataPost,
                  "object" => new \Applications\PMTool\Models\Dao\Task_lab_analyte(),
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
  
  
  private static function ProcessListAnalytesTasks($caller, $result, $params) {
    $result["arrayOfValues"] = str_getcsv($params["dataPost"]["arrayOfValues"], ',');
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($caller->user());
    foreach ($result["arrayOfValues"] as $id) {
      $setMethodObjId = "set" . ucfirst($params["objPropId"]);
      $params["object"]->$setMethodObjId($id);
      $params["object"]->setTask_id($sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
      $dal = $caller->managers()->getManagerOf($caller->module());
      if ($params["dataPost"]["action"] === "add") {
        $analyte = $params["objPropId"] === "field_analyte_id" ?
                new \Applications\PMTool\Models\Dao\Task_field_analyte() : new \Applications\PMTool\Models\Dao\Task_lab_analyte();
        $analyte->setTask_id($sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
        $setMethodObjId = "set" . ucfirst($params["objPropId"]);
        $analyte->$setMethodObjId($id);
        $newId = $dal->add($analyte);
        $result["rows_affected"] += 1;
        $sessionTaskAnalytes = $sessionTask[$params["sessionKey"]];
        array_push($sessionTaskAnalytes, $analyte);
        $sessionTask[$params["sessionKey"]] = $sessionTaskAnalytes;
      } else {
        $result["rows_affected"] += $dal->delete($params["object"], $params["objPropId"]) ? 1 : 0;
        //TODO: remove object deleted from array list
        $propId = $params["objPropId"];
        $match = CommonHelper::FindIndexInObjectListById($params["object"]->$propId(), $params["objPropId"], $sessionTask, $params["sessionKey"]);
        unset($sessionTask[$params["sessionKey"]][$match["key"]]);
      }
    }
    //\Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($caller->user(), $sessionProject);
    return $result;
  }
  
  
  

  public static function AddTabsStatus(\Library\User $user) {
    $tabs = array(
        \Applications\PMTool\Resources\Enums\AnalyteTabKeys::FieldTab => "active",
        \Applications\PMTool\Resources\Enums\AnalyteTabKeys::LabTab => ""
    );
    $user->setAttribute(\Library\Enums\SessionKeys::TabActiveAnalyte, $tabs);
  }

  public static function GetCommonAnalytes($user, $sessionKey) {
    return $user->getAttribute($sessionKey);
  }

  public static function SetCommonAnalytes($user, $sessionKey, $value) {
    $user->setAttribute($sessionKey, $value);
  }

}
