<?php

/**
 *
 * @package		Basic MVC framework
 * @author		Jeremie Litzler
 * @copyright	Copyright (c) 2015
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * CommonHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	TaskHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskHelper {

  public static function AddSessionTask($user, \Applications\PMTool\Models\Dao\Task $task) {
    $sessionTasks = self::GetSessionTasks($user);
    $sessionTask = self::MakeSessionTask($task);
    $sessionTasks[\Library\Enums\SessionKeys::TaskKey . $task->task_id()] = $sessionTask;
    self::SetCurrentSessionTask($user, $sessionTask);
    self::SetSessionTasks($user, $sessionTasks);
  }

  public static function AddTabsStatus(\Library\User $user) {
    $tabs = array(
        \Applications\PMTool\Resources\Enums\TaskTabKeys::InfoTab => "active",
        \Applications\PMTool\Resources\Enums\TaskTabKeys::TechniciansTab => "",
        \Applications\PMTool\Resources\Enums\TaskTabKeys::LocationsTab => "",
        \Applications\PMTool\Resources\Enums\TaskTabKeys::ChecklistTab => "",
        \Applications\PMTool\Resources\Enums\TaskTabKeys::InspFormsTab => "",
        \Applications\PMTool\Resources\Enums\TaskTabKeys::FieldAnalytesTab => "",
        \Applications\PMTool\Resources\Enums\TaskTabKeys::CocTab => "",
        \Applications\PMTool\Resources\Enums\TaskTabKeys::LabAnalytesTab => "",
        \Applications\PMTool\Resources\Enums\TaskTabKeys::ServicesTab => "",
        \Applications\PMTool\Resources\Enums\TaskTabKeys::FieldSampleMatrixTab => "",
        \Applications\PMTool\Resources\Enums\TaskTabKeys::LabSampleMatrixTab => "",
        \Applications\PMTool\Resources\Enums\TaskTabKeys::FormsTab => "",
    );
    $user->setAttribute(\Library\Enums\SessionKeys::TabsStatus, $tabs);
  }

  /**
   * <p>
   * Sets the data into the task session array when it is selected so that the 
   * data necessary is available for the different actions.
   * </p>
   * @param type $caller <p>
   * Instance the controller calling the helper class </p>
   * @param type $sessionTask <p>
   * The session array of type Task to use. It needs to be refreshed at every
   * action </p>
   */
  public static function FillSessionTask($caller, $sessionTask) {
    ServiceHelper::GetAndStoreTaskServices($caller, $sessionTask);

    //Get the refreshed session array value and then update it then
    $sessionTask = self::GetCurrentSessionTask($caller->user());

    //Then you can do your logic to update the session array value
  }

  public static function GetAndStoreCurrentTask(\Library\User $user, $task_id) {
    $sessionTasks = NULL;
    if ($user->keyExistInSession(\Library\Enums\SessionKeys::SessionTasks)) {
      $sessionTasks = $user->getAttribute(\Library\Enums\SessionKeys::SessionTasks);
    }

    //If there is no session tasks yet, create one with the $task id given
    if ($sessionTasks !== NULL) {
      $key = \Library\Enums\SessionKeys::TaskKey . $task_id;
      $user->setAttribute(\Library\Enums\SessionKeys::CurrentTask, $sessionTasks[$key]);
      return array_key_exists($key, $sessionTasks) ?
              $sessionTasks[$key][\Library\Enums\SessionKeys::TaskObject] : NULL;
    }
    return NULL;
  }

  public static function GetFilteredTaskObjectsList(\Library\User $user) {
    $filteredTaskList = array();
    $sessionTasks = self::GetSessionTasks($user);
    foreach (self::GetSessionProjectTasks($user) as $task_key) {
      if (array_key_exists($task_key, $sessionTasks)) {
        array_push($filteredTaskList, $sessionTasks[$task_key][\Library\Enums\SessionKeys::TaskObj]);
      }
    }
    return $filteredTaskList;
  }

  public static function GetSessionTask(\Library\User $user, $task_id) {
    //retrieve the session task from $task_id
    $sessionTasks = self::GetSessionTasks($user);
    $key = \Library\Enums\SessionKeys::TaskKey;
    if ($task_id !== 0) {
      $key .= $task_id;
    }
    $user->setAttribute(\Library\Enums\SessionKeys::CurrentTask, $sessionTasks[$key]);
    return $sessionTasks[$key];
  }

  public static function GetSessionTasks(\Library\User $user) {
    return $user->getAttribute(\Library\Enums\SessionKeys::SessionTasks);
  }

  public static function GetSessionProjectTasks(\Library\User $user) {
    $currentProject = ProjectHelper::GetCurrentSessionProject($user);
    return $currentProject[\Library\Enums\SessionKeys::ProjectTasks];
  }

  public static function GetTabsStatus(\Library\User $user) {
    return $user->getAttribute(\Library\Enums\SessionKeys::TabsStatus);
  }

  public static function GetTaskInfoTabUrl($currentTask) {
    if ($currentTask === NULL) {
      return
              \Library\Enums\ResourceKeys\UrlKeys::TaskShowForm
              . "?mode=add";
    } else {
      return
              \Library\Enums\ResourceKeys\UrlKeys::TaskShowForm
              . "?mode=edit&task_id="
              . $currentTask->task_id();
    }
  }

  public static function GetTaskCocTabUrl($currentTask) {

    return
            \Library\Enums\ResourceKeys\UrlKeys::TaskCOC
            . "?mode=edit&task_id="
            . $currentTask->task_id();
  }

  public static function GetCurrentSessionTask($user) {
    return $user->keyExistInSession(\Library\Enums\SessionKeys::CurrentTask) ?
            $user->getAttribute(\Library\Enums\SessionKeys::CurrentTask) : FALSE;
  }

  public static function MakeSessionTask(\Applications\PMTool\Models\Dao\Task $task) {
    $sessionTask = array(
        \Library\Enums\SessionKeys::TaskObj => $task,
        \Library\Enums\SessionKeys::TaskCocInfoObj => NULL,
        \Library\Enums\SessionKeys::TaskLocations => array(),
        \Library\Enums\SessionKeys::TaskTechnicians => array()
    );
    return $sessionTask;
  }

  public static function UserHasTasks(\Library\User $user, $task_id) {
    return count(self::GetFilteredTaskObjectsList($user)) > 0;
  }

  public static function SetActiveTab(\Library\User $user, $tab_name) {
    $tabs = $user->getAttribute(\Library\Enums\SessionKeys::TabsStatus);
    foreach ($tabs as $key => $value) {
      $tabs[$key] = "";
    }
    $tabs[$tab_name] = "active";
    $user->setAttribute(\Library\Enums\SessionKeys::TabsStatus, $tabs);
  }

  public static function SetCurrentSessionTask(\Library\User $user, $sessionTask = NULL, $task_id = 0) {
    $currentSessionTask = $user->getAttribute(\Library\Enums\SessionKeys::CurrentTask);

    if ($task_id > 0 && $sessionTask === NULL) {
      $sessionTasks = self::GetSessionTasks($user);
      $sessionTask = $sessionTasks[\Library\Enums\SessionKeys::TaskKey . $task_id];
      if ($currentSessionTask != NULL && $sessionTask !== NULL && $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id() != $currentSessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id()) {
        $user->unsetAttribute(\Library\Enums\SessionKeys::CurrentDiscussion);
      }
    }
    $user->setAttribute(\Library\Enums\SessionKeys::CurrentTask, $sessionTask);
    return $sessionTask;
  }

  public static function SetSessionTasks($user, $tasks) {
    $user->setAttribute(\Library\Enums\SessionKeys::SessionTasks, $tasks);
  }

  public static function SetSessionTask(\Library\User $user, $sessionTask) {
    $sessionTasks = $user->getAttribute(\Library\Enums\SessionKeys::SessionTasks);
    $task_id = $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id();
    $sessionTasks[\Library\Enums\SessionKeys::TaskKey . $task_id] = $sessionTask;
    $user->setAttribute(\Library\Enums\SessionKeys::CurrentTask, $sessionTask);
    self::SetSessionTasks($user, $sessionTasks);
  }

  public static function getLabServicesForTask($caller, $sessionTask, $filterCategory) {
    $labServices = $taskServices = array();
    if (isset($sessionTask[\Library\Enums\SessionKeys::TaskServices]) && count($sessionTask[\Library\Enums\SessionKeys::TaskServices]) > 0) {
      $taskServices = \Applications\PMTool\Helpers\ServiceHelper::GetServicesFromTaskServices($caller->user(), $sessionTask);
    } else {
      $taskServices = ServiceHelper::GetAndStoreTaskServices($caller, $sessionTask);
      $sessionTask[\Library\Enums\SessionKeys::TaskServices] = $taskServices;
      TaskHelper::SetSessionTask($caller->user(), $sessionTask);
    }
    foreach ($taskServices as $service) {
      if ($service['service_type'] === $filterCategory) {
        array_push($labServices, $service);
      }
    }
    return $labServices;
  }

  public static function StoreSessionTask($user, $list, $sessionProject = NULL) {
    $SessionTasks = array();
    $project =
            $sessionProject === NULL ?
            ProjectHelper::GetCurrentSessionProject($user) :
            $sessionProject;
    $countActiveTask = 0;
    foreach ($list as $task) {
      $key = \Library\Enums\SessionKeys::TaskKey . $task->task_id();
      $sessionTask = self::MakeSessionTask($task);
      $SessionTasks[$key] = $sessionTask;
      array_push($project[\Library\Enums\SessionKeys::ProjectTasks], $key);
      if ($task->task_active()) {
        $countActiveTask += 1;
        $currentSessionTask = $sessionTask; //Get sessin task object
      }
    }
    ProjectHelper::SetUserSessionProject($user, $project);
    self::SetSessionTasks($user, $SessionTasks);
    if ($countActiveTask === 1) {//Set current task if there is only one active
      self::SetCurrentSessionTask($user, $currentSessionTask);
    }
    return $SessionTasks;
  }

  public static function UnsetUserSessionTask(\Library\User $user, $task_id) {
    $sessionTasks = $user->getAttribute(\Library\Enums\SessionKeys::SessionTasks);
    unset($sessionTasks[\Library\Enums\SessionKeys::TaskKey . $task_id]);
    self::UnsetCurrentSessionTask($user);
    $user->setAttribute(\Library\Enums\SessionKeys::SessionTasks, $sessionTasks);
  }

  public static function UnsetCurrentSessionTask(\Library\User $user) {
    $user->unsetAttribute(\Library\Enums\SessionKeys::CurrentTask);
  }

  public static function UpdateSessionTask(\Library\User $user, $sessionTask) {
    $sessionTasks = self::GetSessionTasks($user);
    if ($sessionTasks !== NULL) {
      $currentSessionTask = $user->getAttribute(\Library\Enums\SessionKeys::CurrentTask);
      $sessionTasks[\Library\Enums\SessionKeys::TaskKey . $sessionTask[\Library\Enums\SessionKeys::TaskObject]->task_id()] = $currentSessionTask = $sessionTask;
      self::SetSessionTask($user, $currentSessionTask);
      self::SetSessionTasks($user, $sessionTasks);
    }
  }

  /**
   * Returns which tabs are to be shown
   * for the passed task
   */
  public static function TabStatusFor($sessionTask) {
    $ret_val = array(
        \Applications\PMTool\Resources\Enums\ViewVariables\Task::task_req_service => $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_req_service(),
        \Applications\PMTool\Resources\Enums\ViewVariables\Task::task_req_form => $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_req_form(),
        \Applications\PMTool\Resources\Enums\ViewVariables\Task::task_req_field_analyte => $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_req_field_analyte(),
        \Applications\PMTool\Resources\Enums\ViewVariables\Task::task_req_lab_analyte => $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_req_lab_analyte(),
    );
    return $ret_val;
  }

  /**
   * For the passed task ID, creates location specific 
   * PDF forms from PDF template
   */
  public static function CreateLocationSpecificPDF($sessionTask, $caller) {

    $task_id = $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id();
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($caller->user());

    //Fetch the locations for this task
    $taskLocations = self::GetTaskLocationsForTask($sessionTask, $caller);

    //Fetch the template file relation for this task
    $taskTemplateForms = self::GetTaskTemplateFormForTask($sessionTask, $caller);

    self::ProcessTemplateForms($caller, $sessionTask, $taskLocations, $taskTemplateForms);
  }

  public static function GetTaskLocationsForTask($sessionTask, $caller) {
    $taskLocationDAO = new \Applications\PMTool\Models\Dao\Task_location();
    $taskLocationDAO->setTask_id($sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
    $dal = $caller->managers()->getManagerOf("Task");
    return $dal->selectMany($taskLocationDAO, "task_id");
  }

  public static function GetTaskTemplateFormForTask($sessionTask, $caller) {
    $templateDAO = new \Applications\PMTool\Models\Dao\Task_template_form();
    $templateDAO->setTask_id($sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
    $dal = $caller->managers()->getManagerOf("Task");
    return $dal->selectMany($templateDAO, "task_id");
  }

  public static function ProcessTemplateForms($caller, $sessionTask, $taskLocations, $taskTemplateForms) {
    foreach ($taskTemplateForms as $template) {
      //And finally the file itself, which needs to be copied
      //It could be a master form or a user forms, check
      if (is_null($template->master_form_id()) || $template->master_form_id() == '0') {
        //master form id doesn't exist, it must be an user form
        $formData = UserFormHelper::GetFormFromTaskTemplateFrom($caller, $template);
        $tmp_name = './uploads/user_form/' . $formData[0]->value();
      } else {
        //mster form
        $formData = MasterFormHelper::GetFormFromTaskTemplateFrom($caller, $template);
        $tmp_name = './uploads/master_form/' . $formData[0]->value();
      }
      //Pseudo array for file based on the master file
      $file['file'] = array(
          'name' => $formData[0]->value(),
          'type' => $formData[0]->content_type(),
          'tmp_name' => $tmp_name,
          'error' => 0,
          'size' => $formData[0]->size() * 1000
      );

      //So now we have to loop over the locations and create multiple files
      self::ProcessTaskLocations($caller, $sessionTask, $taskLocations, $file, $formData);
    }
  }

  public static function ProcessTaskLocations($caller, $sessionTask, $taskLocations, $file, $formData) {
    foreach ($taskLocations as $loc_key => $taskLocationObj) {
      //We have to get the Location names too
      //Check if exists in Session
      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($caller->user());
      //Recall session projects
      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($caller->user());
      $locationRecord = \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById($taskLocationObj->location_id(), "location_id", $sessionProject, \Library\Enums\SessionKeys::ProjectLocations);

      if (!empty($locationRecord)) {
        $locationObject = $locationRecord['object'];
        self::CopyFileForTaskLocation($caller, $taskLocationObj, $locationObject, $file, $formData);
      } else {
        throw new \Exception("TaskHelper::ProcessTaskLocations ==> No location object found in ProjectLocations for location_id=" . $taskLocationObj->location_id());
      }
    }
  }

  public static function CopyFileForTaskLocation($caller, $taskLocationObj, $locationObj, $file, $formData) {
    $dataPost = array(
        'itemCategory' => 'task_location',
        'itemId' => $taskLocationObj->task_location_id(),
        'title' => $formData[0]->title() . '_' . $locationObj->location_name(),
        'itemReplace' => false
    );

    \Library\Controllers\FileController::copyFile($file, $dataPost, $caller);
  }

  public static function GetTaskListFromDb($caller, $sessionProjet = NULL) {
    $project =
            $sessionProjet === NULL ?
            \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($caller->user()) :
            $sessionProjet;
    //Load interface to query the database for tasks
    $task = new \Applications\PMTool\Models\Dao\Task();
    $task->setProject_id($project[\Library\Enums\SessionKeys::ProjectObject]->project_id());
    $manager = $caller->managers()->getManagerOf("Task");
    $sessionTasks = \Applications\PMTool\Helpers\TaskHelper::StoreSessionTask($caller->user(), $manager->selectMany($task, "project_id"));
    foreach ($sessionTasks as $sessionTask) {
      TaskHelper::FillSessionTask($caller, $sessionTask);
    }
    TaskHelper::SetCurrentSessionTask($caller->user(), NULL);
  }

  /**
  * Task Copy: Main method
  * Copies the task corresponding to the passed task id
  * and based on that copies all other dependencies into
  * the new task thus created
  */
  public static function copyTaskWithDependencies($caller, $source_task_id, $new_task_name, $usr) {
    $taskDAO = new \Applications\PMTool\Models\Dao\Task();
    $taskDAO->setTask_id($source_task_id);
    $dal = $caller->managers()->getManagerOf("Task");
    $source_task = $dal->selectMany($taskDAO, "task_id");

    //Create new task out of this
    $taskDAO = null;
    $taskDAO = new \Applications\PMTool\Models\Dao\Task();
    $taskDAO->setProject_id($source_task[0]->project_id());
    $taskDAO->setTask_name($new_task_name);
    $taskDAO->setTask_deadline($source_task[0]->task_deadline());
    $taskDAO->setTask_instructions($source_task[0]->task_instructions());
    $taskDAO->setTask_trigger_cal($source_task[0]->task_trigger_cal());
    $taskDAO->setTask_trigger_cal_value($source_task[0]->task_trigger_cal_value());
    $taskDAO->setTask_trigger_pm($source_task[0]->task_trigger_pm());
    $taskDAO->setTask_trigger_ext($source_task[0]->task_trigger_ext());
    $taskDAO->setTask_active($source_task[0]->task_active());
    $taskDAO->setTask_req_form($source_task[0]->task_req_form());
    $taskDAO->setTask_req_field_analyte($source_task[0]->task_req_field_analyte());
    $taskDAO->setTask_req_lab_analyte($source_task[0]->task_req_lab_analyte());
    $taskDAO->setTask_req_service($source_task[0]->task_req_service());
    $taskDAO->setTask_start_date($source_task[0]->task_start_date());
    $taskDAO->setTask_activated($source_task[0]->task_activated());

    $new_task_id = $dal->add($taskDAO);

    //Copy the relations too
    //FieldAnalyteLocation
    TaskHelper::copyTaskFieldAnalyteLocation($caller, $source_task_id, $new_task_id);

    //LabAnalyteLocation
    TaskHelper::copyTaskLabAnalyteLocation($caller, $source_task_id, $new_task_id);

    //TaskCocInfo
    TaskHelper::copyTaskTaskCocInfo($caller, $source_task_id, $new_task_id);

    //TaskFieldAnalyte
    TaskHelper::copyTaskTaskFieldAnalyte($caller, $source_task_id, $new_task_id);

    //TaskLabAnalyte
    TaskHelper::copyTaskTaskLabAnalyte($caller, $source_task_id, $new_task_id);

    //Task_location
    TaskHelper::copyTaskTaskLocation($caller, $source_task_id, $new_task_id);

    //Task_service
    TaskHelper::copyTaskTaskService($caller, $source_task_id, $new_task_id);

    //Task_technician
    TaskHelper::copyTaskTaskTechnician($caller, $source_task_id, $new_task_id);

    //Task_template_form
    TaskHelper::copyTaskTaskTemplateForm($caller, $source_task_id, $new_task_id);

    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($usr);
    $taskDAO->setTask_id($new_task_id);
    array_push($sessionProject[\Library\Enums\SessionKeys::ProjectTasks], \Library\Enums\SessionKeys::TaskKey . $taskDAO->task_id());

    if ($new_task_id > 0) {
      \Applications\PMTool\Helpers\TaskHelper::AddSessionTask($usr, $taskDAO);
      \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($usr, $sessionProject);
    }

    return $new_task_id;

  }

  /**
  * Task copy: Task_template_form
  * Fetches the related Task_template_form for the
  * original task and creates the relation with the new
  * Task created in "copyTaskWithDependencies"
  */
  public static function copyTaskTaskTemplateForm($caller, $source_task_id, $target_task_id) {
    $ttfDAO = new \Applications\PMTool\Models\Dao\Task_template_form();
    $ttfDAO->setTask_id($source_task_id);
    $dal = $caller->managers()->getManagerOf("Task");
    $allTTFs = $dal->selectMany($ttfDAO, "task_id");

    if(count($allTTFs) > 0) {
      //TTs found, loop and remap with the new task id
      foreach($allTTFs as $ttf) {
        $ttfDAO = null;
        $ttfDAO = new \Applications\PMTool\Models\Dao\Task_template_form();
        $ttfDAO->setTask_id($target_task_id);
        $ttfDAO->setMaster_form_id($ttf->master_form_id());
        $ttfDAO->setUser_form_id($ttf->user_form_id());
        //Save
        $id = $dal->add($ttfDAO);
      }
    }
  }

  /**
  * Task copy: Task_technician
  * Fetches the related Task_technician for the
  * original task and creates the relation with the new
  * Task created in "copyTaskWithDependencies"
  */
  public static function copyTaskTaskTechnician($caller, $source_task_id, $target_task_id) {
    $ttDAO = new \Applications\PMTool\Models\Dao\Task_technician();
    $ttDAO->setTask_id($source_task_id);
    $dal = $caller->managers()->getManagerOf("Task");
    $allTTs = $dal->selectMany($ttDAO, "task_id");

    if(count($allTTs) > 0) {
      //TTs found, loop and remap with the new task id
      foreach($allTTs as $tt) {
        $tsDAO = null;
        $ttDAO = new \Applications\PMTool\Models\Dao\Task_technician();
        $ttDAO->setTask_id($target_task_id);
        $ttDAO->setTechnician_id($tt->technician_id());
        $ttDAO->setIs_lead_tech($tt->is_lead_tech());
        //Save
        $id = $dal->add($ttDAO);
      }
    }
  }

  /**
  * Task copy: Task_service
  * Fetches the related Task_service for the
  * original task and creates the relation with the new
  * Task created in "copyTaskWithDependencies"
  */
  public static function copyTaskTaskService($caller, $source_task_id, $target_task_id) {
    $tsDAO = new \Applications\PMTool\Models\Dao\Task_service();
    $tsDAO->setTask_id($source_task_id);
    $dal = $caller->managers()->getManagerOf("Task");
    $allTSs = $dal->selectMany($tsDAO, "task_id");

    if(count($allTSs) > 0) {
      //TSs found, loop and remap with the new task id
      foreach($allTSs as $ts) {
        $tsDAO = null;
        $tsDAO = new \Applications\PMTool\Models\Dao\Task_service();
        $tsDAO->setTask_id($target_task_id);
        $tsDAO->setService_id($ts->service_id());
        //Save
        $id = $dal->add($tsDAO);

      }
    }
  }

  /**
  * Task copy: Task_location
  * Fetches the related Task_location for the
  * original task and creates the relation with the new
  * Task created in "copyTaskWithDependencies"
  */
  public static function copyTaskTaskLocation($caller, $source_task_id, $target_task_id) {
    $tlDAO = new \Applications\PMTool\Models\Dao\Task_location();
    $tlDAO->setTask_id($source_task_id);
    $dal = $caller->managers()->getManagerOf("Task");
    $allTls = $dal->selectMany($tlDAO, "task_id");

    if(count($allTls) > 0) {
      //fals found, loop and remap with the new task id
      foreach($allTls as $tl) {
        $tlDAO = null;
        $tlDAO = new \Applications\PMTool\Models\Dao\Task_location();
        $tlDAO->setTask_id($target_task_id);
        $tlDAO->setLocation_id($tl->location_id());
        $tlDAO->setTask_location_status($tl->task_location_status());
        //Save
        $id = $dal->add($tlDAO);

      }
    }
  }

  /**
  * Task copy: Task_lab_analyte
  * Fetches the related Task_lab_analyte for the
  * original task and creates the relation with the new
  * Task created in "copyTaskWithDependencies"
  */
  public static function copyTaskTaskLabAnalyte($caller, $source_task_id, $target_task_id) {
    $tlaDAO = new \Applications\PMTool\Models\Dao\Task_lab_analyte();
    $tlaDAO->setTask_id($source_task_id);
    $dal = $caller->managers()->getManagerOf("Task");
    $allTlas = $dal->selectMany($tlaDAO, "task_id");

    if(count($allTlas) > 0) {
      //fals found, loop and remap with the new task id
      foreach($allTlas as $tla) {
        $tlaDAO = null;
        $tlaDAO = new \Applications\PMTool\Models\Dao\Task_lab_analyte();
        $tlaDAO->setTask_id($target_task_id);
        $tlaDAO->setLab_analyte_id($tla->lab_analyte_id());

        //Save
        $dal->add($tlaDAO);
      }
    }
  }

  /**
  * Task copy: Task_field_analyte
  * Fetches the related Task_field_analyte for the
  * original task and creates the relation with the new
  * Task created in "copyTaskWithDependencies"
  */
  public static function copyTaskTaskFieldAnalyte($caller, $source_task_id, $target_task_id) {
    $tfaDAO = new \Applications\PMTool\Models\Dao\Task_field_analyte();
    $tfaDAO->setTask_id($source_task_id);
    $dal = $caller->managers()->getManagerOf("Task");
    $allTfas = $dal->selectMany($tfaDAO, "task_id");

    if(count($allTfas) > 0) {
      //fals found, loop and remap with the new task id
      foreach($allTfas as $tfa) {
        $tfaDAO = null;
        $tfaDAO = new \Applications\PMTool\Models\Dao\Task_field_analyte();
        $tfaDAO->setTask_id($target_task_id);
        $tfaDAO->setField_analyte_id($tfa->field_analyte_id());

        //Save
        $dal->add($tfaDAO);
      }
    }
  }

  /**
  * Task copy: Task_coc_info
  * Fetches the related Task_coc_info for the
  * original task and creates the relation with the new
  * Task created in "copyTaskWithDependencies"
  */
  public static function copyTaskTaskCocInfo($caller, $source_task_id, $target_task_id) {
    $tciDAO = new \Applications\PMTool\Models\Dao\Task_coc_info();
    $tciDAO->setTask_id($source_task_id);
    $dal = $caller->managers()->getManagerOf("Task");
    $allTcis = $dal->selectMany($tciDAO, "task_id");

    if(count($allTcis) > 0) {
      //fals found, loop and remap with the new task id
      foreach($allTcis as $tci) {
        $tciDAO = null;
        $tciDAO = new \Applications\PMTool\Models\Dao\Task_coc_info();
        $tciDAO->setTask_id($target_task_id);
        $tciDAO->setService_id($tci->service_id());
        $tciDAO->setPo_number($tci->po_number());
        $tciDAO->setLab_instructions($tci->lab_instructions());
        $tciDAO->setLab_sample_type($tci->lab_sample_type());
        $tciDAO->setLab_sample_tat($tci->lab_sample_tat());
        $tciDAO->setProject_number($tci->project_number());
        $tciDAO->setResults_to_name($tci->results_to_name());
        $tciDAO->setResults_to_company($tci->results_to_company());
        $tciDAO->setResults_to_address($tci->results_to_address());
        $tciDAO->setResults_to_phone($tci->results_to_phone());
        $tciDAO->setResults_to_email($tci->results_to_email());
        //Save
        $dal->add($tciDAO);
      }
    }
  }

  /**
  * Task copy: Lab_analyte_location
  * Fetches the related Lab_analyte_location for the
  * original task and creates the relation with the new
  * Task created in "copyTaskWithDependencies"
  */
  public static function copyTaskLabAnalyteLocation($caller, $source_task_id, $target_task_id) {
    $lalDAO = new \Applications\PMTool\Models\Dao\Lab_analyte_location();
    $lalDAO->setTask_id($source_task_id);
    $dal = $caller->managers()->getManagerOf("Task");
    $allLals = $dal->selectMany($lalDAO, "task_id");

    if(count($allLals) > 0) {
      //fals found, loop and remap with the new task id
      foreach($allLals as $lal) {
        $lalDAO = null;
        $lalDAO = new \Applications\PMTool\Models\Dao\Lab_analyte_location();
        $lalDAO->setTask_id($target_task_id);
        $lalDAO->setLab_analyte_id($lal->lab_analyte_id());
        $lalDAO->setLocation_id($lal->location_id());
        //Save
        $dal->add($lalDAO);
      }
    }
  }

  /**
  * Task copy: Field_analyte_location
  * Fetches the related Field_analyte_location for the
  * original task and creates the relation with the new
  * Task created in "copyTaskWithDependencies"
  */
  public static function copyTaskFieldAnalyteLocation($caller, $source_task_id, $target_task_id) {
    $falDAO = new \Applications\PMTool\Models\Dao\Field_analyte_location();
    $falDAO->setTask_id($source_task_id);
    $dal = $caller->managers()->getManagerOf("Task");
    $allFals = $dal->selectMany($falDAO, "task_id");

    if(count($allFals) > 0) {
      //fals found, loop and remap with the new task id
      foreach($allFals as $fal) {
        $falDAO = null;
        $falDAO = new \Applications\PMTool\Models\Dao\Field_analyte_location();
        $falDAO->setTask_id($target_task_id);
        $falDAO->setField_analyte_id($fal->field_analyte_id());
        $falDAO->setLocation_id($fal->location_id());
        //Save
        $dal->add($falDAO);
      }
    }
  }

  public static function getFormsforTaskLocation($caller, $task_id, $loc_id) {
    $manager = $caller->managers()->getManagerOf('TaskLocation');
    $taskLocation = $manager->GetTaskLocations($loc_id, $task_id);
    $task_location_id = $taskLocation[0]->task_location_id();

    $manager = $caller->managers()->getManagerOf('Document');
    $locationForms = $manager->GetFormsForTaskLocation($task_location_id);

    $return_data = array(
                    'location_data' => $taskLocation[0],
                    'form_data'     => $locationForms
                  );

    return $return_data;
  }

  public static function GetLatestTaskForTechnician($caller,$technician) {
    $tasks = self::GetTasksForTechnician($caller, $technician);
    if(is_array($tasks)) {
      return $tasks[count($tasks)-1];
    } else {
      return null;
    }
  }

  public static function GetTasksForTechnician($caller, $technician) {
    $manager = $caller->managers()->getManagerOf('Task');
    $tasks = $manager->selectTasksByTechnician($technician);
    foreach($tasks as $task) {
      self::AddSessionTask($caller->user(),$task);
    }
    return $tasks;
  }

}
