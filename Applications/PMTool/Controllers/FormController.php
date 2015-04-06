<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class FormController extends \Library\BaseController {

//    Copied from Service Controller - not fully modified

  public function executeIndex(\Library\HttpRequest $rq) {  }

  public function executeShowForm(\Library\HttpRequest $rq) {
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    //Load Modules for view
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeShowFormMaster(\Library\HttpRequest $rq) {
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeListAll(\Library\HttpRequest $rq) {
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

    $masterForms = \Applications\PMTool\Helpers\FormHelper::GetMasterForms($this,$sessionProject);
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $userForms = \Applications\PMTool\Helpers\FormHelper::GetUserForms($this,$sessionProject);
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $projectForms = \Applications\PMTool\Helpers\FormHelper::GetProjectForms($this,$sessionProject);

    $filteredMasterForms = \Applications\PMTool\Helpers\FormHelper::FilterFormsToExclude($masterForms,$projectForms,'master_form_id');
    $filteredUserForms = \Applications\PMTool\Helpers\FormHelper::FilterFormsToExclude($userForms,$projectForms,'user_form_id');
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $projectForms = \Applications\PMTool\Helpers\FormHelper::GetFormsFromProjectForms($this->user(),$sessionProject);

    //form modules
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
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
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_left => $projectForms,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => \Applications\PMTool\Helpers\FormHelper::SetPropertyNamesForDualList(),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => \Applications\PMTool\Helpers\FormHelper::SetPropertyNamesForDualList()
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);
  }

  public function executeAdd(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $this->dataPost["pm_id"] = $pm[\Library\Enums\SessionKeys::PmObject]->pm_id();
    $files = $this->files();
    if($this->dataPost["title"] == "" or is_null($this->dataPost["title"])) {
      $this->dataPost["title"] = $files["file"]["name"];
    }
    $form = \Applications\PMTool\Helpers\FormHelper::PrepareUserFormObject($this->dataPost());
    $result["dataIn"] = $form;

    //Load interface to query the database
    $manager = $this->managers->getManagerOf("UserForm");
    $manager->setRootDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload));
    $manager->setWebDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $this->app()->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath));

    $result["dataOut"] = $manager->addWithFile($form,$files['file']);

    if ($result["dataOut"] > 0) {
      $form->setForm_id($result["dataOut"]);
      array_push($sessionProject[\Library\Enums\SessionKeys::ProjectAvailableForms][\Library\Enums\SessionKeys::ProjectUserForms], $form);
      \Applications\PMTool\Helpers\ProjectHelper::SetCurrentSessionProject($this->user(), $sessionProject);
    }

    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Form,
      "resx_key" => $this->action(),
      "step" => (intval($result["dataOut"])) > 0 ? "success" : "error"
    ));
  }

  public function executeAddMaster(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $files = $this->files();
    if($this->dataPost["title"] == "" or is_null($this->dataPost["title"])) {
      $this->dataPost["title"] = $files["file"]["name"];
    }

    $form = \Applications\PMTool\Helpers\FormHelper::PrepareMasterFormObject($this->dataPost());
    $result["dataIn"] = $form;
    $manager = $this->managers->getManagerOf("MasterForm");
    $manager->setRootDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload));
    $manager->setWebDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $this->app()->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath));

    $result["dataOut"] = $manager->addWithFile($form,$files['file']);
    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Form,
      "resx_key" => $this->action(),
      "step" => (intval($result["dataOut"])) > 0 ? "success" : "error"
    ));

  }

  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $this->dataPost["pm_id"] = $pm[\Library\Enums\SessionKeys::PmObject]->pm_id();
    $service = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Service());
    $result["data"] = $service;

    $manager = $this->managers->getManagerOf($this->module);
    $result_edit = $manager->edit($service, "service_id");

    if($result_edit){
      $match = \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById($service->service_id(), "service_id", $pm, \Library\Enums\SessionKeys::PmServices);
      $pm[\Library\Enums\SessionKeys::PmServices][$match["key"]] = $service;
      \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->user(), $pm);
    }

    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Service,
      "resx_key" => $this->action(),
      "step" => $result_edit ? "success" : "error"
    ));
  }

  public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());
    $db_result = FALSE;
    $form_id = intval($this->dataPost["form_id"]);
    if($this->dataPost["form_type"] == "user_form") {
      //Check if the service to be deleted is the Project manager's
      $form_selected = \Applications\PMTool\Helpers\FormHelper::GetAUserForm($this->user(), $form_id);
      //Load interface to query the database
      if ($form_selected !== NULL) {
        $manager = $this->managers->getManagerOf("UserForm");
        $manager->setRootDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload));
        $manager->setWebDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $this->app()->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath));
        $db_result = $manager->deleteWithFile($form_selected, "form_id");
        if ($db_result) {
          //since we don't have foreign keys set because this is a relationship between 3 tables we must manually delete all project_form records, we also need to manually remove task forms

          //remove project forms from session
          $relationProjectForms = \Applications\PMTool\Helpers\FormHelper::GetProjectForms($this,$sessionProject);
          $filteredProjectForms = \Applications\PMTool\Helpers\FormHelper::FilterFormsByGivenId($relationProjectForms,'user_form_id',$form_id);
          $sessionProject[\Library\Enums\SessionKeys::ProjectForms] = $filteredProjectForms;

          if($sessionTask !== FALSE) {
            $relationTaskForms = \Applications\PMTool\Helpers\FormHelper::GetTaskForms($this,$sessionTask);
            $filteredTaskForms = \Applications\PMTool\Helpers\FormHelper::FilterFormsByGivenId($relationTaskForms,'user_form_id',$form_id);
            $sessionTask[\Library\Enums\SessionKeys::TaskForms] = $filteredTaskForms;
            \Applications\PMTool\Helpers\TaskHelper::SetCurrentSessionTask($this->user(),$sessionTask);
          }
          //delete from db
          $projectForm = new \Applications\PMTool\Models\Dao\Project_form();
          $projectForm->setUser_form_id($form_id);
          $manager = $this->managers->getManagerOf("ProjectForm");
          $manager->delete($projectForm,"user_form_id");

          $taskForm = new \Applications\PMTool\Models\Dao\Task_template_form();
          $taskForm->setUser_form_id($form_id);
          $manager = $this->managers->getManagerOf("TaskForm");
          $manager->delete($taskForm,"user_form_id");

          //remove user forms from session
          $match = \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById($form_selected->form_id(), "form_id", $sessionProject[\Library\Enums\SessionKeys::ProjectAvailableForms], \Library\Enums\SessionKeys::ProjectUserForms);
          unset($sessionProject[\Library\Enums\SessionKeys::ProjectAvailableForms][\Library\Enums\SessionKeys::ProjectUserForms][$match["key"]]);
          \Applications\PMTool\Helpers\ProjectHelper::SetCurrentSessionProject($this->user(), $sessionProject);
        }
      }
    }

    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Form,
      "resx_key" => $this->action(),
      "step" => $db_result !== FALSE ? "success" : "error"
    ));
  }

  public function executeGetList(\Library\HttpRequest $rq, $isNotAjaxCall = FALSE) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[\Library\Enums\SessionKeys::PmObject]->pm_id();
    $service = $this->_PrepareServiceObject($this->dataPost());
    $result["data"] = $service;

    //Load interface to query the database for services
    $manager = $this->managers->getManagerOf($this->module);
    $list[\Library\Enums\SessionKeys::PmServices] = $manager->selectMany($service, "pm_id");

    $result["lists"] = $list;
    if ($isNotAjaxCall) {
      return $list;
    } else {
      $step_result =
      $step_result = $result[\Library\Enums\SessionKeys::PmServices] !== NULL ? "success" : "error";
      $this->SendResponseWS(
        $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Service,
        "resx_key" => $this->action(),
        "step" => $step_result
      ));
    }
  }

  public function executeGetItem(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $service_id = intval($this->dataPost["service_id"]);

    $service_selected = \Applications\PMTool\Helpers\ServiceHelper::GetAService($this->user(), $service_id);

    $result["service"] = $service_selected;
    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Service,
      "resx_key" => $this->action(),
      "step" => ($service_selected !== NULL) ? "success" : "error"
    ));
  }

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
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
    $project_id = $sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id();
    foreach ($matchedMasterFormElements as $form) {
      $manager = $this->managers->getManagerOf("ProjectForm");
      $projectForm = new \Applications\PMTool\Models\Dao\Project_form();
      $projectForm->setProject_id($project_id);
      $projectForm->setMaster_form_id($form->form_id());
      $projectForm->setUser_form_id(null);
      if($this->dataPost["action"]=="add") {
        $manager->add($projectForm);
        $result["rows_affected"] += 1;
      } else if($this->dataPost["action"]=="remove") {
        $returnRemove = $manager->deleteByFilters($projectForm,array("project_id"=>$project_id,"master_form_id"=>$form->form_id()));
        $result["rows_affected"] += $returnRemove ? 1 : 0;
        //if we remove project form relationship we need to remove all child task form relationships also
        $manager = $this->managers->getManagerOf("TaskForm");
        $taskForm = new \Applications\PMTool\Models\Dao\Task_template_form();
        $taskForm->setUser_form_id(null);
        $taskForm->setMaster_form_id($form->form_id());
        $taskForm->setTask_id(null);
        $manager->deleteByProjectAndFilters($taskForm,array("master_form_id"=>$form->form_id()),$project_id);
      }
    }

    foreach ($matchedUserFormElements as $form) {
      $manager = $this->managers->getManagerOf("ProjectForm");
      $projectForm = new \Applications\PMTool\Models\Dao\Project_form();
      $projectForm->setProject_id($project_id);
      $projectForm->setMaster_form_id(null);
      $projectForm->setUser_form_id($form->form_id());
      if($this->dataPost["action"]=="add") {
        $returnAdd = $manager->add($projectForm);
        $result["rows_affected"] += $returnAdd >= 0 ? 1 : 0;
      } else if($this->dataPost["action"]=="remove") {
        $returnRemove = $manager->deleteByFilters($projectForm,array("project_id"=>$project_id,"user_form_id"=>$form->form_id()));
        $result["rows_affected"] += $returnRemove? 1 : 0;
        //if we remove project form relationship we need to remove all child task form relationships also
        $manager = $this->managers->getManagerOf("TaskForm");
        $taskForm = new \Applications\PMTool\Models\Dao\Task_template_form();
        $taskForm->setUser_form_id($form->form_id());
        $taskForm->setMaster_form_id(null);
        $taskForm->setTask_id(null);
        $manager->deleteByProjectAndFilters($taskForm,array("user_form_id"=>$form->form_id()),$project_id);
      }
    }
    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Form,
      "resx_key" => $this->action(),
      "step" => ($result["rows_affected"] > 0) ? "success" : "error"
    ));
  }

  /**
   * Check if the current pm has services to decide where to send him: stay on the service list or asking him to add a service
   *
   * @param \Applications\PMTool\Models\Dao\Services $pm
   * @return boolean
   */
  private function _CheckIfPmHasServices(\Applications\PMTool\Models\Dao\Service $pm) {

    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::PmServices)) {
      $services = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::PmServices);
      return count($services) > 0 ? TRUE : FALSE;
    }
    $manager = $this->managers->getManagerOf($this->module);
    $count = $manager->countById($pm->pm_id());
    return $count > 0 ? TRUE : FALSE;
  }

  /**
   * Checks if the user services  are not stored in Session.
   * Stores the services and facilities after call to WS to retrieve them
   * Set the data into the session for later use.
   *
   * @param /Library/HttpRequest $rq
   * @return array $lists : the lists of objects if any
   */
  private function _GetAndStoreServicesInSession($rq) {
    $lists = array();
    if (!$this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::PmServices)) {

      $lists = $this->executeGetList($rq, TRUE);

      $this->app()->user->setAttribute(
        \Library\Enums\SessionKeys::PmServices, $lists[\Library\Enums\SessionKeys::PmServices]
      );
    } else {
      $lists[\Library\Enums\SessionKeys::PmServices] = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::PmServices);
    }
    return $lists;
  }

}
