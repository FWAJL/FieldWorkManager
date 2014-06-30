<?php

/**
 *
 * @package		Basic MVC framework
 * @author		FWA DEV Team
 * @copyright	Copyright (c) 2014
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Authenticate controller Class
 *
 * @package		Application/PMTool
 * @subpackage	Controllers
 * @category	ProjectController
 * @author		FWA Dev Team
 * @link		
 */

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ProjectController extends \Library\BaseController {

  /**
   * Method that loads the main view for controller, being the list of project here?
   * 
   * It loads the page title, the logout_url and the resources to load in the placeholders
   * 
   * @param \Library\HttpRequest $rq: the request
   */
  public function executeIndex(\Library\HttpRequest $rq) {
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->page->addVar('pm', $pm[0]);

    if (!$this->_CheckIfPmHasProjects($pm[0])) {//if no project active, redirect to add project page
      header('Location: ' . __BASEURL__ . "project/add");
    } else {
      $resourceFileKey = "project";

      $this->app->pageTitle = $this->app->i8n->getLocalResource($resourceFileKey, "page_title");
      $this->page->addVar('resx', $this->app->i8n->getLocalResourceArray($resourceFileKey));
      $this->page->addVar('logout_url', "logout");
    }
  }

  /**
   * Method that loads the add view for controller.
   * 
   * It loads the page title, the logout_url and the resources to load in the placeholders for the three forms (project, facility, company)
   * 
   * @param \Library\HttpRequest $rq
   */
  public function executeAdd(\Library\HttpRequest $rq) {
    $resourceFileKey = "project";
    $this->app->pageTitle = $this->app->i8n->getLocalResource($resourceFileKey, "page_title");
    $this->page->addVar('resx', $this->app->i8n->getLocalResourceArray($resourceFileKey));
    $this->page->addVar('logout_url', "logout");

    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->page->addVar('pm', $pm[0]);
  }

  /**
   * Check if the current pm has projects to decide where to send him: stay on the project list or asking him to add a project
   * 
   * @param \Library\BO\Project_manager $pm
   * @return boolean
   */
  private function _CheckIfPmHasProjects(\Library\BO\Project_manager $pm) {
    $manager = $this->managers->getManagerOf('Project');
    $count = $manager->countById($pm->pm_id());
    return $count > 0 ? TRUE : FALSE;
  }

}