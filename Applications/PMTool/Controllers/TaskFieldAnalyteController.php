<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskFieldAnalyteController extends \Library\BaseController {

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = \Applications\PMTool\Helpers\AnalyteHelper::UpdateTaskAnalytes($this);
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::TaskFieldAnalyte,
        "resx_key" => $this->action(),
        "step" => ($result["rows_affected"] === count($result["arrayOfValues"])) ? "success" : "error"
    ));
  }

  public function executeManageFieldAnalytes(\Library\HttpRequest $rq) {
	
		$sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
		\Applications\PMTool\Helpers\AnalyteHelper::StoreListsData($this);  
		$pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
		$sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
		//All analytes for this PM, we would be assigning task specific analytes from list list
		$pm_field_analytes = $pm[\Library\Enums\SessionKeys::PmFieldAnalytes];

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
	    
		\Applications\PMTool\Helpers\TaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\TaskTabKeys::FieldAnalytesTab);
		
	    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
		$this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
		
		$field_object_properties = \Applications\PMTool\Helpers\CommonHelper::SetDynamicPropertyNamesForDualList(
	            "field_analyte", \Applications\PMTool\Helpers\AnalyteHelper::GetListPropertiesForFieldAnalyte());
		//Fetch task specific field analytes
		$task_field_analytes = \Applications\PMTool\Helpers\AnalyteHelper::GetAndStoreTaskFieldAnalytes($this, $sessionTask);
		$pm_field_analytes = \Applications\PMTool\Helpers\CommonHelper::FilterObjectsToExcludeRelatedObject($pm_field_analytes, $task_field_analytes, "field_analyte_id");
		
		
		$data_field_analyte = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "fieldanalyte",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_right => $task_field_analytes,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $pm_field_analytes,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => $field_object_properties,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => $field_object_properties
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Analyte::data_field_analyte, $data_field_analyte);
	
	
		//tab status
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, \Applications\PMTool\Helpers\TaskHelper::GetTabsStatus($this->user()));
		$this->page->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
	
		//\Applications\PMTool\Helpers\CommonHelper::pr($this->app()->router()->selectedRoute()->phpModules()); die;
	
  }

  public function executeFieldMatrix(\Library\HttpRequest $rq) {
  	$sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    \Applications\PMTool\Helpers\AnalyteHelper::StoreListsData($this);  
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    //Get task specific lab analytes
    $task_field_analytes = \Applications\PMTool\Helpers\AnalyteHelper::GetAndStoreTaskFieldAnalytes($this, $sessionTask);
    //Task specific locations
    $project_locations = \Applications\PMTool\Helpers\LocationHelper::GetProjectLocations($this, $sessionProject);
    $task_locations = \Applications\PMTool\Helpers\LocationHelper::GetAndStoreTaskLocations($this, $sessionTask);

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

    //Get LocationLabMatrix relation
    $id_map = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::GetAnalyteMatrixForTask($this, $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id(), 'Field');

    \Applications\PMTool\Helpers\TaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\TaskTabKeys::FieldSampleMatrixTab);
    
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::task_locations, $task_locations);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::task_field_analytes, $task_field_analytes);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::task_field_analytes_idmap, $id_map);

    //tab status
    $this->page->addVar(
              \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, \Applications\PMTool\Helpers\TaskHelper::GetTabsStatus($this->user()));
    $this->page->addVar(
              \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());

  }

  public function executeSaveFieldMatrix(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    //Our current Session
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    $sess_task_id = $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id();

    $result_save = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::SaveAnalyteMatrixForTask($this, $sess_task_id, $this->dataPost['field_matrix'], 'Field');

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
        "resx_key" => $this->action(),
        "step" => $result_save ? "success" : "error"
    ));
  }

}
