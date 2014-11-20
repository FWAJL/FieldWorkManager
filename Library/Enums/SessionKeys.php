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
  const SessionPms = "session_pms";  
    
  const UserAuthenticated = "user_auth";
  const UserFlash = 'user_flash';
  const UserConnected = "user_connected";
  
  const UserProjects= "user_projects";
  const UserProjectFacilityList= "user_project_facility_list";
  const UserProjectClientList= "user_project_client_list";
  const UserSessionProjects = "user_session_projects";
  
  const UserLocations= "user_locations";
  
  const UserTechnicianList = "user_technician_list"; 
 
  /* Project manager session array */
  const PmKey =  "pm_";
  const PmObject = "pm_obj";
  const PmProjectIds = "pm_project_ids";
  const PmTechnicians = "pm_technicians";
  const PmServices = "pm_services";
  const PmFieldAnalytes = "pm_fa";
  const PmLabAnalytes = "pm_la";

  /* Project Session array */
  const ProjectKey =  "project_";
  const ProjectObject = "project_obj";
  const FacilityObject = "facility_obj";
  const ClientObject = "client_obj";
  const ProjectTasks = "project_tasks";
  const ProjectLocations = "project_locations";
  
  /* Task session array */
  const TaskKey =  "task_";
  const TaskInfo = "task_info_obj";
  const TaskCocInfo = "task_coc_info_obj";
  const TaskLocationIds = "task_loc_ids";
  const TaskTechnicianIds = "task_tech_ids";
  const TaskServiceIds = "task_service_ids";
  const TaskLeadTechIds = "task_lead_tech_ids";
  const TaskFieldSampleMatrix = "task_fsm";
  const TaskLabSampleMatrix = "task_lsm";
  const TaskFieldAnalyteIds = "task_fa_ids";
  const TaskLabAnalyteIds = "task_la_ids";
  const TaskFieldLocationIds = "task_fdl_ids";
  const TaskLabLocationIds = "task_ldl_ids";



  const UserProjectTaskList= "user_project_task_list";
  
  const UserServiceList = "user_service_list";
  
  const FieldForms = "field_forms";
  const UserFieldFormsList = "user_field_forms_list";

  
  //Current objects
  const CurrentProject = "current_project";
  const CurrentPm = "current_pm";
  const SessionRoutes = "app_routes";
  const SessionRoutesXmlLastModified = "app_routes_last_modified";
}

?>
