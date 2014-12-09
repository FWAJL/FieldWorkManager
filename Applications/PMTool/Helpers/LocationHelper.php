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
 * LocationHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	LocationHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class LocationHelper {

  public static function AddProjectLocation($caller, $result) {
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($caller->user());

    $manager = $caller->managers()->getManagerOf($caller->module());
    $data_sent = $caller->dataPost();
    $data_sent["project_id"] = $sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id();

    $locations = array();
    if (array_key_exists("names", $caller->dataPost())) {
      $locations = self::_PrepareManyLocationObjects($data_sent);
    } else {
      array_push($locations, self::_PrepareLocationObject($data_sent));
    }
    $result["dataIn"] = $locations;

    $result["dataId"] = 0;
    foreach ($locations as $location) {
      $result["dataId"] = $manager->add($location);
      $location->setLocation_id($result["dataId"]);
      array_push($sessionProject[\Library\Enums\SessionKeys::ProjectLocations], $location);
    }
    if ($result["dataId"]) {
      \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($caller->user(), $sessionProject);
    }
    return $result;
  }
  
  public static function DeactivateLocation($caller, $params) {
    
  }

  public static function DeleteLocation($caller, $dal_name, $obj) {
    $manager = $caller->managers()->getManagerOf($dal_name);
    return $manager->delete($obj);
  }

  public static function FilterLocationsToExcludeTaskLocations($locations, $task_locations) {
    $filtered_locations = array();
    foreach ($locations as $location) {
      $to_add = TRUE;
      foreach ($task_locations as $task_location) {
        if (intval($location->location_id()) === intval($task_location->location_id())) {
          $to_add = FALSE;
          break;
        }
      }
      if ($to_add) { array_push($filtered_locations, $location); }
    }
    return $filtered_locations;
  }

  public static function GetAndStoreTaskLocations($caller, $sessionTask) {
    $sessionTasks = $caller->user()->getAttribute(\Library\Enums\SessionKeys::SessionTasks);
//    if (!(count($sessionTask[\Library\Enums\SessionKeys::TaskLocations]) > 0)) {
    $dal = $caller->managers()->getManagerOf("TaskLocation");
    $sessionTask[\Library\Enums\SessionKeys::TaskLocations] = $dal->selectMany($sessionTask[\Library\Enums\SessionKeys::TaskObj]);
//    }
    TaskHelper::SetSessionTask($caller->user(), $sessionTask);
    TaskHelper::SetCurrentSessionTask($caller->user(), $sessionTask);
    return self::GetLocationsFromTaskLocations($caller->user(), $sessionTask);
  }

  public static function GetLocationsFromTaskLocations(\Library\User $user, $sessionTask) {
    $matches = array();
    $sessionProject = ProjectHelper::GetCurrentSessionProject($user);
    foreach ($sessionTask[\Library\Enums\SessionKeys::TaskLocations] as $task_location) {
      foreach ($sessionProject[\Library\Enums\SessionKeys::ProjectLocations] as $location) {
        if (intval($location->location_id()) === intval($task_location->location_id())) {
          array_push($matches, $location);
          break;
        }
      }
    }
    return $matches;
  }

  public static function GetLocationList($caller, $sessionProject) {
    $result = $caller->InitResponseWS();
    if ($sessionProject !== NULL) {
      //Load interface to query the database for locations
      $manager = $caller->managers()->getManagerOf("Location");
      $result[\Library\Enums\SessionKeys::ProjectLocations] =
              $sessionProject[\Library\Enums\SessionKeys::ProjectLocations] =
              $manager->selectMany($sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
      \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($caller->user(), $sessionProject);
    }
    return $result;
  }

  public static function GetProjectLocations($caller, $sessionProject = NULL, $task_locations = NULL) {
    $locations = $sessionProject[\Library\Enums\SessionKeys::ProjectLocations];

    if (count($locations) === 0) {
      $dataOut = self::GetLocationList($caller, $sessionProject);
      $locations = $dataOut[\Library\Enums\SessionKeys::ProjectLocations];
    }
    if ($task_locations !== NULL) {
      self::FilterLocationsToExcludeTaskLocations($locations, $task_locations);
    }

    return $locations;
  }

  private static function _PrepareLocationObject($data_sent) {
    $location = new \Applications\PMTool\Models\Dao\Location();
    $location->setProject_id($data_sent["project_id"]);
    $location->setLocation_id(!array_key_exists('location_id', $data_sent) ? NULL : $data_sent["location_id"]);
    $location->setLocation_name(!array_key_exists('location_name', $data_sent) ? NULL : $data_sent["location_name"]);
    $location->setLocation_document(!array_key_exists('location_document', $data_sent) ? "" : $data_sent["location_document"]);
    $location->setLocation_lat(!array_key_exists('location_lat', $data_sent) ? "" : $data_sent["location_lat"]);
    $location->setLocation_long(!array_key_exists('location_long', $data_sent) ? "" : $data_sent["location_long"]);
    $location->setLocation_desc(!array_key_exists('location_desc', $data_sent) ? "" : $data_sent["location_desc"]);
    $location->setLocation_active(!array_key_exists('location_active', $data_sent) ? 0 : ($data_sent["location_active"] === "1"));
    $location->setLocation_visible(!array_key_exists('location_visible', $data_sent) ? 0 : ($data_sent["location_visible"] === "1"));

    return $location;
  }

  private static function _PrepareManyLocationObjects($dataPost) {
    $locations = array();
    $location_names = \Applications\PMTool\Helpers\CommonHelper::StringToArray("\n", $dataPost["names"]);
    foreach ($location_names as $name) {
      $location = new \Applications\PMTool\Models\Dao\Location();
      $location->setProject_id($dataPost["project_id"]);
      $location->setLocation_name($name);
      $location->setLocation_active($dataPost["active"]);
      array_push($locations, $location);
    }
    return $locations;
  }
  
  public static function UpdateLocations($caller) {
    $result = $caller->InitResponseWS(); // Init result
    $dataPost = $caller->dataPost();
    $rows_affected = 0;
    //Get the location objects from ids received
    $result["location_ids"] = str_getcsv($dataPost["location_ids"], ',');
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($caller->user());
    $locations = $sessionProject[\Library\Enums\SessionKeys::ProjectLocations];
    $matchedElements = $caller->FindObjectsFromIds(
            array(
                "filter" => "location_id",
                "ids" => $result["location_ids"],
                "objects" => $locations)
    );
    $result["rows_affected"] = 0;
    foreach ($matchedElements as $location) {
      $location->setLocation_active($dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $caller->managers()->getManagerOf($caller->module());
      $result["rows_affected"] += $manager->edit($location) ? 1 : 0;
      self::DeleteLocation($caller, "TaskLocation", $location);
      }
    \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($caller->user(), $sessionProject);
    return $result;
  }
  
  public static function UpdateTaskLocations($caller) {
    $result = $caller->InitResponseWS(); // Init result
    $dataPost = $caller->dataPost();
    $result["rows_affected"] = 0;
    $result["location_ids"] = str_getcsv($dataPost["location_ids"], ',');
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($caller->user());
    $task_locations = array();
    foreach ($result["location_ids"] as $id) {
      $task_location = new \Applications\PMTool\Models\Dao\Task_location();
      $task_location->setLocation_id($id);
      $task_location->setTask_id($sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
      $dal = $caller->managers()->getManagerOf($caller->module());
      if ($dataPost["action"] === "add") {
        $result["rows_affected"] += $dal->add($task_location) ? 1 : 0;
      } else {
        $result["rows_affected"] += $dal->delete($task_location) ? 1 : 0;
      }
      array_push($task_locations, $task_location);
    }
    $sessionTask[\Library\Enums\SessionKeys::TaskLocations] = $task_locations;
    \Applications\PMTool\Helpers\TaskHelper::SetSessionTask($caller->user(), $sessionTask);
    return $result;
  }
}
