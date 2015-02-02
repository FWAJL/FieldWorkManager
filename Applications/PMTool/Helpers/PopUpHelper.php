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
  * attribute. This included tooltip and confirm box
  * messages
  */
  public static function getTooltipMsgForAttribute($attrib, $appname) {
    PopUpHelper::$appname = $appname;
    $tooltipMessages = self::loadToolTipMessagefromXML();
    $msg_array = array();
    foreach ($tooltipMessages as $msg) {
      if ($msg->getAttribute('targetattr') == $attrib && $msg->getAttribute('uicomponent') == 'tooltip') {
		array_push($msg_array, array('tooltip' => array('value' => $msg->getAttribute('value'), 'placement' => $msg->getAttribute('placement'))));
      }
	  elseif ($msg->getAttribute('targetattr') == $attrib && $msg->getAttribute('uicomponent') == 'confirmdelete')
	  {
		array_push($msg_array, array('confirmdelete' => array('value' => $msg->getAttribute('value'))));
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
