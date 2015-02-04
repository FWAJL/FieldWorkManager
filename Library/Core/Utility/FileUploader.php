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
 * FileUploader Class
 *
 * @package		Library
 * @subpackage	Core
 * @category	Utility
 * @author		Jeremie Litzler
 * @link		
 */

namespace Library\Core\Utility;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class FileUploader extends \Library\ApplicationComponent {

  public
      $rootDirectory = "",
      $uploadDirectory = "",
      $currentFile = null,
      $files = array(),
      $dataPost = array(),
      $resultJson = array();

  public function __construct(\Library\Application $app, $data) {
    parent::__construct($app);
    $this->rootDirectory = $app->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload);
    $this->files = $data["files"];
    $this->dataPost = $data["dataPost"];
    $this->resultJson = $data["resultJson"];
  }

  public function UploadFiles() {
    $this->uploadDirectory = $this->GetUploadDirectory();
    $this->resultJson["fileUploadResults"] = array();
    foreach ($this->files as $file) {
      $this->currentFile = new \Library\Core\BO\FileUploadResult($file["tmp_name"]);
      //Init object
      $document = $this->InitDocumentObject();
      //Check if file exist before adding a row in DB
      $this->currentFile->setFilePath($this->uploadDirectory . "/" . $document->document_value());
      $fileExists = \Library\Core\DirectoryManager::CreateDirectoryAndReturnFileExists($this->uploadDirectory, $this->currentFile->filePath());
      $this->currentFile->setDoesExist($fileExists);
      //Add document to DB
      if (!$fileExists) {//Don't add the document in DB if already
        $this->AddDocumentToDatabase($document);
      }
      //Add document to uploads directory
      $uploaded = $this->UploadAFile($this->currentFile->tmpFilePath(), $this->currentFile->filePath());
      $this->currentFile->setIsUploaded($uploaded);
      array_push($this->resultJson["fileUploadResults"], $this->currentFile);
    }
    return $this->resultJson;
  }

  private function UploadAFile($tmp, $file) {
    if (move_uploaded_file($tmp, $file)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  private function GetFileNameToSaveInDatabase() {
    return
        $this->dataPost["itemId"]
        . "_" . UUID::v4()
        . "." . $this->GetExtension();
  }

  private function GetExtension() {
    return substr($this->files["file"]["type"], strpos($this->files["file"]["type"], '/') + 1);
  }

  private function GetUploadDirectory() {
    return $this->rootDirectory . $this->GetCategory();
  }
  
  private function GetCategory() {
    return str_replace("_id", "", $this->dataPost["itemCategory"]);
  }
  
  private function GetSizeInKb() {
    $sizeInBytes = intval($this->files["file"]["size"]);
    return $sizeInBytes / 1024;
  }

  private function InitDocumentObject() {
    $document = new \Applications\PMTool\Models\Dao\Document();
    $document->setDocument_category($this->dataPost["itemCategory"]);
    $document->setDocument_content_type($this->GetExtension());
    $document->setDocument_value($this->GetFileNameToSaveInDatabase());
    $document->setDocument_size($this->GetSizeInKb());
    return $document;
  }

  private function AddDocumentToDatabase($document) {
    $db = new \Library\DAL\Managers('PDO', $this->app());
    $dal = $db->getManagerOf("Document", TRUE);
    $dal->add($document);
  }

}
