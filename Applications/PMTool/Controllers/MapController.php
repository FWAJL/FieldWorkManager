<?php

  namespace Applications\PMTool\Controllers;

  if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
    exit('No direct script access allowed');

  class MapController extends \Library\BaseController {

    //TODO: Make this method generic. It can work for a list of:
    //  - facilities
    //  - locations
    //  - task locations
    //TODO: return the list of object in JSON from a AJAX request initiated by the client.
    public function executeListAll(\Library\HttpRequest $rq) {
      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

      //TODO: No need for the Dal call here. Everything is already in Session
      //See => \Applications\PMTool\Helpers\ProjectHelper::GetSessionProjects($this->user());
      //Just loop through each project session array and get the facility object.
      $facility = new \Applications\PMTool\Models\Dao\Facility();
      $manager = $this->managers()->getManagerOf("facility");
      $facilities= $manager->selectManyUser($facility, "", $this->app()->user());
      /*-----------------------------------------------------------------------------------*/
      
      $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module     => $this->resxfile,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects    => $facilities,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList($this->resxfile)
      );
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

      $modules = $this->app()->router()->selectedRoute()->phpModules();
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::all_projects, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::all_projects]);
    }
//
//    public function executeCurrentProject(\Library\HttpRequest $rq) {
//      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
//      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
//
//      $data = array(
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module     => $this->resxfile,
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects    => $sessionProject,
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList($this->resxfile)
//      );
//      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);
//
//      $modules = $this->app()->router()->selectedRoute()->phpModules();
//      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::current_project, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::current_project]);
//    }
//
//    public function executeShowOne(\Library\HttpRequest $rq) {
//      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
//      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
//
//      $id = $rq->getData("id");
//
//      if (@$rq->getData("location") == "true") {
//        $taskLocation = new \Applications\PMTool\Models\Dao\Location();
//        $taskLocation->setLocation_id($id);
//        $dal = $this->managers()->getManagerOf("Location");
//        $oneProject = $dal->selectMany($taskLocation, "location_id");
//      } else
//        $oneProject = \Applications\PMTool\Helpers\ProjectHelper::GetUserSessionProject($this->app()->user(), $id, FALSE);
//
//      //print_r($oneProject);
//
//      $data = array(
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module     => $this->resxfile,
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects    => $oneProject,
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList($this->resxfile)
//      );
//      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);
//
//      $modules = $this->app()->router()->selectedRoute()->phpModules();
//      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::show_one, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::show_one]);
//    }
//
//    public function executeCurrentProjectLocations(\Library\HttpRequest $rq) {
//      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
//      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
//
//      //print_r($sessionProject);
//
//      $data = array(
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module     => $this->resxfile,
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects    => $sessionProject,
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList($this->resxfile)
//      );
//      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);
//
//      $modules = $this->app()->router()->selectedRoute()->phpModules();
//      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::current_project_locations, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::current_project_locations]);
//    }
//
//    public function executeTaskLocations(\Library\HttpRequest $rq) {
//      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
//      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
//
//      $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
//      $task_locations = \Applications\PMTool\Helpers\LocationHelper::GetAndStoreTaskLocations($this, $sessionTask);
//
//      $data = array(
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module     => $this->resxfile,
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects    => $sessionProject,
//        'locations'                                                        => $task_locations,
//        'task'                                                             => $sessionTask['task_info_obj'],
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList($this->resxfile)
//      );
//      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);
//
//      $modules = $this->app()->router()->selectedRoute()->phpModules();
//      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::task_locations, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::task_locations]);
//    }

  }
