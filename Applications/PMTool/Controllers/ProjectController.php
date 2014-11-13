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
    //Get list of projects and store in session
    $this->_GetAndStoreProjectsInSession($rq);

    if (count(\Applications\PMTool\Helpers\CommonHelper::GetSessionProjects($this->app()->user())) > 0) {
      header('Location: ' . __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ProjectsListAll);
    } else {
      header('Location: ' . __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ProjectsShowForm . "?mode=add&test=true");
    }
  }

  /**
   * Method that loads the add or edit view for controller
   * 
   * @param \Library\HttpRequest $rq: the request
   */
  public function executeShowForm(\Library\HttpRequest $rq) {
    $this->page->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  /**
   * Method that loads the list all project view for controller
   * 
   * @param \Library\HttpRequest $rq: the request
   */
  public function executeListAll(\Library\HttpRequest $rq) {
    //Get list of projects stored in session
    $this->_GetAndStoreProjectsInSession($rq);
    $data = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => $this->resxfile,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => \Applications\PMTool\Helpers\CommonHelper::GetListObjectsInSessionByKey($this->app()->user(), \Library\Enums\SessionKeys::ProjectObject),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList($this->resxfile)
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $this->page->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::active_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::active_list]);
    $this->page->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::inactive_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::inactive_list]);
  }

  /**
   * Method that adds a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeAdd(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[0]->pm_id();
    $project = $this->PrepareUserObject($this->dataPost());
    $result["dataIn"] = $project;

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module);
    $result["dataId"] = $manager->add($project);
    $project->setProject_id($result["dataId"]);
    //Clear the project and facility list from session for the connect PM
    \Applications\PMTool\Helpers\CommonHelper::AddSessionProject($this->app()->user(), $project);

    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Project,
      "resx_key" => $this->action(),
      "step" => (intval($result["dataId"])) > 0 ? "success" : "error"
    ));
  }

  /**
   * Method that edits a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[0]->pm_id();
    $project = $this->PrepareUserObject($this->dataPost());
    $result["data"] = $project;
    $result["dataId"] = $project->project_id();

    $manager = $this->managers->getManagerOf($this->module());
    $result_insert = $manager->edit($project);

    $this->executeGetItem($rq, $project);
    //\Applications\PMTool\Helpers\CommonHelper::UpdateUserSessionProject($this->app()->user(), $project);

    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Project,
      "resx_key" => $this->action(),
      "step" => $result_insert ? "success" : "error"
    ));
  }

  /**
   * Method that delete a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $db_result = FALSE;
    $project_id = intval($this->dataPost["project_id"]);

    //Check if the project to be deleted if the Project manager's
    $project_selected = $this->_GetProjectFromSession($project_id);
    //Load interface to query the database
    if ($project_selected !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($project_id);
      \Applications\PMTool\Helpers\CommonHelper::UnsetUserSessionProject($this->app()->user(), $project_id);
    }

    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Project,
      "resx_key" => $this->action(),
      "step" => $db_result !== FALSE ? "success" : "error"
    ));
  }

  /**
   * Method that gets a list of projects and returns the result of operation with the list
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeGetList(\Library\HttpRequest $rq, $isNotAjaxCall = FALSE) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[0]->pm_id();
    $project = $this->PrepareUserObject($this->dataPost());
    $result["data"] = $project;

    //Load interface to query the database for projects
    $manager = $this->managers->getManagerOf($this->module);
    $lists[\Library\Enums\SessionKeys::UserProjects] = $manager->selectMany($project);

    //Load interface to query the database for facilities
    $manager = $this->managers->getManagerOf('Facility');
    $lists[\Library\Enums\SessionKeys::UserProjectFacilityList] = $manager->selectMany($project);

    //Load interface to query the database for clients
    $manager = $this->managers->getManagerOf('Client');
    $lists[\Library\Enums\SessionKeys::UserProjectClientList] = $manager->selectMany($project);

    $ProjectsSession = \Applications\PMTool\Helpers\CommonHelper::StoreSessionProjects($this->app()->user(), $lists);

    $result["lists"] = $lists;
    if (!$isNotAjaxCall) {
      $step_result = $ProjectsSession !== NULL ? "success" : "error";
      $this->SendResponseWS(
          $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Project,
        "resx_key" => $this->action(),
        "step" => $step_result
      ));
    }
  }

  /**
   * Method that get a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeGetItem(\Library\HttpRequest $rq, \Applications\PMTool\Models\Dao\Project $project = NULL) {
    // Init result
    $result = $this->InitResponseWS();
    $project_id = intval($this->dataPost["project_id"]);

    $project_selected = NULL;
    if ($project !== NULL) {
      $sessionProject = \Applications\PMTool\Helpers\CommonHelper::GetUserSessionProject($this->app()->user(), $project->project_id());
      $project_selected = $sessionProject[\Library\Enums\SessionKeys::ProjectObject] = $project;
    } else {
      $project_selected = $this->_GetProjectFromSession($project_id);
      $sessionProject = \Applications\PMTool\Helpers\CommonHelper::GetUserSessionProject($this->app()->user(), $project_selected->project_id());
    }

    $facility_selected = $sessionProject[\Library\Enums\SessionKeys::FacilityObject];
    $client_selected = $sessionProject[\Library\Enums\SessionKeys::ClientObject];

    $result["sessionProject"] = $sessionProject;
    \Applications\PMTool\Helpers\CommonHelper::UpdateUserSessionProject($this->app()->user(), $sessionProject);

    if ($project == NULL) {
      $this->SendResponseWS(
          $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Project,
        "resx_key" => $this->action(),
        "step" => ($project_selected !== NULL && $facility_selected !== NULL && $client_selected !== NULL) ? "success" : "error"
      ));
    } else {
      return $sessionProject;
    }
  }

  /**
   * Method that get a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $rows_affected = 0;
    //Get the project objects from ids received
    $project_ids = str_getcsv($this->dataPost["project_ids"], ',');
    $projects = 
        \Applications\PMTool\Helpers\CommonHelper::GetListObjectsInSessionByKey($this->app()->user(), \Library\Enums\SessionKeys::ProjectObject);
    $matchedElements = $this->FindObjectsFromIds(
        array(
          "filter" => "project_id",
          "ids" => $project_ids,
          "objects" => $projects)
    );

    //Update the project objects in DB and get result (number of rows affected)
    //$this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjects);
    foreach ($matchedElements as $project) {
      $project->setProject_active($this->dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $this->managers->getManagerOf($this->module);
      $rows_affected += $manager->edit($project) ? 1 : 0;
    }

    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Project,
      "resx_key" => $this->action(),
      "step" => ($rows_affected === count($project_ids)) ? "success" : "error"
    ));
  }

  /**
   * Find a project from an id
   * 
   * @param int $project_id : the id of the project to find
   * @return \Applications\PMTool\Models\Dao\Project $projectMatch : the match
   */
  private function _GetProjectFromSession($project_id) {
    return \Applications\PMTool\Helpers\CommonHelper::GetAndStoreCurrentProject($this->app(), $project_id);
  }

  /**
   * Find a facility from an  project id
   * 
   * @param int $project_id : the id of the project to find
   * @return \Applications\PMTool\Models\Dao\Facility $facilityMatch : the match
   */
  private function _GetFacilityProjectFromSession($project_id) {
    $facilities = array();
    $facilityMatch = NULL;
    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserProjectFacilityList)) {
      $facilities = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserProjectFacilityList);
    }
    foreach ($facilities as $facility) {
      if (intval($facility->project_id()) === $project_id) {
        $facilityMatch = $facility;
        break;
      }
    }
    return $facilityMatch;
  }

  /**
   * Find a client from an  project id
   * 
   * @param int $project_id : the id of the client to find
   * @return \Applications\PMTool\Models\Dao\Client $clientMatch : the match
   */
  private function _GetClientProjectFromSession($project_id) {
    $clients = array();
    $clientMatch = NULL;
    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserProjectClientList)) {
      $clients = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserProjectClientList);
    }
    foreach ($clients as $client) {
      if (intval($client->project_id()) === $project_id) {
        $clientMatch = $client;
        break;
      }
    }
    return $clientMatch;
  }

  /**
   * Check if the current pm has projects to decide where to send him: stay on the project list or asking him to add a project
   * 
   * @param \Applications\PMTool\Models\Dao\Project_manager $pm
   * @return boolean
   */
  private function _CheckIfPmHasProjects(\Applications\PMTool\Models\Dao\Project_manager $pm) {

    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserSessionProjects)) {
      $projects = \Applications\PMTool\Helpers\CommonHelper::GetListObjectsInSessionByKey($this->app()->user(), \Library\Enums\SessionKeys::ProjectObject);
      return count($projects) > 0 ? TRUE : FALSE;
    }
    $manager = $this->managers->getManagerOf($this->module);
    $count = $manager->countById($pm->pm_id());
    return $count > 0 ? TRUE : FALSE;
  }

  /**
   * Prepare the Project Object before calling the DB.
   * 
   * @param array $data_sent from POST request
   * @return \Applications\PMTool\Models\Dao\Project_manager
   */
  private function PrepareUserObject($data_sent) {
    $project = new \Applications\PMTool\Models\Dao\Project();
    foreach ($data_sent as $key => $value) {
      $method = "set" . ucfirst($key);
      $project->$method(!array_key_exists($key, $data_sent) ? NULL : $value);
    }
    return $project;
  }

  /**
   * Checks if the user projects and facilities are not stored in Session.
   * Stores the projects and facilities after call to WS to retrieve them
   * Set the data into the session for later use.
   * 
   * @param /Library/HttpRequest $rq
   * @return array $lists : the lists of objects if any 
   */
  private function _GetAndStoreProjectsInSession($rq) {
    $lists = array();
    if (!$this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserSessionProjects)) {
      $this->executeGetList($rq, TRUE);
    }
    return $this->app()->user()->getAttribute(\Library\Enums\SessionKeys::UserSessionProjects);
  }

}
