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
  
  /**
  * A helper method which print_r's a formatted array nicely
  * Mainly used for debuging / developing 
  */
  public static function pr($arr) {
  	echo '<pre>';
  	print_r($arr);
  	echo '</pre>';
  }

  public static function SetDynamicPropertyNamesForDualList($module, $property_list) {
    $dynamicPropertyNames = array();
    foreach ($property_list as $key => $value) {
      $dynamicPropertyNames[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_key . $key] = $module . "_" . $value;
    }
    return $dynamicPropertyNames;
  }

  public static function SetPropertyNamesForDualList($module) {
    return array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id => $module . "_id",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name => $module . "_name",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_active => $module . "_active",
    );
  }

  public static function GetShortClassName($object) {
    $full_class_name = get_class($object);
    $class_name = substr($full_class_name, strrpos($full_class_name, '\\') + 1);
    return strtolower($class_name);
  }

  public static function GetFullClassName($object) {
    return get_class($object);
  }

  public static function GetListObjectsInSessionByKey($user, $key) {
    $objects = array();
    $projects = $user->getAttribute(\Library\Enums\SessionKeys::UserSessionProjects);
    if ($projects === NULL) {
      $projects = array();
    }
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

  /**
   * Find an object in a list filtering by the id value of  given property name of each object.
   * 
   * @param string $idValue <p>
   * The id value used to compare with the property name given of each object of the list. </p>
   * @param string $propName <p>
   * The property name to build the property getter function to perform a comparaison with the id value provided. </p>
   * @param array (of object) $listOfObjects <p>
   * The list of objects of type T to look into. T is defined by the caller. </p>
   * @return mixed{boolean,object} <p>
   * The object if found or FALSE otherwise. </p>
   */
  public static function FindObjectByIntValue($idValue, $propName, $listOfObjects) {
    $match = FALSE;
    foreach ($listOfObjects as $obj) {
      if (intval($obj->$propName()) === $idValue) {
        $match = $obj;
        break;
      }
    }
    return $match;
  }

  /**
   * Find an object in a list filtering by the string value of one property name of each object.
   * 
   * @param string $filter <p>
   * The filter used to compare with the property name of each object of the list. </p>
   * @param string $propName <p>
   * The property name to build the property getter function to perform a comparaison with the filter value provided. </p>
   * @param array (of object) $list_of_obj <p>
   * The list of objects of type T to look into. </p>
   * @param string $key <p>
   * The key to read the value in an associative array if the list of object
   * is a list of associative arrays.
   * @return mixed{boolean,object} <p>
   * The object if found or FALSE otherwise. </p>
   */
  public static function FindObjectByStringValue($filter, $propName, $listOfObjects, $key = NULL) {
    $match = FALSE;
    if ($key === NULL) {
      foreach ($listOfObjects as $obj) {
        if ($obj->$propName() === $filter) {
          $match = $obj;
          break;
        }
      }
    } else {
      foreach ($listOfObjects as $obj) {
        if ($obj[$key]->$propName() === $filter) {
          $match = $obj[$key];
          break;
        }
      }
    }

    return $match;
  }

  public static function FindIndexInObjectListById($id, $prop_name, $sessionArray, $sessionKey) {
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

  public static function FindIndexInIdListById($valueToFind, $idList) {
    $match = NULL;
    foreach (array_keys($idList) as $index => $key) {
      if ($idList[$key] === $valueToFind) {
        $match = $index;
        break;
      }
    }
    return $match;
  }

  public static function FilterObjectsToExcludeRelatedObject($objects, $related_objects, $prop_id) {
    $matches = array();
    foreach ($objects as $object) {
      $to_add = TRUE;
      foreach ($related_objects as $related_object) {
        if (intval($object->$prop_id()) === intval($related_object->$prop_id())) {
          $to_add = FALSE;
          break;
        }
      }
      if ($to_add) {
        array_push($matches, $object);
      }
    }
    return $matches;
  }

  public static function SetActiveTab(\Library\User $user, $tab_name, $sessionKey) {
    $tabs = $user->getAttribute($sessionKey);
    foreach ($tabs as $key => $value) {
      $tabs[$key] = "";
    }
    $tabs[$tab_name] = "active";
    $user->setAttribute($sessionKey, $tabs);
  }

  //TODO: replace with GetValueInSession
  public static function GetTabsStatus(\Library\User $user, $sessionKey) {
    return $user->getAttribute($sessionKey);
  }

  public static function SetValueInSession($user, $sessionKey, $value) {
    $user->setAttribute($sessionKey, $value);
  }

  public static function GetValueInSession($user, $sessionKey) {
    return $user->getAttribute($sessionKey);
  }

  public static function GetObjectListFromSessionArrayBySessionKey($sessionArray, $sessionKey) {
    $list = array();
    foreach ($sessionArray as $array) {
      array_push($list, $array[$sessionKey]);
    }
    return $list;
  }

  public static function GetValueFromArrayByKey($array, $key) {
    return $array[$key];
  }

  public static function GetPropValueFromObjectByPropName($object, $propName, $isArray = TRUE) {
    return
            $isArray ?
            $object[$propName] :
            $object->$propName();
  }

  /**
  * Returns the truncated text based on the passed
  * parameters, at present the method generates the
  * HTML markup as well which is needed for the tooltip
  * to work properly.
  */
  public static function generateEllipsisAndTooltipMarkupFor($textToTruncate, $charLimit, $placement) {
    $truncatedData = null;
    if(strlen($textToTruncate) > intval($charLimit)) {
      //We would have to truncate
      $truncatedData = array(
                        'source'    => $textToTruncate,
                        'truncated' => substr($textToTruncate, 0, $charLimit - 3) . '...'
                      );
    } else {
      //Return the string as it is
      $truncatedData = array(
                        'source'    => $textToTruncate,
                        'truncated' => ''
                      );
    }

    if(trim($truncatedData['truncated']) !== '') {
      echo $truncatedData['truncated'];
      echo '<input type="hidden" class="ellipsis-tooltip" value="' . $truncatedData['source'] . '" placement="' . $placement . '" >';
    } else {
      echo $truncatedData['source'];
    }
  }

}
