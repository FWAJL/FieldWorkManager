<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskServiceController extends \Library\BaseController {
	
  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = \Applications\PMTool\Helpers\ServiceHelper::UpdateTaskServices($this);
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
        "resx_key" => $this->action(),
        "step" => ($result["rows_affected"] === count($result["service_ids"])) ? "success" : "error"
    ));
	
  }
	
  public function executeManageServices(\Library\HttpRequest $rq) {
	// Set $current_project for breadcrumb
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());

    //Analyte Matrix tab status
    $showLabMatrixTabs = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::DoesAnalytesAndLocationsExistsFor($sessionTask, $this, 'Lab');
    $showFieldMatrixTabs = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::DoesAnalytesAndLocationsExistsFor($sessionTask, $this, 'Field');
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Task::task_show_lab_matrix, $showLabMatrixTabs);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Task::task_show_field_matrix, $showFieldMatrixTabs);
    //Analyte Matrix tab status

    \Applications\PMTool\Helpers\TaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\TaskTabKeys::ServicesTab);
    $sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $pm_services = \Applications\PMTool\Helpers\ServiceHelper::GetPmServices($this, $sessionPm);
    $task_services = \Applications\PMTool\Helpers\ServiceHelper::GetAndStoreTaskServices($this, $sessionTask);
    // filter the pm services after we retrieve the task services
	$pm_services = \Applications\PMTool\Helpers\ServiceHelper::FilterServicesToExcludeTaskServices($pm_services, $task_services);
	
	$task_services = \Applications\PMTool\Helpers\ServiceHelper::CategorizeTheList($task_services, "service_type");
    $pm_services = \Applications\PMTool\Helpers\ServiceHelper::CategorizeTheList($pm_services, "service_type");
	
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentPm, $sessionPm[\Library\Enums\SessionKeys::PmObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
    $this->page->addVar("HasItemsToDisplay", \Applications\PMTool\Helpers\PmHelper::DoesPmHaveActiveServices($this->user()));
    
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_right => $pm_services,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_left => $task_services,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower("service")),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower("service"))  
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);
	
	//tab status
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, \Applications\PMTool\Helpers\TaskHelper::GetTabsStatus($this->user()));
    //form modules
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
	
  }
}