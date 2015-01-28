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
 * ResxFileNameKeys Class.
 * These are used for the framework level resources (found Application/Your_App/Resources/Common)
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
   * Common file names
   */
  const Breadcrumb = "breadcrumb";
  const MenuLeft = "menu_left";
  const WsDefaults = "ws_defaults";
  const Config = "config";
  const FileUpload = "fileupload";
}

?>
