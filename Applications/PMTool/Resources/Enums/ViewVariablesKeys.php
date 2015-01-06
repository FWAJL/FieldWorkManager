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
  
  //Location's views variables
  
  //Task's view variabless
  const task_tab_open = "task_tab_open";
  const task_tab_close = "task_tab_close";


  const all_projects = "all_projects";
  const current_project = "current_project";
  const current_project_locations = "current_project_locations";
  const task_locations = "task_locations";
  const show_one = "show_one";
}

?>
