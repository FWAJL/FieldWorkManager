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
    $files = $this->files();
    $dataPost = $this->dataPost();
    if (!file_exists('path/to/directory')) {
      mkdir('path/to/directory', 0777, true);
    }
    $dir = $this->app()->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload);
    //$dir = "";
    if (move_uploaded_file(
            $files['file']['tmp_name'], $dir . $files['file']['name'])) {
      \Library\Utility\DebugHelper::LogAsHtmlComment($files['file']['name'] . " uploaded");
    } else {
      \Library\Utility\DebugHelper::LogAsHtmlComment($files['file']['name'] . " not uploaded");
    }
  }

}
