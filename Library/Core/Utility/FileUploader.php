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
          $dataPost = array();

  public function __construct(\Library\Application $app, $files, $dataPost) {
    parent::__construct($app);
    $this->rootDirectory = $app->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload);
    $this->files = $files;
    $this->dataPost = $dataPost;
  }

  public function UploadFiles() {
    $this->uploadDirectory = $this->rootDirectory . $this->dataPost["category"];

    foreach ($this->files as $file) {
      $fileName = $this->uploadDirectory . "/" . $file['file']['name'];
      $this->_GetDirectory($fileName);
      $this->UploadAFile($file);
    }
  }

  private function _GetDirectory($fileName) {
    if (!file_exists($fileName)) {
      mkdir($dir, 0777, true);
    }
  }

  private function UploadAFile($file) {
    if (move_uploaded_file(
                    $file['tmp_name'], $this->uploadDirectory . "/" . $file['name'])) {
      \Library\Utility\DebugHelper::LogAsHtmlComment($file['name'] . " uploaded in " . $this->uploadDirectory);
      $addToDB = TRUE;
    } else {
      \Library\Utility\DebugHelper::LogAsHtmlComment($file['name'] . " not uploaded");
    }
  }

}
