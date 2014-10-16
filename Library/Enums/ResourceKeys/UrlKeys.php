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
  
  const ResourceRootUrl = "resource";
  const ResourceListAll = "resource/listAll";
  const ResourceShowForm = "resource/showForm";

  /**
   * Absolute Urls
   */
  /*
   * Template
   */
  const TemplateUrl = "tmp";

}

?>
