<?php

/**
 *
 * @package		Basic MVC framework
 * @author		Jeremie Litzler
 * @copyright	Copyright (c) 2014
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
      \Applications\PMTool\Resources\Enums\TaskTabKeys::FieldSampleMatrixTab => "",
      \Applications\PMTool\Resources\Enums\TaskTabKeys::CocTab => "",
      \Applications\PMTool\Resources\Enums\TaskTabKeys::LabAnalytesTab => "",
      \Applications\PMTool\Resources\Enums\TaskTabKeys::LabSampleMatrixTab => "",
      \Applications\PMTool\Resources\Enums\TaskTabKeys::ServicesTab => ""
    );
    $user->setAttribute(\Library\Enums\SessionKeys::TabsStatus, $tabs);
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

  public static function StoreSessionTask($user, $list) {
    $SessionTasks = array();
    $currentProject = ProjectHelper::GetCurrentSessionProject($user);
    foreach ($list as $task) {
      $SessionTasks[\Library\Enums\SessionKeys::TaskKey . $task->task_id()] = self::MakeSessionTask($task);
      array_push($currentProject[\Library\Enums\SessionKeys::ProjectTasks], \Library\Enums\SessionKeys::TaskKey . $task->task_id());
    }
    ProjectHelper::SetUserSessionProject($user, $currentProject);
    self::SetSessionTasks($user, $SessionTasks);
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

}
