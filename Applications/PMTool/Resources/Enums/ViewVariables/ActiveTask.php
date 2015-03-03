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
 * Task Class
 *
 * @package		Applications/PMTool
 * @subpackage	Resources/Enum
 * @category	Task
 * @author		FWM DEV Team
 * @link		
 */


namespace Applications\PMTool\Resources\Enums\ViewVariables;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class ActiveTask {
  //Modules keys
  const task_tabs_open = "task_tabs_open";
  const active_task_tabs_open = "active_task_tabs_open";

  const tabs_close = "tabs_close";
  
  const task_info_lists = "task_info_lists";
  const active_task_info_lists = "active_task_info_lists";

  const task_form = "task_form";
  const active_task_form = "active_task_form"; 

  const task_buttons = "task_buttons";
  const promote_buttons_module = "promote_buttons_module";


  const popup_msg_module = "popup_msg_module";

  const popup_prompt_module = "popup_prompt_module";
  //Data keys

  
}

?>
