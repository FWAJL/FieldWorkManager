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
  public static function GetActiveInactiveIcons($configManager) {
    return array(
        "projectActive" => $configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsProjectActiveIcon),
        "projectInactive" => $configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsProjectInactiveIcon),
        "locationActive" => $configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsLocationActiveIcon),
        "locationInactive" => $configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsLocationInactiveIcon),
        "task" => $configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsTaskIcon),
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
   * The array in Google Maps API format
   * </p>
   */
  public static function CreateFacilityMarkerItems($sessionProjects, $properties, $icons) {
    $markers = array();
    foreach ($sessionProjects as $project) {
      $marker = array();
      foreach ($properties as $objectType => $objectProperties) {
        $currentObject = \Applications\PMTool\Helpers\CommonHelper::GetValueFromArrayByKey($project, $objectType);
        if (isset($objectProperties["objectLatPropName"]) && isset($objectProperties["objectLngPropName"]) && self::CheckCoordinateValue($currentObject->$objectProperties["objectLatPropName"]()) && self::CheckCoordinateValue($currentObject->$objectProperties["objectLngPropName"]())) {
          $marker["lat"] = $currentObject->$objectProperties["objectLatPropName"]();
          $marker["lng"] = $currentObject->$objectProperties["objectLngPropName"]();
        } else if (isset($objectProperties["objectActivePropName"])) {
          $marker["icon"] = ($currentObject->$objectProperties["objectActivePropName"]()) ? $icons["projectActive"] : $icons["projectInactive"];
        }
      }
      if(isset($marker["lat"]) && isset($marker["lng"])){
          array_push($markers, $marker);
      }
    }
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
   * The array in Google Maps API format
   * </p>
   */
  public static function CreateLocationMarkerItems($sessionProject, $properties, $icons) {
    $markers = array();
    $marker = array();
    $locationObjectType = reset($properties);
    $locations = $sessionProject[\Library\Enums\SessionKeys::ProjectLocations];
    foreach ($locations as $location) {
      if (isset($locationObjectType["objectLatPropName"]) && isset($locationObjectType["objectLngPropName"]) && self::CheckCoordinateValue($location->$locationObjectType["objectLatPropName"]()) && self::CheckCoordinateValue($location->$locationObjectType["objectLngPropName"]())) {
        $marker["lat"] = $location->$locationObjectType["objectLatPropName"]();
        $marker["lng"] = $location->$locationObjectType["objectLngPropName"]();
      }
      if (isset($locationObjectType["objectActivePropName"])) {
        $marker["icon"] = ($location->$locationObjectType["objectActivePropName"]()) ? $icons["locationActive"] : $icons["locationInactive"];
      }
      if(isset($marker["lat"]) && isset($marker["lng"])){
        array_push($markers, $marker);
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
   * The array in Google Maps API format
   * </p>
   */
  public static function CreateTaskLocationMarkerItems($locations, $properties, $icons) {
    $markers = array();
    $marker = array();
    $locationObjectType = reset($properties);
    foreach ($locations as $locationType => $currentLocations) {
      foreach ($currentLocations as $location) {
        if (isset($locationObjectType["objectLatPropName"]) && isset($locationObjectType["objectLngPropName"]) && self::CheckCoordinateValue($location->$locationObjectType["objectLatPropName"]()) && self::CheckCoordinateValue($location->$locationObjectType["objectLngPropName"]())) {
          $marker["lat"] = $location->$locationObjectType["objectLatPropName"]();
          $marker["lng"] = $location->$locationObjectType["objectLngPropName"]();
        }
        if (isset($locationObjectType["objectActivePropName"])) {
          if ($locationType == \Library\Enums\SessionKeys::TaskLocations) {
            $marker["icon"] = $icons['task'];
          } else {
            $marker["icon"] = ($location->$locationObjectType["objectActivePropName"]()) ? $icons["locationActive"] : $icons["locationInactive"];
          }
        }
        if(isset($marker["lat"]) && isset($marker["lng"])){
            array_push($markers, $marker);
        }
      }
    }

    return $markers;
  }

  private static function CheckCoordinateValue($value) {
    return $value !== "" && $value !== "0.000000";
  }

}
