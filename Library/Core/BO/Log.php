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
* Log Dao Class
*
* @package     Library
* @subpackage  Core\BO
* @category    Log
* @author      Jeremie Litzler
* @link
*/

namespace Library\Core\BO;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Log extends \Library\Entity{
  public 
    $log_id,
    $log_request_id,
    $log_start,
    $log_end,
    $log_execution_time,
    $log_type,
    $log_filter;

  const 
    LOG_ID_ERR = 0,
    LOG_REQUEST_ID_ERR = 1,
    LOG_START_ERR = 2,
    LOG_END_ERR = 3,
    LOG_EXECUTION_TIME_ERR = 4,
    LOG_TYPE_ERR = 5,
    LOG_FILTER_ERR = 6;

  // SETTERS //
  public function setLog_id($log_id) {
      $this->log_id = $log_id;
  }

  public function setLog_request_id($log_request_id) {
      $this->log_request_id = $log_request_id;
  }

  public function setLog_start($log_start) {
      $this->log_start = $log_start;
  }

  public function setLog_end($log_end) {
      $this->log_end = $log_end;
  }

  public function setLog_execution_time($log_execution_time) {
      $this->log_execution_time = $log_execution_time;
  }

  public function setLog_type($log_type) {
      $this->log_type = $log_type;
  }

  public function setLog_filter($log_filter) {
      $this->log_filter = $log_filter;
  }

  // GETTERS //
  public function log_id() {
    return $this->log_id;
  }

  public function log_request_id() {
    return $this->log_request_id;
  }

  public function log_start() {
    return $this->log_start;
  }

  public function log_end() {
    return $this->log_end;
  }

  public function log_execution_time() {
    return $this->log_execution_time;
  }

  public function log_type() {
    return $this->log_type;
  }

  public function log_filter() {
    return $this->log_filter;
  }


}