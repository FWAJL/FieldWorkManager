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
 * @subpackage	Controllers
 * @category	ProjectHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ProjectHelper {

  public static function AddSessionProject($user, \Applications\PMTool\Models\Dao\Project $project) {
    $sessionProjects = $user->getAttribute(\Library\Enums\SessionKeys::UserSessionProjects);
    $sessionProjects[\Library\Enums\SessionKeys::ProjectKey . $project->project_id()] = self::MakeSessionProject($project);
    self::SetSessionProjects($user, $sessionProjects);
  }

  public static function GetAndStoreCurrentProject(\Library\User $user, $project_id) {
    $userSessionProjects = NULL;
    if ($user->keyExistInSession(\Library\Enums\SessionKeys::UserSessionProjects)) {
      $userSessionProjects = $user->getAttribute(\Library\Enums\SessionKeys::UserSessionProjects);
    }

    //If there is no user session projects yet, create one with the project id given
    if ($userSessionProjects !== NULL) {
      $key = \Library\Enums\SessionKeys::ProjectKey . $project_id;
      $user->setAttribute(\Library\Enums\SessionKeys::CurrentProject, $userSessionProjects[$key]);
      return array_key_exists($key, $userSessionProjects) ?
              $userSessionProjects[$key][\Library\Enums\SessionKeys::ProjectObject] : NULL;
    }
    return NULL;
  }

  public static function GetUserSessionProject(\Library\User $user, $project_id) {
    //retrieve the user session project from project_id
    $userSessionProjects = self::GetSessionProjects($user);
    $key = \Library\Enums\SessionKeys::ProjectKey . $project_id;
    $user->setAttribute(\Library\Enums\SessionKeys::CurrentProject, $userSessionProjects[$key]);
    return $userSessionProjects[$key];
  }

  public static function GetSessionProjects($user) {
    return $user->getAttribute(\Library\Enums\SessionKeys::UserSessionProjects);
  }

  public static function GetCurrentSessionProject($user) {
    return $user->getAttribute(\Library\Enums\SessionKeys::CurrentProject);
  }

  public static function ManageProjectsSession(\Library\User $user, \Applications\PMTool\Models\Dao\Project $project) {
//Check if a session item exists and add to the existing
    if ($user->keyExistInSession(\Library\Enums\SessionKeys::UserSessionProjects)) {
      $ExistingProjectsSession = $user->getAttribute(\Library\Enums\SessionKeys::UserSessionProjects);
//only add if not already in array
      if (!array_key_exists($project->project_id(), $ExistingProjectsSession)) {
        $ExistingProjectsSession[\Library\Enums\SessionKeys::ProjectKey . $project->project_id()] = CommonHelper::MakeSessionProject($project);
      }
      $user->setAttribute(\Library\Enums\SessionKeys::UserSessionProjects, $ExistingProjectsSession);
      return $ExistingProjectsSession;
    } else {
//If not, init a new array
      $ProjectsSession = array();
      $ProjectsSession[\Library\Enums\SessionKeys::ProjectKey . $project->project_id()] = CommonHelper::MakeSessionProject($project);
      $user->setAttribute(\Library\Enums\SessionKeys::UserSessionProjects, $ProjectsSession);
      return $ProjectsSession;
    }
  }

  public static function MakeSessionProject(\Applications\PMTool\Models\Dao\Project $project) {
    $arrayToReturn = array(
        \Library\Enums\SessionKeys::ProjectObject => $project,
        \Library\Enums\SessionKeys::FacilityObject => NULL,
        \Library\Enums\SessionKeys::ClientObject => NULL,
        \Library\Enums\SessionKeys::ProjectLocations => array(),
        \Library\Enums\SessionKeys::ProjectTasks => array(),
        \Library\Enums\SessionKeys::UserTechnicians => array(),
        \Library\Enums\SessionKeys::UserResources => array()
            //Add a line for data linked to a project, e.g. results/reports?
    );
    return $arrayToReturn;
  }

  public static function RedirectAfterProjectSelection(\Library\Application $app, $project_id) {
    $project = \Applications\PMTool\Helpers\ProjectHelper::GetAndStoreCurrentProject($app->user(), $project_id);
    $redirect = FALSE;
    if ($project == !NULL) {
//      \Applications\PMTool\Helpers\ProjectHelper::SetUserSessionProject($app->user(), $project);
      $redirect = TRUE;
    } else if ($app->user->getAttribute(\Library\Enums\SessionKeys::CurrentProject) !== NULL) {
      $redirect = TRUE;
    }
    return $redirect;
  }

  public static function SetSessionProjects($user, $projects) {
    $user->setAttribute(\Library\Enums\SessionKeys::UserSessionProjects, $projects);
  }

  public static function SetUserSessionProject(\Library\User $user, $sessionProject) {
    $userSessionProjects = $user->getAttribute(\Library\Enums\SessionKeys::UserSessionProjects);
    $project_id = $sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id();
    if (array_key_exists(\Library\Enums\SessionKeys::ProjectKey . $project_id, $userSessionProjects)) {
      $userSessionProjects[\Library\Enums\SessionKeys::ProjectKey . $project_id] = $sessionProject;
      $user->setAttribute(\Library\Enums\SessionKeys::CurrentProject, $sessionProject);
      $user->setAttribute(\Library\Enums\SessionKeys::UserSessionProjects, $userSessionProjects);
    }
  }

  public static function StoreSessionProjects($user, $lists) {
    $ProjectsSession = array();
    foreach ($lists[\Library\Enums\SessionKeys::UserProjects] as $project) {
      $ProjectsSession[\Library\Enums\SessionKeys::ProjectKey . $project->project_id()] = self::MakeSessionProject($project);
    }

    foreach ($lists[\Library\Enums\SessionKeys::UserProjectFacilityList] as $facility) {
      foreach ($ProjectsSession as $sessionProject) {
        $project_id = intval($sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id());
        if (intval($facility->project_id()) === $project_id) {
          $ProjectsSession[\Library\Enums\SessionKeys::ProjectKey . $project_id][\Library\Enums\SessionKeys::FacilityObject] = $facility;
          break;
        }
      }
    }

    foreach ($lists[\Library\Enums\SessionKeys::UserProjectClientList] as $client) {
      foreach ($ProjectsSession as $sessionProject) {
        $project_id = intval($sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id());
        if (intval($client->project_id()) === $project_id) {
          $ProjectsSession[\Library\Enums\SessionKeys::ProjectKey . $project_id][\Library\Enums\SessionKeys::ClientObject] = $client;
          break;
        }
      }
    }
    self::SetSessionProjects($user, $ProjectsSession);
    return $ProjectsSession;
  }

  public static function UnsetUserSessionProject($user, $project_id) {
    $userSessionProjects = $user->getAttribute(\Library\Enums\SessionKeys::UserSessionProjects);
    unset($userSessionProjects[\Library\Enums\SessionKeys::ProjectKey . $project_id]);
    $user->unsetAttribute(\Library\Enums\SessionKeys::CurrentProject);
    $user->setAttribute(\Library\Enums\SessionKeys::UserSessionProjects, $userSessionProjects);
  }

  public static function UpdateUserSessionProject(\Library\User $user, $sessionProject) {
    $userSessionProjects = self::GetSessionProjects($user);
    if ($userSessionProjects !== NULL) {
      $currentSessionProject = $user->getAttribute(\Library\Enums\SessionKeys::CurrentProject);
      $userSessionProjects[\Library\Enums\SessionKeys::ProjectKey . $sessionProject[\Library\Enums\SessionKeys::ProjectObject]->project_id()]
              = $currentSessionProject
              = $sessionProject;
      self::SetUserSessionProject($user, $currentSessionProject);
      self::SetSessionProjects($user, $userSessionProjects);
    }
  }

}

