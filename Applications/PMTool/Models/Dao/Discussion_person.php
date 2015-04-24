<?php
/**
*
* @package    Basic MVC framework
* @author     Jeremie Litzler
* @copyright  Copyright (c) 2014
* @license
* @link
* @since
* @filesource
*/
// ------------------------------------------------------------------------
/**
*
* Discussion_person Dao Class
*
* @package     Application/PMTool
* @subpackage  Models/Dao
* @category    Discussion_person
* @author      FWM DEV Team
* @link
*/
namespace Applications\PMTool\Models\Dao;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Discussion_person extends \Library\Entity{
  public 
    $discussion_person_id,
    $discussion_id,
    $user_id,
    $discussion_person_is_author;
  const 
    DISCUSSION_PERSON_ID_ERR = 0,
    DISCUSSION_ID_ERR = 1,
    USER_ID_ERR = 2,
    DISCUSSION_PERSON_IS_AUTHOR_ERR = 3;
  // SETTERS //
  public function setDiscussion_person_id($discussion_person_id) {
      $this->discussion_person_id = $discussion_person_id;
  }
  public function setDiscussion_id($discussion_id) {
      $this->discussion_id = $discussion_id;
  }
  public function setUser_id($user_id) {
      $this->user_id = $user_id;
  }
  public function setDiscussion_person_is_author($discussion_person_is_author) {
      $this->discussion_person_is_author = $discussion_person_is_author;
  }
  // GETTERS //
  public function discussion_person_id() {
    return $this->discussion_person_id;
  }
  public function discussion_id() {
    return $this->discussion_id;
  }
  public function user_id() {
    return $this->user_id;
  }
  public function discussion_person_is_author() {
    return $this->discussion_person_is_author;
  }

}
