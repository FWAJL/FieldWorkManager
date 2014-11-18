<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class LocationController extends \Library\BaseController {

  public function executeIndex(\Library\HttpRequest $rq) {
    if (\Applications\PMTool\Helpers\ProjectHelper::RedirectAfterProjectSelection($this->app(), intval($rq->getData("project_id")))) {
      if ($rq->getData("target") !== "") {
        header('Location: ' . __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::LocationListAll);
      } else {
        header('Location: ' . __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::LocationRootUrl . "/" . $rq->getData("target"));
      }
    }
  }

  public function executeShowForm(\Library\HttpRequest $rq) {
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    if ($rq->getData("mode") === "edit") {
      $this->page->addVar("location_editing_header", $this->resxData["location_legend_edit"]);
    } else {
      $this->page->addVar("location_editing_header", $this->resxData["location_legend_add"]);
    }
    //Which module?
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeListAll(\Library\HttpRequest $rq) {
    //Get list of location stored in session
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

    $this->_GetAndStoreLocationsInSession($sessionProject);
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $locations = $sessionProject[\Library\Enums\SessionKeys::ProjectLocations];
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => $locations,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower($this->module()))
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
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module);
    $this->dataPost["project_id"] = $sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id();

    $locations = array();
    if (array_key_exists("names", $this->dataPost())) {
      $locations = $this->_PrepareManyLocationObjects();
    } else {
      array_push($locations, $this->_PrepareLocationObject($this->dataPost()));
    }
    $result["dataIn"] = $locations;

    $result["dataId"] = 0;
    foreach ($locations as $location) {
      $result["dataId"] = $manager->add($location);
      $location->setLocation_id($result["dataId"]);
      array_push($sessionProject[\Library\Enums\SessionKeys::ProjectLocations], $location);
    }
    if ($result["dataId"]) {
      \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($this->app()->user(), $sessionProject);
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Location,
        "resx_key" => $this->action(),
        "step" => $result["dataId"] > 0 ? "success" : "error"
    ));
  }

  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());

    //Init PDO
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $location = $this->_PrepareLocationObject($this->dataPost());
    $result["data"] = $location;

    $manager = $this->managers->getManagerOf($this->module());
    $result_edit = $manager->edit($location);

    //Clear the location and facility list from session for the connect PM
    if ($result_edit) {
      $locationMatch = $this->_GetLocationFromSession(intval($location->location_id()));
      $sessionProject[\Library\Enums\SessionKeys::ProjectLocations][$locationMatch["key"]] = $location;
      \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($this->app()->user(), $sessionProject);
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Location,
        "resx_key" => $this->action(),
        "step" => $result_edit ? "success" : "error"
    ));
  }

  public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $db_result = FALSE;
    $location_id = intval($this->dataPost["location_id"]);

    //Check if the location to be deleted if the Location manager's
    $location_selected = $this->_GetLocationFromSession($location_id);
    //Load interface to query the database
    if ($location_selected["object"] !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($location_id);
      if ($db_result) {
        unset($sessionProject[\Library\Enums\SessionKeys::ProjectLocations][$location_selected["key"]]);
        \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($this->app()->user(), $sessionProject);
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
      $result[\Library\Enums\SessionKeys::ProjectLocations] = 
              $sessionProject[\Library\Enums\SessionKeys::ProjectLocations] = 
              $manager->selectMany($sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
      \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($this->app()->user(), $sessionProject);
    }
    if ($isAjaxCall) {
      $step_result = $result[\Library\Enums\SessionKeys::ProjectLocations] !== NULL ? "success" : "error";
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

    $result["location"] = $location_selected["object"];
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Location,
        "resx_key" => $this->action(),
        "step" => ($location_selected !== NULL) ? "success" : "error"
    ));
  }

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $rows_affected = 0;
    //Get the location objects from ids received
    $location_ids = str_getcsv($this->dataPost["location_ids"], ',');
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $locations = $sessionProject[\Library\Enums\SessionKeys::ProjectLocations];
    $matchedElements = $this->FindObjectsFromIds(
            array(
                "filter" => "location_id",
                "ids" => $location_ids,
                "objects" => $locations)
    );

    foreach ($matchedElements as $location) {
      $location->setLocation_active($this->dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $this->managers->getManagerOf($this->module);
      $rows_affected += $manager->edit($location) ? 1 : 0;
    }
    \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($this->app()->user(), $sessionProject);

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
      //The locations are already in Session
    }
  }

  private function _PrepareLocationObject($data_sent) {
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
    $locationMatch = array();
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $locations = $sessionProject[\Library\Enums\SessionKeys::ProjectLocations];
    foreach (array_keys($locations) as $index => $key) {
      if (intval($locations[$key]->location_id()) === $location_id) {
        $locationMatch["object"] = $locations[$key];
        $locationMatch["key"] = $key;
        break;
      }
    }
    return $locationMatch;
  }

}
