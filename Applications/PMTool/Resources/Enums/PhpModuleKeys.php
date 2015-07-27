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
  const popup_msg = "popup_msg_module";
  const popup_prompt = "popup_prompt_module";
  const promote_buttons = "promote_buttons_module";
  const popup_selector_module = "popup_selector_module";
  const popup_maplegends_module = "popup_maplegends_module";
  const popup_maplegend_projects_module = "popup_maplegend_projects_module";
  const popup_maplegend_project_module = "popup_maplegend_project_module";
  const popup_maplegend_proj_locations_module = "popup_maplegend_proj_locations_module";
  const popup_maplegend_task_locations_module = "popup_maplegend_task_locations_module";
  const popup_maplegend_active_task = "popup_maplegend_active_task";
  const tooltip_msg = "tooltip_msg_module";
  
  const group_list_left = "group_list_left";
  const group_list_right = "group_list_right";
	
  const project_tabs_open = "project_tabs_open";
  const project_form = "project_form";
  const facility_form = "facility_form";
  const client_form = "client_form";
  const project_buttons = "project_buttons";

  const tabs_close = "tabs_close";
  
  const task_tabs_open = "task_tabs_open";
  const task_form = "task_form";
  const task_instr_checklist = "task_instr_checklist";
  const task_buttons = "task_buttons";
  
  const analyte_tabs_open = "analyte_tabs_open";
  const field_analyte_list = "field_analyte_list";
  const lab_analyte_list = "lab_analyte_list";
  const field_analyte_form = "field_analyte_form";
  const lab_analyte_form = "lab_analyte_form";
  const analyte_buttons = "analyte_buttons";

  const upload_file = "upload_file";
  const load_file = "load_file";

  const notes = "notes";
  const checklist = "checklist";
}

?>
