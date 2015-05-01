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
 *  UserFormHelper Class
 *
 * @package		Applications\PMTool
 * @subpackage	Helpers
 * @author		Jeremie Litzler
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class UserFormHelper extends \Library\ApplicationComponent {

  public static function GetFormFromTaskTemplateFrom($caller, $template) {
    $userformDAO = new \Applications\PMTool\Models\Dao\User_form();
    $userformDAO->setForm_id($template->user_form_id());
    $dal = $caller->managers()->getManagerOf("Task");
    return $dal->selectMany($userformDAO, "form_id");
  }

}