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
   * 
   * @param string $dir <p>
   * Directory value to scan.</p>
   * @return array <p>
   * List of files found in directory scanned.</p>
   */
  public static function GetFileNames($dir) {
    return array_diff(scandir($dir), array('..', '.'));
  }

  /**
   * 
   * @param type $dirName <p>
   * Directory value to scan.</p>
   * @param type $type <p>
   * File extension to find.</p>
   * @return array(of SplFileInfo) <p>
   * List of SplFileInfo objects scanned in the top-level directory.</p>
   */
  public static function GetFilesNamesRecursively($dirName, $extension) {
    $files = array();
    $dir_iterator = new \RecursiveDirectoryIterator($dirName);
    $iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);
    foreach ($iterator as $file) {
      if (preg_match('~^.*'.$extension.'$~', $file->getFileName())) {
        array_push($files, $file);
      }
    }
    return $files;
  }

  /**
   * <p>
   * Create a directory if doesn't exist.
   * Return True if file exists, otherwise False after creation of directory.
   * </p>
   * @param string <p>
   * $dir Value of directory to create. </p>
   * @param string $filePath <p>
   * The path to check to figure out if the directory exists.</p>
   * @return boolean <p>
   * File exists or not. </p>
   */
  public static function CreateDirectoryAndReturnFileExists($dir, $filePath) {
    if (!file_exists($filePath) && !is_dir($filePath)) {
      mkdir($dir, 0777, true);
      return FALSE;
    } else {
      return TRUE;
    }
  }
}
