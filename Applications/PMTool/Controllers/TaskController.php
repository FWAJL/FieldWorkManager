<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskController extends \Library\BaseController {

  public function executeIndex(\Library\HttpRequest $rq) {
    if (!\Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user())) {
      header('Location: ' . __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ProjectsRootUrl);
    }
    $this->executeGetList($rq, NULL, FALSE);
    if (\Applications\PMTool\Helpers\TaskHelper::UserHasTasks($this->app()->user(), 0)) {
      if ($rq->getData("target") !== "") {
        header('Location: ' . __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::TaskListAll);
      } else {
        header('Location: ' . __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::TaskRootUrl . "/" . $rq->getData("target"));
      }
    }
  }

  public function executeShowForm(\Library\HttpRequest $rq) {
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
//    $sessionProject = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::CurrentProject);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    if ($rq->getData("mode") === "edit") {
      $this->page->addVar("task_editing_header", $this->resxData["task_legend_edit"]);
    } else {
      $this->page->addVar("task_editing_header", $this->resxData["task_legend_add"]);
    }
    //Which module?
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeListAll(\Library\HttpRequest $rq) {
    //Get list of task stored in session
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

    $tasks = \Applications\PMTool\Helpers\TaskHelper::GetSessionTasks($this->app()->user());
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => $tasks,
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

    $tasks = array();
    if (array_key_exists("names", $this->dataPost())) {
      $tasks = $this->_PrepareManyTaskObjects();
    } else {
      array_push($tasks, $this->_PrepareTaskObject($this->dataPost()));
    }
    $result["dataIn"] = $tasks;

    $result["dataOut"] = 0;
    foreach ($tasks as $task) {
      $result["dataOut"] = $manager->add($task);
      $task->setTask_id("$result[dataOut]"); 
      array_push($sessionProject[\Library\Enums\SessionKeys::ProjectTasks], $task);
    }
    if ($result["dataOut"]) {
      \Applications\PMTool\Helpers\CommonHelper::SetUserSessionProject($this->app()->user(), $sessionProject);
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
    $sessionProject = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::CurrentProject);

    //Init PDO
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $task = $this->_PrepareTaskObject($this->dataPost());
    $result["data"] = $task;

    $manager = $this->managers->getManagerOf($this->module());
    $result_edit = $manager->edit($task);

    //Clear the task and facility list from session for the connect PM
    if ($result_edit) {
      $sessionProject[\Library\Enums\SessionKeys::ProjectTasks] = array();
      \Applications\PMTool\Helpers\CommonHelper::SetUserSessionProject($this->app()->user(), $sessionProject);
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
    $sessionProject = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::CurrentProject);
    $db_result = FALSE;
    $task_id = intval($this->dataPost["task_id"]);

    //Check if the task to be deleted if the Task manager's
    $task_selected = $this->_GetTaskFromSession($task_id);
    //Load interface to query the database
    if ($task_selected !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($task_id);
      if ($db_result) {
        $sessionProject[\Library\Enums\SessionKeys::ProjectTasks] = array();
        \Applications\PMTool\Helpers\CommonHelper::SetUserSessionProject($this->app()->user(), $sessionProject);
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
    if ($sessionTask === NULL) {
      //Load interface to query the database for tasks
      $project = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
      $manager = $this->managers->getManagerOf($this->module);
      \Applications\PMTool\Helpers\TaskHelper::StoreSessionTask($this->app()->user(), $manager->selectMany($project[\Library\Enums\SessionKeys::ProjectObject]));
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

    $task_selected = $this->_GetTaskFromSession($task_id);

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
    $sessionProject = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::CurrentProject);
    $tasks = $sessionProject[\Library\Enums\SessionKeys::ProjectTasks];
    $matchedElements = $this->FindObjectsFromIds(
            array(
                "filter" => "task_id",
                "ids" => $task_ids,
                "objects" => $tasks)
    );

    foreach ($matchedElements as $task) {
      $task->setTask_active($this->dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $this->managers->getManagerOf($this->module);
      $rows_affected += $manager->edit($task) ? 1 : 0;
    }
    \Applications\PMTool\Helpers\CommonHelper::SetUserSessionProject($this->app()->user(), $sessionProject);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Task,
        "resx_key" => $this->action(),
        "step" => ($rows_affected === count($task_ids)) ? "success" : "error"
    ));
  }

  private function _GetAndStoreTasksInSession($sessionProject) {
    $lists = array();
    if (count($sessionProject[\Library\Enums\SessionKeys::ProjectTasks]) === 0) {
      $this->executeGetList(NULL, $sessionProject, false);
    } else {
      //The tasks are already in Session
    }
  }

  private function _PrepareTaskObject($data_sent) {
    $task = new \Applications\PMTool\Models\Dao\Task();
    $task->setProject_id($data_sent["project_id"]);
    $task->setTask_id(!array_key_exists('task_id', $data_sent) ? NULL : $data_sent["task_id"]);
    $task->setTask_name(!array_key_exists('task_name', $data_sent) ? NULL : $data_sent["task_name"]);
    $task->setTask_deadline(!array_key_exists('task_deadline', $data_sent) ? "" : $data_sent["task_deadline"]);
    $task->setTask_instructions(!array_key_exists('task_instructions', $data_sent) ? "" : $data_sent["task_instructions"]);
    $task->setTask_trigger_cal(!array_key_exists('task_trigger_cal', $data_sent) ? "" : $data_sent["task_trigger_cal"]);
    $task->setTask_trigger_pm(!array_key_exists('task_trigger_pm', $data_sent) ? "" : $data_sent["task_trigger_pm"]);
    $task->setTask_trigger_ext(!array_key_exists('task_trigger_ext', $data_sent) ? "" : $data_sent["task_trigger_ext"]);
//    $task->setTask_active(!array_key_exists('task_active', $data_sent) ? 0 : ($data_sent["task_active"] === "1"));

    return $task;
  }

  private function _PrepareManyTaskObjects() {
    $tasks = array();
    $task_names = \Applications\PMTool\Helpers\CommonHelper::StringToArray("\n", $this->dataPost["names"]);
    foreach ($task_names as $name) {
      $task = new \Applications\PMTool\Models\Dao\Task();
      $task->setProject_id($this->dataPost["project_id"]);
      $task->setTask_name($name);
      array_push($tasks, $task);
    }
    return $tasks;
  }

  private function _GetTaskFromSession($task_id) {
    $taskMatch = NULL;
    $sessionProject = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::CurrentProject);
    $tasks = $sessionProject[\Library\Enums\SessionKeys::ProjectTasks];
    foreach ($tasks as $task) {
      if (intval($task->task_id()) === $task_id) {
        $taskMatch = $task;
        break;
      }
    }
    return $taskMatch;
  }

}
