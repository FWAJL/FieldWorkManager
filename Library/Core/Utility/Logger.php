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
 * Logger Class
 *
 * @package		Library
 * @subpackage	Utility
 * @category	Logger
 * @author		Jeremie Litzler
 * @link		
 */

namespace Library\Core\Utility;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class Logger {

  public static function StoreLogs($user, $logs) {
    $user->setAttribute("time_live_logs", $logs);
  }

  public static function GetLogs(\Library\User $user) {
    return $user->getAttribute("time_live_logs");
  }

  public static function PrintOutLogs($logs) {
    \Library\Utility\DebugHelper::LogAsHtmlComment(var_export($logs));
  }

  public static function AddLogToDatabase($app, $log) {
    $db = new \Library\DAL\Managers('PDO', $app);
    $dal = $db->getManagerOf("Log", TRUE);
    $dal->Add($log);
  }

  public static function GetTime() {
    return microtime(true);
  }

}
