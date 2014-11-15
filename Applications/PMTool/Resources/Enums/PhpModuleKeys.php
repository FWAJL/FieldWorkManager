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
 * PhpModuleKeys Class
 *
 * @package		Applications/PMTool
 * @subpackage	Resources/Enum
 * @category	PhpModuleKeys
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Resources\Enums;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class PhpModuleKeys {

  const active_list = "active_list_module";
  const inactive_list = "inactive_list_module";
  
  const project_tabs_open = "project_tabs_open";
  const project_form = "project_form";
  const facility_form = "facility_form";
  const client_form = "client_form";
  const project_buttons = "project_buttons";
  const project_tabs_close = "project_tabs_close";
  
  const task_tabs_open = "task_tabs_open";
  const task_form = "task_form";
  const task_buttons = "task_buttons";
  const task_tabs_close = "task_tabs_close";

}

?>
