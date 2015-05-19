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
 * UserRoleType Class
 *
 * @package       Library
 * @subpackage    Enums
 * @author        Jeremie Litzler
 * @link		
 */

namespace Library\Enums;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class UserRoleType {
  const Admin = "administrator_id";
  const ProjectManager = "pm_id";
  const Technician = "technician_id";
  const Visitor = "";
  const Client = "client_id";
  const Service = "service_id";
  const TBD = "";
}