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
 * _TemplateHelper Class
 *
 * @package		Library
 * @subpackage	Utility
 * @category	DebugHelper
 * @author		Jeremie Litzler
 * @link		
 */

namespace Library\Utility;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class DebugHelper {
  public static function LogAsHtmlComment($data_to_print) {
    echo '<!--'.$data_to_print.'-->';
  }
}
