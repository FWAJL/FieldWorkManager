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

class Task {
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
  
  const task_cocinfo_lists = "task_cocinfo_lists";
  const task_coc_info_form = "task_coc_info_form";


  const popup_msg_module = "popup_msg_module";

  const popup_prompt_module = "popup_prompt_module";
  //module for analyte matrix
  const task_analyte_matrix_switch = "task_analyte_matrix_switch";
  //view variable for analyte matrix
  const task_show_lab_matrix = "task_show_lab_matrix";
  const task_show_field_matrix = "task_show_field_matrix";
  //Data keys

  
}

?>
