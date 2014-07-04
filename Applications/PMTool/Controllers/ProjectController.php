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

    $resourceFileKey = "project";

    $this->app->pageTitle = $this->app->i8n->getLocalResource($resourceFileKey, "page_title");
    $this->page->addVar('resx', $this->app->i8n->getLocalResourceArray($resourceFileKey));
    $this->page->addVar('logout_url', "logout");
    
    //Load Modules for view
    $this->page->addVar('form_modules', $this->app()->router()->selectedRoute()->phpModules());
    $this->page->addVar('project_list_modules', array());//$this->app()->router()->selectedRoute()->phpModules());
  }

  /**
   * Method that loads the add view for controller.
   * 
   * It loads the page title, the logout_url and the resources to load in the placeholders for the three forms (project, facility, company)
   * 
   * @param \Library\HttpRequest $rq
   */
  public function executeAdd(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->ManageResponseWS();
    //Process data received from Post
    $data_sent = $rq->retrievePostAjaxData(NULL, FALSE);

    //Init PDO
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $data_sent["pm_id"] = $pm === NULL ? NULL : $pm[0]->pm_id();
    $project = $this->PrepareUserObject($data_sent);
    $result["data"] = $project;
    /* Add to DB */
    //Load interface to query the database
    $manager = $this->managers->getManagerOf('Project');
    $result_insert = $manager->add($project);

    //Process DB result and send result
    if ($result_insert) $result = $this->ManageResponseWS(array("resx_file" => "project", "resx_key" => "_insert", "step" => "success"));
    //return the JSON data
    echo \Library\HttpResponse::encodeJson($result);
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

  /**
   * Prepare the Project Object before calling the DB.
   * 
   * @param array $data_sent from POST request
   * @return \Library\BO\Project_manager
   */
  private function PrepareUserObject($data_sent) {
    $project = new \Library\BO\Project();
    $project->setPm_id($data_sent["pm_id"]);
    $project->setProject_name(!array_key_exists('project_name', $data_sent) ? NULL : $data_sent["project_name"]);
    $project->setProject_number(!array_key_exists('project_num', $data_sent) ? NULL : $data_sent["project_num"]);
    $project->setProject_desc(!array_key_exists('project_desc', $data_sent) ? NULL : $data_sent["project_desc"]);
    $project->setActive(!array_key_exists('project_active_flag', $data_sent) ? NULL : $data_sent["project_active_flag"]);
    $project->setVisible(!array_key_exists('project_visible_flag', $data_sent) ? NULL : $data_sent["project_visible_flag"]);

    return $project;
  }

}