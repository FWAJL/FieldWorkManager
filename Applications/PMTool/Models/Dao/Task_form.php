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
 * Task_form Dao Class
 *
 * @package     Application/PMTool
 * @subpackage  Models/Dao
 * @category    Task_form
 * @author      FWM DEV Team
 * @link
 */

namespace Applications\PMTool\Models\Dao;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Task_form extends \Library\Entity{
  public
    $task_id,
    $master_form_id,
    $user_form_id;

  const
    TASK_ID_ERR = 0,
    MASTER_FORM_ID_ERR = 1,
    USER_FORM_ID_ERR = 2;

  // SETTERS //
  public function setTask_id($task_id) {
    $this->task_id = $task_id;
  }

  public function setMaster_form_id($master_form_id) {
    $this->master_form_id = $master_form_id;
  }

  public function setUser_form_id($user_form_id) {
    $this->user_form_id = $user_form_id;
  }

  // GETTERS //
  public function task_id() {
    return $this->task_id;
  }

  public function master_form_id() {
    return $this->master_form_id;
  }

  public function user_form_id() {
    return $this->user_form_id;
  }


}