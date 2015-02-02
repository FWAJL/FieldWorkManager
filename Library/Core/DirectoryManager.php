<?php

/**
 *
 * @package     Basic MVC framework
 * @author      Jeremie Litzler
 * @copyright   Copyright (c) 2015
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * DirectoryManager Class
 *
 * @package       Library
 * @subpackage    Core
 * @category      DirectoryManager
 * @author        Jeremie Litzler
 * @link		
 */

namespace Library\Core;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class DirectoryManager {

  /**
   * Get the file paths for the current directory
   * @return array
   */
  public static function GetFileNames($dir) {
    return array_diff(scandir($dir), array('..', '.'));
  }

  public static function GetFilesNamesRecursively($dirName, $type) {
    $files = array();
    $dir_iterator = new \RecursiveDirectoryIterator($dirName);
    $iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);
    foreach ($iterator as $file) {
      if (preg_match('~^.*'.$type.'$~', $file->getFileName())) {
        array_push($files, $file);
      }
    }
    return $files;
  }

}
