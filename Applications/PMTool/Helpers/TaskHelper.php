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
    if ($task_id > 0 && $sessionTask === NULL) {
      $sessionTasks = self::GetSessionTasks($user);
      $sessionTask = $sessionTasks[\Library\Enums\SessionKeys::TaskKey . $task_id];
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

  public static function StoreSessionTask($user, $list) {
    $SessionTasks = array();
    $currentProject = ProjectHelper::GetCurrentSessionProject($user);
    $countActiveTask = 0;
    foreach ($list as $task) {
      $key = \Library\Enums\SessionKeys::TaskKey . $task->task_id();
      $sessionTask = self::MakeSessionTask($task);
      $SessionTasks[$key] = $sessionTask;
      array_push($currentProject[\Library\Enums\SessionKeys::ProjectTasks], $key);
      if ($task->task_active()) {
        $countActiveTask += 1;
        $currentSessionTask = $sessionTask; //Get sessin task object
      }
    }
    ProjectHelper::SetUserSessionProject($user, $currentProject);
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
                \Applications\PMTool\Resources\Enums\ViewVariables\Task::task_req_form    => $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_req_form(),
                \Applications\PMTool\Resources\Enums\ViewVariables\Task::task_req_field_analyte => $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_req_field_analyte(),
                \Applications\PMTool\Resources\Enums\ViewVariables\Task::task_req_lab_analyte => $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_req_lab_analyte(),
              );
    return $ret_val;
  }

  /**
  * For the passed task ID, creates location specific 
  * PDF forms from PDF template
  */
  public static function CreateLocationSpecificPDF($task_id, $sessionProject, $caller) {
    //Fetch the locations for this task
    $taskLocationDAO = new \Applications\PMTool\Models\Dao\Task_location();
    $taskLocationDAO->setTask_id($task_id);
    $dal = $caller->managers()->getManagerOf("Task");
    $location_data = $dal->selectMany($taskLocationDAO, "task_id");

    //Fetch the template file relation for this task
    $templateDAO = new \Applications\PMTool\Models\Dao\Task_template_form();
    $templateDAO->setTask_id($task_id);
    $template_data = $dal->selectMany($templateDAO, "task_id");
    
    //Loop on all the template files
    foreach($template_data as $template){
      //And finally the file itself, which needs to be copied
      $masterformDAO = new \Applications\PMTool\Models\Dao\Master_form();
      $masterformDAO->setForm_id($template->master_form_id());
      $masterform_data = $dal->selectMany($masterformDAO, "form_id");

      //Pseudo array for file based on the master file
      $files['file'] = array(
                              'name'      => $masterform_data[0]->value(),
                              'type'      => $masterform_data[0]->content_type(),
                              'tmp_name'  => './uploads/master_form/' . $masterform_data[0]->value(),
                              'error'     => 0,
                              'size'      => $masterform_data[0]->size() * 1000
                            );

      //So now we have to loop over the locations and create multiple files
      foreach($location_data as $loc_key => $loc_val) {

        //We have to get the Location names too
        $location_record = \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById($loc_val->location_id(), 
                "location_id", $sessionProject, \Library\Enums\SessionKeys::ProjectLocations);
        $location_object = null;
        foreach($location_record as $elem){
          $location_object = $elem; break;
        }

        $dataPost = null;
        //Create the 'Document' specific array
        $dataPost['itemCategory'] = 'task_location';
        $dataPost['itemId']       = $loc_val->task_location_id();
        $dataPost['title']        = $masterform_data[0]->title() . '_' . $location_object->location_name();
        $dataPost['itemReplace']  = false;

        \Library\Controllers\FileController::copyFile($files, $dataPost, $caller);
      }
    }
  }
}
