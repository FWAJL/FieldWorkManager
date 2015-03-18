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
    /*
    $uploader = new \Library\Core\Utility\FileLoader(
      $this->app(), array(
        "dataPost" => $dataPost,
        "resultJson" => $result)
    );
    $result = $uploader->LoadFiles();*/

    $manager = $this->managers()->getManagerOf("Document");
    $manager->setRootDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload));
    $manager->setWebDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $this->app()->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath));
    $directory = str_replace("_id", "", $dataPost['itemCategory']);
    $manager->setObjectDirectory($directory);
    $list = $manager->selectManyByCategoryAndId($dataPost['itemCategory'],$dataPost['itemId']);
    $i=0;
    $result['fileResults'] = array();
    foreach($list as $document) {
      $result['fileResults'][$i]['webPath'] = $document->WebPath();
      $result['fileResults'][$i]['title'] = $document->document_title();
      $result['fileResults'][$i]['id'] = $document->document_id();
      $result['fileResults'][$i]['category'] = $document->document_category();
      $i++;
    }

    $this->SendResponseWS(
      $result, array(
      "directory" => "common",
      "resx_file" => \Library\Enums\ResourceKeys\ResxFileNameKeys::FileLoad,
      "resx_key" => $this->action(),
      "step" => true ? "success" : "error"
    ));
  }

}
