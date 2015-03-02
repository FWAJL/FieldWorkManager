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
		
		//Fetch tooltip data from xml and pass to view as an array
		$tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"map", "targetaction": "allProjects", "targetattr": ["map-info-add","map-info-shape","map-info-ruler","question-map-h3"]}', $this->app->name());
		$this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);
		
        $modules = $this->app()->router()->selectedRoute()->phpModules();
        $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $modules);
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
		
		//Fetch tooltip data from xml and pass to view as an array
		$tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"map", "targetaction": "currentProject", "targetattr": ["question-map-h3"]}', $this->app->name());
		$this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

        //refresh locations
        $this->_GetAndStoreLocationsInSession($sessionProject);
        $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());

        $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
        $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $modules);
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
		
		//Fetch tooltip data from xml and pass to view as an array
		$tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"map", "targetaction": "currentProjectLocations", "targetattr": ["question-map-h3"]}', $this->app->name());
		$this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

        $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
        $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $modules);
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
		
		//Fetch tooltip data from xml and pass to view as an array
		$tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"map", "targetaction": "taskLocations", "targetattr": ["question-map-h3"]}', $this->app->name());
		$this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

        //add view vars for breadcrumb
        $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
        $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
        $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $modules);
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
        $icons = \Applications\PMTool\Helpers\MapHelper::GetActiveInactiveIcons($this->app()->config());

        //create google maps marker items
        $items = \Applications\PMTool\Helpers\MapHelper::CreateFacilityMarkerItems(\Applications\PMTool\Helpers\ProjectHelper::GetSessionProjects($this->user()),$properties,$icons);

        $result["ruler"] = $icons["ruler"];
        $result["defaultPosition"] = \Applications\PMTool\Helpers\MapHelper::GetCoordinatesToCenterOverARegion($this->app()->config());
        $result["items"] = $items;
        $this->SendResponseWS(
            $result, array(
            "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Map,
            "resx_key" => $this->action(),
            "step" => (count($items) >= 0) ? "success" : "error"
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
        $icons = \Applications\PMTool\Helpers\MapHelper::GetActiveInactiveIcons($this->app()->config());

        //create google maps marker items
        $items = \Applications\PMTool\Helpers\MapHelper::CreateFacilityMarkerItems(array($sessionProject),$properties,$icons);

        $result["ruler"] = $icons["ruler"];
        $result["noLatLngIcon"] = $icons["noLatLng"];
        $result["items"] = $items;
        $result["defaultPosition"] = \Applications\PMTool\Helpers\MapHelper::GetCoordinatesToCenterOverARegion($this->app()->config());
        $this->SendResponseWS(
            $result, array(
            "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Map,
            "resx_key" => $this->action(),
            "step" => (count($items) >= 0) ? "success" : "error"
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
        $this->_GetAndStoreLocationsInSession($sessionProject);
        $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());

        //load marker icons from config
        $icons = \Applications\PMTool\Helpers\MapHelper::GetActiveInactiveIcons($this->app()->config());

        //create google maps marker items
        $items = \Applications\PMTool\Helpers\MapHelper::CreateLocationMarkerItems($sessionProject,$properties,$icons);

        $result["ruler"] = $icons["ruler"];
        $result["noLatLngIcon"] = $icons["noLatLng"];
        $result["activeIcon"] = $icons["locationActive"];
        $result["inactiveIcon"] = $icons["locationInactive"];
        $result["items"] = $items;
        $result["defaultPosition"] = \Applications\PMTool\Helpers\MapHelper::GetCoordinatesToCenterOverARegion($this->app()->config());

        //if there are no markers try to set default position to facility location
        if(count($items)==0){
            $defaultLocations = \Applications\PMTool\Helpers\MapHelper::BuildLatAndLongCoordFromGeoObjects(array(\Applications\PMTool\Helpers\CommonHelper::GetValueFromArrayByKey($sessionProject,$defaultLocationProperties['object'])),$defaultLocationProperties['objectLatPropName'],$defaultLocationProperties['objectLngPropName']);
            if(count($defaultLocations)>0){
                $result['defaultPosition'] = $defaultLocations[0];
            }
        }

        $this->SendResponseWS(
            $result, array(
            "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Map,
            "resx_key" => $this->action(),
            "step" => (count($items) >= 0) ? "success" : "error"
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

        //get current sesion project and refresh project's locations then get current session task
        $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
        $this->_GetAndStoreLocationsInSession($sessionProject);
        $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
        $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->app()->user());

        //create two arrays with current project's locations, one for locations linked with the task and other unlinked
        $taskLocations = \Applications\PMTool\Helpers\LocationHelper::GetAndStoreTaskLocations($this, $sessionTask);

        $projectLocations = \Applications\PMTool\Helpers\LocationHelper::GetProjectLocations($this->app()->user(),$sessionProject,$taskLocations);
        $locations = array(\Library\Enums\SessionKeys::ProjectLocations=>$projectLocations,\Library\Enums\SessionKeys::TaskLocations=>$taskLocations);

        //load marker icons from config
        $icons = \Applications\PMTool\Helpers\MapHelper::GetActiveInactiveIcons($this->app()->config());

        //create google maps marker items
        $items = \Applications\PMTool\Helpers\MapHelper::CreateTaskLocationMarkerItems($locations, $properties, $icons);

        $result["ruler"] = $icons["ruler"];
        $result["noLatLngIcon"] = $icons["noLatLng"];
        $result["items"] = $items;
        $result["defaultPosition"] = \Applications\PMTool\Helpers\MapHelper::GetCoordinatesToCenterOverARegion($this->app()->config());
        $this->SendResponseWS(
            $result, array(
            "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Map,
            "resx_key" => $this->action(),
            "step" => (count($items) >= 0) ? "success" : "error"
        ));
    }

    private function _GetAndStoreLocationsInSession($sessionProject) {
        $lists = array();
        if (count($sessionProject[\Library\Enums\SessionKeys::ProjectLocations]) === 0 || !$sessionProject[\Library\Enums\SessionKeys::ProjectLocations]) {
            \Applications\PMTool\Helpers\LocationHelper::GetLocationList($this, $sessionProject);
        } else {
            //The locations are already in Session
        }
    }

}
