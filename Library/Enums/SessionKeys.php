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
 * SessionKeys Class
 *
 * @package		Library
 * @subpackage	Enums
 * @category	SessionKeys
 * @author		FWM DEV Team
 * @link		
 */
namespace Library\Enums;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

/**
 * Lists all the SessionKeys used throughout the applications so that we don't use hard-coded strings.
 */
abstract class SessionKeys {
  const AllUsers = "all_users";  
    
  const UserAuthenticated = "user_auth";
  const UserFlash = 'user_flash';
  const UserConnected = "user_connected";
  
  const UserProjects= "user_projects";
  const UserProjectFacilityList= "user_project_facility_list";
  const UserProjectClientList= "user_project_client_list";
  const UserSessionProjects = "user_session_projects";
  
  const UserLocations= "user_locations";
  const ProjectLocations = "project_locations";
  
  const UserTechnicians = "user_technicians";
  const UserTechnicianList = "user_technician_list"; 
 
  const ProjectKey =  "project_";
  const ProjectObject = "project_obj";
  const FacilityObject = "facility_obj";
  const ClientObject = "client_obj";
  
  const ProjectTasks = "project_tasks";
  const UserProjectTaskList= "user_project_task_list";
  
  const UserResources = "user_resources";
  const UserResourceList = "user_resource_list";
  
  const FieldForms = "field_forms";
   const UserFieldFormsList = "user_field_forms_list";

  
  //Current objects
  const CurrentProject = "current_project";

  const SessionRoutes = "app_routes";
  const SessionRoutesXmlLastModified = "app_routes_last_modified";
}

?>
