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
 * Service Dao Class
 *
 * @package     Application/PMTool
 * @subpackage  Models/Dao
 * @category    User_form
 * @author      FWM DEV Team
 * @link
 */
namespace Applications\PMTool\Models\Dao;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class User_form extends \Library\Entity implements \Library\Interfaces\IDocument{
  public
    $form_id,
    $content_type,
    $category,
    $value,
    $size,
    $title,
    $pm_id;

  // members required for document interface
  private
    $webPath,
    $absolutePath;

  const
    USER_FORM_ID_ERR = 0,
    CONTENT_TYPE_ERR = 1,
    CATEGORY_ERR = 2,
    VALUE_ERR = 3,
    SIZE_ERR = 4,
    TITLE_ERR = 5,
    PM_ID_ERR = 6;

  // SETTERS //
  public function setForm_id($user_form_id) {
    $this->form_id = $user_form_id;
  }

  public function setContent_type($content_type) {
    $this->content_type = $content_type;
  }

  public function setCategory($category) {
    $this->category = $category;
  }

  public function setValue($value) {
    $this->value = $value;
  }

  public function setSize($size) {
    $this->size = $size;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function setPm_id($pm_id) {
    $this->pm_id = $pm_id;
  }

  // Document interface //

  public function setWebPath($webPath){
    $this->webPath = $webPath;
  }

  public function setAbsolutePath($absolutePath) {
    $this->absolutePath = $absolutePath;
  }

  public function setFilename($filename) {
    $this->value = $filename;
  }

  public function setContentType($contentType) {
    $this->content_type = $contentType;
  }

  public function setContentSize($contentSize) {
    $this->size = $contentSize;
  }

  // GETTERS //
  public function form_id() {
    return $this->form_id;
  }

  public function content_type() {
    return $this->content_type;
  }

  public function category() {
    return $this->category;
  }

  public function value() {
    return $this->value;
  }

  public function size() {
    return $this->size;
  }

  public function title() {
    return $this->title;
  }

  public function pm_id() {
    return $this->pm_id;
  }

  //type getter
  public function data_identifier() {
    return 'user_form';
  }


  // Document interface //

  public function WebPath(){
    return $this->webPath;
  }

  public function AbsolutePath() {
    return $this->absolutePath;
  }

  public function Filename() {
    return $this->value;
  }

  public function ContentSize() {
    return $this->size;
  }

  public function ContentType() {
    return $this->content_type;
  }

  public function ValidExtensions() {
    return array("pdf");
  }

  public function getOrderByField() {
    return "title";
  }
}