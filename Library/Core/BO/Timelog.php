<?php

/**
 *
 * @package     Basic MVC framework
 * @author      Jeremie Litzler
 * @copyright   Copyright (c) 2015
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Timelog Class
 *
 * @package       {ROOT_FOLDER}
 * @subpackage    {SUB_FOLDER}
 * @category      Timelog
 * @author        Jeremie Litzler
 * @link		
 */

namespace Library\Core\BO;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class Timelog extends Log {

  public
      $timeStart,
      $timeEnd;

  //Getters
  public function timeStart() {
    return $this->timeStart;
  }

  public function timeEnd() {
    return $this->timeEnd;
  }

  //Setters
  public function setTimeStart($timeStart) {
    $this->timeStart = $timeStart;
  }

  public function setTimeEnd($timeEnd) {
    $this->timeEnd = $timeEnd;
  }

}
