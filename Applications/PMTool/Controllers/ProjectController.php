<?php

/**
 *
 * @package		Basic MVC framework test
 * @author		FWM DEV Team
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
 * @author		FWM DEV Team
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
    $this->page->addVar('logout_url', __BASEURL__ . "logout");

    //Get list of projects and store in session
    $this->_GetAndStoreProjectsInSession($this, $rq);
  }
  /**
   * Method that loads the add or edit view for controller
   * 
   * @param \Library\HttpRequest $rq: the request
   */
  public function executeShowForm(\Library\HttpRequest $rq) {
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->page->addVar('pm', $pm[0]);

    $resourceFileKey = "project";

    $this->app->pageTitle = $this->app->i8n->getLocalResource($resourceFileKey, "page_title");
    $this->page->addVar('resx', $this->app->i8n->getLocalResourceArray($resourceFileKey));
    $this->page->addVar('logout_url', __BASEURL__ . "logout");

    //Load Modules for view
    $this->page->addVar('form_modules', $this->app()->router()->selectedRoute()->phpModules());
  }
  /**
   * Method that loads the list all project view for controller
   * 
   * @param \Library\HttpRequest $rq: the request
   */
  public function executeListAll(\Library\HttpRequest $rq) {
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->page->addVar('pm', $pm[0]);

    $resourceFileKey = "project";

    $this->app->pageTitle = $this->app->i8n->getLocalResource($resourceFileKey, "page_title");
    $this->page->addVar('resx', $this->app->i8n->getLocalResourceArray($resourceFileKey));
    $this->page->addVar('logout_url', __BASEURL__ . "logout");

    //Get list of projects stored in session
    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserProjects)) {
      $this->page->addVar('projects', $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserProjects));
    } else {
      $lists = $ctrl->executeGetList($rq, TRUE);
      $this->app()->user->setAttribute(\Library\Enums\SessionKeys::UserProjects, $lists["projects"]);
      $this->page->addVar('projects', $lists["projects"]);
    }
  }

  /**
   * Method that adds a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
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
    $result["dataIn"] = $project;
    /* Add to DB */
    //Load interface to query the database
    $manager = $this->managers->getManagerOf('Project');
    $result["dataOut"] = $manager->add($project);


    //Clear the project and facility list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjects);
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjectFacilityList);

    //Process DB result and send result
    if (intval($result["dataOut"]) > 0)
      $result = $this->UpdateResponseWS($result, array("resx_file" => "project", "resx_key" => "_insert", "step" => "success"));
    //return the JSON data
    echo \Library\HttpResponse::encodeJson($result);
  }

  /**
   * Method that edits a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeEdit(\Library\HttpRequest $rq) {
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
    $result_insert = $manager->edit($project);

    //Clear the project and facility list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjects);
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjectFacilityList);

    //Process DB result and send result
    if ($result_insert)
      $result = $this->ManageResponseWS(array("resx_file" => "project", "resx_key" => "_edit", "step" => "success"));
    //return the JSON data
    echo \Library\HttpResponse::encodeJson($result);
  }

  /**
   * Method that delete a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->ManageResponseWS();

    $data_sent = $rq->retrievePostAjaxData(NULL, FALSE);

    //Load interface to query the database
    $manager = $this->managers->getManagerOf('Project');
    $result_insert = $manager->delete($data_sent["project_id"]);

    //Clear the project and facility list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjects);
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjectFacilityList);

    $result = $this->ManageResponseWS(array("resx_file" => "project", "resx_key" => "_delete", "step" => "success"));
    //return the JSON data
    echo \Library\HttpResponse::encodeJson($result);
  }

  /**
   * Method that adds a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeGetList(\Library\HttpRequest $rq, $isNotWs = FALSE) {
    // Init result
    $result = $this->ManageResponseWS();

    //Init PDO
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $data_sent["pm_id"] = $pm === NULL ? NULL : $pm[0]->pm_id();
    $project = $this->PrepareUserObject($data_sent);
    $result["data"] = $project;
    /* Get list from DB */
    //Load interface to query the database for projects
    $manager = $this->managers->getManagerOf('Project');
    $list['projects'] = $manager->selectMany($project);

    //Load interface to query the database for facilities
    $manager = $this->managers->getManagerOf('Facility');
    $list['facilities'] = $manager->selectMany($project);

    //Process DB result and send result
    $result = $this->ManageResponseWS(array("resx_file" => "project", "resx_key" => "_getlist", "step" => "success"));
    $result["lists"] = $list;
    //return the JSON data
    if ($isNotWs) {
      return $list;
    } else {
      echo \Library\HttpResponse::encodeJson($result);
    }
  }

  /**
   * Method that adds a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeGetItem(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->ManageResponseWS();

    $data_sent = $rq->retrievePostAjaxData(NULL, FALSE);

    $project_selected = $this->_GetProjectFromSession($data_sent);

    $facility = array();
    $facility_selected = $this->_GetFacilityProjectFromSession($data_sent);

    $result = $this->ManageResponseWS(array("resx_file" => "project", "resx_key" => "_getItem", "step" => "success"));
    $result["project"] = $project_selected;
    $result["facility"] = $facility_selected;
    //return the JSON data
    return $result;
  }

  private function _GetProjectFromSession($data_sent) {
    $projects = array();
    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserProjects)) {
      $projects = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserProjects);
    } else {
      return NULL;
    }

    foreach ($projects as $project) {
      if ($project->project_id() === $data_sent["project_id"]) {
        return $project;
      }
    }
    return NULL;
  }

  private function _GetFacilityProjectFromSession($data_sent) {
    $facilities = array();
    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserProjectFacilityList)) {
      $facilities = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserProjectFacilityList);
    } else {
      return NULL;
    }

    foreach ($facilities as $facility) {
      if ($facility->project_id() === $data_sent["project_id"]) {
        return $facility;
      }
    }
    return NULL;
  }

  /**
   * Check if the current pm has projects to decide where to send him: stay on the project list or asking him to add a project
   * 
   * @param \Library\BO\Project_manager $pm
   * @return boolean
   */
  private function _CheckIfPmHasProjects(\Library\BO\Project_manager $pm) {
    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserProjects)) {
      $projects = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserProjects);
      return count($projects) > 0 ? TRUE : FALSE;
    }
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
    $project->setProject_id(!array_key_exists('project_id', $data_sent) ? NULL : $data_sent["project_id"]);
    $project->setProject_name(!array_key_exists('project_name', $data_sent) ? NULL : $data_sent["project_name"]);
    $project->setProject_number(!array_key_exists('project_num', $data_sent) ? "" : $data_sent["project_num"]);
    $project->setProject_desc(!array_key_exists('project_desc', $data_sent) ? "" : $data_sent["project_desc"]);
    $project->setActive(!array_key_exists('project_active_flag', $data_sent) ? 0 : $data_sent["project_active_flag"]);
    $project->setVisible(!array_key_exists('project_visible_flag', $data_sent) ? 0 : $data_sent["project_visible_flag"]);

    return $project;
  }

  private function _ShowOrHideSectionsOnPage($currentController, $pm) {
    //e.g. decide whether to see the "Add project" or the "View all projects"
    if ($this->_CheckIfPmHasProjects($pm[0])) {
      $currentController->page->addVar('display_project_welcome', 'show');
      $currentController->page->addVar('display_add_project', 'hide');
      $currentController->page->addVar('display_project_list', 'hide');
      $currentController->page->addVar('active_project_list', 'active');
      $currentController->page->addVar('active_add_project', '');
    } else {
      $currentController->page->addVar('display_project_welcome', 'hide');
      $currentController->page->addVar('display_add_project', 'show');
      $currentController->page->addVar('active_project_list', '');
      $currentController->page->addVar('active_add_project', 'active');
    }
  }

  private function _GetAndStoreProjectsInSession($ctrl, $rq) {
    if (!$ctrl->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserProjects) &&
            !$ctrl->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserProjectFacilityList)) {
      $lists = $ctrl->executeGetList($rq, TRUE);
      $ctrl->app()->user->setAttribute(\Library\Enums\SessionKeys::UserProjects, $lists["projects"]);
      $ctrl->app()->user->setAttribute(\Library\Enums\SessionKeys::UserProjectFacilityList, $lists["facilities"]);
    }
  }

}
