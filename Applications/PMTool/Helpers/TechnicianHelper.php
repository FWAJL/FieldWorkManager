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
 * TechnicianHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	TechnicianHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TechnicianHelper {

  public static function AddPmTechnician($caller, $result) {
    $sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($caller->user());

    $manager = $caller->managers()->getManagerOf($caller->module());
    $data_sent = $caller->dataPost();
    $data_sent["pm_id"] = $sessionPm[\Library\Enums\SessionKeys::PmObject]->pm_id();

    $technicians = array();
    if (array_key_exists("names", $caller->dataPost())) {
      $technicians = self::_PrepareManyTechnicianObjects($data_sent);
    } else {
      array_push($technicians, CommonHelper::PrepareUserObject($data_sent, new \Applications\PMTool\Models\Dao\Technician()));
    }
    $result["dataIn"] = $technicians;

    $result["dataId"] = 0;
    foreach ($technicians as $technician) {
      $result["dataId"] = $manager->add($technician);
      $technician->setTechnician_id($result["dataId"]);
      array_push($sessionPm[\Library\Enums\SessionKeys::PmTechnicians], $technician);
    }
    if ($result["dataId"]) {
      \Applications\PMTool\Helpers\PmHelper::SetSessionPm($caller->user(), $sessionPm);
    }
    return $result;
  }
  
  public static function DeactivateTechnician($caller, $params) {
    
  }

  public static function DeleteTechnician($caller, $dal_name, $obj, $where_filter_id) {
    $manager = $caller->managers()->getManagerOf($dal_name);
    return $manager->delete($obj, $where_filter_id);
  }

  public static function FilterTechniciansToExcludeTaskTechnicians($technicians, $task_technicians) {
    $filtered_technicians = array();
    foreach ($technicians as $technician) {
      $to_add = TRUE;
      foreach ($task_technicians as $task_technician) {
        if (intval($technician->technician_id()) === intval($task_technician->technician_id())) {
          $to_add = FALSE;
          break;
        }
      }
      if ($to_add) { array_push($filtered_technicians, $technician); }
    }
    return $filtered_technicians;
  }

  public static function GetAndStoreTaskTechnicians($caller, $sessionTask) {
    $sessionTasks = $caller->user()->getAttribute(\Library\Enums\SessionKeys::SessionTasks);
//    if (!(count($sessionTask[\Library\Enums\SessionKeys::TaskTechnicians]) > 0)) {
    $dal = $caller->managers()->getManagerOf("TaskTechnician");
    $sessionTask[\Library\Enums\SessionKeys::TaskTechnicians] = $dal->selectMany($sessionTask[\Library\Enums\SessionKeys::TaskObj]);
//    }
    TaskHelper::SetSessionTask($caller->user(), $sessionTask);
    TaskHelper::SetCurrentSessionTask($caller->user(), $sessionTask);
    return self::GetTechniciansFromTaskTechnicians($caller->user(), $sessionTask);
  }

  public static function GetTechniciansFromTaskTechnicians(\Library\User $user, $sessionTask) {
    $matches = array();
    $sessionPm = PmHelper::GetCurrentSessionPm($user);
    foreach ($sessionTask[\Library\Enums\SessionKeys::TaskTechnicians] as $task_technician) {
      foreach ($sessionPm[\Library\Enums\SessionKeys::PmTechnicians] as $technician) {
        if (intval($technician->technician_id()) === intval($task_technician->technician_id())) {
          array_push($matches, $technician);
          break;
        }
      }
    }
    return $matches;
  }

  public static function GetTechnicianList($caller, $sessionPm) {
    $result = $caller->InitResponseWS();
    if ($sessionPm !== NULL) {
      //Load interface to query the database for technicians
      $manager = $caller->managers()->getManagerOf("Technician");
      $result[\Library\Enums\SessionKeys::PmTechnicians] =
              $sessionPm[\Library\Enums\SessionKeys::PmTechnicians] =
              $manager->selectMany($sessionPm[\Library\Enums\SessionKeys::PmObject]);
      \Applications\PMTool\Helpers\PmHelper::SetSessionPm($caller->user(), $sessionPm);
    }
    return $result;
  }

  public static function GetPmTechnicians($caller, $sessionPm = NULL, $task_technicians = NULL) {
    $technicians = $sessionPm[\Library\Enums\SessionKeys::PmTechnicians];

    if (count($technicians) === 0) {
      $dataOut = self::GetTechnicianList($caller, $sessionPm);
      $technicians = $dataOut[\Library\Enums\SessionKeys::PmTechnicians];
    }
    if ($task_technicians !== NULL) {
      self::FilterTechniciansToExcludeTaskTechnicians($technicians, $task_technicians);
    }

    return $technicians;
  }

  private static function _PrepareManyTechnicianObjects($dataPost) {
    $technicians = array();
    $technician_names = \Applications\PMTool\Helpers\CommonHelper::StringToArray("\n", $dataPost["names"]);
    foreach ($technician_names as $name) {
      $technician = new \Applications\PMTool\Models\Dao\Technician();
      $technician->setPm_id($dataPost["pm_id"]);
      $technician->setTechnician_name($name);
      $technician->setTechnician_active($dataPost["active"]);
      array_push($technicians, $technician);
    }
    return $technicians;
  }
  
  public static function UpdateTechnicians($caller) {
    $result = $caller->InitResponseWS(); // Init result
    $dataPost = $caller->dataPost();
    $rows_affected = 0;
    //Get the technician objects from ids received
    $result["technician_ids"] = str_getcsv($dataPost["technician_ids"], ',');
    $sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($caller->user());
    $technicians = $sessionPm[\Library\Enums\SessionKeys::PmTechnicians];
    $matchedElements = $caller->FindObjectsFromIds(
            array(
                "filter" => "technician_id",
                "ids" => $result["technician_ids"],
                "objects" => $technicians)
    );
    $result["rows_affected"] = 0;
    foreach ($matchedElements as $technician) {
      $technician->setTechnician_active($dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $caller->managers()->getManagerOf($caller->module());
      $result["rows_affected"] += $manager->edit($technician, "technician_id") ? 1 : 0;
      self::DeleteTechnician($caller, "TaskTechnician", $technician, "technician_id");
      }
    \Applications\PMTool\Helpers\PmHelper::SetSessionPm($caller->user(), $sessionPm);
    return $result;
  }
  
  public static function UpdateTaskTechnicians($caller) {
    $result = $caller->InitResponseWS(); // Init result
    $dataPost = $caller->dataPost();
    $result["rows_affected"] = 0;
    $result["technician_ids"] = str_getcsv($dataPost["technician_ids"], ',');
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($caller->user());
    $task_technicians = array();
    foreach ($result["technician_ids"] as $id) {
      $task_technician = new \Applications\PMTool\Models\Dao\Task_technician();
      $task_technician->setTechnician_id($id);
      $task_technician->setTask_id($sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
      $dal = $caller->managers()->getManagerOf($caller->module());
      if ($dataPost["action"] === "add") {
        $result["rows_affected"] += $dal->add($task_technician) >= 0 ? 1 : 0;
      } else {
        $result["rows_affected"] += $dal->delete($task_technician, "technician_id") ? 1 : 0;
      }
      array_push($task_technicians, $task_technician);
    }
    $sessionTask[\Library\Enums\SessionKeys::TaskTechnicians] = $task_technicians;
    \Applications\PMTool\Helpers\TaskHelper::SetSessionTask($caller->user(), $sessionTask);
    return $result;
  }
}
