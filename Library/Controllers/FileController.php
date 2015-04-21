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

class FileController extends \Library\BaseController {

  public function executeLoad(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();

    $manager = $this->managers()->getManagerOf("Document");
    $manager->setRootDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload));
    $manager->setWebDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $this->app()->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath));
    $directory = str_replace("_id", "", $dataPost['itemCategory']);
    $manager->setObjectDirectory($directory);
    $fileList = $manager->selectManyByCategoryAndId($dataPost['itemCategory'],$dataPost['itemId']);
    $result['fileResults'] = $fileList;

    $this->SendResponseWS(
      $result, array(
      "directory" => "common",
      "resx_file" => \Library\Enums\ResourceKeys\ResxFileNameKeys::File,
      "resx_key" => $this->action(),
      "step" => true ? "success" : "error"
    ));
  }

  public function executeUpload(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();
    $files = $this->files();
    $manager = $this->managers()->getManagerOf("Document");
    $manager->setRootDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload));
    $manager->setWebDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $this->app()->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath));
    $directory = str_replace("_id", "", $dataPost['itemCategory']);
    $manager->setObjectDirectory($directory);
    if($dataPost['itemReplace']==="true") {
      $list = $manager->selectManyByCategoryAndId($dataPost['itemCategory'],$dataPost['itemId']);
    }
    $manager->setFilenamePrefix($dataPost['itemId'].'_');
    $document = new \Applications\PMTool\Models\Dao\Document();
    $document->setDocument_category($dataPost['itemCategory']);
    if(isset($dataPost['title']) && $dataPost['title']!="") {
      $document->setDocument_title($dataPost['title']);
    } else {
      $document->setDocument_title($files['file']['name']);
    }
    $result["dataOut"] = $manager->addWithFile($document,$files['file']);

    if($dataPost['itemReplace']==="true" && $result["dataOut"]!=-1) {
      $manager->DeleteObjectsWithFile($list, 'document_id');
    }
    $this->SendResponseWS(
      $result, array(
      "directory" => "common",
      "resx_file" => \Library\Enums\ResourceKeys\ResxFileNameKeys::File,
      "resx_key" => $this->action(),
      "step" => ($result["dataOut"]!=-1) ? "success" : "error"
    ));
  }

  public function executeRemove(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();

    $manager = $this->managers()->getManagerOf("Document");
    $manager->setRootDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload));
    $manager->setWebDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $this->app()->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath));
    $directory = str_replace("_id", "", $dataPost['itemCategory']);
    $manager->setObjectDirectory($directory);
    $document = new \Applications\PMTool\Models\Dao\Document();
    $document->setDocument_id($dataPost['document_id']);
    $document = $manager->selectOne($document);

    $result['dataOut'] = -1;
    if($document !== NULL) {
      $result['dataOut'] = $manager->deleteWithFile($document,'document_id');
    }

    $this->SendResponseWS(
      $result, array(
      "directory" => "common",
      "resx_file" => \Library\Enums\ResourceKeys\ResxFileNameKeys::File,
      "resx_key" => $this->action(),
      "step" => ($result["dataOut"]===true) ? "success" : "error"
    ));

  }

  /**
  * PDF copy method
  * $dataPost is actually having the Document model
  * $file is having the master file details in pseudo format
  */
  public static function copyFile($files, $dataPost, $caller) {
    
    $manager = $caller->managers()->getManagerOf("Document");
    $manager->setRootDirectory($caller->app()->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload));
    $manager->setWebDirectory($caller->app()->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $caller->app()->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath));
    $directory = str_replace("_id", "", $dataPost['itemCategory']);
    $manager->setObjectDirectory($directory);
    if($dataPost['itemReplace']==="true") {
      $list = $manager->selectManyByCategoryAndId($dataPost['itemCategory'],$dataPost['itemId']);
    }
    $manager->setFilenamePrefix($dataPost['itemId'].'_');
    $document = new \Applications\PMTool\Models\Dao\Document();
    $document->setDocument_category($dataPost['itemCategory']);
    if(isset($dataPost['title']) && $dataPost['title']!="") {
      $document->setDocument_title($dataPost['title']);
    } else {
      $document->setDocument_title($files['file']['name']);
    }
    
    $result["dataOut"] = $manager->copyWithFile($document, $files['file']);
  }

  
}
