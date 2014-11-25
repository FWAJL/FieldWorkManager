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
  
  const LocationRootUrl = "location";
  const LocationListAll = "location/listAll";
  const LocationShowForm = "location/showForm";
  
  const TechnicianRootUrl = "technician";
  const TechnicianListAll = "technician/listAll";
  const TechnicianShowForm = "technician/showForm";
  
  const ServiceRootUrl = "service";
  const ServiceListAll = "service/listAll";
  const ServiceShowForm = "service/showForm";
  
  const TaskRootUrl = "task";
  const TaskListAll = "task/listAll";
  const TaskShowForm = "task/showForm";

  
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
