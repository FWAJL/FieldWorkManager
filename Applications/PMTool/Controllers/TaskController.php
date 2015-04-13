<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskController extends \Library\BaseController {

  public function executeIndex(\Library\HttpRequest $rq) {
    $currentTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    if ($currentTask !== NULL) {
      $this->Redirect(
              \Library\Enums\ResourceKeys\UrlKeys::TaskShowForm
              . "?mode=edit&task_id="
              . $currentTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
    } else {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::TaskShowForm . "?mode=add");
    }
  }

  public function executeShowForm(\Library\HttpRequest $rq) {
    \Applications\PMTool\Helpers\TaskHelper::AddTabsStatus($this->user());
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->user());
    //Check if a project needs to be selected in order to display this page
    if (!$sessionProject) {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::TaskAddPrompt);
    }
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::SetCurrentSessionTask($this->user(), NULL, $rq->getData("task_id"));

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

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);

    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"task", "targetaction": "showForm", "targetattr": ["question-task-showForm-manual-box", "question-task-showForm-external-box"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    //Fetch prompt box data from xml and pass to view as an array
    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"task", "targetaction": "view", "operation": ["addNullCheck","addNullCheckForCopy"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);

    //Fetch alert box data
    $alert_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"task", "targetaction": "view", "operation": ["addUniqueCheck","delete"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $alert_msg);

    if ($rq->getData("mode") === "edit") {
      $this->page->addVar("task_editing_header", $this->resxData["task_legend_edit"]);
    } else {
      $this->page->addVar("task_editing_header", $this->resxData["task_legend_add"]);
    }
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, \Applications\PMTool\Helpers\TaskHelper::GetTabsStatus($this->app()->user()));
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeListAll(\Library\HttpRequest $rq) {
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());

    //Check if a project needs to be selected in order to display this page
    if (!$sessionProject)
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::TaskListAll);

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

    if (!\Applications\PMTool\Helpers\TaskHelper::UserHasTasks($this->user(), 0)) {
      $this->executeGetList($rq, NULL, FALSE);
    }
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => \Applications\PMTool\Helpers\TaskHelper::GetFilteredTaskObjectsList($this->app()->user()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower($this->module()))
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
	//Set passed task as current task

	if($rq->getData("task_id") !== '' && !is_null($rq->getData("task_id"))) {
	  $sessionTask = \Applications\PMTool\Helpers\TaskHelper::SetCurrentSessionTask($this->user(), NULL, $rq->getData("task_id"));
	  //check if we passed a redirect URL too
	  if($rq->getData("onSuccess") !== '' && !is_null($rq->getData("onSuccess"))) {
	  //rediect to it
	    $this->Redirect($rq->getData("onSuccess"));
	  }
	}
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);


    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"task", "targetaction": "list", "targetattr": ["active-task-header","inactive-task-header"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    //Get confirm msg for project deletion from context menu
    $confirm_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"task", "targetaction": "list", "operation": ["activate","addUniqueCheck"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $confirm_msg);

    //Fetch prompt box data from xml and pass to view as an array
    //Also let's just fetch the message for the showForm view and reuse it
    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"task", "targetaction": "view", "operation": ["addNullCheck","addNullCheckForCopy"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);

    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::active_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::active_list]);
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::inactive_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::inactive_list]);
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::promote_buttons, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::promote_buttons]);
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariables\Popup::popup_msg, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_msg]);
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_msg, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_prompt]);
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message_module, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::tooltip_msg]);
  }
  
  /**
   * Method that loads the select project view for controller
   * This is when any other action of any other controller
   * is selected but that is dependent on a project which is
   * already selected. What this action does is, lets the user
   * know if the action they selected is dependent on a project
   * and let's them select a project. If a project is already 
   * selected, it just redirects to the actual action user 
   * wanted to visit.
   *
   * @param \Library\HttpRequest $rq: the request
   */
  public function executeSelectTask(\Library\HttpRequest $rq) {
	
	
    //Fetch prompt box data from xml and pass to view as an array
    $urlParts = explode("/", $rq->getData('onSuccess'));
    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"' . $urlParts[0] . '", "targetaction": "' . $urlParts[1] . '", "operation": ["checkCurrentTask"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::redirect_on_success, $rq->getData('onSuccess'));
	
	if (!\Applications\PMTool\Helpers\TaskHelper::UserHasTasks($this->user(), 0)) {
      $this->executeGetList($rq, NULL, FALSE);
    }
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => \Applications\PMTool\Helpers\TaskHelper::GetFilteredTaskObjectsList($this->app()->user()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower($this->module()))
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);
	
	
	$modules = $this->app()->router()->selectedRoute()->phpModules();
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariables\Popup::popup_prompt_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::active_list]);
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariables\Popup::popup_msg, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_msg]);
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_msg, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_selector_module]);
  }

  public function executeAddPrompt(\Library\HttpRequest $rq) {
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    //Check if a project needs to be selected in order to display this page
    if (!$sessionProject)
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::TaskAddPrompt);

    if (!\Applications\PMTool\Helpers\TaskHelper::UserHasTasks($this->user(), 0)) {
      $this->executeGetList($rq, NULL, FALSE);
    }

    //Fetch prompt box data from xml and pass to view as an array
    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"task", "targetaction": "addPrompt", "operation": ["addNullCheckAddPrompt"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);

    //Fetch alert box data
    $alert_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"task", "targetaction": "view", "operation": ["addUniqueCheck"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $alert_msg);

    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeAdd(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module);
    $this->dataPost["project_id"] = $sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id();

    $task = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Task());

    $result["dataIn"] = $task;

    $result["dataOut"] = $manager->add($task);
    $task->setTask_id($result["dataOut"]);
    array_push($sessionProject[\Library\Enums\SessionKeys::ProjectTasks], \Library\Enums\SessionKeys::TaskKey . $task->task_id());

    if ($result["dataOut"] !== NULL) {
      \Applications\PMTool\Helpers\TaskHelper::AddSessionTask($this->app()->user(), $task);
      \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($this->app()->user(), $sessionProject);
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
        "resx_key" => $this->action(),
        "step" => $result["dataOut"] > 0 ? "success" : "error"
    ));
  }

  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetSessionTask($this->app()->user(), $this->dataPost["task_id"]);
    \Applications\PMTool\Helpers\TaskHelper::FillSessionTask($this, $sessionTask);
    //Init PDO
    $task = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Task());
    $result["data"] = $task;

    $manager = $this->managers->getManagerOf($this->module());
    $result_edit = $manager->edit($task, "task_id");

    //Clear the task and facility list from session for the connect PM
    if ($result_edit) {
      $sessionTask[\Library\Enums\SessionKeys::TaskObj] = $task;
      \Applications\PMTool\Helpers\TaskHelper::SetSessionTask($this->app()->user(), $sessionTask);
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
        "resx_key" => $this->action(),
        "step" => $result_edit ? "success" : "error"
    ));
  }

  public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $db_result = FALSE;
    $task_id = intval($this->dataPost["task_id"]);

    //Check if the task to be deleted if the Task manager's
    $task_selected = \Applications\PMTool\Helpers\TaskHelper::GetSessionTask($this->app()->user(), $task_id);
    //Load interface to query the database
    if ($task_selected !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      if ($manager->delete($task_selected[\Library\Enums\SessionKeys::TaskObj], "task_id")) {
        $sessionTasks = \Applications\PMTool\Helpers\TaskHelper::GetSessionTasks($this->app()->user());
        unset($sessionTasks[\Library\Enums\SessionKeys::TaskKey . $task_id]);
        \Applications\PMTool\Helpers\TaskHelper::SetSessionTasks($this->app()->user(), $sessionTasks);

        $index = \Applications\PMTool\Helpers\CommonHelper::FindIndexInIdListById(
                        (\Library\Enums\SessionKeys::TaskKey . $task_id), $sessionProject[\Library\Enums\SessionKeys::ProjectTasks]);
        $db_result = $index === NULL ? FALSE : TRUE;
        unset($sessionProject[\Library\Enums\SessionKeys::ProjectTasks][$index]);
        \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($this->app()->user(), $sessionProject);
        $currentSessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->app()->user());
        if($currentSessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id() == $task_id){
          \Applications\PMTool\Helpers\TaskHelper::UnsetCurrentSessionTask($this->app()->user());
        }
      }
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
        "resx_key" => $this->action(),
        "step" => $db_result !== FALSE ? "success" : "error"
    ));
  }

  public function executeGetList(\Library\HttpRequest $rq = NULL, $sessionTask = NULL, $isAjaxCall = FALSE) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $list = array();
    $project = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    if ($sessionTask === NULL) {
      //Load interface to query the database for tasks
      $task = new \Applications\PMTool\Models\Dao\Task();
      $task->setProject_id($project[\Library\Enums\SessionKeys::ProjectObject]->project_id());
      $manager = $this->managers->getManagerOf($this->module);
      \Applications\PMTool\Helpers\TaskHelper::StoreSessionTask(
              $this->app()->user(), $manager->selectMany($task, "project_id"));
    }
    if ($isAjaxCall) {
      $step_result = $result[\Library\Enums\SessionKeys::ProjectTasks] !== NULL ? "success" : "error";
      $this->SendResponseWS(
              $result, array(
          "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
          "resx_key" => $this->action(),
          "step" => $step_result
      ));
    }
  }

  public function executeGetItem(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $task_id = intval($this->dataPost["task_id"]);

    $task_selected = \Applications\PMTool\Helpers\TaskHelper::GetSessionTask($this->app()->user(), $task_id);
    \Applications\PMTool\Helpers\TaskHelper::SetSessionTask($this->user(), $task_selected);

    $result["task"] = $task_selected;
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
        "resx_key" => $this->action(),
        "step" => ($task_selected !== NULL) ? "success" : "error"
    ));
  }

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $rows_affected = 0;
    //Get the task objects from ids received
    $task_ids = str_getcsv($this->dataPost["task_ids"], ',');
    $sessionTasks = \Applications\PMTool\Helpers\TaskHelper::GetSessionTasks($this->app()->user());

    foreach ($task_ids as $id) {
      $task = $sessionTasks[\Library\Enums\SessionKeys::TaskKey . $id][\Library\Enums\SessionKeys::TaskObj];
      $task->setTask_active($this->dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $this->managers->getManagerOf($this->module);
      $rows_affected += $manager->edit($task, "task_id") ? 1 : 0;
    }
    \Applications\PMTool\Helpers\TaskHelper::SetSessionTasks($this->app()->user(), $sessionTasks);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
        "resx_key" => $this->action(),
        "step" => ($rows_affected === count($task_ids)) ? "success" : "error"
    ));
  }

  public function executeIfTaskExists(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $taskSession = \Applications\PMTool\Helpers\TaskHelper::GetSessionTasks($this->user());

    $task = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Task());

    $match = \Applications\PMTool\Helpers\CommonHelper::FindObjectByStringValue(
                    $task->task_name(), "task_name", $taskSession, \Library\Enums\SessionKeys::TaskObj
    );

    $result['record_count'] = (!$match || empty($match)) ? 0 : 1;

    $this->SendResponseWS(
      $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
        "resx_key" => $this->action(),
        "step" => ($result['record_count'] > 0) ? "success" : "error"
      )
	);
  }

}
