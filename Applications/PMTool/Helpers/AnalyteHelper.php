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
 * AnalyteHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	AnalyteHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class AnalyteHelper {
  public static function GetListData($caller) {
    $sessionPm = PmHelper::GetCurrentSessionPm($caller->user());
    if (!count($sessionPm[\Library\Enums\SessionKeys::PmFieldAnalytes]) > 0) {
      $analyte = new \Applications\PMTool\Models\Dao\Field_analyte();
      $analyte->setPm_id($sessionPm[\Library\Enums\SessionKeys::PmObject]->pm_id());
      $dal = $caller->managers()->getManagerOf($caller->module());
      $analytes = $dal->selectMany($analyte, "pm_id");
    }
    return $sessionPm;
  }
}
