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

    //Fecth active task from DB
    $dbFetchedTask = \Applications\PMTool\Helpers\ActiveTaskHelper::QueryDBForActiveTaskData($rq->getData("task_id"), $this);

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $dbFetchedTask);

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

    //Fetch tooltip data from xml and pass to view as an array
    //Let's use the same gtooltips from the maps module as this module is just a special case of that module
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"map", "targetaction": "taskLocations", "targetattr": ["question-map-h3", "map-info-ruler", "map-info-shape", "map-info-add"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::SetCurrentSessionTask($this->user(), NULL, $rq->getData("task_id"));
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);

    \Applications\PMTool\Helpers\ActiveTaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskMapTab);

    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::activeTaskTabStatus, \Applications\PMTool\Helpers\ActiveTaskHelper::GetTabsStatus($this->app()->user()));
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Map::default_active_control, $rq->getData('active') ? : 'pan');
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

    //Fetch the documents for this active task
    $documents = \Applications\PMTool\Helpers\ActiveTaskHelper::GetDocumentsForActiveTask($sessionTask, $this);
    //Complete forms array, empty array for the time being
    $completedForms = array();

    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"activeTask", "targetaction": "forms", "targetattr": ["h4-taskforms-leftcol-gi", "h4-taskforms-rightcol-gi"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    $data_left = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $completedForms,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => \Applications\PMTool\Helpers\ActiveTaskHelper::SetPropertyNamesOfDocumentsForDualList()
    );
    $data_right = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_right => $documents,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => \Applications\PMTool\Helpers\ActiveTaskHelper::SetPropertyNamesOfDocumentsForDualList()
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data_left, $data_left);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data_right, $data_right);
    //\Applications\PMTool\Helpers\CommonHelper::pr($data_right);

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
    $currentDiscussion = \Applications\PMTool\Helpers\DiscussionHelper::GetCurrentDiscussion($this->user);
    if($currentDiscussion){
      $manager = $this->managers()->getManagerOf('User');
      $discussion_person = \Applications\PMTool\Helpers\DiscussionHelper::GetOtherDiscussionPerson($this->user(),$currentDiscussion[\Library\Enums\SessionKeys::DiscussionPeople]);
      $discussion_user_type = $manager->selectUserTypeObjectByUserId($discussion_person->user_id());
      if($discussion_user_type) {
        $currentDiscussion['comm_with'] = $discussion_user_type;
        $currentDiscussion['comm_type'] = \Applications\PMTool\Helpers\UserHelper::FindUserTypeFromObject($discussion_user_type);
      }
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentDiscussion, $currentDiscussion);
    }

    //Let's get this task specific services
    $sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $pm_services = \Applications\PMTool\Helpers\ServiceHelper::GetPmServices($this, $sessionPm);
    $task_services = \Applications\PMTool\Helpers\ServiceHelper::GetAndStoreTaskServices($this, $sessionTask);
    // filter the pm services after we retrieve the task services
    $pm_services = \Applications\PMTool\Helpers\ServiceHelper::FilterServicesToExcludeTaskServices($pm_services, $task_services);
    //Categorize the list for showing in the list
    $task_services = \Applications\PMTool\Helpers\ServiceHelper::CategorizeTheList($task_services, "service_type");
    //load forms
    $documents = \Applications\PMTool\Helpers\ActiveTaskHelper::GetDocumentsForActiveTask($sessionTask, $this);
    //Set data for frontend
    $data = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_left => $task_services,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower("service")),
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);
    //use group list right for popup task location form attachments
    $data_right = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower('document'),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_right => $documents,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => \Applications\PMTool\Helpers\ActiveTaskHelper::SetPropertyNamesOfDocumentsForDualList()
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data_right, $data_right);
    
    //Similarly let's get the task specific technicians
    $sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $pm_technicians = \Applications\PMTool\Helpers\TechnicianHelper::GetPmTechnicians($this, $sessionPm);
    $task_technicians = \Applications\PMTool\Helpers\TechnicianHelper::GetAndStoreTaskTechnicians($this, $sessionTask);
    // filter the pm technicians after we retrieve the task technicians
    $pm_technicians = \Applications\PMTool\Helpers\TechnicianHelper::FilterTechniciansToExcludeTaskTechnicians($pm_technicians, $task_technicians);

    //\Applications\PMTool\Helpers\CommonHelper::pr($task_technicians);
    //Check if the technician count for this task is only one, if so
    //startComm for this technician
    if(count($task_technicians) === 1 and !$currentDiscussion) {
      //Only one, let's start comm
      \Applications\PMTool\Helpers\ActiveTaskHelper::StartCommunicationWith(
              $this, 
              'technician_id', 
              $task_technicians[0]->technician_id());

      
      header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
      exit;
    }

    $data_left = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $task_technicians,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower("technician")),
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data_left, $data_left);

    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"activeTask", "targetaction": "communications", "targetattr": ["h4-taskcomm-leftcol-gi", "h4-taskcomm-rightcol-gi","h4-taskcomm-services-gi","h4-taskcomm-technicians-gi"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::activeTaskTabStatus, \Applications\PMTool\Helpers\ActiveTaskHelper::GetTabsStatus($this->app()->user()));
    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $modules);

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::popup_prompt_list,$modules['group_list_right']);
  }
  
    public function executeNotes(\Library\HttpRequest $rq) {
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

   \Applications\PMTool\Helpers\ActiveTaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskNotesTab);

    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::activeTaskTabStatus, \Applications\PMTool\Helpers\ActiveTaskHelper::GetTabsStatus($this->app()->user()));
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeFieldData(\Library\HttpRequest $rq) {
    \Applications\PMTool\Helpers\ActiveTaskHelper::AddTabsStatus($this->user());
    \Applications\PMTool\Helpers\AnalyteHelper::StoreListsData($this);  

    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->user());

    \Applications\PMTool\Helpers\AnalyteHelper::StoreListsData($this);

    //Check if a project needs to be selected in order to display this page
    if (!$sessionProject) {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ProjectsSelectProject . "?onSuccess=" . \Library\Enums\ResourceKeys\UrlKeys::TaskAddPrompt);
    }
    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::SetCurrentSessionTask($this->user(), NULL, $rq->getData("task_id"));

    //Get task specific field analytes
    $task_field_analytes = \Applications\PMTool\Helpers\AnalyteHelper::GetAndStoreTaskFieldAnalytes($this, $sessionTask);
    //Check which page to render
    $pg = (is_null($rq->getData('pg'))) ? 1 : (intval($rq->getData('pg')) == 0) ? 1 : intval($rq->getData('pg'));
    //Calculate pages
    $pages = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::returnTotalPagesOfAnalytes($task_field_analytes, $this->app);
    //Filter paged result set of analytes
    //$task_field_analytes = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::returnPagedAnalyteObjects($task_field_analytes, $pg, $this->app);

    //Task specific locations
    $project_locations = \Applications\PMTool\Helpers\LocationHelper::GetProjectLocations($this, $sessionProject);
    $task_locations = \Applications\PMTool\Helpers\LocationHelper::GetAndStoreTaskLocations($this, $sessionTask);

    //Get LocationLabMatrix relation
    $id_map = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::GetFieldDataMatrixForTaskWithResult($this, 
            $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
    //\Applications\PMTool\Helpers\CommonHelper::pr($id_map);


    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentTask, $sessionTask[\Library\Enums\SessionKeys::TaskObj]);

    \Applications\PMTool\Helpers\ActiveTaskHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskFieldDataTab);

    
    //Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"activeTask", "targetaction": "showForm", "targetattr": ["h4-taskstatus-leftcol-gi", "h4-taskstatus-rightcol-gi", "h4-taskstatus-notes-gi", "h4-taskstatus-notesrecord-gi"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::activeTaskTabStatus, \Applications\PMTool\Helpers\ActiveTaskHelper::GetTabsStatus($this->app()->user()));
    $this->page->addVar(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());

    //--------
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::task_locations, $task_locations);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::task_field_analytes, $task_field_analytes);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::task_analytes_pages, $pages);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::current_page, $pg);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::task_field_analytes_idmap, $id_map);
    //footer task id
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::task_id, $rq->getData("task_id"));
    

  }

  public function executeStartCommWith(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $result['success'] = false;
    
    $result['success'] = \Applications\PMTool\Helpers\ActiveTaskHelper::StartCommunicationWith($this, $this->dataPost['selection_type'], $this->dataPost['id']);


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
    $images = array();
    if(isset($this->dataPost['images'])) {
      $images = json_decode($this->dataPost['images']);
    //Unset image data from POST so that we may create the DAO
    unset($this->dataPost['images']);
    }
    //process images

    //Prepare data object
    $userConnected = \Applications\PMTool\Helpers\UserHelper::GetUserConnectedSession($this->user());
    $data = array('task_id' => $currSessTask['task_info_obj']->task_id(), 'task_note_category_type' => $userConnected->user_type(), 'task_note_category_value' => $userConnected->user_value(), 'task_note_value' => $this->dataPost['note']);

    //Init PDO
    $task_note = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($data, new \Applications\PMTool\Models\Dao\Task_note());

    $result["data"] = '';



    $manager = $this->managers->getManagerOf($this->module());
    $result_save = $manager->add($task_note);
    if($result_save) {
      $userTypeObject = $this->user->getAttribute(\Library\Enums\SessionKeys::UserTypeObject);
      if ($this->user->getUserType() == 'pm_id') {
        $result['user'] = $userTypeObject->pm_name();
      } else if ($this->user->getUserType() == 'technician_id') {
        $result['user'] = $userTypeObject->technician_name();
      } else if ($this->user->getUserType() == 'service_id') {
        $result['user'] = $userTypeObject->service_name();
      }
      $task_note = new \Applications\PMTool\Models\Dao\Task_note();
      $task_note->setTask_note_id($result_save);
      $noteAttachments = '';

      foreach($images as $image) {
        $manager = $this->managers()->getManagerOf('Document');
        $manager->setWebDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $this->app()->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath));
        $docObj = $manager->getRecordsMatchingDocumentValue($image);
        //Our new document value is
        $newDocumentValue = $result_save . '_' . $docObj[0]->document_value();
        //Rename the file on disk
        rename('./uploads/task_note/' . $docObj[0]->document_value(), './uploads/task_note/' . $newDocumentValue);
        //Update the document value into the Dal
        $docObj[0]->setDocument_value($newDocumentValue);
        $noteAttachments .= ' '.$this->getHostUrl().$manager->webDirectory.'task_note/'.$docObj[0]->document_value();
        //and commit the edit
        $result_edit = $manager->edit($docObj[0], "document_id");
      }
      $manager = $this->managers->getManagerOf($this->module());
      $task_notes = $manager->selectMany($task_note,'task_note_id');
      if(!empty($images)) {
        $task_notes[0]->setTask_note_value($task_notes[0]->task_note_value().$noteAttachments);
        $manager->edit($task_notes[0],'task_note_id');
      }
      $result["data"] = $task_notes[0];
    }
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

    $result_get = 1;
    $onlyLoggedInUser = $this->dataPost['onlyuser'];
    $userConnected = \Applications\PMTool\Helpers\UserHelper::GetUserConnectedSession($this->user());
    if(!empty($result_note)) {
      $user_arr = array();
      foreach($result_note as $note_key => $note_obj) {
        $datauser = null;
        if((isset($onlyLoggedInUser) && $onlyLoggedInUser==true) && $note_obj->task_note_category_value() != $userConnected->user_value()){
          unset($result_note[$note_key]);
        } else {
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

      }
      $result_note = array_values($result_note);
      $result_get = 1;
    }

    //cleanup into a nice array
    $result['notes'] = $result_note;
    $result['users'] = (!empty($user_arr)) ? $user_arr : array();


    //\Applications\PMTool\Helpers\CommonHelper::pr($user_arr);

    $this->SendResponseWS(
      $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::ActiveTask,
        "resx_key" => $this->action(),
        "step" => ($result_get) ? "success" : "error"
      )
    );
  }

  public function executeSendMessage(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $currentSessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());

    //Prepare data object
    $userConnected = \Applications\PMTool\Helpers\UserHelper::GetUserConnectedSession($this->user());


    $currentDiscussion = \Applications\PMTool\Helpers\DiscussionHelper::GetCurrentDiscussion($this->user);
    if ($currentDiscussion) {
      $dataPost = $this->dataPost();
      $discussion_content_id = \Applications\PMTool\Helpers\DiscussionHelper::AddMessageToThread($this, $userConnected, $currentDiscussion, $dataPost);
      //here goes mail sending...
      if ($discussion_content_id > 0) {
        $result['success'] = true;
        $discussion_content = new \Applications\PMTool\Models\Dao\Discussion_content();
        $discussion_content->setDiscussion_content_id($discussion_content_id);
        $manager = $this->managers()->getManagerOf('DiscussionContent');
        $discussion_content = $manager->selectMany($discussion_content, 'discussion_content_id');
        $userTypeObject = $this->user->getAttribute(\Library\Enums\SessionKeys::UserTypeObject);
        if ($this->user->getUserType() == 'pm_id') {
          $discussion_content[0]->user_name = $userTypeObject->pm_name();
        } else if ($this->user->getUserType() == 'technician_id') {
          $discussion_content[0]->user_name = $userTypeObject->technician_name();
        } else if ($this->user->getUserType() == 'service_id') {
          $discussion_content[0]->user_name = $userTypeObject->service_name();
        }

        $result['data'] = $discussion_content[0];
      } else {
        $result['succes'] = false;
      }
    } else {
      $result['success'] = false;
      $result['data'] = '';
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::ActiveTask,
        "resx_key" => $this->action(),
        "step" => ($result['success']) ? "success" : "error"
            )
    );
  }

  public function executeGetDiscussionThread(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();

    $currentSessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($this->user());

    $currentDiscussion = \Applications\PMTool\Helpers\DiscussionHelper::GetCurrentDiscussion($this->user);
    $discussionNames = array();

    if ($currentDiscussion) {
      foreach ($currentDiscussion[\Library\Enums\SessionKeys::DiscussionPeople] as $person) {
        $manager = $this->managers()->getManagerOf('User');
        $userTypeObject = $manager->selectUserTypeObjectByUserId($person->user_id());
        $userType = \Applications\PMTool\Helpers\UserHelper::FindUserTypeFromObject($userTypeObject);
        if ($userType == 'pm_id') {
          $discussionNames[$person->discussion_person_id()] = $userTypeObject->pm_name();
        } else if ($userType == 'technician_id') {
          $discussionNames[$person->discussion_person_id()] = $userTypeObject->technician_name();
        } else if ($userType == 'service_id') {
          $discussionNames[$person->discussion_person_id()] = $userTypeObject->service_name();
        }
        if($person->discussion_person_is_author()!=1) {
          $result['user_type'] = $userType;
        }
      }
      $thread = \Applications\PMTool\Helpers\DiscussionHelper::GetDiscussionThread($this, $currentDiscussion);
      $time = $this->dataPost['time'];
      if(isset($time) && !is_null('time') && $thread){
        $thread = \Applications\PMTool\Helpers\DiscussionHelper::SliceThread($thread,$time);
      }
      if ($thread) {
        foreach ($thread as &$content) {
          foreach ($discussionNames as $id => $name) {
            if ($id == $content->discussion_person_id()) {
              $content->user_name = $name;
              break;
            }
          }
        }
        $result['thread'] = $thread;
      }
      $result['discussion'] = $currentDiscussion[\Library\Enums\SessionKeys::DiscussionObj];
      $result['success'] = true;
    } else {
      $result['success'] = false;
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::ActiveTask,
        "resx_key" => $this->action(),
        "step" => ($result['success']) ? "success" : "error"
            )
    );
  }

  private function getHostUrl(){
    $ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($_SERVER['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $_SERVER['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = isset($host) ? $host : $_SERVER['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
  }

}