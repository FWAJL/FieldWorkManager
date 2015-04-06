<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class AnalyteController extends \Library\BaseController {

  public function executeShowForm(\Library\HttpRequest $rq) {
    $this->page()->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeUploadList(\Library\HttpRequest $rq) {
    $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TabActiveAnalyte);
	
	//Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"analyte", "targetaction": "uploadList", "targetattr": ["activequestion-fieldanalyte-uploadList-headerH4", "inactivequestion-fieldanalyte-uploadList-headerH4", "activequestion-labanalyte-uploadList-headerH4", "inactivequestion-labanalyte-uploadList-headerH4"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);
	
    if ($tabsStatus === NULL) {
      \Applications\PMTool\Helpers\AnalyteHelper::AddTabsStatus($this->user());
      $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TabActiveAnalyte);
    }
    $this->page()->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, $tabsStatus);
    $this->page()->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());

    \Applications\PMTool\Helpers\AnalyteHelper::StoreListsData($this, TRUE);

    $data_common_field_analyte = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "common_field_analyte",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => \Applications\PMTool\Helpers\CommonHelper::GetValueInSession(
          $this->user(), \Library\Enums\SessionKeys::CommonFieldAnalytes),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList("common_field_analyte")
    );
    $this->page()->addVar(
        "data_common_field_analyte", $data_common_field_analyte);

    $data_common_lab_analyte = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "common_lab_analyte",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => \Applications\PMTool\Helpers\CommonHelper::GetValueInSession(
          $this->user(), \Library\Enums\SessionKeys::CommonLabAnalytes),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList("common_lab_analyte")
    );
    $this->page()->addVar(
        "data_common_lab_analyte", $data_common_lab_analyte);
  }
  public function executeUploadCommonAnalytes(\Library\HttpRequest $rq) {
    $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TabActiveAnalyte);
    if ($tabsStatus === NULL) {
      \Applications\PMTool\Helpers\AnalyteHelper::AddTabsStatus($this->user());
      $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TabActiveAnalyte);
    }
    $this->page()->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, $tabsStatus);
    $this->page()->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());

    \Applications\PMTool\Helpers\AnalyteHelper::StoreCommonListData($this);

    $data_common_field_analyte = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "common_field_analyte",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => \Applications\PMTool\Helpers\CommonHelper::GetValueInSession(
          $this->user(), \Library\Enums\SessionKeys::CommonFieldAnalytes),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList("common_field_analyte")
    );
    $this->page()->addVar(
        "data_common_field_analyte", $data_common_field_analyte);

    $data_common_lab_analyte = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "common_lab_analyte",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => \Applications\PMTool\Helpers\CommonHelper::GetValueInSession(
          $this->user(), \Library\Enums\SessionKeys::CommonLabAnalytes),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList("common_lab_analyte")
    );
    $this->page()->addVar(
        "data_common_lab_analyte", $data_common_lab_analyte);
  }

  public function executeListAll(\Library\HttpRequest $rq) {
    $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TabActiveAnalyte);
    if ($tabsStatus === NULL) {
      \Applications\PMTool\Helpers\AnalyteHelper::AddTabsStatus($this->user());
      $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TabActiveAnalyte);
    }
    $this->page()->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, $tabsStatus);

    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
	
	//Check if a project needs to be selected in order to display this page
    if (!$sessionProject)
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::AnalyteListAll);
	
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
	
	//Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"analyte", "targetaction": "list", "targetattr": ["active-fieldanalyte-header","inactive-fieldanalyte-header","active-labanalyte-header","inactive-labanalyte-header"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);
	
	//Get confirm msg for analyte deletion from showForm screen
    $confirm_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"analyte", "targetaction": "list", "operation": ["deleteField", "deleteLab", "noAnalyteForProject","noAnalyteAvailable"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $confirm_msg);

    \Applications\PMTool\Helpers\AnalyteHelper::StoreListsData($this);
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
	
	//Fetch prompt box data from xml and pass to view as an array
	$prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"analyte", "targetaction": "listAll", "operation": ["edit"]}', $this->app->name());
	$this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);

    //variables for the field analyte module
    $field_object_properties = \Applications\PMTool\Helpers\CommonHelper::SetDynamicPropertyNamesForDualList(
            "field_analyte", \Applications\PMTool\Helpers\AnalyteHelper::GetListPropertiesForFieldAnalyte());
    $field_analytes = $pm[\Library\Enums\SessionKeys::PmFieldAnalytes];
    $project_field_analytes = \Applications\PMTool\Helpers\AnalyteHelper::FilterAnalytesByProjectAnalytesList($this);
    $field_analytes = \Applications\PMTool\Helpers\CommonHelper::FilterObjectsToExcludeRelatedObject($field_analytes, $project_field_analytes, "field_analyte_id");

    $data_field_analyte = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "fieldanalyte",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_right => $field_analytes,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $project_field_analytes,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => $field_object_properties,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => $field_object_properties
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Analyte::data_field_analyte, $data_field_analyte);

    //variable for the lab analyte module
    $lab_object_properties = \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList("lab_analyte");
    $lab_analytes = $pm[\Library\Enums\SessionKeys::PmLabAnalytes];
    $project_lab_analytes = \Applications\PMTool\Helpers\AnalyteHelper::FilterAnalytesByProjectAnalytesList($this, FALSE);
    $lab_analytes = \Applications\PMTool\Helpers\CommonHelper::FilterObjectsToExcludeRelatedObject($lab_analytes, $project_lab_analytes, "lab_analyte_id");

    $data_lab_analyte = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "labanalyte",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_right => $lab_analytes,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $project_lab_analytes,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => $lab_object_properties,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => $lab_object_properties
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Analyte::data_lab_analyte, $data_lab_analyte);


    $this->page->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    
  }

}
