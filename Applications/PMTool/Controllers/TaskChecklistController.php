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
    // Set $current_project for breadcrumb
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
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

    \Applications\PMTool\Helpers\TaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\TaskTabKeys::ChecklistTab);
    $sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());

    //**********
    //Get all checklist data
    $all_checklist = \Applications\PMTool\Helpers\TaskChecklistHelper::GetAllChecklistsfor($sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id(), $this);
    //\Applications\PMTool\Helpers\CommonHelper::pr($all_checklist);
    $data_all_checklists = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "task_check_list",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => $all_checklist,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\TaskCheckListHelper::SetPropertyNamesForDualList("task_check_list")
    );

    $this->page()->addVar(
            "data_all_checklists", $data_all_checklists);
    //**********

    //Fetch alert box data
    $alert_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"TaskChecklist", 
            "targetaction": "uploadList", "operation": ["addUniqueCheck", "deleteCheckList"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $alert_msg);

    //Fetch prompt box data from xml and pass to view as an array
    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"TaskChecklist", "targetaction": "uploadList", "operation": ["edit"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);


    $data = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module())
    );

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);
    //tab status
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, \Applications\PMTool\Helpers\TaskHelper::GetTabsStatus($this->user()));
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);

    
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

  public function executeAddCheckList(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    $task_id = $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id();

    //Call the helper which actually adds the checklist
    $result = \Applications\PMTool\Helpers\TaskChecklistHelper::AddChecklist($this, $result, $task_id);

    $this->SendResponseWS(
      $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::TaskChecklist,
        "resx_key" => $this->action(),
        "step" => ($result['rec_count'] >= 1) ? "success" : "error"
      )
    );    
  }

  public function executeDelCheckList(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();    

    $is_deleted = \Applications\PMTool\Helpers\TaskChecklistHelper::DelChecklist($this, $this->dataPost);

    $this->SendResponseWS(
      $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::TaskChecklist,
        "resx_key" => $this->action(),
        "step" => ($is_deleted) ? "success" : "error"
      )
    ); 
  }

  public function executeCheckDuplicateCheckList(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();    

    $is_duplicate = \Applications\PMTool\Helpers\TaskChecklistHelper::IsDuplicateCheckList($this, $this->dataPost);

    $this->SendResponseWS(
      $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::TaskChecklist,
        "resx_key" => $this->action(),
        "step" => ($is_duplicate) ? "error" : "success"
      )
    ); 
  }

  public function executeEditCheckList(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();    

    $is_edited = \Applications\PMTool\Helpers\TaskChecklistHelper::UpdateCheckList($this, $this->dataPost);

    $this->SendResponseWS(
      $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::TaskChecklist,
        "resx_key" => $this->action(),
        "step" => ($is_edited) ? "success" : "error"
      )
    );
  }

  public function executeGetCheckList(\Library\HttpRequest $rq) {
    //Init result
    $result = $this->InitResponseWS();
    $error = true;
    //Get current task
    $currSessTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    $task_id = $currSessTask['task_info_obj']->task_id();

    $checklist = new \Applications\PMTool\Models\Dao\Task_check_list();
    $checklist->setTask_id($task_id);
    $manager = $this->managers()->getManagerOf('TaskChecklist');
    $checklists = $manager->selectMany($checklist, 'task_id');
    if(count($checklists)>0) {
      $result[\Library\Enums\SessionKeys::TaskChecklist]=$checklists;
      $error = false;
    }

    $this->SendResponseWS(
      $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::TaskChecklist,
        "resx_key" => $this->action(),
        "step" => $error ? "error" : "success"
      )
    );
  }

  public function executeSetStatusCheckList(\Library\HttpRequest $rq) {
    //Init result
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();
    $error = true;
    //Get current task
    $currSessTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    $task_id = $currSessTask['task_info_obj']->task_id();

    $checklist = new \Applications\PMTool\Models\Dao\Task_check_list();
    $checklist->setTask_check_list_id($dataPost['id']);
    $manager = $this->managers()->getManagerOf('TaskCheckList');
    $checklists = $manager->selectMany($checklist, 'task_check_list_id');
    if(count($checklists)>0) {
      $checklist = $checklists[0];
      //check if checklist is part of the current task
      if($checklist->task_id() == $task_id) {
        $checklist->setTask_check_list_complete($dataPost['complete']);
        $result_edit = $manager->edit($checklist, 'task_check_list_id');
      }
    }

    if(isset($result_edit) && $result_edit) {
      $error = false;
    }

    $this->SendResponseWS(
      $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::TaskChecklist,
        "resx_key" => $this->action(),
        "step" => $error ? "error" : "success"
      )
    );
  }

}
