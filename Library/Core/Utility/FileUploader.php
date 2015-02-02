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
    $this->uploadDirectory = $this->rootDirectory . $this->dataPost["category"];
    foreach ($this->files as $file) {
      $this->currentFile = new \Library\Core\BO\FileUploadResult(
              $this->uploadDirectory . "/" . $file['name'], $file["tmp_name"]
      );
      $this->currentFile->setDoesExist($this->GetDirectory($this->currentFile->filePath()));
      $this->currentFile->setIsUploaded($this->UploadAFile(
                      $this->currentFile->tmpFilePath(), $this->currentFile->filePath()));
      $result["fileUploadResult"] = $this->currentFile;
    }
    return $this->resultJson;
  }

  private function GetDirectory($filePath) {
    if (!file_exists($filePath) && !is_dir($filePath)) {
      mkdir($this->uploadDirectory, 0777, true);
      return FALSE;
    } else {
      return TRUE;
    }
  }

  private function UploadAFile($tmp, $file) {
    if (move_uploaded_file($tmp, $file)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

}
