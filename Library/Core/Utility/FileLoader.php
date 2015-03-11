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
 * FileLoader Class
 *
 * @package		Library
 * @subpackage	Utility
 * @category	FileLoader
 * @author		Jeremie Litzler
 * @link		
 */

namespace Library\Core\Utility;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class FileLoader extends \Library\ApplicationComponent {

  public
    $rootDirectory = "",
    $webDirectory = "",
    $uploadDirectory = "",
    $currentFile = null,
    $files = array(),
    $dataPost = array(),
    $resultJson = array();

  function __construct(\Library\Application $app, $data) {
    parent::__construct($app);
    $this->rootDirectory = $app->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload);
    $this->webDirectory = $app->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $app->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath);
    $this->dataPost = $data["dataPost"];
  }

  function LoadFiles() {
    $this->uploadDirectory = $this->GetUploadDirectory();
    $this->resultJson["fileResults"] = array();
    $documents = $this->LoadDocumentObjects();
    foreach($documents as &$document) {
      $this->currentFile = new \Library\Core\BO\FileUploadResult($document->document_value());
      $this->currentFile->setFilePath($this->uploadDirectory . "/" . $document->document_value());
      $this->currentFile->setWebPath($this->GetWebUploadDirectory() . "/" . $document->document_value());
      $fileExists = \Library\Core\DirectoryManager::FileExists($this->currentFile->filePath());
      if($fileExists) {
        $this->currentFile->setDoesExist(true);
        array_push($this->resultJson["fileResults"], $this->currentFile);
      }
    }
    return $this->resultJson;
  }

  private function GetUploadDirectory() {
    return $this->rootDirectory . $this->GetCategory();
  }

  private function GetWebUploadDirectory() {
    return $this->webDirectory . $this->GetCategory();
  }

  private function GetCategory() {
    return str_replace("_id", "", $this->dataPost["itemCategory"]);
  }

  private function LoadDocumentObjects() {
    $db = new \Library\DAL\Managers('PDO', $this->app());
    $dal = $db->getManagerOf("Document", false);
    if(isset($this->dataPost["itemCategory"]) and isset($this->dataPost["itemId"]) and is_numeric($this->dataPost["itemId"]))
    {
      return $dal->selectManyByCategoryAndId($this->dataPost["itemCategory"],$this->dataPost["itemId"]);
    } else {
      return array();
    }
  }
}
