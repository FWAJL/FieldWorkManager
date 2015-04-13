<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskLabAnalyteController extends \Library\BaseController {
  public function executeUpdateItems(\Library\HttpRequest $rq) {
	$result = \Applications\PMTool\Helpers\AnalyteHelper::UpdateTaskAnalytes($this);
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::TaskLabAnalyte,
        "resx_key" => $this->action(),
        "step" => ($result["rows_affected"] === count($result["arrayOfValues"])) ? "success" : "error"
    ));
  }
  
  public function executeManageLabAnalytes(\Library\HttpRequest $rq) {
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
  	\Applications\PMTool\Helpers\AnalyteHelper::StoreListsData($this);  
  	$pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
  	$sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
  	//All analytes for this PM, we would be assigning task specific analytes from list
  	$pm_lab_analytes = $pm[\Library\Enums\SessionKeys::PmLabAnalytes];

    //Task tab status 
    $tab_status_arr = \Applications\PMTool\Helpers\TaskHelper::TabStatusFor($sessionTask);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Task::task_tab_status_keys, $tab_status_arr);
    //Task tab status 

    //Analyte Matrix tab status
    $showLabMatrixTabs = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::DoesAnalytesAndLocationsExistsFor($sessionTask, $this, 'Lab');
    $showFieldMatrixTabs = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::DoesAnalytesAndLocationsExistsFor($sessionTask, $this, 'Field');
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Task::task_show_lab_matrix, $showLabMatrixTabs);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Task::task_show_field_matrix, $showFieldMatrixTabs);
    //Analyte Matrix tab status
      
  	\Applications\PMTool\Helpers\TaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\TaskTabKeys::LabAnalytesTab);
  	
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
  	$this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
  	
  	$lab_object_properties = \Applications\PMTool\Helpers\CommonHelper::SetDynamicPropertyNamesForDualList(
              "lab_analyte", \Applications\PMTool\Helpers\AnalyteHelper::GetListPropertiesForLabAnalyte());
  	//Fetch task specific field analytes
  	$task_lab_analytes = \Applications\PMTool\Helpers\AnalyteHelper::GetAndStoreTaskLabAnalytes($this, $sessionTask);
  	$pm_lab_analytes = \Applications\PMTool\Helpers\CommonHelper::FilterObjectsToExcludeRelatedObject($pm_lab_analytes, $task_lab_analytes, "lab_analyte_id");
  	
  	$data_lab_analyte = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "labanalyte",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_right => $task_lab_analytes,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $pm_lab_analytes,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => $lab_object_properties,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => $lab_object_properties
      );
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Analyte::data_lab_analyte, $data_lab_analyte);
  	
  	
  	//tab status
    $this->page->addVar(
              \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, \Applications\PMTool\Helpers\TaskHelper::GetTabsStatus($this->user()));
  	$this->page->addVar(
          \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  	
  }

  public function executeLabMatrix(\Library\HttpRequest $rq) {
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    \Applications\PMTool\Helpers\AnalyteHelper::StoreListsData($this);  
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());

    //Task tab status 
    $tab_status_arr = \Applications\PMTool\Helpers\TaskHelper::TabStatusFor($sessionTask);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Task::task_tab_status_keys, $tab_status_arr);
    //Task tab status 

    //Analyte Matrix tab status
    $showLabMatrixTabs = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::DoesAnalytesAndLocationsExistsFor($sessionTask, $this, 'Lab');
    $showFieldMatrixTabs = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::DoesAnalytesAndLocationsExistsFor($sessionTask, $this, 'Field');
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Task::task_show_lab_matrix, $showLabMatrixTabs);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Task::task_show_field_matrix, $showFieldMatrixTabs);
    //Analyte Matrix tab status

    //Get task specific lab analytes
    $task_lab_analytes = \Applications\PMTool\Helpers\AnalyteHelper::GetAndStoreTaskLabAnalytes($this, $sessionTask);
    //Task specific locations
    $project_locations = \Applications\PMTool\Helpers\LocationHelper::GetProjectLocations($this, $sessionProject);
    $task_locations = \Applications\PMTool\Helpers\LocationHelper::GetAndStoreTaskLocations($this, $sessionTask);
    
    //Get LocationLabMatrix relation
    $id_map = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::GetAnalyteMatrixForTask($this, $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id(), 'Lab');

    \Applications\PMTool\Helpers\TaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\TaskTabKeys::LabSampleMatrixTab);
    
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::task_locations, $task_locations);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::task_lab_analytes, $task_lab_analytes);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::task_lab_analytes_idmap, $id_map);

    //tab status
    $this->page->addVar(
              \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, \Applications\PMTool\Helpers\TaskHelper::GetTabsStatus($this->user()));
    $this->page->addVar(
              \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());


  }

  public function executeSaveLabMatrix(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    //Our current Session
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    $sess_task_id = $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id();

    $result_save = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::SaveAnalyteMatrixForTask($this, $sess_task_id, $this->dataPost['lab_matrix'], 'Lab');

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
        "resx_key" => $this->action(),
        "step" => $result_save ? "success" : "error"
    ));
  }

}
