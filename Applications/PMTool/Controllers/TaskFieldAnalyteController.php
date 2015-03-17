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

}
