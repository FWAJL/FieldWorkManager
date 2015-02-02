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
 *  FileUploadResult Class
 *
 * @package		Library
 * @subpackage	Core
 * @category	BO
 * @author		Jeremie Litzler
 * @link		
 */

namespace Library\Core\BO;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class FileUploadResult {
  public 
          $filePath = "",
          $tmpFilePath = "",
          $doesExist = FALSE,
          $isUploaded = FALSE;
  
  public function __construct($filePath, $tmpFilePath) {
    $this->setFilePath($filePath);
    $this->setTmpFilePath($tmpFilePath);
  }
  
  public function filePath() {
    return $this->filePath;
  }
  public function tmpFilePath() {
    return $this->tmpFilePath;
  }
  public function doesExist() {
    return $this->doesExist;
  }
  public function uploaded() {
    return $this->uploaded;
  }

  public function setFilePath($filePath) {
    $this->filePath = $filePath;
  }
  public function setTmpFilePath($tmpFilePath) {
    $this->tmpFilePath = $tmpFilePath;
  }
  public function setDoesExist($doesExist) {
    $this->doesExist = $doesExist;
  }
  public function setIsUploaded($uploaded) {
    $this->isUploaded = $uploaded;
  }
}