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
    $dataPost = $caller->dataPost();
    $dataPost["project_id"] = $sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id();

    $origin = $originId = "";
    if (array_key_exists("origin", $dataPost)) {
      $postUserData = (array) $dataPost["userData"];
      $postUserData["project_id"] = $dataPost["project_id"];
      $origin = $dataPost["origin"];
      $originId = $dataPost["originid"];
    } else {
      $postUserData = $dataPost;
    }

    $locations = array();
    if (array_key_exists("names", $dataPost)) {
      $locations = self::_PrepareManyLocationObjects($dataPost);
    } elseif (array_key_exists("userData", $dataPost) && array_key_exists("names", $dataPost["userData"])) {
      $locations = self::_PrepareManyLocationObjects($dataPost, TRUE);
    } else {
      array_push($locations, CommonHelper::PrepareUserObject($postUserData, new \Applications\PMTool\Models\Dao\Location()));
    }
    $result["dataIn"] = $locations;

    $result["dataId"] = 0;
    foreach ($locations as $location) {
      $result["dataId"] = $manager->add($location);
      $location->setLocation_id($result["dataId"]);
      if ($location->location_id() > 0 &&
          \Library\Utility\StringHelper::Equals($origin, "task") &&
          !\Library\Utility\StringHelper::IsNullOrEmpty($originId)) {
        $result["dataId"] = self::AddTaskLocation($caller, $originId, $location->location_id());
      }
      array_push($sessionProject[\Library\Enums\SessionKeys::ProjectLocations], $location);
    }
    if ($result["dataId"]) {
      \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($caller->user(), $sessionProject);
    }
    return $result;
  }

  public static function AddTaskLocation($caller, $task_id, $location_id) {
    $db = $caller->managers()->getManagerOf("TaskLocation");
    $taskLoc = new \Applications\PMTool\Models\Dao\Task_location();
    $taskLoc->setLocation_id($location_id);
    $taskLoc->setTask_id($task_id);
    $taskLoc->setTask_location_status(0);

    //At the same time create the relationship in "field_analyte_location"
    \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::CreateFALocationRelationForFT(
              $caller, 
              $task_id, 
              $location_id
            );

    return $db->add($taskLoc);
  }

  public static function DeactivateLocation($caller, $params) {
    
  }

  public static function DeleteLocation($caller, $dal_name, $obj, $where_filter_id) {
    $manager = $caller->managers()->getManagerOf($dal_name);
    return $manager->delete($obj, $where_filter_id);
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
      if ($to_add) {
        array_push($filtered_locations, $location);
      }
    }
    return $filtered_locations;
  }

  public static function GetAndStoreTaskLocations($caller, $sessionTask) {
    $sessionTasks = $caller->user()->getAttribute(\Library\Enums\SessionKeys::SessionTasks);
    $taskLocation = new \Applications\PMTool\Models\Dao\Task_location();
    $taskLocation->setTask_id($sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
//    if (!(count($sessionTask[\Library\Enums\SessionKeys::TaskLocations]) > 0)) {
    $dal = $caller->managers()->getManagerOf("TaskLocation");
    $sessionTask[\Library\Enums\SessionKeys::TaskLocations] = $dal->selectMany($taskLocation, "task_id");
//    }
    TaskHelper::SetSessionTask($caller->user(), $sessionTask);
    TaskHelper::SetCurrentSessionTask($caller->user(), $sessionTask);
    return self::GetLocationsFromTaskLocations($caller->user(), $sessionTask);
  }

  public static function GetLocationsFromTaskLocations(\Library\User $user, $sessionTask) {
    $matches = array();
    $sessionProject = ProjectHelper::GetCurrentSessionProject($user);

    foreach ($sessionProject[\Library\Enums\SessionKeys::ProjectLocations] as $location) {
      foreach ($sessionTask[\Library\Enums\SessionKeys::TaskLocations] as $task_location) {
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
      $location = new \Applications\PMTool\Models\Dao\Location();
      $location->setProject_id($sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id());
      $manager = $caller->managers()->getManagerOf("Location");
      $result[\Library\Enums\SessionKeys::ProjectLocations] = $sessionProject[\Library\Enums\SessionKeys::ProjectLocations] = $manager->selectMany($location, "project_id");
      if (!$result[\Library\Enums\SessionKeys::ProjectLocations]) {
        $result[\Library\Enums\SessionKeys::ProjectLocations] = $sessionProject[\Library\Enums\SessionKeys::ProjectLocations] = array();
      }
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
      $locations = self::FilterLocationsToExcludeTaskLocations($locations, $task_locations);
    }

    return $locations;
  }

  private static function _PrepareManyLocationObjects($dataPost, $isAddingTaskLocations = FALSE) {
    $locations = array();
    if ($isAddingTaskLocations) {
      $data = (array) $dataPost["userData"];
      $dataPost["active"] = array_key_exists("active", $data) ? $data["active"] : "0";
      $location_names = \Applications\PMTool\Helpers\CommonHelper::StringToArray("\n", $data["names"]);
    } else {
      $location_names = \Applications\PMTool\Helpers\CommonHelper::StringToArray("\n", $dataPost["names"]);
    }
    foreach ($location_names as $name) {

      $location = new \Applications\PMTool\Models\Dao\Location();
      $location->setProject_id($dataPost["project_id"]);

      //Check if the $name could be split on "tab"
      //If yes, we may assume the columns are name,
      //lat and long and prepare dao accordingly
      $loc_data = \Applications\PMTool\Helpers\CommonHelper::StringToArray("\t", $name);
      if (count($loc_data) > 1) {
        // lat, long
        $location->setLocation_lat($loc_data[1]);
        $location->setLocation_long($loc_data[2]);
      }
      //name
      $location->setLocation_name($loc_data[0]);

      $location->setLocation_active($dataPost["active"]);
      if (isset($dataPost["visible"])) {
        $location->setLocation_visible($dataPost["visible"]);
      } else {
        $location->setLocation_visible("");
      }

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
      $active = ($dataPost["action"] === "active") ? TRUE : FALSE;
      $location->setLocation_active($active);
      $manager = $caller->managers()->getManagerOf($caller->module());
      $result["rows_affected"] += $manager->edit($location, "location_id") ? 1 : 0;
      if ($active === false) {
        $task_location = new \Applications\PMTool\Models\Dao\Task_location();
        $task_location->setLocation_id($location->location_id());
        $manager = $caller->managers()->getManagerOf("TaskLocation");
        $manager->delete($task_location, "location_id");
      }
      //self::DeleteLocation($caller,'TaskLocation',$location,'location_id');
    }
    self::GetLocationList($caller, $sessionProject);
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
      $task_location->setTask_location_status(0);
      $dal = $caller->managers()->getManagerOf($caller->module());
      if ($dataPost["action"] === "add") {
        $result["rows_affected"] += $dal->add($task_location) >= 0 ? 1 : 0;
        //At the same time create the relationship in "field_analyte_location"
        \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::CreateFALocationRelationForFT(
                  $caller, 
                  $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id(), 
                  $id
                );
      } else {
        $result["rows_affected"] += $dal->delete($task_location, "location_id") ? 1 : 0;
      }
      array_push($task_locations, $task_location);
    }
    $sessionTask[\Library\Enums\SessionKeys::TaskLocations] = $task_locations;
    \Applications\PMTool\Helpers\TaskHelper::SetSessionTask($caller->user(), $sessionTask);
    return $result;
  }
  public static function GetLocationFromDB($caller, $location_id) {
    $manager = $caller->managers()->getManagerOf("Location");
    $location = new \Applications\PMTool\Models\Dao\Location();
    $location->setLocation_id($location_id);
    $location = $manager->selectOne($location, 'location_id');
    return $location;
  }

  public static function GetLocationListFromDB($caller, $project_id) {
    $manager = $caller->managers()->getManagerOf('Location');
    $location = new \Applications\PMTool\Models\Dao\Location();
    $location->setProject_id($project_id);
    $locations = $manager->selectMany($location, 'project_id');
    return $locations;
  }
}
