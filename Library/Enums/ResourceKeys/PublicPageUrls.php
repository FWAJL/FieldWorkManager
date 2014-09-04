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
 * PublicPageUrls Class
 *
 * @package		Library
 * @subpackage	Enums/ResourceKeys
 * @category	PublicPageUrls
 * @author		FWM DEV Team
 * @link		
 */

namespace Library\Enums\ResourceKeys;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class PublicPageUrls {
  /**
   * Relative Urls
   */
  
  const LoginUrl = "login";
  const LogoutUrl = "logout";
  const AuthenticationUrl = "auth";

  const ProjectsRootUrl = "project";
  const ProjectsListAll = "project/listAll";
  const ProjectsShowForm = "project/showForm";
  /**
   * Absolute Urls
   */
  /*
   * Template
   */
  const TemplateUrl = "tmp";

}

?>
