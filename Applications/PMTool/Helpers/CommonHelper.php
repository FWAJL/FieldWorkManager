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
 * @category	CommonHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class CommonHelper {

  public static function GetAndStoreCurrentProject($caller, \Library\HttpRequest $rq) {
    $project_id = intval($rq->getData("project_id"));
    if ($caller->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserProjects)) {
      foreach ($caller->app()->user->getAttribute(\Library\Enums\SessionKeys::UserProjects) as $project) {
        if (intval($project->project_id()) === $project_id) {
          $caller->app()->user->setAttribute(\Library\Enums\SessionKeys::CurrentProject, $project);
          CommonHelper::ManageProjectsSession($caller->app()->user(), $project_id);
          return TRUE;
        }
      }
    }
    return FALSE;
  }

  /**
   * To store the projects's information, we use associative arrays
   * 
   * The structure is the following and you need to access the array using a key
   * 
   *  array (
   *     "projectId_X" => array(
   *       "locations" => array( of Location objects),
   *       "tasks" => array( of Task objects),
   *       "technicians" => array( of Technician objects)
   *     ),
   *     "projectId_Y" => array(
   *       "locations" => array( of Location objects),
   *      "tasks" => array( of Task objects),
   *      "technicians" => array( of Technician objects)
   *    )
   *  )
   * 
   * @param \Library\User $user
   * @param \Library\HttpRequest $rq
   * @return array : the list of projects with dependent data, e.g. locations, tasks, technicians 
   * 
   */
  public static function ManageProjectsSession(\Library\User $user, $project_id) {
    //Check if a session item exists and add to the existing
    if ($user->keyExistInSession(\Library\Enums\SessionKeys::UserSessionProjects)) {
      $ExistingSession = $user->getAttribute(\Library\Enums\SessionKeys::UserSessionProjects);
      //only add if not already in array
      if (!array_key_exists($project_id, $ExistingSession)) {
        array_push($ExistingSession, CommonHelper::MakeNewObject($project_id));
      }
      $user->setAttribute(\Library\Enums\SessionKeys::UserSessionProjects, $ExistingSession);
      return $ExistingSession;
    } else {
      //If not, init a new array
      $NewProjectsSession = CommonHelper::MakeNewObject($project_id);
      $user->setAttribute(\Library\Enums\SessionKeys::UserSessionProjects, $NewProjectsSession);
      return $NewProjectsSession;
    }
  }

  public static function MakeNewObject($project_id) {
    return array(
        $project_id => array(
            \Library\Enums\SessionKeys::ProjectLocations => array(),
            \Library\Enums\SessionKeys::ProjectTasks => array(),
            \Library\Enums\SessionKeys::ProjectTechnicians => array(),
            //Add a line for data linked to a project, e.g. results/reports?
        )
    );
  }
  
  public static function StringToArray($delimiter, $string) {
    $arrayRaw = explode($delimiter, $string);
    $arrayCleaned = array();
    foreach ($arrayRaw as $value) {
      array_push($arrayCleaned, CommonHelper::CleanString($value));
    }
    return $arrayCleaned;
  }

  public static function CleanString($string) {
    return trim($string);
  }
}