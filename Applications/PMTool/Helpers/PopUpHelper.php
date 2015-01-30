<?php

/**
 *
 * @package		Basic MVC framework
 * @author		Souvik Ghosh
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
 * @category	ProjectHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class PopUpHelper {

  public static $appname;	
	
  public static function getTooltipMsgForAttribute($attrib, $appname)
  {
    PopUpHelper::$appname = $appname;
	$tooltipMessages = PopUpHelper::loadToolTipMessagefromXML();
	foreach ($tooltipMessages as $msg) {
	  if($msg->getAttribute('targetattr') == $attrib)
	    $tooltip_array = array('value' => $msg->getAttribute('value'), 'placement' => $msg->getAttribute('placement'));
	}
	
	return $tooltip_array;
  }
  
  public static function loadToolTipMessagefromXML()
  {
    $xml = new \DOMDocument;
    $filename = __ROOT__ . \Library\Enums\FolderName::AppsFolderName . PopUpHelper::$appname . '/Resources/Common/tooltipandpopupstrings.en.xml';
    if (file_exists($filename)) {
      $xml->load($filename);
    } else {
      throw new Exception("In " . __CLASS__ . " > Method: " . __METHOD__);
    }
    return $xml->getElementsByTagName("resource");  
  }
}