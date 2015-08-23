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
  
  const active_task_status = "active_task_status";
  const active_task_forms = "active_task_forms";
  const active_task_comm = "active_task_comm";
  const active_task_notes = "active_task_notes";
  const active_task_make_notes = "active_task_make_notes";
  const active_task_show_notes = "active_task_show_notes";
  
  const active_task_progress_details = "active_task_progress_details";
  const active_task_instructions = "active_task_instructions";
  const active_task_field_data = "active_task_field_data";
  const active_task_checklist = "active_task_checklist";


  const task_buttons = "task_buttons";
  const active_task_buttons = "active_task_buttons";
  const promote_buttons_module = "promote_buttons_module";


  const popup_msg_module = "popup_msg_module";
  const tooltip_msg_module = "tooltip_msg_module";
  const popup_prompt_module = "popup_prompt_module";

  const upload_file = "upload_file";
  //Data keys

  
}

?>
