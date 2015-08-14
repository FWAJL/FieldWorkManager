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
 * MapHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	MapHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class MapHelper {

  /**
   * <p> Retrieve the lattitude and longitude from the appsettings.xml
   * to build an associative array in the Google Maps API format </p>
   * 
   * @param object $configManager <p>
   * The object of Library\Config that read the appconfig.xml
   * </p>
   * @return array  $coordinates <p>
   * The array in Google Maps API format
   * </p>
   */
  public static function GetCoordinatesToCenterOverARegion($configManager) {
    return array(
        "lat" => $configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsCenterLat),
        "lng" => $configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsCenterLng),
    );
  }

  /**
   * <p> Retrieve active/inactive marker icons from the appsettings.xml
   * to build an associative array in the Google Maps API format </p>
   *
   * @param object $configManager <p>
   * The object of Library\Config that read the appconfig.xml
   * </p>
   * @return array $icons <p>
   * The array of marker icons
   * </p>
   */
  public static function GetActiveInactiveIcons($relativePath, $imageUtil, $configManager) {
    return array(
        "projectActive" => $relativePath . $imageUtil->getImageUrl($configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsProjectActiveIcon)),
        "projectInactive" => $relativePath . $imageUtil->getImageUrl($configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsProjectInactiveIcon)),
        "locationActive" => $relativePath . $imageUtil->getImageUrl($configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsLocationActiveIcon)),
        "locationActiveSmall" => $relativePath . $imageUtil->getImageUrl($configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsLocationActiveIconSmall)),
        "locationInactive" => $relativePath . $imageUtil->getImageUrl($configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsLocationInactiveIcon)),
        "task" => $relativePath . $imageUtil->getImageUrl($configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsTaskIcon)),
        "noLatLng" => $relativePath . $imageUtil->getImageUrl($configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsNoLatLngIcon)),
        "taskNoLatLng" => $relativePath . $imageUtil->getImageUrl($configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsTaskNoLatLngIcon)),
        "taskInProcess" => $relativePath . $imageUtil->getImageUrl($configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsTaskInProcess)),
        "taskFinished" => $relativePath . $imageUtil->getImageUrl($configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsTaskFinished)),
    );
  }

  /**
   * <p> Retrieve the lattitudes and longitudes from a list of objects
   * based the lattitude and longitude property names filter.
   * </p>
   * <p> Build as an output an associative array in the Google Maps API format
   * </p>
   * @param array $objects <p>
   * The array of objects of a given type
   * </p>
   * @param string $latPropName <p>
   * The lattitude property name of a given object type
   * </p>
   * @param string $lngPropName <p>
   * The longitude property name of a given object type
   * </p>
   * @return array $coordinates <p>
   * The array in Google Maps API format
   * </p>
   */
  public static function BuildLatAndLongCoordFromGeoObjects($objects, $latPropName, $lngPropName) {
    $coordinates = array();
    foreach ($objects as $object) {
      if (self::CheckCoordinateValue($object->$latPropName()) && self::CheckCoordinateValue($object->$lngPropName())) {
        $coordinate = array(
            "lat" => $object->$latPropName(),
            "lng" => $object->$lngPropName()
        );
        array_push($coordinates, $coordinate);
      }
    }
    return $coordinates;
  }

  /**
   * <p> Retrieve complete marker items based on latitude, longitude and active properties of Facility and Project objects
   * </p>
   * <p> Build as an output an associative array in the Google Maps API format
   * </p>
   * @param array $sessionProjects <p>
   * The array of session projects with both project object and facility object
   * </p>
   * @param string $properties <p>
   * Associative object->property list
   * @return array $markers <p>
   * The array with location info and nested array "marker" in Google Maps API format
   * </p>
   */
  public static function CreateFacilityMarkerItems($sessionProjects, $properties, $icons) {
    $markers = array();
    foreach ($sessionProjects as $project) {
      $marker = array();
      foreach ($properties as $objectType => $objectProperties) {
        $currentObject = \Applications\PMTool\Helpers\CommonHelper::GetValueFromArrayByKey($project, $objectType);
        if (isset($objectProperties["objectLatPropName"]) && isset($objectProperties["objectLngPropName"]) && self::CheckCoordinateValue($currentObject->$objectProperties["objectLatPropName"]()) && self::CheckCoordinateValue($currentObject->$objectProperties["objectLngPropName"]())) {
          $marker["marker"]["lat"] = $currentObject->$objectProperties["objectLatPropName"]();
          $marker["marker"]["lng"] = $currentObject->$objectProperties["objectLngPropName"]();
          $marker["id"] = $currentObject->$objectProperties["objectIdPropName"]();
          $marker["name"] = $currentObject->$objectProperties["objectNamePropName"]();
        } else if (isset($objectProperties["objectLatPropName"]) && isset($objectProperties["objectLngPropName"]) && self::CheckCoordinateValue($currentObject->$objectProperties["objectLatPropName"]()) === false && self::CheckCoordinateValue($currentObject->$objectProperties["objectLngPropName"]()) === false) {
          $marker["noLatLng"] = true;
          $marker["id"] = $currentObject->$objectProperties["objectIdPropName"]();
          $marker["name"] = $currentObject->$objectProperties["objectNamePropName"]();
        } else if (isset($objectProperties["objectActivePropName"])) {
          $marker["marker"]["icon"] = ($currentObject->$objectProperties["objectActivePropName"]()) ? $icons["projectActive"] : $icons["projectInactive"];
          $marker["active"] = $currentObject->$objectProperties["objectActivePropName"]();
        }
      }
      if (!isset($marker["marker"]["lat"]) && !isset($marker["marker"]["lng"])) {
        unset($marker["marker"]);
      }
      $markers[] = $marker;
    }
    usort($markers, function($elem1,$elem2){
      return strcmp($elem1['name'],$elem2['name']);
    });
    return $markers;
  }

  /**
   * <p> Retrieve complete marker items based on latitude, longitude and active properties of Location objects
   * </p>
   * <p> Build as an output an associative array in the Google Maps API format
   * </p>
   * @param array $sessionProject <p>
   * The session project with all project objects and nested location objects
   * </p>
   * @param string $properties <p>
   * Associative object->property list
   * @return array $markers <p>
   * The array with location info and nested array "marker" in Google Maps API format
   * </p>
   */
  public static function CreateLocationMarkerItems($locations, $properties, $icons) {
    $markers = array();
    $marker = array();
    $locationObjectType = reset($properties);
    //$locations = $sessionProject[\Library\Enums\SessionKeys::ProjectLocations];
    if(!is_null($locations)) {
      foreach ($locations as $location) {
        if (isset($locationObjectType["objectLatPropName"]) && isset($locationObjectType["objectLngPropName"]) && self::CheckCoordinateValue($location->$locationObjectType["objectLatPropName"]()) && self::CheckCoordinateValue($location->$locationObjectType["objectLngPropName"]())) {
          $marker["marker"]["lat"] = $location->$locationObjectType["objectLatPropName"]();
          $marker["marker"]["lng"] = $location->$locationObjectType["objectLngPropName"]();
          $marker["noLatLng"] = false;
        } else {
          $marker["noLatLng"] = true;
        }
        $marker["id"] = $location->$locationObjectType["objectIdPropName"]();
        $marker["marker"]["title"] = $marker["name"] = $location->$locationObjectType["objectNamePropName"]();
        $marker["active"] = $location->$locationObjectType["objectActivePropName"]();
        if (isset($locationObjectType["objectActivePropName"])) {
          $marker["marker"]["icon"] = ($location->$locationObjectType["objectActivePropName"]()) ? $icons["locationActive"] : $icons["locationInactive"];
        }
        if (!isset($marker["marker"]["lat"]) && !isset($marker["marker"]["lng"])) {
          unset($marker["marker"]);
        }
        $markers[] = $marker;
      }
    }


    return $markers;
  }

  /**
   * <p> Retrieve complete marker items based on latitude, longitude and active properties of Location objects with no task and Location objects with task linked
   * </p>
   * <p> Build as an output an associative array in the Google Maps API format
   * </p>
   * @param array $locations <p>
   * 2 element array with nested array for locations with no tasks and for locations with tasks linked
   * array(
   * "\Library\Enums\SessionKeys::TaskLocations" => $projectLocationsArray,
   * "\Library\Enums\SessionKeys::ProjectLocations" => $taskLocationsArray
   * )
   * </p>
   * @param string $properties <p>
   * Associative object->property list
   * @return array $markers <p>
   * The array with location info and nested array "marker" in Google Maps API format
   * </p>
   */
  public static function CreateTaskLocationMarkerItems($caller, $locations, $properties, $icons, $activeTask) {
    $markers = array();
    $marker = array();
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($caller->app()->user());
    $taskLocations = $sessionTask[\Library\Enums\SessionKeys::TaskLocations];
    $locationObjectType = reset($properties);
    foreach ($locations as $locationType => $currentLocations) {
      foreach ($currentLocations as $location) {
        if (isset($locationObjectType["objectLatPropName"]) && isset($locationObjectType["objectLngPropName"]) && self::CheckCoordinateValue($location->$locationObjectType["objectLatPropName"]()) && self::CheckCoordinateValue($location->$locationObjectType["objectLngPropName"]())) {
          $marker["marker"]["lat"] = $location->$locationObjectType["objectLatPropName"]();
          $marker["marker"]["lng"] = $location->$locationObjectType["objectLngPropName"]();
          $marker["id"] = $location->$locationObjectType["objectIdPropName"]();
          $marker["marker"]["title"] = $marker["name"] = $location->$locationObjectType["objectNamePropName"]();
          $marker["noLatLng"] = false;
        } else {
          $marker["noLatLng"] = true;
          $marker["id"] = $location->$locationObjectType["objectIdPropName"]();
          $marker["name"] = $location->$locationObjectType["objectNamePropName"]();
        }
        $marker["active"] = $location->$locationObjectType["objectActivePropName"]();
        if (isset($locationObjectType["objectActivePropName"])) {
          if ($locationType == \Library\Enums\SessionKeys::TaskLocations) {
            $taskLocationObj = \Applications\PMTool\Helpers\CommonHelper::FindObjectByIntValue(intval($location->location_id()),'location_id',$taskLocations);
            if($taskLocationObj) {
              if($taskLocationObj->task_location_status() == 2 and $activeTask) {
                $marker["marker"]["icon"] = $icons['taskFinished'];
              } else if ($taskLocationObj->task_location_status() == 1 and $activeTask){
                $marker["marker"]["icon"] = $icons['taskInProcess'];
              } else {
                $marker["marker"]["icon"] = $icons['task'];
              }
            }
            $marker["task"] = true;
          } else {
            $marker["marker"]["icon"] = ($location->$locationObjectType["objectActivePropName"]()) ? $icons["locationActiveSmall"] : $icons["locationInactive"];
            $marker["task"] = false;
          }
        }
        if (!isset($marker["marker"]["lat"]) && !isset($marker["marker"]["lng"])) {
          unset($marker["marker"]);
        }
        $markers[] = $marker;
      }
    }

    return $markers;
  }

  public static function GetBoundary($sessionProject) {
    $boundary = "";

    //get facility object for current project
    $facilityObj = $sessionProject[\Library\Enums\SessionKeys::FacilityObject];

    $boundary = $facilityObj->boundary();

    return $boundary;
  }

  private static function CheckCoordinateValue($value) {
    return isset($value) && $value !== "" && $value !== "0.000000";
  }

}
