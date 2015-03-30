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

}
