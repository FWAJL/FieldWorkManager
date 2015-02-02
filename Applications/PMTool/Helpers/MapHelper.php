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
        "lng" => $configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsCenterLng)
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
      $coordinate = array(
          "lat" => $object->$latPropName(),
          "lng" => $object->$lngPropName()
      );
      array_push($coordinates, $coordinate);
    }
    return $coordinates;
  }

}
