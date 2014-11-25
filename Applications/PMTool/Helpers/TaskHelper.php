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
    $sessionTasks[\Library\Enums\SessionKeys::TaskKey . $task->task_id()] = self::MakeSessionTask($task);
    self::SetSessionTasks($user, $sessionTasks);
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

  public static function GetSessionTasks($user) {
    return $user->getAttribute(\Library\Enums\SessionKeys::SessionTasks);
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
    $redirect = TRUE;
    if (self::GetSessionTasks($user) != NULL) {
      $redirect = count(self::GetSessionTasks($user)) > 0;
    }
    return $redirect;
  }

  public static function SetSessionTasks($user, $tasks) {
    $user->setAttribute(\Library\Enums\SessionKeys::SessionTasks, $tasks);
  }

  public static function SetSessionTask(\Library\User $user, $sessionTask) {
    $sessionTasks = $user->getAttribute(\Library\Enums\SessionKeys::SessionTasks);
    $task_id = $sessionTask[\Library\Enums\SessionKeys::TaskObject]->task_id();
    if (array_key_exists(\Library\Enums\SessionKeys::TaskKey . $task_id, $sessionTasks)) {
      $sessionTasks[\Library\Enums\SessionKeys::TaskKey . $task_id] = $sessionTask;
      $user->setAttribute(\Library\Enums\SessionKeys::CurrentTask, $sessionTask);
      self::SetSessionTasks($user, $sessionTasks);
    }
  }

  public static function StoreSessionTask($user, $list) {
    $SessionTasks = array();
    foreach ($list as $task) {
      $SessionTasks[\Library\Enums\SessionKeys::TaskKey . $task->task_id()] = self::MakeSessionProject($task);
    }

    self::SetSessionTasks($user, $SessionTasks);
    return $SessionTasks;
  }

  public static function UnsetUserSessionTask($user, $task_id) {
    $sessionTasks = $user->getAttribute(\Library\Enums\SessionKeys::SessionTasks);
    unset($sessionTasks[\Library\Enums\SessionKeys::TaskKey . $task_id]);
    $user->unsetAttribute(\Library\Enums\SessionKeys::CurrentTask);
    $user->setAttribute(\Library\Enums\SessionKeys::SessionTasks, $sessionTasks);
  }

  public static function UpdateSessionTask(\Library\User $user, $sessionTask) {
    $sessionTasks = self::GetSessionTasks($user);
    if ($sessionTasks !== NULL) {
      $currentSessionTask = $user->getAttribute(\Library\Enums\SessionKeys::CurrentTask);
      $sessionTasks[\Library\Enums\SessionKeys::TaskKey . $sessionTask[\Library\Enums\SessionKeys::TaskObject]->task_id()]
              = $currentSessionTask
              = $sessionTask;
      self::SetSessionTask($user, $currentSessionTask);
      self::SetSessionTasks($user, $sessionTasks);
    }
  }

}

