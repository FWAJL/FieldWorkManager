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
 * PmHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	PmHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class PmHelper {

  public static function AddSessionPm($user, \Applications\PMTool\Models\Dao\Project_manager $pm) {
    $sessionPms = $user->getAttribute(\Library\Enums\SessionKeys::SessionPms);
    $sessionPms[\Library\Enums\SessionKeys::PmKey . $pm->pm_id()] = self::MakeSessionPm($pm);
    self::SetSessionPms($user, $sessionPms);
  }
  
  public static function DoesPmHaveActiveTechnicians(\Library\User $user) {
    $itDoes = FALSE;
    $currentPm = self::GetCurrentSessionPm($user);
    foreach ($currentPm[\Library\Enums\SessionKeys::PmTechnicians] as $technician) {
      if ($technician->technician_active()) { 
        $itDoes = TRUE;
        break;
      }
    }
    return $itDoes;
  }
  
  public static function DoesPmHaveActiveServices(\Library\User $user) {
    $itDoes = FALSE;
    $currentPm = self::GetCurrentSessionPm($user);
    foreach ($currentPm[\Library\Enums\SessionKeys::PmServices] as $service) {
      if ($service->service_active()) { 
        $itDoes = TRUE;
        break;
      }
    }
    return $itDoes;
  }
  

  public static function AddAProjectIdToList(\Library\User $user, $project_id) {
    $pmSession = self::GetSessionPm($user, 0);
    array_push($pmSession[\Library\Enums\SessionKeys::PmProjectIds], intval($project_id));
    self::SetSessionPm($user, $pmSession);
  }

  public static function GetAndStoreCurrentPm(\Library\User $user, $pm_id) {
    $sessionPms = NULL;
    if ($user->keyExistInSession(\Library\Enums\SessionKeys::SessionPms)) {
      $sessionPms = $user->getAttribute(\Library\Enums\SessionKeys::SessionPms);
    }

    //If there is no user session pms yet, create one with the pm id given
    if ($sessionPms !== NULL) {
      $key = \Library\Enums\SessionKeys::PmKey . $pm_id;
      $user->setAttribute(\Library\Enums\SessionKeys::CurrentPm, $sessionPms[$key]);
      return array_key_exists($key, $sessionPms) ?
              $sessionPms[$key][\Library\Enums\SessionKeys::PmObject] : NULL;
    }
    return NULL;
  }

  public static function GetSessionPm(\Library\User $user, $pm_id) {
    //retrieve the user session pm from pm_id
    $sessionPms = self::GetSessionPms($user);
    $key = \Library\Enums\SessionKeys::PmKey;
    if ($pm_id !== 0) {
      $key .= $pm_id;
    } else {
      $pm = $user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
      $key .= $pm[0]->pm_id();
    }
    $user->setAttribute(\Library\Enums\SessionKeys::CurrentPm, $sessionPms[$key]);
    return $sessionPms[$key];
  }

  public static function GetSessionPms($user) {
    return $user->getAttribute(\Library\Enums\SessionKeys::SessionPms);
  }

  public static function GetCurrentSessionPm($user) {
    return $user->keyExistInSession(\Library\Enums\SessionKeys::CurrentPm) ?
            $user->getAttribute(\Library\Enums\SessionKeys::CurrentPm) : FALSE;
  }

  public static function MakeSessionPm(\Applications\PMTool\Models\Dao\Project_manager $pm) {
    $sessionPm = array(
        \Library\Enums\SessionKeys::PmObject => $pm,
        \Library\Enums\SessionKeys::PmProjectIds => array(),
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
      $sessionPms[\Library\Enums\SessionKeys::PmKey . $pm_id] = $sessionPm;
      $user->setAttribute(\Library\Enums\SessionKeys::CurrentPm, $sessionPm);
      self::SetSessionPms($user, $sessionPms);
    }
  }

  public static function StoreSessionPm($user, \Applications\PMTool\Models\Dao\Project_manager $pm, $setCurrentPm) {
    $PmsSession = array();
    $PmsSession[\Library\Enums\SessionKeys::PmKey . $pm->pm_id()] = self::MakeSessionPm($pm);

    self::SetSessionPms($user, $PmsSession);
    if ($setCurrentPm) {
      self::GetAndStoreCurrentPm($user, $pm->pm_id());
    }
    return $PmsSession;
  }

  public static function UnsetSessionPm($user, $pm_id) {
    $sessionPms = $user->getAttribute(\Library\Enums\SessionKeys::SessionPms);
    unset($sessionPms[\Library\Enums\SessionKeys::PmKey . $pm_id]);
    $user->unsetAttribute(\Library\Enums\SessionKeys::CurrentPm);
    $user->setAttribute(\Library\Enums\SessionKeys::SessionPms, $sessionPms);
  }

  public static function UpdateSessionPm(\Library\User $user, $sessionPm) {
    $sessionPms = self::GetSessionPms($user);
    if ($sessionPms !== NULL) {
      $currentSessionPm = $user->getAttribute(\Library\Enums\SessionKeys::CurrentPm);
      $sessionPms[\Library\Enums\SessionKeys::PmKey . $sessionPm[\Library\Enums\SessionKeys::PmObject]->pm_id()]
              = $currentSessionPm
              = $sessionPm;
      self::SetSessionPm($user, $currentSessionPm);
      self::SetSessionPms($user, $sessionPms);
    }
  }

}

