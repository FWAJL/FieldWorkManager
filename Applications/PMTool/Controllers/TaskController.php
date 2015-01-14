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
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::SetCurrentSessionTask($this->user(), NULL, $rq->getData("task_id"));
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
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
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    
    if(!\Applications\PMTool\Helpers\TaskHelper::UserHasTasks($this->user(), 0)) {
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
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::active_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::active_list]);
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::inactive_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::inactive_list]);
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

}
