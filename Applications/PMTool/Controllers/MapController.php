<?php

  namespace Applications\PMTool\Controllers;

  if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
    exit('No direct script access allowed');

  class MapController extends \Library\BaseController {

    public function executeListAll(\Library\HttpRequest $rq) {
      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

      $facility = new \Applications\PMTool\Models\Dao\Facility();

      $manager = $this->managers()->getManagerOf("facility");



      $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module     => $this->resxfile,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects    => $manager->selectManyUser($facility, "", $this->app()->user()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList($this->resxfile)
      );
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

      $modules = $this->app()->router()->selectedRoute()->phpModules();
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::all_projects, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::all_projects]);
    }

    public function executecurrentProject(\Library\HttpRequest $rq) {
      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

      $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module     => $this->resxfile,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects    => $sessionProject,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList($this->resxfile)
      );
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

      $modules = $this->app()->router()->selectedRoute()->phpModules();
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::current_project, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::current_project]);
    }

    public function executeshowOne(\Library\HttpRequest $rq) {
      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

      $id = $_GET['id'];

      if (@$_GET['location'] == "true") {
        $taskLocation = new \Applications\PMTool\Models\Dao\Location();
        $taskLocation->setLocation_id($id);
        $dal = $this->managers()->getManagerOf("Location");
        $oneProject = $dal->selectMany($taskLocation, "location_id");
      } else
        $oneProject = \Applications\PMTool\Helpers\ProjectHelper::GetUserSessionProject($this->app()->user(), $id, FALSE);

      //print_r($oneProject);

      $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module     => $this->resxfile,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects    => $oneProject,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList($this->resxfile)
      );
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

      $modules = $this->app()->router()->selectedRoute()->phpModules();
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::show_one, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::show_one]);
    }

    public function executecurrentProjectLocations(\Library\HttpRequest $rq) {
      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

      //print_r($sessionProject);

      $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module     => $this->resxfile,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects    => $sessionProject,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList($this->resxfile)
      );
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

      $modules = $this->app()->router()->selectedRoute()->phpModules();
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::current_project_locations, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::current_project_locations]);
    }

    public function executetaskLocations(\Library\HttpRequest $rq) {
      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

      $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
      $task_locations = \Applications\PMTool\Helpers\LocationHelper::GetAndStoreTaskLocations($this, $sessionTask);

      $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module     => $this->resxfile,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects    => $sessionProject,
        'locations'                                                        => $task_locations,
        'task'                                                             => $sessionTask['task_info_obj'],
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList($this->resxfile)
      );
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

      $modules = $this->app()->router()->selectedRoute()->phpModules();
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::task_locations, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::task_locations]);
    }

  }
