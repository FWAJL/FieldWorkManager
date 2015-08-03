<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskChecklistController extends \Library\BaseController {

  public function executeShowForm(\Library\HttpRequest $rq) {
    $this->page()->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeUploadList(\Library\HttpRequest $rq) {
    $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TaskChecklist);

    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"taskchecklist", "targetaction": "uploadList", "targetattr": ["activequestion-taskchecklist-uploadList-headerH4", "inactivequestion-taskchecklist-uploadList-headerH4"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    if ($tabsStatus === NULL) {
//      \Applications\PMTool\Helpers\TaskChecklistHelper::AddTabsStatus($this->user());
      $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TaskChecklist);
    }
    $this->page()->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, $tabsStatus);
    $this->page()->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());

    \Applications\PMTool\Helpers\TaskChecklistHelper::StoreListsData($this, TRUE);

  }

  

  public function executeListAll(\Library\HttpRequest $rq) {
    $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TaskChecklist);
    if ($tabsStatus === NULL) {
      \Applications\PMTool\Helpers\TaskChecklistHelper::AddTabsStatus($this->user());
      $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TaskChecklist);
    }
    $this->page()->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, $tabsStatus);

    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());

    //Check if a project needs to be selected in order to display this page
    if (!$sessionProject)
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::TaskChecklistListAll);

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"taskchecklist", "targetaction": "list", "targetattr": ["active-fieldtaskchecklist-header","inactive-fieldtaskchecklist-header","active-labtaskchecklist-header","inactive-labtaskchecklist-header"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    //Get confirm msg for taskchecklist deletion from showForm screen
    $confirm_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"taskchecklist", 
                "targetaction": "list", "operation": ["deleteField", "deleteLab", "noTaskChecklistForProject", 
                "noTaskChecklistAvailable", "faExists"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $confirm_msg);

    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    if (!isset($pm[\Library\Enums\SessionKeys::PmFieldTaskChecklists]) &&
            count($pm[\Library\Enums\SessionKeys::PmFieldTaskChecklists]) > 0) {
      \Applications\PMTool\Helpers\TaskChecklistHelper::StoreListsData($this);
    }
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());

    //Fetch prompt box data from xml and pass to view as an array
    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"taskchecklist", "targetaction": "listAll", "operation": ["edit"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);

    //variables for the field taskchecklist module
    $field_object_properties = \Applications\PMTool\Helpers\CommonHelper::SetDynamicPropertyNamesForDualList(
                    "field_taskchecklist", \Applications\PMTool\Helpers\TaskChecklistHelper::GetListPropertiesForFieldTaskChecklist());
    $field_taskchecklists = $pm[\Library\Enums\SessionKeys::PmFieldTaskChecklists];
    $project_field_taskchecklists = \Applications\PMTool\Helpers\TaskChecklistHelper::FilterTaskChecklistsByProjectTaskChecklistsList($this);
    $field_taskchecklists = \Applications\PMTool\Helpers\CommonHelper::FilterObjectsToExcludeRelatedObject($field_taskchecklists, $project_field_taskchecklists, "field_taskchecklist_id");

    $data_field_taskchecklist = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "fieldtaskchecklist",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_right => $field_taskchecklists,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $project_field_taskchecklists,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => $field_object_properties,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => $field_object_properties
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\TaskChecklist::data_field_taskchecklist, $data_field_taskchecklist);

    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = \Applications\PMTool\Helpers\TaskChecklistHelper::UpdateProjectTaskChecklists($this);

    $tabsStatus = \Applications\PMTool\Helpers\CommonHelper::GetTabsStatus($this->user(), \Library\Enums\SessionKeys::TaskChecklist);

    \Applications\PMTool\Helpers\CommonHelper::SetActiveTab(
            $this->user(), \Applications\PMTool\Resources\Enums\TaskChecklistTabKeys::FieldTab, \Library\Enums\SessionKeys::TaskChecklist);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::FieldTaskChecklist,
        "resx_key" => $this->action(),
        "step" => ($result["rows_affected"] === count($result["arrayOfValues"])) ? "success" : "error"
    ));
  }
  }
