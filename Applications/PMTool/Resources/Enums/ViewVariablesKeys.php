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
 * ViewVariablesKeys Class
 *
 * @package		Applications/PMTool
 * @subpackage	Resources/Enum
 * @category	ViewVariablesKeys
 * @author		FWM DEV Team
 * @link		
 */


namespace Applications\PMTool\Resources\Enums;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class ViewVariablesKeys {
  //Generic keys
  const data = "data";
  const data_left = "data_left";
  const data_right = "data_right";
  const module = "module";
  const objects = "objects";
  const objects_list_left = "objects_list_left";
  const objects_list_right = "objects_list_right";
  const categorized_list = "categorized_list";
  const categorized_list_left = "categorized_list_left";
  const categorized_list_right = "categorized_list_right";
  const properties = "properties";
  const properties_right = "properties_right";
  const properties_left = "properties_left";
  const property_key = "prop_";
  const property_id = "prop_id";
  const property_name = "prop_name";
  const property_active = "prop_active";

  const tabStatus = "tab";

  const active_list = "active_list_module";
  const inactive_list = "inactive_list_module";
  
  const currentPm = "current_pm";
  const currentProject = "current_project";
  const currentTask = "current_task";
  
  //Project's views variables
  const form_modules = "form_modules"; 
  const projects = "projects";
  const redirect_on_success = "redirect_on_success";
  
  //Location's views variables
  
  //Task's view variabless
  const task_tab_open = "task_tab_open";
  const tab_close = "tab_close";
  
  //Mapping
  const map_module = "map_module";
  const map_info_module = "map_info_module";
}
