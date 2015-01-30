<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class MapController extends \Library\BaseController {

  /**
   * <p> Method to load the Map view as configured in routes.xml </p>
   * <p> Set the modules for the route to the page object </p>
   * @param object $rq <p>
   * The current HttpRequest.
   * </p>
   * @return void
   */
  public function executeLoadView($rq) {
    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $modules);
  }

  /**
   * <p> Retrieve the markers to display on Google Maps and the center position
   * if no items are returned </p>
   * <p> This method is called via an AJAX request from the client side. </p>
   * <p> The method receive by inheritance some post data 
   * an associative array which are:
   *  - the object type of which to retrieve the list of markers
   *  - the object lattitude property name
   *  - the object longitude property name
   * </p>
   * <p> The last two fields are used to create the list markers according to
   * Google Maps API. For more info:
   *  \Applications\PMTool\Helpers\MapHelper::GetCoordinatesToCenterOverARegion
   * @link 
   * @param object $rq <p>
   * The current HttpRequest.
   * </p>
   * @return mixed $result <p>
   * The result is a standard JSON response containing
   * the specific data to the request, e.g.:
   *  - the list markers for a given filter
   *  - the default position used to center the map (this configured in the appsettings.xml
   */
  public function executeListAll(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

    $objects = \Applications\PMTool\Helpers\CommonHelper::GetObjectListFromSessionArrayBySessionKey(
                    $this->user(), \Applications\PMTool\Helpers\ProjectHelper::GetSessionProjects($this->user()), $dataPost["objectType"]);

    $coordinates = \Applications\PMTool\Helpers\MapHelper::BuildLatAndLongCoordFromGeoObjects(
                    $objects, $dataPost["objectLatPropName"], $dataPost["objectLngPropName"]);

    $result["defaultPosition"] = \Applications\PMTool\Helpers\MapHelper::GetCoordinatesToCenterOverARegion($this->app()->config());
    $result["items"] = $coordinates;
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Map,
        "resx_key" => $this->action(),
        "step" => (count($coordinates) >= 0) ? "success" : "error"
    ));
  }

}
