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
  const UserAuthenticated = "user_auth";
  const UserFlash = 'user_flash';
  const UserConnected = "user_connected";
  const UserProjects= "user_projects";
  const UserProjectFacilityList= "user_project_facility_list";
  const UserLocations= "user_locations";
  
  const UserSessionProjects = "user_session_projects";
  const ProjectKey =  "project_";
  const ProjectObject = "project_obj";
  const ProjectLocations = "project_locations";
  const ProjectTasks = "project_tasks";
  const ProjectTechnicians = "project_technicians";
  
  //Current objects
  const CurrentProject = "current_project";

  const SessionRoutes = "app_routes";
  const SessionRoutesXmlLastModified = "app_routes_last_modified";
}

?>
