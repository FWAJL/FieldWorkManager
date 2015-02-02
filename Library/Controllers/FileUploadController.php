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
    $result = $uploader->UploadFiles();
    //if (count($result["fileUploadResults"]) > 0 && $result["fileUploadResults"][0]->isUploaded()) {
    //  $db = new \Library\DAL\Managers('PDO', $this->app());
    //  $dal = $db->getManagerOf("Document", TRUE);
    //  $dal->add();
    //}

    $this->SendResponseWS(
        $result, array(
      "directory" => "common",
      "resx_file" => \Library\Enums\ResourceKeys\ResxFileNameKeys::FileUpload,
      "resx_key" => $this->action(),
      "step" => TRUE ? "success" : "error"
    ));
  }

}
