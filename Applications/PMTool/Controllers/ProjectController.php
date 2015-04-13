<?php

/**
 *
 * @package     Basic MVC framework test
 * @author      FWM DEV Team
 * @copyright   Copyright (c) 2015
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * ProjectController Class
 *
 * @package     Applications 
 * @subpackage  PMTool
 * @category    Controllers
 * @author      FWM DEV Team
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
    //Check session if pm has projects
    $hasProjects = count(\Applications\PMTool\Helpers\ProjectHelper::GetSessionProjects($this->app()->user())) > 0;
    if ($hasProjects) {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsListAll);
    } else {
      $this->executeGetList($rq, true); //Get and store projects to session (even if there is none)
      
      if (count(\Applications\PMTool\Helpers\ProjectHelper::SetCurrentProjectIfPmHasOnlyOneAndReturnProjects($this->user())) > 0) {
        $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsListAll);
      } else {
        $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsShowForm . "?mode=add&test=true");
      }
    }
  }

  /**
   * Method that loads the add or edit view for controller
   * 
   * @param \Library\HttpRequest $rq: the request
   */
  public function executeShowForm(\Library\HttpRequest $rq) {
    //Get confirm msg for Project deletion from showForm screen
    $confirm_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"project", "targetaction": "view", "operation": ["delete", "addNullCheck", "addUniqueCheck","addAddressCheck","addCoordinatesCheck"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $confirm_msg);

    //Fetch prompt box data from xml and pass to view as an array
    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"project", "targetaction": "view", "operation": ["addNullCheck"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);

    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  /**
   * Method that loads the list all project view for controller
   * 
   * @param \Library\HttpRequest $rq: the request
   */
  public function executeListAll(\Library\HttpRequest $rq) {
    \Applications\PMTool\Helpers\ProjectHelper::SetCurrentProjectIfPmHasOnlyOneAndReturnProjects($this->user());
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"project", "targetaction": "list", "targetattr": ["active-project-header","inactive-project-header"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    //Get confirm msg for project deletion from context menu
    $confirm_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"project", "targetaction": "list", "operation": ["delete","activate","addUniqueCheck"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $confirm_msg);

    //Fetch prompt box data from xml and pass to view as an array
    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"project", "targetaction": "list", "operation": ["addNullCheck"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);


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
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::promote_buttons, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::promote_buttons]);
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariables\Popup::popup_msg, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_msg]);
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_msg, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_prompt]);
	$this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message_module, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::tooltip_msg]);
  }

  /**
   * Method that loads the select project view for controller
   * This is when any other action of any other controller
   * is selected but that is dependent on a project which is
   * already selected. What this action does is, lets the user
   * know if the action they selected is dependent on a project
   * and let's them select a project. If a project is already 
   * selected, it just redirects to the actual action user 
   * wanted to visit.
   *
   * @param \Library\HttpRequest $rq: the request
   */
  public function executeSelectProject(\Library\HttpRequest $rq) {

    //Fetch prompt box data from xml and pass to view as an array
    $urlParts = explode("/", $rq->getData('onSuccess'));
    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"' . $urlParts[0] . '", "targetaction": "' . $urlParts[1] . '", "operation": ["checkCurrentProject"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::redirect_on_success, $rq->getData('onSuccess'));

    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => $this->resxfile,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => \Applications\PMTool\Helpers\CommonHelper::GetListObjectsInSessionByKey($this->app()->user(), \Library\Enums\SessionKeys::ProjectObject),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList($this->resxfile)
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariables\Popup::popup_prompt_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::active_list]);
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariables\Popup::popup_msg, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_msg]);
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_msg, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_selector_module]);
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
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[\Library\Enums\SessionKeys::PmObject]->pm_id();
    $project = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Project());
    $result["dataIn"] = $project;

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module);
    $result["dataId"] = $manager->add($project);
    $project->setProject_id($result["dataId"]);
    \Applications\PMTool\Helpers\ProjectHelper::AddSessionProject($this->app()->user(), $project);

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
    $pmSession = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $this->dataPost["pm_id"] = $pmSession === NULL ? NULL : $pmSession[\Library\Enums\SessionKeys::PmObject]->pm_id();
    $project = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Project());
    $result["data"] = $project;
    $result["dataId"] = $project->project_id();

    $manager = $this->managers->getManagerOf($this->module());
    $result_insert = $manager->edit($project, "project_id");

    $this->executeGetItem($rq, $project);
    //\Applications\PMTool\Helpers\ProjectHelper::UpdateUserSessionProject($this->app()->user(), $project);

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
    $project_selected = \Applications\PMTool\Helpers\ProjectHelper::GetAndStoreCurrentProject($this->app()->user(), $project_id);
    //Load interface to query the database
    if ($project_selected !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($project_selected, "project_id");
      \Applications\PMTool\Helpers\ProjectHelper::UnsetUserSessionProject($this->app()->user(), $project_id);
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
    if($this->app()->user->getUserType() == 'pm_id'){
      $pmid = $this->app()->user->getUserTypeId();
      $this->dataPost["pm_id"] = $pmid === NULL ? NULL : $pmid;
    }

    $project = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Project());
    $result["data"] = $project;

    //Load interface to query the database for projects
    $manager = $this->managers->getManagerOf($this->module);
    $lists[\Library\Enums\SessionKeys::UserProjects] = $manager->selectMany($project, "pm_id");

    //Load interface to query the database for facilities
    $manager = $this->managers->getManagerOf('Facility');
    $lists[\Library\Enums\SessionKeys::UserProjectFacilityList] = $manager->selectMany($project, "pm_id");

    //Load interface to query the database for clients
    $manager = $this->managers->getManagerOf('Client');
    $lists[\Library\Enums\SessionKeys::UserProjectClientList] = $manager->selectMany($project, "pm_id");

    $ProjectsSession = \Applications\PMTool\Helpers\ProjectHelper::StoreSessionProjects($this, $lists);

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
      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetUserSessionProject($this->app()->user(), $project->project_id());
      $project_selected = $sessionProject[\Library\Enums\SessionKeys::ProjectObject] = $project;
    } else {
      $project_selected = \Applications\PMTool\Helpers\ProjectHelper::GetAndStoreCurrentProject($this->app()->user(), $project_id);
      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetUserSessionProject($this->app()->user(), $project_selected->project_id());
    }

    $facility_selected = $sessionProject[\Library\Enums\SessionKeys::FacilityObject];
    $client_selected = $sessionProject[\Library\Enums\SessionKeys::ClientObject];

    $result["sessionProject"] = $sessionProject;
    \Applications\PMTool\Helpers\ProjectHelper::UpdateUserSessionProject($this->app()->user(), $sessionProject);

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
    $projects = \Applications\PMTool\Helpers\CommonHelper::GetListObjectsInSessionByKey($this->app()->user(), \Library\Enums\SessionKeys::ProjectObject);
    $matchedElements = $this->FindObjectsFromIds(
            array(
                "filter" => "project_id",
                "ids" => $project_ids,
                "objects" => $projects)
    );

    foreach ($matchedElements as $project) {
      $project->setProject_active($this->dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $this->managers->getManagerOf($this->module);
      $rows_affected += $manager->edit($project, "project_id") ? 1 : 0;
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Project,
        "resx_key" => $this->action(),
        "step" => ($rows_affected === count($project_ids)) ? "success" : "error"
    ));
  }

  /**
   * Method that get a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeSetCurrentProject(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $project = \Applications\PMTool\Helpers\ProjectHelper::GetAndStoreCurrentProject($this->user(), $this->dataPost["project_id"]);
    $result["dataId"] = $project->project_id();

    \Applications\PMTool\Helpers\TaskHelper::UnsetCurrentSessionTask($this->user());

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Project,
        "resx_key" => $this->action(),
        "step" => ($project != NULL) ? "success" : "error"
    ));
  }

  /**
   * Method which checks for existence of the project name in the database
   */
  public function executeIfProjectExists(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result
    $project = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Project());

    //Check session if the project name is already used
    $match = \Applications\PMTool\Helpers\CommonHelper::FindObjectByStringValue(
                    $project->project_name(), "project_name", \Applications\PMTool\Helpers\ProjectHelper::GetSessionProjects($this->user()), \Library\Enums\SessionKeys::ProjectObject
    );
    $result['record_count'] = (!$match || empty($match)) ? 0 : 1;


    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Project,
        "resx_key" => $this->action(),
        "step" => ($result['record_count'] > 0) ? "success" : "error"
    ));
  }

  /**
   * Method that edits a project and facility from map modal
   *
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeMapEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $dataPost = json_decode($this->dataPost["params"],true);
    $sessionProjects = \Applications\PMTool\Helpers\ProjectHelper::GetSessionProjects($this->user());
    if($dataPost["project"]["project_id"]) {
      $sessionProject = $sessionProjects[\Library\Enums\SessionKeys::ProjectKey . $dataPost["project"]["project_id"]];
      $facility = $sessionProject[\Library\Enums\SessionKeys::FacilityObject];
      $project = $sessionProject[\Library\Enums\SessionKeys::ProjectObject];
    }

    if ($facility !== NULL && $project !== NULL) {
      //Init PDO
      $facility = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($dataPost["facility"], $facility);
      $project = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($dataPost["project"], $project);
      $manager = $this->managers->getManagerOf($this->module());
      $result_edit["facility"] = $manager->edit($facility, "facility_id");
      $manager = $this->managers->getManagerOf("Project");
      $result_edit["project"] = $manager->edit($project, "project_id");

      $result["data"]["facility"] = $facility;
      $result["data"]["project"] = $project;
    }

    //Update this project in session projects list
    if ($result_edit) {
      $sessionProject[\Library\Enums\SessionKeys::ProjectObject] = $project;
      $sessionProject[\Library\Enums\SessionKeys::FacilityObject] = $facility;
      $sessionProjects[\Library\Enums\SessionKeys::ProjectKey . $dataPost["project"]["project_id"]] = $sessionProject;
      \Applications\PMTool\Helpers\ProjectHelper::SetSessionProjects($this->user(),$sessionProjects);
    }

    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Project,
      "resx_key" => $this->action(),
      "step" => $result_edit ? "success" : "error"
    ));
  }

}
