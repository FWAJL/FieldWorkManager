<?php
/**
 *
 * @package		Basic MVC framework
 * @author		FWM DEV Team
 * @copyright	Copyright (c) 2014
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * ResxFileNameKeys Class
 *
 * @package		Library
 * @subpackage	Enums/ResourceKeys
 * @category	ResxFileNameKeys
 * @author		FWM DEV Team
 * @link		
 */

namespace Library\Enums\ResourceKeys;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

abstract class ResxFileNameKeys {
  /**
   * Local file names
   */
  const Project = "project";
  const Facility = "facility";
  const Location = "location";
  
  /**
   * Common file names
   */
  const Breadcrumb = "breadcrumb";
  const MenuLeft = "menu_left";
  const WsDefaults = "ws_defaults";
}

?>
