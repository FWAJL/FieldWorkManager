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

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class PublicPageUrls {
  /**
   * Relative Urls
   */

  const LoginUrl = "loginUrl";
  const LogoutUrl = "logoutUrl";
  const ProjectsUrl = "projectsUrl";
  const AuthenticationUrl = "authUrl";

  /**
   * Absolute Urls
   */
  /*
   * Template
   */
  const TemplateUrl = "tmp";

}

?>
