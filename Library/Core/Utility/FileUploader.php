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
 * @subpackage	Utility
 * @category	_TemplateClass
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
    $this->resultJson["fileUploaded"] = 0;
    foreach ($this->files as $file) {
      $fileName = $this->uploadDirectory . "/" . $file['name'];
      $this->GetDirectory($fileName);
      $this->resultJson["fileUploaded"] += $this->UploadAFile($file);
    }
    return $this->resultJson;
  }

  private function GetDirectory($fileName) {
    if (!file_exists($fileName)) {
      mkdir($this->uploadDirectory, 0777, true);
    }
  }

  private function UploadAFile($file) {
    if (move_uploaded_file(
            $file['tmp_name'], $this->uploadDirectory . "/" . $file['name'])) {
      return 1;
    } else {
      return 0;
    }
  }

}
