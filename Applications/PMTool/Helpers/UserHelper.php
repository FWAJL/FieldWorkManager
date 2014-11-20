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
 * @category	UserHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class UserHelper {
  public static function AddSessionPm($user, \Applications\PMTool\Models\Dao\Pm_manager $pm) {
    $sessionPms = $user->getAttribute(\Library\Enums\SessionKeys::SessionPms);
    $sessionPms[\Library\Enums\SessionKeys::PmKey . $pm->pm_id()] = self::MakeSessionPm($pm);
    self::SetSessionPms($user, $sessionPms);
  }

  public static function GetAndStoreCurrentPm(\Library\User $user, $pm_id) {
    $userSessionPms = NULL;
    if ($user->keyExistInSession(\Library\Enums\SessionKeys::SessionPms)) {
      $userSessionPms = $user->getAttribute(\Library\Enums\SessionKeys::SessionPms);
    }

    //If there is no user session pms yet, create one with the pm id given
    if ($userSessionPms !== NULL) {
      $key = \Library\Enums\SessionKeys::PmKey . $pm_id;
      $user->setAttribute(\Library\Enums\SessionKeys::CurrentPm, $userSessionPms[$key]);
      return array_key_exists($key, $userSessionPms) ?
              $userSessionPms[$key][\Library\Enums\SessionKeys::PmObject] : NULL;
    }
    return NULL;
  }

  public static function GetSessionPm(\Library\User $user, $pm_id) {
    //retrieve the user session pm from pm_id
    $userSessionPms = self::GetSessionPms($user);
    $key = \Library\Enums\SessionKeys::PmKey . $pm_id;
    $user->setAttribute(\Library\Enums\SessionKeys::CurrentPm, $userSessionPms[$key]);
    return $userSessionPms[$key];
  }

  public static function GetSessionPms($user) {
    return $user->getAttribute(\Library\Enums\SessionKeys::SessionPms);
  }

  public static function GetCurrentSessionPm($user) {
    return $user->keyExistInSession(\Library\Enums\SessionKeys::CurrentPm) ?
            $user->getAttribute(\Library\Enums\SessionKeys::CurrentPm) : FALSE;
  }

  public static function MakeSessionPm(\Applications\PMTool\Models\Dao\Pm_manager $pm) {
    $sessionPm = array(
        \Library\Enums\SessionKeys::PmObject => $pm,
        \Library\Enums\SessionKeys::PmPmIds => array(),
        \Library\Enums\SessionKeys::PmTechnicians => array(),
        \Library\Enums\SessionKeys::PmServices => array(),
        \Library\Enums\SessionKeys::PmFieldAnalytes => array(),
        \Library\Enums\SessionKeys::PmLabAnalytes => array()
    );
    return $sessionPm;
  }

  public static function RedirectAfterPmSelection(\Library\Application $app, $pm_id) {
    $redirect = FALSE;

    if ($app->user()->keyExistInSession(\Library\Enums\SessionKeys::CurrentPm)) {
      return TRUE;
    }

    if ($pm_id === 0) {
      return FALSE;
    } else {
      $pm = self::GetAndStoreCurrentPm($app->user(), $pm_id);
      if ($pm == !NULL) {
        return TRUE;
      }
    }
  }

  public static function SetSessionPms($user, $pms) {
    $user->setAttribute(\Library\Enums\SessionKeys::SessionPms, $pms);
  }

  public static function SetSessionPm(\Library\User $user, $sessionPm) {
    $sessionPms = $user->getAttribute(\Library\Enums\SessionKeys::SessionPms);
    $pm_id = $sessionPm[\Library\Enums\SessionKeys::PmObject]->pm_id();
    if (array_key_exists(\Library\Enums\SessionKeys::PmKey . $pm_id, $sessionPms)) {
      $userSessionPms[\Library\Enums\SessionKeys::PmKey . $pm_id] = $sessionPm;
      $user->setAttribute(\Library\Enums\SessionKeys::CurrentPm, $sessionPm);
      self::SetSessionPms($user,$sessionPms);
    }
  }

  public static function StoreSessionPms($user, $lists) {
    $PmsSession = array();
    foreach ($lists[\Library\Enums\SessionKeys::UserPms] as $pm) {
      $PmsSession[\Library\Enums\SessionKeys::PmKey . $pm->pm_id()] = self::MakeSessionPm($pm);
    }

    $PmsSession = self::StoreProjectIds($PmsSession, $lists);
    $PmsSession = self::StoreTechnicians($PmsSession, $lists);
    $PmsSession = self::StoreServiceProviders($PmsSession, $lists);
    $PmsSession = self::StoreFieldAnalytes($PmsSession, $lists);
    $PmsSession = self::StoreLabAnalytes($PmsSession, $lists);
    
    self::SetSessionPms($user, $PmsSession);
    return $PmsSession;
  }

  private static function StoreProjectIds($PmsSession, $lists) {

    return $PmsSession;
  }
  private static function StoreTechnicians($PmsSession, $lists) {

    return $PmsSession;
  }
  private static function StoreServiceProviders($PmsSession, $lists) {
    //TODO: Implement the Dal method is done
    return $PmsSession;
  }
  private static function StoreFieldAnalytes($PmsSession, $lists) {
    //TODO: Implement the Dal method is done
    return $PmsSession;
  }
  private static function StoreLabAnalytes($PmsSession, $lists) {
    //TODO: Implement the Dal method is done
    return $PmsSession;
  }

  public static function UnsetUserSessionPm($user, $pm_id) {
    $userSessionPms = $user->getAttribute(\Library\Enums\SessionKeys::SessionPms);
    unset($userSessionPms[\Library\Enums\SessionKeys::PmKey . $pm_id]);
    $user->unsetAttribute(\Library\Enums\SessionKeys::CurrentPm);
    $user->setAttribute(\Library\Enums\SessionKeys::SessionPms, $userSessionPms);
  }

  public static function UpdateUserSessionPm(\Library\User $user, $sessionPm) {
    $userSessionPms = self::GetSessionPms($user);
    if ($userSessionPms !== NULL) {
      $currentSessionPm = $user->getAttribute(\Library\Enums\SessionKeys::CurrentPm);
      $userSessionPms[\Library\Enums\SessionKeys::PmKey . $sessionPm[\Library\Enums\SessionKeys::PmObject]->pm_id()]
              = $currentSessionPm
              = $sessionPm;
      self::SetUserSessionPm($user, $currentSessionPm);
      self::SetSessionPms($user, $userSessionPms);
    }
  }

}

