<?php
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

class ActiveTaskHelper {
	
  public static function AddTabsStatus(\Library\User $user) {
    $tabs = array(
        \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskStatusTab => "active",
        \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskMapTab => "",
        \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskFormsTab => "",
        \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskCommTab => "",
    );
    $user->setAttribute(\Library\Enums\SessionKeys::ActiveTaskTabsStatus, $tabs);
  }
	
  public static function GetTabsStatus(\Library\User $user) {
    return $user->getAttribute(\Library\Enums\SessionKeys::ActiveTaskTabsStatus);
  }
  
  public static function SetActiveTab(\Library\User $user, $tab_name) {
    $tabs = $user->getAttribute(\Library\Enums\SessionKeys::ActiveTaskTabsStatus);
    foreach ($tabs as $key => $value) {
      $tabs[$key] = "";
    }
    $tabs[$tab_name] = "active";
    $user->setAttribute(\Library\Enums\SessionKeys::ActiveTaskTabsStatus, $tabs);
  }
}