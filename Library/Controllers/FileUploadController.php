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

  public function executeUploadFile(\Library\HttpRequest $rq) {
    $uploader = new \Library\Core\Utility\FileUploader($this->app(), $this->files(), $this->dataPost());
    $uploader->UploadFiles();
  }

}
