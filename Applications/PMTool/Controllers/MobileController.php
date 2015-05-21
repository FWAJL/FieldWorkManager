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
    $task = \Applications\PMTool\Helpers\TaskHelper::GetLatestTaskForTechnician($this,$technician);
    if($task) {
      \Applications\PMTool\Helpers\TaskHelper::SetCurrentSessionTask($this->user(),$task);
      $manager = $this->managers()->getManagerOf('Project');
      $project = new \Applications\PMTool\Models\Dao\Project();
      $project->setProject_id($task->project_id());
      $projects = $manager->selectMany($project,'project_id');
      if($projects) {
        \Applications\PMTool\Helpers\ProjectHelper::AddSessionProject($this->user(),$projects[0]);
        \Applications\PMTool\Helpers\ProjectHelper::GetAndStoreCurrentProject($this->user(),$task->project_id());
      }


    }


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

}
