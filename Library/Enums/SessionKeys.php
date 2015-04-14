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
  const UserRole = "user_role";
  const UserTypeId = "user_value";
  const UserType = "user_type";
  const UserRoutes = "user_routes";
  const AllUsers = "all_users";

  const UserProjects= "user_projects";
  const UserProjectFacilityList= "user_project_facility_list";
  const UserProjectClientList= "user_project_client_list";
  const UserLocations= "user_locations";
  const UserTechnicianList = "user_technician_list"; 
  const UserTaskList = "user_task_list"; 
  
  const CommonFieldAnalytes = "common_fa";
  const CommonLabAnalytes = "common_la";
  
  /* Project manager session array */
  const SessionPms = "session_pms";  
  const PmKey =  "pm_";
  const PmObject = "pm_obj";
  const PmProjectIds = "pm_project_ids";
  const PmTechnicians = "pm_technicians";
  const PmServices = "pm_services";
  const PmFieldAnalytes = "pm_fa";
  const PmLabAnalytes = "pm_la";

  /* Project Session array */
  const UserSessionProjects = "user_session_projects";
  const ProjectKey =  "project_";
  const ProjectObject = "project_obj";
  const FacilityObject = "facility_obj";
  const ClientObject = "client_obj";
  const ProjectTasks = "project_tasks";
  const ProjectServices = "project_services";
  const ProjectLocations = "project_locations";
  const ProjectFieldAnalytes = "pfa";
  const ProjectLabAnalytes = "pla";
  const ProjectForms = "project_forms";
  const ProjectUserForms = "project_user_forms";
  const ProjectMasterForms = "project_master_forms";
  const ProjectAvailableForms = "project_available_forms";

  /* Task session array */
  const SessionTasks = "session_tasks";
  const TaskKey =  "task_";
  const TaskObj = "task_info_obj";
  const TaskCocInfoObj = "task_coc_info_obj";
  const TaskLocations = "task_locations";
  const TaskTechnicians = "task_technicians";
  const TaskServices = "task_services";
  const TaskFieldSampleMatrix = "task_field_sample_matrix";
  const TaskLabSampleMatrix = "task_lab_sample_matrix";
  const TaskFieldAnalytes = "task_field_analytes";
  const TaskLabAnalytes = "task_lab_analytes";
  const TaskFieldLocations = "task_field_locations";
  const TaskLabLocations = "task_lab_locations";
  const TaskForms = "task_forms";



  const UserProjectTaskList= "user_project_task_list";
  
  const UserServiceList = "user_service_list";
  
  const FieldForms = "field_forms";
  const UserFieldFormsList = "user_field_forms_list";

  
  //Current objects
  const CurrentProject = "current_project";
  const CurrentPm = "current_pm";
  const CurrentTask = "current_task";
  const CurrentDiscussion = "current_discussion";
  
  //Routing
  const SessionRoutes = "app_routes";
  const SessionRoutesXmlLastModified = "app_routes_last_modified";
  
  //Tabs
  const TabsStatus = "tabs_status";
  const ActiveTaskTabsStatus = "active_task_tabs_status";
  const TabActiveAnalyte = "tab_active_analyte";
}

?>
