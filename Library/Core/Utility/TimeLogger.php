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

  public static function SetLog($user, \Library\Core\BO\Log $log) {
    $logs = Logger::GetLogs($user);
    $logs[$log->log_type()][$log->log_request_id()] = $log;
    Logger::StoreLogs($user, $logs);
  }
  
  public static function StartLog(\Library\Application $app, $type) {
    $log = new \Library\Core\BO\Log();
    $log->setLog_type($type);
    $log->setLog_request_id($app->HttpRequest()->requestId());
    $log->setLog_start(Logger::GetTime());
    $log->setLog_filter($app->HttpRequest()->requestURI());
    self::SetLog($app->user(), $log);
  }

  public static function EndLog(\Library\Application $app, $type) {
    $logs = Logger::GetLogs($app->user());
    $log = $logs[$type][$app->HttpRequest()->requestID()];
    $log->setLog_end(Logger::GetTime());
    $log->setLog_execution_time(
        ($log->log_end - $log->log_start()) * 1000
        );
    $log->setLog_start(gmdate("Y-m-d H:i:s", $log->log_start()));
    $log->setLog_end(gmdate("Y-m-d H:i:s", $log->log_end()));
    Logger::AddLogToDatabase($app, $log);
    Logger::StoreLogs($app->user(), $logs);
  }

}
