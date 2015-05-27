<?php

/**
 *
 * @package     The Loffy Framework
 * @author      Jeremie Litzler
 * @copyright   Copyright (c) 2015
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * UserRole Class
 *
 * @package       Library
 * @subpackage    Enums
 * @author        Jeremie Litzler
 * @link		
 */

namespace Library\Enums;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class UserRole {
  const Admin = 1;
  const ProjectManager = 2;
  const Technician = 3;
  const Visitor = 4;
  const Client = 5;
  const Service = 6;
  const TBD = 7;
}