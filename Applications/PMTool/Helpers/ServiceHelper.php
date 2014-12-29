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
 * ServiceHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	ServiceHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ServiceHelper {

  public static function AddPmService($caller, $result) {
    $sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($caller->user());

    $manager = $caller->managers()->getManagerOf($caller->module());
    $data_sent = $caller->dataPost();
    $data_sent["pm_id"] = $sessionPm[\Library\Enums\SessionKeys::PmObject]->pm_id();

    $services = array();
    if (array_key_exists("names", $caller->dataPost())) {
      $services = self::_PrepareManyServiceObjects($data_sent);
    } else {
      array_push($services, CommonHelper::PrepareUserObject($data_sent, new \Applications\PMTool\Models\Dao\Service()));
    }
    $result["dataIn"] = $services;

    $result["dataId"] = 0;
    foreach ($services as $service) {
      $result["dataId"] = $manager->add($service);
      $service->setService_id($result["dataId"]);
      array_push($sessionPm[\Library\Enums\SessionKeys::PmServices], $service);
    }
    if ($result["dataId"]) {
      \Applications\PMTool\Helpers\PmHelper::SetSessionPm($caller->user(), $sessionPm);
    }
    return $result;
  }

  public static function CategorizeTheList($listOfObject, $prop_cat_name) {
    $categorizedArray = array();

    foreach ($listOfObject as $object) {
      if ($object->$prop_cat_name() === "") {
        if (array_key_exists("Uncategorized", $categorizedArray)) {
          array_push($categorizedArray["Uncategorized"], $object);
        } else {
          $categorizedArray["Uncategorized"] = array($object);
        }
      } else {
        if (array_key_exists($object->$prop_cat_name(), $categorizedArray)) {
          array_push($categorizedArray[$object->$prop_cat_name()], $object);
        } else {
          $categorizedArray[$object->$prop_cat_name()] = array($object);
        }
      }
    }

    return $categorizedArray;
  }

  public static function DeactivateService($caller, $params) {
    
  }

  public static function DeleteService($caller, $dal_name, $obj, $where_filter_id) {
    $manager = $caller->managers()->getManagerOf($dal_name);
    return $manager->delete($obj, $where_filter_id);
  }

  public static function FilterServicesToExcludeProjectServices($services, $project_services) {
    $filtered_services = array();
    foreach ($services as $service) {
      $to_add = TRUE;
      foreach ($project_services as $project_service) {
        if (intval($service->service_id()) === intval($project_service->service_id())) {
          $to_add = FALSE;
          break;
        }
      }
      if ($to_add) {
        array_push($filtered_services, $service);
      }
    }
    return $filtered_services;
  }

  public static function GetAndStoreProjectServices($caller, $sessionProject) {
    $sessionProjects = $caller->user()->getAttribute(\Library\Enums\SessionKeys::UserSessionProjects);
    $projectService = new \Applications\PMTool\Models\Dao\Project_service();
    $projectService->setProject_id($sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id());
//    if (!(count($sessionProject[\Library\Enums\SessionKeys::ProjectServices]) > 0)) {
    $dal = $caller->managers()->getManagerOf("ProjectService");
    $sessionProject[\Library\Enums\SessionKeys::ProjectServices] = $dal->selectMany($projectService, "project_id");
//    }
    ProjectHelper::SetUserSessionProject($caller->user(), $sessionProject);
    ProjectHelper::SetCurrentSessionProject($caller->user(), $sessionProject);
    return self::GetServicesFromProjectServices($caller->user(), $sessionProject);
  }

  public static function GetServicesFromProjectServices(\Library\User $user, $sessionProject) {
    $matches = array();
    $sessionPm = PmHelper::GetCurrentSessionPm($user);
    foreach ($sessionProject[\Library\Enums\SessionKeys::ProjectServices] as $project_service) {
      foreach ($sessionPm[\Library\Enums\SessionKeys::PmServices] as $service) {
        if (intval($service->service_id()) === intval($project_service->service_id())) {
          array_push($matches, $service);
          break;
        }
      }
    }
    return $matches;
  }
  public static function GetListProperties() {
    return array("name" => "name","id" => "id");
  }

  public static function GetServiceList($caller, $sessionPm) {
    $result = $caller->InitResponseWS();
    if ($sessionPm !== NULL) {
      $service = new \Applications\PMTool\Models\Dao\Service();
      $service->setPm_id($sessionPm[\Library\Enums\SessionKeys::PmObject]->pm_id());
      $manager = $caller->managers()->getManagerOf("Service");
      $result[\Library\Enums\SessionKeys::PmServices] =
              $sessionPm[\Library\Enums\SessionKeys::PmServices] =
              $manager->selectMany($service, "pm_id");
      \Applications\PMTool\Helpers\PmHelper::SetSessionPm($caller->user(), $sessionPm);
    }
    return $result;
  }

  public static function GetPmServices($caller, $sessionPm = NULL, $project_services = NULL, $categorizeTheList = FALSE) {
    $services = $sessionPm[\Library\Enums\SessionKeys::PmServices];

    if (count($services) === 0) {
      $dataOut = self::GetServiceList($caller, $sessionPm);
      $services = $dataOut[\Library\Enums\SessionKeys::PmServices];
    }
    if ($project_services !== NULL) {
      self::FilterServicesToExcludeProjectServices($services, $project_services);
    }

    return $services;
  }

  private static function _PrepareManyServiceObjects($dataPost) {
    $services = array();
    $service_names = \Applications\PMTool\Helpers\CommonHelper::StringToArray("\n", $dataPost["names"]);
    foreach ($service_names as $name) {
      $service = new \Applications\PMTool\Models\Dao\Service();
      $service->setPm_id($dataPost["pm_id"]);
      $service->setService_name($name);
      $service->setService_active($dataPost["active"]);
      array_push($services, $service);
    }
    return $services;
  }

  public static function UpdateServices($caller) {
    $result = $caller->InitResponseWS(); // Init result
    $dataPost = $caller->dataPost();
    $rows_affected = 0;
    //Get the service objects from ids received
    $result["service_ids"] = str_getcsv($dataPost["service_ids"], ',');
    $sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($caller->user());
    $services = $sessionPm[\Library\Enums\SessionKeys::PmServices];
    $matchedElements = $caller->FindObjectsFromIds(
            array(
                "filter" => "service_id",
                "ids" => $result["service_ids"],
                "objects" => $services)
    );
    $result["rows_affected"] = 0;
    foreach ($matchedElements as $service) {
      $service->setService_active($dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $caller->managers()->getManagerOf($caller->module());
      $result["rows_affected"] += $manager->edit($service, "service_id") ? 1 : 0;
      self::DeleteService($caller, "ProjectService", $service, "service_id");
    }
    \Applications\PMTool\Helpers\PmHelper::SetSessionPm($caller->user(), $sessionPm);
    return $result;
  }

  public static function UpdateProjectServices($caller) {
    $result = $caller->InitResponseWS(); // Init result
    $dataPost = $caller->dataPost();
    $result["rows_affected"] = 0;
    $result["service_ids"] = str_getcsv($dataPost["arrayOfIds"], ',');
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($caller->user());
    $project_services = array();
    foreach ($result["service_ids"] as $id) {
      $project_service = new \Applications\PMTool\Models\Dao\Project_service();
      $project_service->setService_id($id);
      $project_service->setProject_id($sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id());
      $dal = $caller->managers()->getManagerOf($caller->module());
      if ($dataPost["action"] === "add") {
        $result["rows_affected"] += $dal->add($project_service) >= 0 ? 1 : 0;
      } else {
        $result["rows_affected"] += $dal->delete($project_service, "service_id") ? 1 : 0;
      }
      array_push($project_services, $project_service);
    }
    $sessionProject[\Library\Enums\SessionKeys::ProjectServices] = $project_services;
    \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($caller->user(), $sessionProject);
    return $result;
  }

}

