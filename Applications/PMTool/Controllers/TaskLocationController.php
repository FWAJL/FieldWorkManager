<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskLocationController extends \Library\BaseController {

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $rows_affected = 0;
    //Get the task objects from ids received
    $location_ids = str_getcsv($this->dataPost["location_ids"], ',');
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    $task_locations = array();
    foreach ($location_ids as $id) {
      $task_location = new \Applications\PMTool\Models\Dao\Task_location();
      $task_location->setLocation_id($id);
      $task_location->setTask_id($sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
      $manager = $this->managers->getManagerOf($this->module);
      if ($this->dataPost["action"] === "add") {
        $rows_affected += $manager->add($task_location) ? 1 : 0;
      } else {
        $rows_affected += $manager->delete($task_location) ? 1 : 0;
      }
      array_push($task_locations, $task_location);
    }
    $sessionTask[\Library\Enums\SessionKeys::TaskLocations] = $task_locations;
    \Applications\PMTool\Helpers\TaskHelper::SetSessionTask($this->user(), $sessionTask);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
        "resx_key" => $this->action(),
        "step" => ($rows_affected === count($location_ids)) ? "success" : "error"
    ));
  }

  public function executeManageLocations(\Library\HttpRequest $rq) {
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    if ($sessionTask[\Library\Enums\SessionKeys::TaskObj] === NULL) {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::TaskRootUrl);
    }

    \Applications\PMTool\Helpers\TaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\TaskTabKeys::LocationsTab);
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->user());
    $project_locations = \Applications\PMTool\Helpers\LocationHelper::GetProjectLocations($this, $sessionProject);
    $task_locations = \Applications\PMTool\Helpers\LocationHelper::GetAndStoreTaskLocations($this, $sessionTask);
    //filter the project locations after we retrieve the task locations
    $project_locations = \Applications\PMTool\Helpers\LocationHelper::FilterLocationsToExcludeTaskLocations($project_locations, $task_locations);

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
    $this->page->addVar("HasItemsToDisplay", \Applications\PMTool\Helpers\ProjectHelper::DoesProjectHasActiveLocations($this->user()));
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_right => $project_locations,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $task_locations,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower("location")),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower("location"))
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);


    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, \Applications\PMTool\Helpers\TaskHelper::GetTabsStatus($this->user()));

    //Which module?
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

}
