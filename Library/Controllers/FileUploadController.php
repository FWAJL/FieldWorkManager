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
 * FileUploadController controller Class
 *
 * @package		Library
 * @subpackage	Controllers
 * @category	FileUploadController
 * @author		FWM DEV Team
 * @link		
 */

namespace Library\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class FileUploadController extends \Library\BaseController {

  public function executeUpload(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();

    $uploader = new \Library\Core\Utility\FileUploader(
        $this->app(), array(
      "files" => $this->files(),
      "dataPost" => $this->dataPost(),
      "resultJson" => $result)
    );
    $uploader->UploadFiles();

    $isValid = $uploader->resultJson["fileUploaded"] == count($this->files());

    $this->SendResponseWS(
        $result, array(
      "directory" => "common",
      "resx_file" => \Library\Enums\ResourceKeys\ResxFileNameKeys::FileUpload,
      "resx_key" => $this->action(),
      "step" => $isValid ? "success" : "error"
    ));
  }

}
