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
  * attribute passed through the JSON var param.
  */
  public static function getTooltipMsgForAttribute($param, $appname) {
	
	PopUpHelper::$appname = $appname;
	$param_arr = json_decode($param, true);
	$msg_array = array();
	$resourcesFromXml = self::loadToolTipMessagefromXML();
	foreach ($resourcesFromXml as $msg) {
	  if($msg->getAttribute('uicomponent') == 'tooltip' &&
	      $msg->getAttribute('targetcontroller') == $param_arr['targetcontroller'] &&
		  $msg->getAttribute('targetaction') == $param_arr['targetaction'] &&
		  in_array($msg->getAttribute('targetattr'), $param_arr['targetattr'])
		  )
	  {
		//Check if delay exists as an attribute
		$delayshow = $msg->getAttribute('delayshow');
		if($delayshow != '') {
			//Check if delayhide exists in xml, then use it, else use 0 by default
			$delayhide = ($msg->getAttribute('delayhide') != '') ? $msg->getAttribute('delayhide') : 0;
			
			$msgconfig_arr = array('value' => $msg->getAttribute('value'), 'targetattr' => $msg->getAttribute('targetattr'), 'placement' => $msg->getAttribute('placement'), 'delayshow' => $delayshow, 'delayhide' => $delayhide);
		}
		else {
			$msgconfig_arr = array('value' => $msg->getAttribute('value'), 'targetattr' => $msg->getAttribute('targetattr'), 'placement' => $msg->getAttribute('placement'));
		}
		array_push($msg_array, array('tooltipmsg' => $msgconfig_arr));
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
