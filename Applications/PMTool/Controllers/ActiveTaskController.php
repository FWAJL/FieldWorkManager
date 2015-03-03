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
    \Applications\PMTool\Helpers\TaskHelper::AddTabsStatus($this->user());
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->user());
    //Check if a project needs to be selected in order to display this page
    if (!$sessionProject) {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::TaskAddPrompt);
    }
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::SetCurrentSessionTask($this->user(), NULL, $rq->getData("task_id"));
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);
//
//    //Fetch prompt box data from xml and pass to view as an array
//    $prompt_msg = \Applications\PMTool\Helpers\PopUpHelper::getPromptBoxMsg('{"targetcontroller":"task", "targetaction": "view", "operation": ["addNullCheck","addNullCheckForCopy"]}', $this->app->name());
//    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::prompt_message, $prompt_msg);
//
//    //Fetch alert box data
//    $alert_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"task", "targetaction": "view", "operation": ["addUniqueCheck"]}', $this->app->name());
//    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $alert_msg);
//
      $this->page->addVar("active_task_header", $this->resxData["active_task_header"]);
//    $this->page->addVar(
//            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, \Applications\PMTool\Helpers\TaskHelper::GetTabsStatus($this->app()->user()));
//    $this->page->addVar(
//            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeListAll(\Library\HttpRequest $rq) {
   
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

  }

  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result

  }

  public function executeDelete(\Library\HttpRequest $rq) {
    // Init result

  }

  public function executeGetList(\Library\HttpRequest $rq = NULL, $sessionTask = NULL, $isAjaxCall = FALSE) {
    // Init result
  }

  public function executeGetItem(\Library\HttpRequest $rq) {
    // Init result

  }

  public function executeUpdateItems(\Library\HttpRequest $rq) {

  }

  public function executeIfTaskExists(\Library\HttpRequest $rq) {


}
}