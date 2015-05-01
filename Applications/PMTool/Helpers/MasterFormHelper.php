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
 *  MasterFormHelper Class
 *
 * @package		Applications\PMTool
 * @subpackage	Helpers
 * @author		Jeremie Litzler
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class MasterFormHelper extends \Library\ApplicationComponent {

  public static function GetFormFromTaskTemplateFrom($caller, $template) {
    $masterformDAO = new \Applications\PMTool\Models\Dao\Master_form();
    $masterformDAO->setForm_id($template->master_form_id());
    $dal = $caller->managers()->getManagerOf("Task");
    return $dal->selectMany($masterformDAO, "form_id");
  }

}