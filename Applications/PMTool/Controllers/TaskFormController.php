<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskFormController extends \Library\BaseController {

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    $result["rows_affected"] = 0;
    //Get the service objects from ids received
    $user_form_ids = str_getcsv($this->dataPost["userFormIds"], ',');
    $master_form_ids = str_getcsv($this->dataPost["masterFormIds"], ',');
    $masterForms = $sessionProject[\Library\Enums\SessionKeys::ProjectAvailableForms][\Library\Enums\SessionKeys::ProjectMasterForms];
    $userForms = $sessionProject[\Library\Enums\SessionKeys::ProjectAvailableForms][\Library\Enums\SessionKeys::ProjectUserForms];
    $matchedMasterFormElements = $this->FindObjectsFromIds(
      array(
        "filter" => "form_id",
        "ids" => $master_form_ids,
        "objects" => $masterForms)
    );
    $matchedUserFormElements = $this->FindObjectsFromIds(
      array(
        "filter" => "form_id",
        "ids" => $user_form_ids,
        "objects" => $userForms)
    );
    $task_id = $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id();
    foreach ($matchedMasterFormElements as $form) {
      $manager = $this->managers->getManagerOf("TaskForm");
      $taskForm = new \Applications\PMTool\Models\Dao\Task_template_form();
      $taskForm->setTask_id($task_id);
      $taskForm->setMaster_form_id($form->form_id());
      $taskForm->setUser_form_id(null);
      if($this->dataPost["action"]=="add") {
        $manager->add($taskForm);
        $result["rows_affected"] += 1;
      } else if($this->dataPost["action"]=="remove") {
        $returnRemove = $manager->deleteByFilters($taskForm,array("task_id"=>$task_id,"master_form_id"=>$form->form_id()));
        $result["rows_affected"] += $returnRemove ? 1 : 0;
      }
    }

    foreach ($matchedUserFormElements as $form) {
      $manager = $this->managers->getManagerOf("ProjectForm");
      $taskForm = new \Applications\PMTool\Models\Dao\Task_template_form();
      $taskForm->setTask_id($task_id);
      $taskForm->setMaster_form_id(null);
      $taskForm->setUser_form_id($form->form_id());
      if($this->dataPost["action"]=="add") {
        $returnAdd = $manager->add($taskForm);
        $result["rows_affected"] += $returnAdd >= 0 ? 1 : 0;
      } else if($this->dataPost["action"]=="remove") {
        $returnRemove = $manager->deleteByFilters($taskForm,array("task_id"=>$task_id,"user_form_id"=>$form->form_id()));
        $result["rows_affected"] += $returnRemove? 1 : 0;
      }
    }
    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
      "resx_key" => $this->action(),
      "step" => ($result["rows_affected"] > 0) ? "success" : "error"
    ));

  }

  public function executeManageForms(\Library\HttpRequest $rq) {
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

    \Applications\PMTool\Helpers\TaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\TaskTabKeys::FormsTab);
    $sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    //$pm_services = \Applications\PMTool\Helpers\ServiceHelper::GetPmServices($this, $sessionPm);
    $masterForms = \Applications\PMTool\Helpers\FormHelper::GetMasterForms($this,$sessionProject);
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $userForms = \Applications\PMTool\Helpers\FormHelper::GetUserForms($this,$sessionProject);
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    \Applications\PMTool\Helpers\FormHelper::GetProjectForms($this,$sessionProject);
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $projectForms = \Applications\PMTool\Helpers\FormHelper::GetFormsFromProjectForms($this->user(),$sessionProject);

    $taskForms = \Applications\PMTool\Helpers\FormHelper::GetTaskForms($this,$sessionTask);
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    if(isset($projectForms[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::master_forms]) && !empty($projectForms[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::master_forms])){
      $filteredMasterForms = \Applications\PMTool\Helpers\FormHelper::FilterFormsToExclude($projectForms[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::master_forms],$taskForms,'master_form_id');
    }
    if(isset($projectForms[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_forms]) && !empty($projectForms[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_forms])){
      $filteredUserForms = \Applications\PMTool\Helpers\FormHelper::FilterFormsToExclude($projectForms[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_forms],$taskForms,'user_form_id');
    }
    $taskForms = \Applications\PMTool\Helpers\FormHelper::GetFormsFromTaskForms($this->user(),$sessionProject, $sessionTask);

    //$this->page->addVar("HasItemsToDisplay", \Applications\PMTool\Helpers\PmHelper::DoesPmHaveActiveServices($this->user()));

    if(!empty($filteredMasterForms)){
      $filteredMasterForms  = array(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::master_forms=>$filteredMasterForms);
    } else {
      $filteredMasterForms = array();
    }

    if(!empty($filteredUserForms)){
      $filteredUserForms = array(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_forms=>$filteredUserForms);
    } else {
      $filteredUserForms = array();
    }
    $templateForms = array_merge($filteredUserForms,$filteredMasterForms);

    $data = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_right => $templateForms,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_left => $taskForms,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => \Applications\PMTool\Helpers\FormHelper::SetPropertyNamesForDualList(),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => \Applications\PMTool\Helpers\FormHelper::SetPropertyNamesForDualList()
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

    //tab status
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, \Applications\PMTool\Helpers\TaskHelper::GetTabsStatus($this->user()));
    //form modules
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentPm, $sessionPm[\Library\Enums\SessionKeys::PmObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
  }
}