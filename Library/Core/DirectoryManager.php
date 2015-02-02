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
 * DirectoryManager Class
 *
 * @package		Library
 * @subpackage	Core
 * @category	DirectoryManager
 * @author		Jeremie Litzler
 * @link		
 */

namespace Library\Core;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class DirectoryManager {
  public static function GetFileNames($dir) {
    return array_diff(scandir($dir), array("..", "."));
  }
}
