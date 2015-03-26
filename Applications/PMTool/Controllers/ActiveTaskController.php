<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ActiveTaskController extends \Library\BaseController {

  public function executeIndex(\Library\HttpRequest $rq) {
    $currentTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    if ($currentTask !== NULL) {
      $this->Redirect(
              \Library\Enums\ResourceKeys\UrlKeys::ActiveTaskShowForm
              . "task_id="
              . $currentTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
    } else {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ActiveTaskShowForm);
    }
  }

  public function executeShowForm(\Library\HttpRequest $rq) {
    \Applications\PMTool\Helpers\ActiveTaskHelper::AddTabsStatus($this->user());
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->user());
    //Check if a project needs to be selected in order to display this page
    if (!$sessionProject) {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::TaskAddPrompt);
    }
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::SetCurrentSessionTask($this->user(), NULL, $rq->getData("task_id"));
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);

	/*
    //Fetch prompt box data from xml and pass to view as an array
    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"task", "targetaction": "view", "operation": ["addNullCheck","addNullCheckForCopy"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);

    //Fetch alert box data
    $alert_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"task", "targetaction": "view", "operation": ["addUniqueCheck"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $alert_msg);
	*/
	
	//Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"activeTask", "targetaction": "showForm", "targetattr": ["h4-taskstatus-leftcol-gi", "h4-taskstatus-rightcol-gi"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::activeTaskTabStatus, \Applications\PMTool\Helpers\ActiveTaskHelper::GetTabsStatus($this->app()->user()));
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeMap(\Library\HttpRequest $rq) {
	$sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->user());
    //Check if a project needs to be selected in order to display this page
    if (!$sessionProject) {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::TaskAddPrompt);
    }
	$sessionTask = \Applications\PMTool\Helpers\TaskHelper::SetCurrentSessionTask($this->user(), NULL, $rq->getData("task_id"));
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
	
	\Applications\PMTool\Helpers\ActiveTaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskMapTab);
	
	$this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::activeTaskTabStatus, \Applications\PMTool\Helpers\ActiveTaskHelper::GetTabsStatus($this->app()->user()));
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }
  
  public function executeForms(\Library\HttpRequest $rq) {
	$sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->user());
    //Check if a project needs to be selected in order to display this page
    if (!$sessionProject) {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::TaskAddPrompt);
    }
	$sessionTask = \Applications\PMTool\Helpers\TaskHelper::SetCurrentSessionTask($this->user(), NULL, $rq->getData("task_id"));
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
	
	\Applications\PMTool\Helpers\ActiveTaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskFormsTab);
	
	//Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"activeTask", "targetaction": "forms", "targetattr": ["h4-taskforms-leftcol-gi", "h4-taskforms-rightcol-gi"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);
	
	$this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::activeTaskTabStatus, \Applications\PMTool\Helpers\ActiveTaskHelper::GetTabsStatus($this->app()->user()));
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }
  
  public function executeCommunications(\Library\HttpRequest $rq) {
	$sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->user());
    //Check if a project needs to be selected in order to display this page
    if (!$sessionProject) {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::TaskAddPrompt);
    }
	$sessionTask = \Applications\PMTool\Helpers\TaskHelper::SetCurrentSessionTask($this->user(), NULL, $rq->getData("task_id"));
	//\Applications\PMTool\Helpers\CommonHelper::pr($sessionTask);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
	
	\Applications\PMTool\Helpers\ActiveTaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskCommTab);
	
	//Get current Discussion from session and set for view
	if(isset($_SESSION[\Library\Enums\SessionKeys::CurrentDiscussion]))
		$this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentDiscussions, $_SESSION[\Library\Enums\SessionKeys::CurrentDiscussion]);
	
	//Let's get this task specific services
	$sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $pm_services = \Applications\PMTool\Helpers\ServiceHelper::GetPmServices($this, $sessionPm);
	$task_services = \Applications\PMTool\Helpers\ServiceHelper::GetAndStoreTaskServices($this, $sessionTask);
	// filter the pm services after we retrieve the task services
	$pm_services = \Applications\PMTool\Helpers\ServiceHelper::FilterServicesToExcludeTaskServices($pm_services, $task_services);
	//Categorize the list for showing in the list
	$task_services = \Applications\PMTool\Helpers\ServiceHelper::CategorizeTheList($task_services, "service_type");
	//Set data for frontend
	$data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_left => $task_services,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower("service"))  
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);
	
	
	//Similarly let's get the task specific technicians
	$sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $pm_technicians = \Applications\PMTool\Helpers\TechnicianHelper::GetPmTechnicians($this, $sessionPm);
	$task_technicians = \Applications\PMTool\Helpers\TechnicianHelper::GetAndStoreTaskTechnicians($this, $sessionTask);
	// filter the pm technicians after we retrieve the task technicians
    $pm_technicians = \Applications\PMTool\Helpers\TechnicianHelper::FilterTechniciansToExcludeTaskTechnicians($pm_technicians, $task_technicians);
	$data_left = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $task_technicians,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower("technician"))
    );
	$this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data_left, $data_left);
	
	//Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"activeTask", "targetaction": "communications", "targetattr": ["h4-taskcomm-leftcol-gi", "h4-taskcomm-rightcol-gi","h4-taskcomm-services-gi","h4-taskcomm-technicians-gi"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);
	
	$this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::activeTaskTabStatus, \Applications\PMTool\Helpers\ActiveTaskHelper::GetTabsStatus($this->app()->user()));
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }
  
  public function executeStartCommWith(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $result['success'] = false;
	if($this->dataPost['selection_type'] == 'technician') {
	  foreach($_SESSION[\Library\Enums\SessionKeys::CurrentPm][\Library\Enums\SessionKeys::PmTechnicians] as $technician) {
		if($technician['technician_id'] == $this->dataPost['id']) {
		  $_SESSION[\Library\Enums\SessionKeys::CurrentDiscussion]['comm_with'] = $technician;
		  $_SESSION[\Library\Enums\SessionKeys::CurrentDiscussion]['comm_type'] = $this->dataPost['selection_type'];
		  $result['success'] = true;
		  break;
		}
	  }
	} else {
	  foreach($_SESSION[\Library\Enums\SessionKeys::CurrentPm][\Library\Enums\SessionKeys::PmServices] as $service) {
		if($service['service_id'] == $this->dataPost['id']) {
		  $_SESSION[\Library\Enums\SessionKeys::CurrentDiscussion]['comm_with'] = $service;
		  $_SESSION[\Library\Enums\SessionKeys::CurrentDiscussion]['comm_type'] = $this->dataPost['selection_type'];
		  $result['success'] = true;
		  break;
		}
	  }
	}

    $this->SendResponseWS(
      $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::ActiveTask,
        "resx_key" => $this->action(),
        "step" => ($result['success']) ? "success" : "error"
      )
	);
  }
}