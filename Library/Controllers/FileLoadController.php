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

class FileLoadController extends \Library\BaseController {

  public function executeLoad(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();
    $uploader = new \Library\Core\Utility\FileLoader(
      $this->app(), array(
        "dataPost" => $dataPost,
        "resultJson" => $result)
    );
    $result = $uploader->LoadFiles();

    $this->SendResponseWS(
      $result, array(
      "directory" => "common",
      "resx_file" => \Library\Enums\ResourceKeys\ResxFileNameKeys::FileLoad,
      "resx_key" => $this->action(),
      "step" => count($result['fileResults']) ? "success" : "error"
    ));
  }

}
