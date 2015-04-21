<?php

/**
*
* @package    Basic MVC framework
* @author     Jeremie Litzler
* @copyright  Copyright (c) 2015
* @license
* @link
* @since
* @filesource
*/
// ------------------------------------------------------------------------

/**
*
* DocumentDal Class
*
* @package     Library
* @subpackage  DAL
* @category    Modules
* @author      FWM DEV Team
* @link
*/

namespace Library\DAL\Modules;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class DocumentDal extends \Library\DAL\BaseManager {

  public $rootDirectory,$webDirectory;
  public $filenamePrefix = "";
  public $objectDirectory = "";

  public function __construct($dao, $filters) {
    parent::__construct($dao,$filters);
  }

  public function setRootDirectory($rootDirectory) {
    $this->rootDirectory = $rootDirectory;
  }

  public function setWebDirectory($webDirectory) {
    $this->webDirectory = $webDirectory;
  }

  public function setObjectDirectory($directory) {
    $this->objectDirectory = $directory;
  }

  public function setFilenamePrefix($prefix) {
    $this->filenamePrefix = $prefix;
  }

  public function selectMany($object, $where_filter_id, $filter_as_string = false){
    $list = parent::selectMany($object, $where_filter_id, $filter_as_string);
    $list = $this->AddFilePathToObjectList($list);
    return $list;
  }

  public function addWithFile($object, $file) {
    if($object instanceof \Library\Interfaces\IDocument) {
      $object->setFilename($this->GetFileNameToSaveInDatabase($file));
      $fileExists = \Library\Core\DirectoryManager::FileExists($this->GetUploadDirectory($object) . "/" . $object->Filename());
      $extensions = $object->ValidExtensions();
      if(is_array($extensions)) {
        $validExtension = $this->CheckExtension($file, $extensions);
      } else {
        $validExtension = true;
      }
      if(!$fileExists && $validExtension) {
        \Library\Core\DirectoryManager::CreateDirectory($this->GetUploadDirectory($object));
        $object->setContentSize($this->GetSizeInKb($file));
        $object->setContentType($this->GetExtension($file));
        $this->UploadAFile($file['tmp_name'],$this->GetUploadDirectory($object) . "/" . $object->Filename());
        return parent::add($object);
      } else {
        return -1;
      }
    }
  }

  /**
  * Location specific PDF document requirement
  * Copies the file which is passed to it, with 
  * the generated name
  */
  public function copyWithFile($object, $file) {
    if($object instanceof \Library\Interfaces\IDocument) {
      $object->setFilename($this->GetFileNameToSaveInDatabase($file));
      $fileExists = \Library\Core\DirectoryManager::FileExists($this->GetUploadDirectory($object) . "/" . $object->Filename());
      $extensions = $object->ValidExtensions();
      if(is_array($extensions)) {
        $validExtension = $this->CheckExtension($file, $extensions);
      } else {
        $validExtension = true;
      }
      if(!$fileExists && $validExtension) {
        \Library\Core\DirectoryManager::CreateDirectory($this->GetUploadDirectory($object));
        $object->setContentSize($this->GetSizeInKb($file));
        $object->setContentType($this->GetExtension($file));

        //LEt's copy the file
        if(copy($file['tmp_name'], $this->GetUploadDirectory($object) . '/' . $object->Filename())){
          return parent::add($object);  
        }
      } else {
        return -1;
      }
    }
  }

  public function deleteWithFile($object, $where_filter_id) {
    if($object instanceof \Library\Interfaces\IDocument) {
      $fileExists = \Library\Core\DirectoryManager::FileExists($this->GetUploadDirectory($object) . "/" . $object->Filename());
      if($fileExists) {
        $this->DeleteAFile($this->GetUploadDirectory($object) . "/" . $object->Filename());
      }
      return parent::delete($object, $where_filter_id);
    }
  }

  public function DeleteObjectsWithFile($objects, $where_filter_id) {
    $return = array();
    foreach($objects as $object){
      if($object instanceof \Library\Interfaces\IDocument) {
        $fileExists = \Library\Core\DirectoryManager::FileExists($this->GetUploadDirectory($object) . "/" . $object->Filename());
        if($fileExists) {
          $this->DeleteAFile($this->GetUploadDirectory($object) . "/" . $object->Filename());
        }
        $return[] = parent::delete($object, $where_filter_id);
      }
    }
    return !in_array(-1,$return);
  }

  private function GetUploadDirectory($object) {
    if(isset($this->objectDirectory) and $this->objectDirectory!=""){
      $directory = $this->objectDirectory;
    } else {
      $directory = strtolower($this->GetClassName($object));
    }
    return $this->rootDirectory . $directory;
  }

  private function GetWebUploadDirectory($object) {
    if(isset($this->objectDirectory) and $this->objectDirectory!=""){
      $directory = $this->objectDirectory;
    } else {
      $directory = strtolower($this->GetClassName($object));
    }
    return $this->webDirectory . $directory;
  }

  private function UploadAFile($tmp, $file) {
    if (move_uploaded_file($tmp, $file)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  private function DeleteAFile($file) {
    if (unlink($file)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  private function GetExtension($file) {
    return strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  }

  private function GetFileNameToSaveInDatabase($file) {
    return $this->filenamePrefix.\Library\Core\Utility\UUID::v4(). "." . $this->GetExtension($file);
  }

  private function GetSizeInKb($file) {
    $sizeInBytes = intval($file["size"]);
    return round($sizeInBytes / 1024);
  }

  private function GetClassName($object) {
    $classname = get_class($object);

    if (preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
      $classname = $matches[1];
    }

    return strtolower($classname);
  }

  private function CheckExtension($file, $extensions) {
    return in_array($this->GetExtension($file),$extensions);
  }

  protected function AddFilePathToObjectList($list) {
    if(is_array($list)) {
      foreach($list as &$object) {
        if($object instanceof \Library\Interfaces\IDocument) {
          $filePath = $this->GetUploadDirectory($object) . "/" . $object->Filename();
          $fileExists = \Library\Core\DirectoryManager::FileExists($filePath);
          if($fileExists){
            $object->setWebPath($this->GetWebUploadDirectory($object) . "/" . $object->Filename());
          }
        }
      }
    }
    return $list;
  }
}
