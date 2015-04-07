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
 * UserHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	UserHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class UserHelper {

  public static function SaveRoutes($user, $routes) {
    $user->setAttribute(\Library\Enums\SessionKeys::UserRoutes, $routes);
  }

  public static function GetUserConnectedSession($user) {
    return $user->keyExistInSession(\Library\Enums\SessionKeys::UserConnected) ?
            $user->getAttribute(\Library\Enums\SessionKeys::UserConnected) : FALSE;
  }

  public static function GetRoleFromType($userType) {
    $roleId = 1;
    switch ($userType) {
      case "administrator_id":
        $roleId = 1;
        break;
      case "pm_id":
        $roleId = 2;
        break;
      case "technician_id":
        $roleId = 3;
        break;
      default:
        $roleId = 0;
        break;
    }
    return $roleId;
  }

  /**
   * Checks if the users  are not stored in Session.
   * Stores the users
   * Set the data into the session for later use.
   *
   * @param /Library/HttpRequest $rq
   * @return array $lists : the lists of objects if any
   */
  public static function GetAndStoreUsersInSession($caller) {
    $users = array();
    if (!$caller->app()->user()->keyExistInSession(\Library\Enums\SessionKeys::AllUsers)) {
      $manager = $caller->managers()->getManagerOf($caller->module());
      $users = $manager->selectAllUsers();

      $caller->app()->user->setAttribute(
        \Library\Enums\SessionKeys::AllUsers, $users
      );
    } else {
      $users = $caller->app()->user->getAttribute(\Library\Enums\SessionKeys::AllUsers);
    }
    return $users;
  }
  /*
   * Add new user in session
   */
  public static function AddNewUserToSession($caller,$user) {
    $users = self::GetAndStoreUsersInSession($caller);
    $users[] = $user;
    $caller->app()->user->setAttribute(
      \Library\Enums\SessionKeys::AllUsers, $users
    );
  }

  /*
   * Categorize user list by type
   */
  public static function CategorizeUsersList($users) {
    $list = array();
    if(is_array($users) && count($users)>0) {
      foreach($users as $user) {
        $userType = $user->user_type();
        $list[$userType][] = $user;
      }
    }
    return $list;
  }

  public static function SetPropertyNamesForDualList() {
    return array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id => "user_id",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name => "user_login"
    );
  }

  public static function PrepareUserObject($dataPost,$config, $setPass = FALSE) {
    $user = new \Applications\PMTool\Models\Dao\User();
    $protect = new \Library\BL\Core\Encryption();
    $user->setUser_hint($dataPost['user_hint']);
    $user->setUser_login($dataPost['user_login']);
    if($setPass==TRUE) {
      $user->setUser_password($protect->Encrypt($config->get("encryption_key"), $dataPost['user_password']));
    }

    return $user;
  }

  public static function PreparePmObject($dataPost) {
    $pm = new \Applications\PMTool\Models\Dao\Project_manager();
    $pm->setPm_id($dataPost["pm_id"]);
    $pm->setPm_name($dataPost["pm_name"]);
    $pm->setPm_address(!array_key_exists('pm_address', $dataPost) ? "" : $dataPost["pm_address"]);
    $pm->setPm_comp_name(!array_key_exists('pm_comp_name', $dataPost) ? "" : $dataPost["pm_comp_name"]);
    $pm->setPm_phone(!array_key_exists('pm_phone', $dataPost) ? "" : $dataPost["pm_phone"]);
    $pm->setPm_email(!array_key_exists('pm_email', $dataPost) ? "" : $dataPost["pm_email"]);
    return $pm;
  }

}
