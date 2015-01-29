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

  public static function GetCoordinatesToCenterOverUsa($configManager) {
    return array(
      "lat" => $configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsCenterLat),
      "lng" => $configManager->get(\Library\Enums\AppSettingKeys::GoogleMapsCenterLng)
    );
//    return array(
//      array("lat" => "47.395895", "lng" => "-68.250934"),
//      array("lat" => "25.241417", "lng" => "-80.643512"),
//      array("lat" => "25.994292", "lng" => "-97.342731"),
//      array("lat" => "32.534480", "lng" => "-117.124067"), 
//      array("lat" => "49.002072", "lng" => "-122.757847")
//    );
  }
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
