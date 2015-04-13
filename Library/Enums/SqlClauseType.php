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
 * SqlClauseType Class
 *
 * @package		Library
 * @subpackage	Enums
 * @author		Jeremie Litzler
 * @link		
 */

namespace Library\Enums;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class SqlClauseType {
  const WHERE = "WHERE";
  const ORDERBY = "ORDER_BY";
  const GROUPBY = "GROUP_BY";
  const HAVING = "HAVING";
  const INNERJOIN = "INNER_JOIN";
  const LEFTJOIN = "LEFT_JOIN";
  const RIGHTJOIN = "RIGHT_JOIN";
  
}