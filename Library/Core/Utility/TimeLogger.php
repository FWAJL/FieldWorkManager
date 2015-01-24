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
 * TimeLogger Class
 *
 * @package		Library
 * @subpackage	Utility
 * @category	TimeLogger
 * @author		Jeremie Litzler
 * @link		
 */

namespace Library\Core\Utility;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TimeLogger extends Logger {

//  public function __construct() {
//    $this->logs[\Library\Enums\ResourceKeys\GlobalAppKeys::log_http_request] = array();
//    $this->logs[\Library\Enums\ResourceKeys\GlobalAppKeys::log_controller_method_request] = array();
//  }

  public static function SetLog($user, \Library\Core\BO\Timelog $log) {
    $logs = Logger::GetLogs($user);
    $logs[$log->type()][$log->id()] = $log;
    Logger::StoreLogs($user, $logs);
  }
  
  public static function StartHttpRequestLog(\Library\Application $app) {
    $log = new \Library\Core\BO\Timelog();
    $log->setType(\Library\Enums\ResourceKeys\GlobalAppKeys::log_http_request);
    $log->setId($app->HttpRequest()->requestId());
    $log->setTimeStart(gmdate("Y-m-d H:i:s", time()));
    $log->setTimeEnd("");
    $log->setUrl($app->HttpRequest()->requestURI());
    self::SetLog($app->user(), $log);
  }

  public static function EndHttpRequestLog(\Library\Application $app) {
    $logs = Logger::GetLogs($app->user());
    $log = $logs[\Library\Enums\ResourceKeys\GlobalAppKeys::log_http_request][$app->HttpRequest()->requestID()];
    $log->setTimeEnd(gmdate("Y-m-d H:i:s", time()));
    
//    Logger::PrintOutLogs($logs);
    Logger::StoreLogs($app->user(), $logs);
  }

}
