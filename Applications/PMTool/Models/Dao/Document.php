<?php
/**** @package    Basic MVC framework* @author     Jeremie Litzler* @copyright  Copyright (c) 2015* @license* @link* @since* @filesource*/// ------------------------------------------------------------------------
/**** Document Dao Class** @package     Application/PMTool* @subpackage  Models/Dao* @category    Document* @author      FWM DEV Team* @link*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Document extends \Library\Entity{  public     $document_id,    $document_content_type,    $document_category,    $document_value,
    $document_size;
  const     DOCUMENT_ID_ERR = 0,    DOCUMENT_CONTENT_TYPE_ERR = 1,    DOCUMENT_CATEGORY_ERR = 2,    DOCUMENT_VALUE_ERR = 3,
    DOCUMENT_SIZE_ERR = 4;
  // SETTERS //  public function setDocument_id($document_id) {      $this->document_id = $document_id;  }
  public function setDocument_content_type($document_content_type) {      $this->document_content_type = $document_content_type;  }
  public function setDocument_category($document_category) {      $this->document_category = $document_category;  }
  public function setDocument_value($document_value) {      $this->document_value = $document_value;  }

  public function setDocument_size($document_size) {
      $this->document_size = $document_size;
  }
  // GETTERS //  public function document_id() {    return $this->document_id;  }
  public function document_content_type() {    return $this->document_content_type;  }
  public function document_category() {    return $this->document_category;  }
  public function document_value() {    return $this->document_value;  }
  public function document_size() {
    return $this->document_size;
  }

}