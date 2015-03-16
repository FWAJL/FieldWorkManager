<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UrlKeys
 *
 * @author jl
 */

namespace Library\Enums\ResourceKeys;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class UrlKeys {
  /**
   * Relative Urls
   */
  
  const LoginUrl = "login";
  const LogoutUrl = "logout";
  const AuthenticationUrl = "auth";

  const ProjectsRootUrl = "project";
  const ProjectsListAll = "project/listAll";
  const ProjectsShowForm = "project/showForm";
  const ProjectsSelectProject = "project/selectProject";
  
  const LocationRootUrl = "location";
  const LocationListAll = "location/listAll";
  const LocationShowForm = "location/showForm";
  const LocationUploadList = "location/uploadList";
  
  const MapCurrentProject = "map/currentProject";
  const MapCurrentProjectLocations = "map/currentProjectLocations";
  const MapTaskLocations = "map/taskLocations";
  
  const TechnicianRootUrl = "technician";
  const TechnicianListAll = "technician/listAll";
  const TechnicianShowForm = "technician/showForm";
  
  const ServiceRootUrl = "service";
  const ServiceListAll = "service/listAll";
  const ServiceShowForm = "service/showForm";
  
  const AnalyteListAll = "analyte/listAll";
  const FieldAnalytes = "field_analyte/listAll";
  const FieldSampleMatrix = "field_analyte/sampleMatrix";
  const LabAnalytes = "lab_analyte/listAll";
  const LabSampleMatrix = "lab_analyte/sampleMatrix";

  const TaskRootUrl = "task";
  const TaskListAll = "task/listAll";
  const TaskShowForm = "task/showForm";
  const TaskAddPrompt = "task/addPrompt";
  const TaskTechnicians = "task/technicians";
  const TaskLocations = "task/locations";
  const TaskServices = "task/services";
  const TaskFieldAnalytes = "task/fieldAnalytes";
  const TaskInspForms = "task/inspForms";
  const TaskCOC = "task/coc";
  const TaskSelectTask = "task/selectTask";
  
  const ActiveTaskShowForm = "activetask/showForm";
  const ActiveTaskMap = "xxx";
  const ActiveTaskInspForms = "xxx";
  const ActiveTaskComm = "xxx";
  
  const CompleteTaskMap = "xxx";
  const CompleteTaskInspForms = "xxx";
  const CompleteTaskServices = "xxx";
  
  const PmRootUrl = "pm";
  const PmListAll = "pm/listAll";
  const PmShowForm = "pm/showForm";

  /**
   * Absolute Urls
   */
  /*
   * Template
   */
  const TemplateUrl = "tmp";

}

?>
