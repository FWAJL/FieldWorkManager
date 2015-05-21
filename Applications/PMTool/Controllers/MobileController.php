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
 * MobileController Class
 *
 * @package     Applications 
 * @subpackage  PMTool
 * @category    Controllers
 * @author      FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Controllers;

use Applications\PMTool\Helpers\UserHelper;
use Applications\PMTool\Models\Dao\User;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

/* * ******  Mobile Controller   *********** */

class MobileController extends \Library\BaseController {

  /**
   * Method For Mobile Controller
   * 
   * @param \Library\HttpRequest $rq: the request
   */
  public function executeViewTasksForTechnician(\Library\HttpRequest $rq) {

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeViewMap(\Library\HttpRequest $rq) {
    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $technician = $this->user()->getAttribute(\Library\Enums\SessionKeys::UserTypeObject);





    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->app()->user());



    //Check if a project needs to be selected in order to display this page
    if (!$sessionProject)
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::MapTaskLocations);

    //Next check if a task is selected
    if(!$sessionTask)
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::TaskSelectTask . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::MapTaskLocations);
    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"map", "targetaction": "taskLocations", "targetattr": ["question-map-h3", "map-info-ruler", "map-info-shape", "map-info-add"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    $alert_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"map", "targetaction": "loadMaps", "operation": ["addUniqueCheck","checkCoordinates"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $alert_msg);

    //add view vars for breadcrumb
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $modules);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Map::default_active_control, $rq->getData('active') ?: 'pan');
  }

  public function executeListTasks(\Library\HttpRequest $rq) {
    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $technician = $this->user()->getAttribute(\Library\Enums\SessionKeys::UserTypeObject);
    $project = new \Applications\PMTool\Models\Dao\Project();
    $project->setPm_id($technician->pm_id());
    //Load interface to query the database for projects
    $manager = $this->managers->getManagerOf('Project');
    $lists[\Library\Enums\SessionKeys::UserProjects] = $manager->selectMany($project, "pm_id");

    //Load interface to query the database for facilities
    $manager = $this->managers->getManagerOf('Facility');
    $lists[\Library\Enums\SessionKeys::UserProjectFacilityList] = $manager->selectMany($project, "pm_id");

    //Load interface to query the database for clients
    $manager = $this->managers->getManagerOf('Client');
    $lists[\Library\Enums\SessionKeys::UserProjectClientList] = $manager->selectMany($project, "pm_id");

    $ProjectsSession = \Applications\PMTool\Helpers\ProjectHelper::StoreSessionProjects($this, $lists);
     \Applications\PMTool\Helpers\TaskHelper::GetTasksForTechnician($this,$technician);
    $tasks = \Applications\PMTool\Helpers\TaskHelper::GetSessionTasks($this->user());
    if(!is_array($tasks)) {
      $tasks = array();
    }
    $task_id = $rq->getData("task_id");

    $data = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => 'task',
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => \Applications\PMTool\Helpers\CommonHelper::GetObjectListFromSessionArrayBySessionKey($tasks, \Library\Enums\SessionKeys::TaskObj),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower('task'))
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    if(count($tasks)==1) {
      $firstTask = reset($tasks);
      $task_id = $firstTask[\Library\Enums\SessionKeys::TaskObj]->task_id();
    }
    //Set passed task as current task
    if($task_id !== '' && !is_null($task_id)) {
      $sessionTask = \Applications\PMTool\Helpers\TaskHelper::SetCurrentSessionTask($this->user(), NULL, $task_id);
      $taskObj = $sessionTask[\Library\Enums\SessionKeys::TaskObj];
      $manager = $this->managers()->getManagerOf('Project');
      $project = new \Applications\PMTool\Models\Dao\Project();
      $project->setProject_id($taskObj->project_id());
      $projects = $manager->selectMany($project,'project_id');

      if($projects) {
        \Applications\PMTool\Helpers\ProjectHelper::GetAndStoreCurrentProject($this->user(),$taskObj->project_id());
      }
      //check if we passed a redirect URL too
      if($rq->getData("onSuccess") !== '' && !is_null($rq->getData("onSuccess"))) {
        //rediect to it
        $this->Redirect($rq->getData("onSuccess"));
      }
    }
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->user);
    if(count($tasks)==1) {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::MobileMap);
    }
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);

    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"task", "targetaction": "list", "targetattr": ["active-task-header","inactive-task-header"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    //Get confirm msg for project deletion from context menu
    $confirm_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"task", "targetaction": "list", "operation": ["activate","addUniqueCheck"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $confirm_msg);

    //Fetch prompt box data from xml and pass to view as an array
    //Also let's just fetch the message for the showForm view and reuse it
    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"task", "targetaction": "view", "operation": ["addNullCheck","addNullCheckForCopy"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);

    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::active_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::active_list]);
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariables\Popup::popup_msg, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_msg]);
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_msg, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_prompt]);
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message_module, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::tooltip_msg]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
  }

}
