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
* Discussion_content Dao Class
*
* @package     Application/PMTool
* @subpackage  Models/Dao
* @category    Discussion_content
* @author      FWM DEV Team
* @link
*/
namespace Applications\PMTool\Models\Dao;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Discussion_content extends \Library\Entity{
  public 
    $discussion_content_id,
    $discussion_person_id,
    $discussion_content_time,
    $discussion_content_message;
  const 
    DISCUSSION_CONTENT_ID_ERR = 0,
    DISCUSSION_PERSON_ID_ERR = 1,
    DISCUSSION_CONTENT_TIME_ERR = 2,
    DISCUSSION_CONTENT_MESSAGE_ERR = 3;
  // SETTERS //
  public function setDiscussion_content_id($discussion_content_id) {
      $this->discussion_content_id = $discussion_content_id;
  }
  public function setDiscussion_person_id($discussion_person_id) {
      $this->discussion_person_id = $discussion_person_id;
  }
  public function setDiscussion_content_time($discussion_content_time) {
      $this->discussion_content_time = $discussion_content_time;
  }
  public function setDiscussion_content_message($discussion_content_message) {
      $this->discussion_content_message = $discussion_content_message;
  }
  // GETTERS //
  public function discussion_content_id() {
    return $this->discussion_content_id;
  }
  public function discussion_person_id() {
    return $this->discussion_person_id;
  }
  public function discussion_content_time() {
    return $this->discussion_content_time;
  }
  public function discussion_content_message() {
    return $this->discussion_content_message;
  }

}
