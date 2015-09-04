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
      case \Library\Enums\UserRoleType::Admin:
        $roleId = \Library\Enums\UserRole::Admin;
        break;
      case \Library\Enums\UserRoleType::ProjectManager:
        $roleId = \Library\Enums\UserRole::ProjectManager;
        break;
      case \Library\Enums\UserRoleType::Technician:
        $roleId = \Library\Enums\UserRole::Technician;
        break;
      case \Library\Enums\UserRoleType::Client:
        $roleId = \Library\Enums\UserRole::Client;
        break;
      case \Library\Enums\UserRoleType::Service:
        $roleId = \Library\Enums\UserRole::Service;
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

  /**
   * Add new user in session
   */
  public static function AddNewUserToSession($caller, $user) {
    $users = self::GetAndStoreUsersInSession($caller);
    $users[] = $user;
    $caller->app()->user->setAttribute(
        \Library\Enums\SessionKeys::AllUsers, $users
    );
  }

  /**
   * Categorize user list by type
   */
  public static function CategorizeUsersList($users) {
    $list = array();
    if (is_array($users) && count($users) > 0) {
      foreach ($users as $user) {
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
    $user->setUser_hint(array_key_exists("user_hint", $dataPost) ? $dataPost['user_hint'] : "");
    $user->setUser_login(array_key_exists("user_login", $dataPost) ? $dataPost['user_login'] : "");
    $user->setUser_email(array_key_exists("pm_email", $dataPost) ? $dataPost['pm_email'] : "jl".\Library\Core\Utility\UUID::v4()."@test.com");
    if($setPass==TRUE) {
      $user->setUser_password($protect->Encrypt($config->get("encryption_key"), $dataPost['user_password']));
    }

    return $user;
  }

  public static function PrepareTechnicianObject($dataPost, $oldObject = null) {
    if(!is_null($oldObject)){
      $technician = $oldObject;
    } else {
      $technician = new \Applications\PMTool\Models\Dao\Technician();
    }

    $technician->setTechnician_id($dataPost["technician_id"]);
    $technician->setTechnician_name($dataPost["technician_name"]);
    $technician->setTechnician_phone(!array_key_exists('technician_phone', $dataPost) ? "" : $dataPost["technician_phone"]);
    $technician->setTechnician_active(!array_key_exists('technician_active', $dataPost) ? "" : $dataPost["technician_active"]);
    return $technician;
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

  public static function FindUserTypeFromObject($object) {
    if ($object instanceof \Applications\PMTool\Models\Dao\Technician) {
      return 'technician_id';
    } else if ($object instanceof \Applications\PMTool\Models\Dao\Service) {
      return 'service_id';
    } else if ($object instanceof \Applications\PMTool\Models\Dao\Project_manager) {
      return 'pm_id';
    } else {
      return '';
    }
  }

  public static function AddUser($caller, $user_value, $user_role_id, $user_type = NULL) {
    if ($user_type === NULL) {
      $user_type = self::GetTypeFromRoleId($user_role_id);
    }
    $dataPost = $caller->dataPost();
    $userEmail = self::GetEMailForUser($caller, $dataPost, $user_type, $user_value);
    $manager = $caller->managers()->getManagerOf('User');
    $generatedDataPost = array(
      'user_login' => $userEmail,
      'user_password' => $userEmail,
      'user_email' => $userEmail,
      'user_hint' => '',
      'user_role_id'=> $user_role_id,
      'user_type' => $user_type,
      'user_value' => $user_value
    );
    $user = CommonHelper::PrepareUserObject($generatedDataPost, new \Applications\PMTool\Models\Dao\User());
    $protect = new \Library\BL\Core\Encryption();
    $user->setUser_password($protect->Encrypt($caller->app()->config()->get("encryption_key"), $user->user_password()));
    return $manager->add($user);
  }
  public static function GetEmailForUser($caller, $dataPost, $user_type, $user_value) {
    if (is_null($dataPost['user_email']) || $dataPost['user_email'] == '') {
      return ($user_type . '_' . $user_value . '@' . $caller->app()->config()->get(\Library\Enums\AppSettingKeys::DefaultEmailDomainValue)); 
    } else {
      return $dataPost['user_email'];
    }
  }
  
  public static function GetTypeFromRoleId($role_id) {
    switch ($role_id) {
      case \Library\Enums\UserRole::Admin:
        return \Library\Enums\UserRoleType::Admin;
      case \Library\Enums\UserRole::ProjectManager:
        return \Library\Enums\UserRoleType::ProjectManager;
      case \Library\Enums\UserRole::Technician:
        return \Library\Enums\UserRoleType::Technician;
      case \Library\Enums\UserRole::Client:
        return \Library\Enums\UserRoleType::Client;
      case \Library\Enums\UserRole::Service:
        return \Library\Enums\UserRoleType::Service;
      default://role = 4 and others
        return "";
    }
  }
  
  public static function EditUser($caller, $type) {
    $dataPost = $caller->dataPost();
    //Get user from Session
    //
    //
    //Then update email from given value
    //$user = new \Applications\PMTool\Models\Dao\User();
    //
    //$user->setUser_email($dataPost[$type + "_email"]);
  }
  
  public static function GetGeneratedPostArray($originalPost) {
    
  }
}
