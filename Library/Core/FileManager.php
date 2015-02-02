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
 * FileManager Class
 *
 * @package       Library
 * @subpackage    Core
 * @category      FileManager
 * @author        Jeremie Litzler
 * @link		
 */

namespace Library\Core;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class FileManager {

  /**
   * @property string $filePath File Path being read or written
   */
  protected $filePath = "";

  /**
   * 
   * @param type $filePath
   * File to use in instance
   */
  public function __construct($filePath) {
    $this->filePath = $filePath;
  }
  /**
   * Get content of the file
   * @return array
   */
  public function GetFileContent() {
    
  }
  

}
