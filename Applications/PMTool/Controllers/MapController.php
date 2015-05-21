<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class MapController extends \Library\BaseController {

  /**
   * <p> Method to load the Map view as configured in routes.xml </p>
   * <p> Set the modules for the route to the page object </p>
   * @param \Library\HttpRequest $rq <p>
   * The current HttpRequest.
   * </p>
   * @return void
   */
  public function executeLoadView($rq) {

    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"map", "targetaction": "allProjects", "targetattr": ["map-info-add","map-info-shape","map-info-ruler","question-map-h3"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    $alert_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"map", "targetaction": "loadMaps", "operation": ["addUniqueCheck","checkCoordinates"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $alert_msg);

    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $modules);
    //Fetch prompt box data from xml and pass to view as an array
    $infoWindow = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"map", "targetaction": "loadProjectInfo", "operation": ["addNullCheckAddPrompt"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Map::popup_project_info, $infoWindow);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Map::default_active_control, $rq->getData('active') ?: 'pan');
  }

  /**
   * <p> Method to load the Map view as configured in routes.xml </p>
   * <p> Set the modules for the route to the page object </p>
   * @param object $rq <p>
   * The current HttpRequest.
   * </p>
   * @return void
   */
  public function executeLoadCurrentView($rq) {
    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
	
	//Check if a project needs to be selected in order to display this page
    if (!$sessionProject) {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::MapCurrentProject);
    }

    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"map", "targetaction": "currentProject", "targetattr": ["question-map-h3", "map-info-shape", "map-info-ruler", "map-info-add"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    $alert_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"map", "targetaction": "loadMaps", "operation": ["addUniqueCheck","checkCoordinates"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $alert_msg);

    //refresh locations
    $sessionProject[\Library\Enums\SessionKeys::ProjectLocations] = $this->_GetAndStoreLocationsInSession($sessionProject);

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $modules);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Map::default_active_control, $rq->getData('active') ?: 'pan');
  }


  /**
   * <p> Method to load the Map view as configured in routes.xml </p>
   * <p> Set the modules for the route to the page object </p>
   * @param object $rq <p>
   * The current HttpRequest.
   * </p>
   * @return void
   */
  public function executeLoadCurrentLocationsView($rq) {
    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
	
	//Check if a project needs to be selected in order to display this page
    if (!$sessionProject)
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::MapCurrentProjectLocations);

	//Fetch tooltip data from xml and pass to view as an array
	$tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"map", "targetaction": "currentProjectLocations", "targetattr": ["question-map-h3", "map-info-ruler", "map-info-shape", "map-info-add"]}', $this->app->name());
	$this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    //Fetch prompt box data from xml and pass to view as an array
    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"map", "targetaction": "loadCurrentLocationsView", "operation": ["addNullCheckAddPrompt"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);

    //Fetch alert box data
    $alert_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"map", "targetaction": "loadCurrentLocationsView", "operation": ["addUniqueCheck","noPhotos"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $alert_msg);

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $modules);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Map::default_active_control, $rq->getData('active') ?: 'pan');

  }

  /**
   * <p> Method to load the Map view as configured in routes.xml </p>
   * <p> Set the modules for the route to the page object </p>
   * @param object $rq <p>
   * The current HttpRequest.
   * </p>
   * @return void
   */
  public function executeLoadCurrentTasksView($rq) {
    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->app()->user());

		//Check if a project needs to be selected in order to display this page
		if (!$sessionProject)
		  $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::MapTaskLocations);
		  
		//Next check if a task is selected
		if(!$sessionTask)
		  $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::TaskSelectTask . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::MapTaskLocations);
    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"map", "targetaction": "taskLocations", "targetattr": ["question-map-h3", "map-info-ruler", "map-info-shape", "map-info-add"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    $alert_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"map", "targetaction": "loadMaps", "operation": ["addUniqueCheck","checkCoordinates"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $alert_msg);

    //add view vars for breadcrumb
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $modules);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Map::default_active_control, $rq->getData('active') ?: 'pan');
  }


  /**
   * <p> Retrieve the markers to display on Google Maps and the center position
   * if no items are returned for All Projects</p>
   * <p> This method is called via an AJAX request from the client side. </p>
   * <p> The method receive by inheritance some post data
   * properties JSON object which includes objectTypes as "keys" and nested associative propNames
   * example:
   * "properties": {
   *  "facility_obj": {
   *   "objectLatPropName": "facility_lat",
   *   "objectLngPropName": "facility_long"
   *   },
   *   "project_obj": {
   *   "objectActivePropName": "project_active"
   *   }
   * Since we must use "project_active" property to determine marker icon we must use multiple mapped objects there and we can't send only facility property info
   * For more info:
   * \Applications\PMTool\Helpers\MapHelper::CreateFacilityMarkerItems
   * @link
   * </p>
   * <p>
   * @param object $rq <p>
   * The current HttpRequest.
   * </p>
   * @return mixed $result <p>
   * The result is a standard JSON response containing
   * the specific data to the request, e.g.:
   *  - the list markers for a given filter
   *  - the default position used to center the map (this configured in the appsettings.xml)
   */
  public function executeListAll(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();
    $properties = json_decode($dataPost['properties'], true);

    //get current session project
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());

    //load marker icons from config
    $icons = \Applications\PMTool\Helpers\MapHelper::GetActiveInactiveIcons($this->app()->relative_path,$this->app()->imageUtil,$this->app()->config());

    //create google maps marker items
    $projectLocationMarkers = \Applications\PMTool\Helpers\MapHelper::CreateFacilityMarkerItems(\Applications\PMTool\Helpers\ProjectHelper::GetSessionProjects($this->user()),$properties,$icons);

    $result["defaultPosition"] = \Applications\PMTool\Helpers\MapHelper::GetCoordinatesToCenterOverARegion($this->app()->config());
    $result["items"] = $projectLocationMarkers;
    $result["noLatLngIcon"] = $icons["noLatLng"];
    $result["type"] = "facility";
    $result["activeTask"] = false;

    $result["controls"] = array(
      "markers" => false,
      "shapes" => false,
      "ruler" => true
    );
    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Map,
      "resx_key" => $this->action(),
      "step" => (count($projectLocationMarkers) >= 0) ? "success" : "error"
    ));
  }


  /**
   * <p> Retrieve the markers to display on Google Maps and the center position
   * if no items are returned for the Current Project</p>
   * <p> This method is called via an AJAX request from the client side. </p>
   * <p> The method receive by inheritance some post data
   * properties JSON object which includes objectTypes as "keys" and nested associative propNames
   * example:
   * "properties": {
   *  "facility_obj": {
   *   "objectLatPropName": "facility_lat",
   *   "objectLngPropName": "facility_long"
   *   },
   *   "project_obj": {
   *   "objectActivePropName": "project_active"
   *   }
   * Since we must use "project_active" property to determine marker icon we must use multiple mapped objects there and we can't send only facility property info
   * For more info:
   * \Applications\PMTool\Helpers\MapHelper::CreateFacilityMarkerItems
   * @link
   * </p>
   * <p>
   * @param object $rq <p>
   * The current HttpRequest.
   * </p>
   * @return mixed $result <p>
   * The result is a standard JSON response containing
   * the specific data to the request, e.g.:
   *  - the list markers for a given filter
   *  - the default position used to center the map (this configured in the appsettings.xml)
   */
  public function executeListCurrentProject(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    //get datapost and decode properties string to array
    $dataPost = $this->dataPost();
    $properties = json_decode($dataPost['properties'], true);

    //get current session project
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());

    //load marker icons from config
    $icons = \Applications\PMTool\Helpers\MapHelper::GetActiveInactiveIcons($this->app()->relative_path,$this->app()->imageUtil,$this->app()->config());

    //create google maps marker items
    $projectLocationMarkers = \Applications\PMTool\Helpers\MapHelper::CreateFacilityMarkerItems(array($sessionProject),$properties,$icons);

    $result["noLatLngIcon"] = $icons["noLatLng"];
    $result["items"] = $projectLocationMarkers;
    $result["defaultPosition"] = \Applications\PMTool\Helpers\MapHelper::GetCoordinatesToCenterOverARegion($this->app()->config());
    $result["boundary"] = \Applications\PMTool\Helpers\MapHelper::GetBoundary($sessionProject);
    $result["facility_id"] = $sessionProject[\Library\Enums\SessionKeys::FacilityObject]->facility_id();
    $result["project_id"] = $sessionProject[\Library\Enums\SessionKeys::FacilityObject]->project_id();
    $result["type"] = "facility";
    $result["activeTask"] = false;

    $result["controls"] = array(
      "markers" => false,
      "shapes" => true,
      "ruler" => true
    );
    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Map,
      "resx_key" => $this->action(),
      "step" => (count($projectLocationMarkers) >= 0) ? "success" : "error"
    ));
  }

  /**
   * <p> Retrieve the markers to display on Google Maps and the center position
   * if no items are returned for the Current Project Locations</p>
   * <p> This method is called via an AJAX request from the client side. </p>
   * <p> The method receive by inheritance some post data
   * properties JSON object which includes objectTypes as "keys" and nested associative propNames
   * example:
   * "properties": {
   *  "location_obj": {
   *       "objectLatPropName": "location_lat",
   *       "objectLngPropName": "location_long",
   *       "objectActivePropName": "location_active"
   *      }
   *   }
   * For more info:
   * \Applications\PMTool\Helpers\MapHelper::CreateLocationMarkerItems
   * @link
   * </p>
   * <p>
   * @param object $rq <p>
   * The current HttpRequest.
   * </p>
   * @return mixed $result <p>
   * The result is a standard JSON response containing
   * the specific data to the request, e.g.:
   *  - the list markers for a given filter
   *  - the default position used to center the map (this configured in the appsettings.xml)
   */

  public function executeListCurrentProjectLocations(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    //get datapost and decode properties string to array
    $dataPost = $this->dataPost();
    $properties = json_decode($dataPost['properties'],true);

    //get facility location info
    $defaultLocationProperties = $properties['defaultLocation'];

    //unset default location because we don't want to show facility marker
    unset($properties['defaultLocation']);

    //get current sesion project and refresh project's locations
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $sessionProject[\Library\Enums\SessionKeys::ProjectLocations] = $this->_GetAndStoreLocationsInSession($sessionProject);

    //load marker icons from config
    $icons = \Applications\PMTool\Helpers\MapHelper::GetActiveInactiveIcons($this->app()->relative_path,$this->app()->imageUtil,$this->app()->config());

    //create google maps marker items
    $projectLocationMarkers = \Applications\PMTool\Helpers\MapHelper::CreateLocationMarkerItems($sessionProject,$properties,$icons);

    $result["noLatLngIcon"] = $icons["noLatLng"];
    $result["activeIcon"] = $icons["locationActive"];
    $result["inactiveIcon"] = $icons["locationInactive"];
    $result["items"] = $projectLocationMarkers;
    $result["defaultPosition"] = \Applications\PMTool\Helpers\MapHelper::GetCoordinatesToCenterOverARegion($this->app()->config());
    $result["boundary"] = \Applications\PMTool\Helpers\MapHelper::GetBoundary($sessionProject);
    $result["facility_id"] = $sessionProject[\Library\Enums\SessionKeys::FacilityObject]->facility_id();
    $result["project_id"] = $sessionProject[\Library\Enums\SessionKeys::FacilityObject]->project_id();
    $result["type"] = "location";
    $result["activeTask"] = false;
    $result["controls"] = array(
      "markers" => true,
      "shapes" => true,
      "ruler" => true
    );
    $result["activeControl"] = "pan";

    $noCoordinateMarkers = count(array_filter($projectLocationMarkers,function($marker){return !$marker['noLatLng'];}));
    //if there are no markers try to set default position to facility location
    if($noCoordinateMarkers==0){
      $defaultLocations = \Applications\PMTool\Helpers\MapHelper::BuildLatAndLongCoordFromGeoObjects(array(\Applications\PMTool\Helpers\CommonHelper::GetValueFromArrayByKey($sessionProject,$defaultLocationProperties['object'])),$defaultLocationProperties['objectLatPropName'],$defaultLocationProperties['objectLngPropName']);
      if(count($defaultLocations)>0){
        $result['defaultPosition'] = $defaultLocations[0];
      }
    }

    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Map,
      "resx_key" => $this->action(),
      "step" => (count($projectLocationMarkers) >= 0) ? "success" : "error"
    ));
  }

  /**
   * <p> Retrieve the markers to display on Google Maps and the center position
   * if no items are returned for the Current Project Task Locations</p>
   * <p> This method is called via an AJAX request from the client side. </p>
   * <p> The method receive by inheritance some post data
   * properties JSON object which includes objectTypes as "keys" and nested associative propNames
   * example:
   * "properties": {
   *  "location_obj": {
   *      "objectLatPropName": "location_lat",
   *      "objectLngPropName": "location_long",
   *      "objectActivePropName": "location_active"
   *      }
   *   }
   * For more info:
   * \Applications\PMTool\Helpers\MapHelper::CreateTaskLocationMarkerItems
   * @link
   * </p>
   * <p>
   * @param object $rq <p>
   * The current HttpRequest.
   * </p>
   * @return mixed $result <p>
   * The result is a standard JSON response containing
   * the specific data to the request, e.g.:
   *  - the list markers for a given filter
   *  - the default position used to center the map (this configured in the appsettings.xml)
   */


  public function executeListCurrentProjectTasks(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();
    $properties = json_decode($dataPost['properties'],true);

    //get facility location info
    $defaultLocationProperties = $properties['defaultLocation'];

    //unset default location because we don't want to show facility marker
    unset($properties['defaultLocation']);

    //get current sesion project and refresh project's locations then get current session task
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $sessionProject[\Library\Enums\SessionKeys::ProjectLocations] = $this->_GetAndStoreLocationsInSession($sessionProject);
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->app()->user());

    //create two arrays with current project's locations, one for locations linked with the task and other unlinked
    $taskLocations = \Applications\PMTool\Helpers\LocationHelper::GetAndStoreTaskLocations($this, $sessionTask);

    $projectLocations = \Applications\PMTool\Helpers\LocationHelper::GetProjectLocations($this,$sessionProject,$taskLocations);
    $locations = array(\Library\Enums\SessionKeys::TaskLocations=>$taskLocations,\Library\Enums\SessionKeys::ProjectLocations=>$projectLocations);

    //load marker icons from config
    $icons = \Applications\PMTool\Helpers\MapHelper::GetActiveInactiveIcons($this->app()->relative_path,$this->app()->imageUtil,$this->app()->config());

    if(isset($properties['activeTask']) && $properties['activeTask']) {
      $activeTask = $result["activeTask"] = true;
    } else {
      $activeTask = $result["activeTask"] = false;
    }
    //create google maps marker items
    $projectLocationMarkers = \Applications\PMTool\Helpers\MapHelper::CreateTaskLocationMarkerItems($this, $locations, $properties, $icons, $activeTask);

    $result["noLatLngIcon"] = $icons["taskNoLatLng"];
    $result["activeIcon"] = $icons["task"];
    $result["inactiveIcon"] = $icons["task"];
    $result["items"] = $projectLocationMarkers;
    $result["defaultPosition"] = \Applications\PMTool\Helpers\MapHelper::GetCoordinatesToCenterOverARegion($this->app()->config());
    $result["boundary"] = \Applications\PMTool\Helpers\MapHelper::GetBoundary($sessionProject);
    $result["facility_id"] = $sessionProject[\Library\Enums\SessionKeys::FacilityObject]->facility_id();
    $result["project_id"] = $sessionProject[\Library\Enums\SessionKeys::FacilityObject]->project_id();
    $result["type"] = 'task';

    $noCoordinateMarkers = count(array_filter($projectLocationMarkers,function($item){return !$item['noLatLng'];}));

    if($noCoordinateMarkers==0){
      $defaultLocations = \Applications\PMTool\Helpers\MapHelper::BuildLatAndLongCoordFromGeoObjects(array(\Applications\PMTool\Helpers\CommonHelper::GetValueFromArrayByKey($sessionProject,$defaultLocationProperties['object'])),$defaultLocationProperties['objectLatPropName'],$defaultLocationProperties['objectLngPropName']);
      if(count($defaultLocations)>0){
        $result['defaultPosition'] = $defaultLocations[0];
      }
    }

    $result["controls"] = array(
      "markers" => true,
      "shapes" => true,
      "ruler" => true
    );
    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Map,
      "resx_key" => $this->action(),
      "step" => (count($projectLocationMarkers) >= 0) ? "success" : "error"
    ));
  }

  private function _GetAndStoreLocationsInSession($sessionProject) {
    if (count($sessionProject[\Library\Enums\SessionKeys::ProjectLocations]) === 0 || !$sessionProject[\Library\Enums\SessionKeys::ProjectLocations]) {
      $result = \Applications\PMTool\Helpers\LocationHelper::GetLocationList($this, $sessionProject);
      $sessionProject[\Library\Enums\SessionKeys::ProjectLocations] = $result[\Library\Enums\SessionKeys::ProjectLocations];
    } else {
      return $sessionProject[\Library\Enums\SessionKeys::ProjectLocations];
    }
  }

}
