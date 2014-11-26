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
 * CommonHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	CommonHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class CommonHelper {

  public static function StringToArray($delimiter, $string) {
    $arrayRaw = explode($delimiter, $string);
    $arrayCleaned = array();
    foreach ($arrayRaw as $value) {
      if (!empty($value))
        array_push($arrayCleaned, CommonHelper::CleanString($value));
    }
    return $arrayCleaned;
  }

  public static function CleanString($string) {
    return trim($string);
  }

  public static function SetPropertyNamesForDualList($module) {
    return array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id => $module . "_id",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name => $module . "_name",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_active => $module . "_active",
    );
  }

  public static function GetListObjectsInSessionByKey($user, $key) {
    $objects = array();
    $projects = $user->getAttribute(\Library\Enums\SessionKeys::UserSessionProjects);
    if ($projects === NULL) { $projects = array(); }
    foreach ($projects as $project) {
      array_push($objects, $project[$key]);
    }
    return $objects;
  }

  /**
   * Prepare an object Object before calling the DB.
   * 
   * @param array $data_sent from POST request
   * @param object $object_is an instance of Class found in Models/Dao
   * @return Object (possible types in Models/Dao)
   */
  public static function PrepareUserObject($data_sent, $object) {
    foreach ($data_sent as $key => $value) {
      $method = "set" . ucfirst($key);
      $object->$method(!array_key_exists($key, $data_sent) ? NULL : $value);
    }
    return $object;
  }
  
  public static function FindObject($id, $prop_name, $list_of_obj) {
    $match = FALSE;
    foreach ($list_of_obj as $obj) {
      if (intval($obj->$prop_name()) === $id) {
        $match = $obj;
        break;
      }
    }
    return $match;
  }
  public static function FindIndexById($id, $prop_name, $sessionArray, $sessionKey) {
    $match = array();
    $list = $sessionArray[$sessionKey];
    foreach (array_keys($list) as $index => $key) {
      if (intval($list[$key]->$prop_name()) === intval($id)) {
        $match["object"] = $list[$key];
        $match["key"] = $key;
        break;
      }
    }
    return $match;
  }

}

