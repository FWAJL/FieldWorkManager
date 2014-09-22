<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class LocationController extends \Library\BaseController {

  public function executeIndex(\Library\HttpRequest $rq) {
    $project = \Applications\PMTool\Helpers\CommonHelper::GetAndStoreCurrentProject($this, intval($rq->getData("project_id")));
    if ($project == !NULL) {
      $sessionProject = \Applications\PMTool\Helpers\CommonHelper::GetUserSessionProject($this->app()->user(), $project);
      header('Location: ' . __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::LocationListAll);
    }
  }

  public function executeShowForm(\Library\HttpRequest $rq) {
    $this->page->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $this->app()->user->getAttribute(\Library\Enums\SessionKeys::CurrentProject));

    //Which module?
    $this->page->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeListAll(\Library\HttpRequest $rq) {
    //Get list of location stored in session
    $sessionProject = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::CurrentProject);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

    $this->_GetAndStoreLocationsInSession($sessionProject);
    $sessionProject = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::CurrentProject);
    $locations = $sessionProject[\Library\Enums\SessionKeys::ProjectLocations];
    $data = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => $this->resxfile,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => $locations,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList($this->resxfile)
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $this->page->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::active_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::active_list]);
    $this->page->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::inactive_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::inactive_list]);
  }

  public function executeAdd(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $sessionProject = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::CurrentProject);

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module);
    $this->dataPost["project_id"] = $sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id();

    if (array_key_exists("names", $this->dataPost())) {
      $locations = $this->_PrepareManyLocationObjects();
    } else {
      array_push($locations, $this->_PrepareUserObject($this->dataPost()));
    }
    $result["dataIn"] = $locations;

    $result["dataOut"] = 0;
    foreach ($locations as $location) {
      $result["dataOut"] = $manager->add($location);
      array_push($sessionProject[\Library\Enums\SessionKeys::ProjectLocations], $location);
    }
    if ($result["dataOut"]) {
      \Applications\PMTool\Helpers\CommonHelper::SetUserSessionProject($this->app()->user(), $sessionProject);
    }
    
    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Location,
      "resx_key" => $this->action(),
      "step" => $result["dataOut"] ? "success" : "error"
    ));
  }

  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[0]->pm_id();
    $location = $this->_PrepareUserObject($this->dataPost());
    $result["data"] = $location;

    $manager = $this->managers->getManagerOf('Location');
    $result_insert = $manager->edit($location);

    //Clear the location and facility list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserLocations);
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserLocationFacilityList);

    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Location,
      "resx_key" => $this->action(),
      "step" => $result_insert ? "success" : "error"
    ));
  }

  public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $sessionProject = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::CurrentProject);
    $db_result = FALSE;
    $location_id = intval($this->dataPost["location_id"]);

    //Check if the location to be deleted if the Location manager's
    $location_selected = $this->_GetLocationFromSession($location_id);
    //Load interface to query the database
    if ($location_selected !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($location_id);
      if ($db_result) {
        $sessionProject[\Library\Enums\SessionKeys::ProjectLocations] = array();
        \Applications\PMTool\Helpers\CommonHelper::SetUserSessionProject($this->app()->user(), $sessionProject);
      }
    }

    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Location,
      "resx_key" => $this->action(),
      "step" => $db_result !== FALSE ? "success" : "error"
    ));
  }

  public function executeGetList(\Library\HttpRequest $rq = NULL, $sessionProject = NULL, $isAjaxCall = FALSE) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $list = array();
    if ($sessionProject !== NULL) {
      //Load interface to query the database for locations
      $manager = $this->managers->getManagerOf($this->module);
      $result["locations"] = $sessionProject[\Library\Enums\SessionKeys::ProjectLocations] = $manager->selectMany($sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
      \Applications\PMTool\Helpers\CommonHelper::SetUserSessionProject($this->app()->user(), $sessionProject);
    }
    if ($isAjaxCall) {
      $step_result = $result["locations"] !== NULL ? "success" : "error";
      $this->SendResponseWS(
          $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Location,
        "resx_key" => $this->action(),
        "step" => $step_result
      ));
    }
  }

  public function executeGetItem(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $location_id = intval($this->dataPost["location_id"]);

    $location_selected = $this->_GetLocationFromSession($location_id);
    $facility_selected = $this->_GetFacilityLocationFromSession($location_id);

    $result["location"] = $location_selected;
    $result["facility"] = $facility_selected;
    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Location,
      "resx_key" => $this->action(),
      "step" => ($location_selected !== NULL && $facility_selected !== NULL) ? "success" : "error"
    ));
  }

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $rows_affected = 0;
    //Get the location objects from ids received
    $location_ids = str_getcsv($this->dataPost["location_ids"], ',');
    $locations = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserLocations);
    $matchedElements = $this->FindObjectsFromIds(
        array(
          "filter" => "location_id",
          "ids" => $location_ids,
          "objects" => $locations)
    );

    //Update the location objects in DB and get result (number of rows affected)
    //$this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserLocations);
    foreach ($matchedElements as $location) {
      $location->setActive($this->dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $this->managers->getManagerOf($this->module);
      $rows_affected += $manager->edit($location) ? 1 : 0;
    }

    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Location,
      "resx_key" => $this->action(),
      "step" => ($rows_affected === count($location_ids)) ? "success" : "error"
    ));
  }

  private function _GetAndStoreLocationsInSession($sessionProject) {
    $lists = array();
    if (count($sessionProject[\Library\Enums\SessionKeys::ProjectLocations]) === 0) {
      $this->executeGetList(NULL, $sessionProject, false);
    } else {
      
    }
  }

  private function _PrepareUserObject($data_sent) {
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

  private function _PrepareManyLocationObjects() {
    $locations = array();
    $location_names = \Applications\PMTool\Helpers\CommonHelper::StringToArray("\n", $this->dataPost["names"]);
    foreach ($location_names as $name) {
      $location = new \Applications\PMTool\Models\Dao\Location();
      $location->setProject_id($this->dataPost["project_id"]);
      $location->setLocation_name($name);
      array_push($locations, $location);
    }
    return $locations;
  }

  private function _GetLocationFromSession($location_id) {
    $locationMatch = NULL;
    $sessionProject = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::CurrentProject);
    $locations = $sessionProject[\Library\Enums\SessionKeys::ProjectLocations];
    foreach ($locations as $location) {
      if (intval($location->location_id()) === $location_id) {
        $locationMatch = $location;
        break;
      }
    }
    return $locationMatch;
  }

}
