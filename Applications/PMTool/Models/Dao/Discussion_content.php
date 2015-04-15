<?php
/**** @package    Basic MVC framework* @author     Jeremie Litzler* @copyright  Copyright (c) 2014* @license* @link* @since* @filesource*/// ------------------------------------------------------------------------
/**** Discussion_content Dao Class** @package     Application/PMTool* @subpackage  Models/Dao* @category    Discussion_content* @author      FWM DEV Team* @link*/
namespace Applications\PMTool\Models\Dao;if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
class Discussion_content extends \Library\Entity{  public     $discussion_content_id,    $discussion_id,    $discussion_content_category_type,    $discussion_content_category_value,    $discussion_content_value;
  const     DISCUSSION_CONTENT_ID_ERR = 0,    DISCUSSION_ID_ERR = 1,    DISCUSSION_CONTENT_CATEGORY_TYPE_ERR = 2,    DISCUSSION_CONTENT_CATEGORY_VALUE_ERR = 3,    DISCUSSION_CONTENT_VALUE_ERR = 4;
  // SETTERS //  public function setDiscussion_content_id($discussion_content_id) {      $this->discussion_content_id = $discussion_content_id;  }
  public function setDiscussion_id($discussion_id) {      $this->discussion_id = $discussion_id;  }
  public function setDiscussion_content_category_type($discussion_content_category_type) {      $this->discussion_content_category_type = $discussion_content_category_type;  }
  public function setDiscussion_content_category_value($discussion_content_category_value) {      $this->discussion_content_category_value = $discussion_content_category_value;  }
  public function setDiscussion_content_value($discussion_content_value) {      $this->discussion_content_value = $discussion_content_value;  }
  // GETTERS //  public function discussion_content_id() {    return $this->discussion_content_id;  }
  public function discussion_id() {    return $this->discussion_id;  }
  public function discussion_content_category_type() {    return $this->discussion_content_category_type;  }
  public function discussion_content_category_value() {    return $this->discussion_content_category_value;  }
  public function discussion_content_value() {    return $this->discussion_content_value;  }
}