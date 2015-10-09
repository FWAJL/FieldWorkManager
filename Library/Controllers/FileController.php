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


  public function executeLoadOne(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();
    $manager = $this->managers()->getManagerOf("Document");
    $manager->setRootDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload));
    $manager->setWebDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $this->app()->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath));
    $document = new \Applications\PMTool\Models\Dao\Document();
    $document->setDocument_id($dataPost['id']);
    $document = $manager->selectOne($document);
    if(!is_null($document)) {
      $directory = str_replace("_id", "", $document->document_category());
      $result['document'] = $document;
      $result["filepath"] = $this->getHostUrl().$manager->webDirectory.$directory.'/'.$document->document_value();
      $result['success'] = true;
    } else {
      $result['success'] = false;
    }

    $this->SendResponseWS(
      $result, array(
      "directory" => "common",
      "resx_file" => \Library\Enums\ResourceKeys\ResxFileNameKeys::File,
      "resx_key" => $this->action(),
      "step" => $result['success'] ? "success" : "error"
    ));
  }


  public function executeLoad(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();

    $manager = $this->managers()->getManagerOf("Document");
    $manager->setRootDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload));
    $manager->setWebDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $this->app()->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath));
    $directory = str_replace("_id", "", $dataPost['itemCategory']);
    $manager->setObjectDirectory($directory);
    $fileList = $manager->selectManyByCategoryAndId($dataPost['itemCategory'],$dataPost['itemId']);
    foreach($fileList as $key=>$file) {
      $fileList[$key]->filePath = $file->WebPath();
    }
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
    if(intval($files['file']['size'])>0){
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
      $document->setDocument_id($result['dataOut']);
      $result["document"] = $document;
      $result["filepath"] = $this->getHostUrl().$manager->webDirectory.$directory.'/'.$document->document_value();
      if($dataPost['itemReplace']==="true" && $result["dataOut"]!=-1) {
        $manager->DeleteObjectsWithFile($list, 'document_id');
      }
    } else {
      $result["dataOut"] = -1;
    }
    $this->SendResponseWS(
      $result, array(
      "directory" => "common",
      "resx_file" => \Library\Enums\ResourceKeys\ResxFileNameKeys::File,
      "resx_key" => $this->action(),
      "step" => ($result["dataOut"]!=-1) ? "success" : "error"
    ));
  }

  /**
  * Fineuploader version of file uploader
  */
  public function executeUploadFine(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();
    $files = $this->files();
    
    //Set filename with the custom name sent from 
    //Fine Uploader, everything should fall in place
    $files['file']['name'] = $dataPost['qqfilename'];
    
    if(intval($files['file']['size'])>0){
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
        $document->setDocument_title($dataPost['qqfilename']);
      }
      $result["dataOut"] = $manager->addWithFile($document,$files['file']);
      $document->setDocument_id($result['dataOut']);
      $result["document"] = $document;
      $result["filepath"] = $this->getHostUrl().$manager->webDirectory.$directory.'/'.$document->document_value();
      if($dataPost['itemReplace']==="true" && $result["dataOut"]!=-1) {
        $manager->DeleteObjectsWithFile($list, 'document_id');
      }
    } else {
      $result["dataOut"] = -1;
    }
    
    //Fine uploader related response 
    //It explicitly expects an object
    //of the form:
    //{'success': true/false}
    if($result["dataOut"]!=-1) {
      //Success
      $result['success'] = true;
    } else {
      //Error
      $result['success'] = false;
    }

    $this->SendResponseWS(
      $result, 
      array(
        "directory" => "common",
        "resx_file" => \Library\Enums\ResourceKeys\ResxFileNameKeys::File,
        "resx_key" => $this->action(),
        "step" => ($result["dataOut"]!=-1) ? "success" : "error"
      )
    );
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
    $manager->setFilenamePrefix($dataPost['itemId'] . '_');
    $document = new \Applications\PMTool\Models\Dao\Document();
    $document->setDocument_category($dataPost['itemCategory']);
    if(isset($dataPost['title']) && $dataPost['title']!="") {
      $document->setDocument_title($dataPost['title']);
    } else {
      $document->setDocument_title($files['file']['name']);
    }
    
    $result["dataOut"] = $manager->copyWithFile($document, $files['file']);
  }


  public function executeRemoveMany(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();

    $manager = $this->managers()->getManagerOf("Document");
    $manager->setRootDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::RootDocumentUpload));
    $manager->setWebDirectory($this->app()->config()->get(\Library\Enums\AppSettingKeys::BaseUrl) . $this->app()->config()->get(\Library\Enums\AppSettingKeys::RootUploadsFolderPath));
    $directory = str_replace("_id", "", $dataPost['itemCategory']);
    $manager->setObjectDirectory($directory);
    $result['dataOut'] = -1;
    $fileList = $manager->selectManyByCategoryAndId($dataPost['itemCategory'],$dataPost['itemId']);
    $result['dataOut']=0;
    foreach($fileList as $key=>$file) {
      if($manager->deleteWithFile($file,'document_id'))
        $result['dataOut']++;
    }



    $this->SendResponseWS(
      $result, array(
      "directory" => "common",
      "resx_file" => \Library\Enums\ResourceKeys\ResxFileNameKeys::File,
      "resx_key" => $this->action(),
      "step" => (count($fileList)==$result['dataOut']) ? "success" : "error"
    ));
  }

  private function getHostUrl(){
    $ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($_SERVER['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $_SERVER['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = isset($host) ? $host : $_SERVER['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
  }

  
}
