<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskTechnicianController extends \Library\BaseController {

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = \Applications\PMTool\Helpers\TechnicianHelper::UpdateTaskTechnicians($this);
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
        "resx_key" => $this->action(),
        "step" => ($result["rows_affected"] === count($result["technician_ids"])) ? "success" : "error"
    ));
  }

  public function executeManageTechnicians(\Library\HttpRequest $rq) {
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

    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"taskTechnician", "targetaction": "list", "targetattr": ["active-taskTechnician-header","inactive-taskTechnician-header"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    \Applications\PMTool\Helpers\TaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\TaskTabKeys::TechniciansTab);
    $sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $pm_technicians = \Applications\PMTool\Helpers\TechnicianHelper::GetPmTechnicians($this, $sessionPm);
    $task_technicians = \Applications\PMTool\Helpers\TechnicianHelper::GetAndStoreTaskTechnicians($this, $sessionTask);
    // filter the pm technicians after we retrieve the task technicians
    $pm_technicians = \Applications\PMTool\Helpers\TechnicianHelper::FilterTechniciansToExcludeTaskTechnicians($pm_technicians, $task_technicians);

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentPm, $sessionPm[\Library\Enums\SessionKeys::PmObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
    $this->page->addVar("HasItemsToDisplay", \Applications\PMTool\Helpers\PmHelper::DoesPmHaveActiveTechnicians($this->user()));
    $data_left = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $task_technicians,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower("technician"))
    );
    $data_right = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_right => $pm_technicians,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower("technician"))
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data_left, $data_left);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data_right, $data_right);

    //tab status
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, \Applications\PMTool\Helpers\TaskHelper::GetTabsStatus($this->user()));
    //form modules
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

}
