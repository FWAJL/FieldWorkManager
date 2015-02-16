<?php

/**
 *
 * @package		Basic MVC framework
 * @author		FWM DEV Team
 * @copyright	Copyright (c) 2015
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * PopUpHelper Class
 *
 * @package		Applications/
 * @subpackage	PMTool
 * @category	Helpers
 * @author		Souvik Ghosh
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class PopUpHelper {

  public static $appname;
  
  /**
  * Fetches all messages associated with a particular
  * attribute.
  */
  public static function getTooltipMsgForAttribute($attrib, $appname) {
    PopUpHelper::$appname = $appname;
    $tooltipMessages = self::loadToolTipMessagefromXML();
    $msg_array = array();
    foreach ($tooltipMessages as $msg) {
      if ($msg->getAttribute('targetattr') == $attrib && $msg->getAttribute('uicomponent') == 'tooltip') {
				array_push($msg_array, array('tooltip' => array('value' => $msg->getAttribute('value'), 'placement' => $msg->getAttribute('placement'))));
      }
    }

    return $msg_array;
  }
  
  /**
  * Fetches configurations for confirmBox/AlertBox
  * Accepts a json of the form:
  * {targetcontroller: the_controller, targetaction: the_action, operation: the_operation}
  */
  public static function getConfirmBoxMsg($param, $appname)
  {
	PopUpHelper::$appname = $appname;
	$param_arr = json_decode($param, true);
	$msg_array = array();
	$resourcesFromXml = self::loadToolTipMessagefromXML();
	foreach ($resourcesFromXml as $msg) {
	  if(($msg->getAttribute('uicomponent') == 'confirm' || $msg->getAttribute('uicomponent') == 'alert') &&
	      $msg->getAttribute('targetcontroller') == $param_arr['targetcontroller'] &&
		  $msg->getAttribute('targetaction') == $param_arr['targetaction'] &&
		  in_array($msg->getAttribute('operation'), $param_arr['operation'])
		  )
	  {
		array_push($msg_array, array('confirmmsg' => array('value' => $msg->getAttribute('value'), 'operation' => $msg->getAttribute('operation'))));
	  }
	}
	  
	return $msg_array;
  }
  
  /**
  * Fetches configurations for Prompt boxes
  * Accepts a json of the form:
  * {targetcontroller: the_controller, targetaction: the_action, operation: the_operation}
  */
  public static function getPromptBoxMsg($param, $appname)
  {
	PopUpHelper::$appname = $appname;
	$param_arr = json_decode($param, true);
	$msg_array = array();
	$resourcesFromXml = self::loadToolTipMessagefromXML();
	foreach ($resourcesFromXml as $msg) {
	  if($msg->getAttribute('uicomponent') == 'prompt' &&
	      $msg->getAttribute('targetcontroller') == $param_arr['targetcontroller'] &&
		  $msg->getAttribute('targetaction') == $param_arr['targetaction'] &&
		  in_array($msg->getAttribute('operation'), $param_arr['operation'])
		  )
	  {
		array_push($msg_array, array('promptmsg' => array('value' => $msg->getAttribute('value'), 'operation' => $msg->getAttribute('operation'))));
	  }
	}
	  
	return $msg_array;
  }
  

  public static function loadToolTipMessagefromXML() {
    $xml = new \DOMDocument;
    $filename = __ROOT__ . \Library\Enums\ApplicationFolderName::AppsFolderName . PopUpHelper::$appname . '/Resources/Common/tooltipandpopupstrings.en.xml';
    if (file_exists($filename)) {
      $xml->load($filename);
    } else {
      throw new \Exception("In " . __CLASS__ . " > Method: " . __METHOD__);
    }
    return $xml->getElementsByTagName("resource");
  }

}
