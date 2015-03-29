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
	
	//\Applications\PMTool\Helpers\CommonHelper::pr($_SESSION);

	//Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"activeTask", "targetaction": "showForm", "targetattr": ["h4-taskstatus-leftcol-gi", "h4-taskstatus-rightcol-gi", "h4-taskstatus-notes-gi", "h4-taskstatus-notesrecord-gi"]}', $this->app->name());
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
  
  public function executePostNote(\Library\HttpRequest $rq) {
	$result = $this->InitResponseWS(); // Init result
	
	$currSessTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
	
	//Prepare data object
	$userConnected = \Applications\PMTool\Helpers\UserHelper::GetUserConnectedSession($this->user());
	$data = array('task_id' => $currSessTask['task_info_obj']->task_id(), 'task_note_category_type' => $userConnected->user_type(), 'task_note_category_value' => $userConnected->user_id(), 'task_note_value' => $this->dataPost['note']);
	
	//Init PDO
    $task_note = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($data, new \Applications\PMTool\Models\Dao\Task_note());
	
    $result["data"] = $task_note;

    $manager = $this->managers->getManagerOf($this->module());
    $result_save = $manager->add($task_note);
	
	$this->SendResponseWS(
      $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::ActiveTask,
        "resx_key" => $this->action(),
        "step" => ($result_save) ? "success" : "error"
      )
	);
  }
  
  public function executeGetNotes(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result
	//Get current task
	$currSessTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
	
	//Init data structure
	$data['task_id'] = $currSessTask['task_info_obj']->task_id();
	
	//Init PDO
    $task_note = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($data, new \Applications\PMTool\Models\Dao\Task_note());
	$manager = $this->managers->getManagerOf($this->module());
    $result_note = $manager->selectMany($task_note, "task_id");
	
	$result_get = 0;
	
	if(!empty($result_note)) {
	  $user_arr = array();
	  foreach($result_note as $note_key => $note_obj) {
		$datauser = null;
		//Fetch user details who posted that note
		if($note_obj->task_note_category_type() == 'pm_id') {
		  //Project Manager
		  //Init data structure
		  $datauser['pm_id'] = $note_obj->task_note_category_value();
			
		  //Init PDO
	      $pm_user = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($datauser, new \Applications\PMTool\Models\Dao\Project_manager());
		  $manager = $this->managers->getManagerOf($this->module());
		  $result_pm_user = $manager->selectMany($pm_user, "pm_id");
		  
		  //Stuff into main array
		  array_push($user_arr, $result_pm_user[0]->pm_name());
		  
		}
		else {
		  //Technician
		  //Init data structure
		  $datauser['technician_id'] = $note_obj->task_note_category_value();
			
		  //Init PDO
	      $tech_user = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($datauser, new \Applications\PMTool\Models\Dao\Technician());
		  $manager = $this->managers->getManagerOf($this->module());
		  $result_tech_user = $manager->selectMany($tech_user, "technician_id");
		  
		  //Stuff into main array
		  array_push($user_arr, $result_tech_user[0]->technician_name());
		}
	  }
	  $result_get = 1;
	}
	
	//cleanup into a nice array
	$result['notes'] = $result_note;
	$result['users'] = $user_arr;
	
		
	//\Applications\PMTool\Helpers\CommonHelper::pr($user_arr);
	
	$this->SendResponseWS(
      $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::ActiveTask,
        "resx_key" => $this->action(),
        "step" => ($result_get) ? "success" : "error"
      )
	);
  }
  
}