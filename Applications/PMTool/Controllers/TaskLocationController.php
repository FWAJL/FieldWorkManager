<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskLocationController extends \Library\BaseController {

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $rows_affected = 0;
    //Get the task objects from ids received
    $task_ids = str_getcsv($this->dataPost["task_ids"], ',');
    $sessionTasks = \Applications\PMTool\Helpers\TaskHelper::GetSessionTasks($this->app()->user());

    foreach ($task_ids as $id) {
      $task = $sessionTasks[\Library\Enums\SessionKeys::TaskKey . $id][\Library\Enums\SessionKeys::TaskObj];
      $task->setTask_active($this->dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $this->managers->getManagerOf($this->module);
      $rows_affected += $manager->edit($task) ? 1 : 0;
    }
    \Applications\PMTool\Helpers\TaskHelper::SetSessionTasks($this->app()->user(), $sessionTasks);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
        "resx_key" => $this->action(),
        "step" => ($rows_affected === count($task_ids)) ? "success" : "error"
    ));
  }

  public function executeManageLocations(\Library\HttpRequest $rq) {
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    if ($sessionTask[\Library\Enums\SessionKeys::TaskObj] === NULL) { $this->Redirect (\Library\Enums\ResourceKeys\UrlKeys::TaskRootUrl); }
        
    \Applications\PMTool\Helpers\TaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\TaskTabKeys::LocationsTab);
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->user());
    $task_locations = \Applications\PMTool\Helpers\TaskHelper::GetAndStoreTaskLocations($this, $sessionTask);
    
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
    $this->page->addVar("ProjectHasActiveLocation", \Applications\PMTool\Helpers\ProjectHelper::DoesProjectHasActiveLocations($this->user()));
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_right => $sessionProject[\Library\Enums\SessionKeys::ProjectLocations],
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $task_locations,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower("location")),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower($this->module()))
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);


    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, \Applications\PMTool\Helpers\TaskHelper::GetTabsStatus($this->user()));

    //Which module?
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

}
