<?php
/**
 *
 * @package		Basic MVC framework
 * @author		FWM DEV Team
 * @copyright	Copyright (c) 2014
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * QueryStringKeys Class
 *
 * @package		Library
 * @subpackage	Enums
 * @category	QueryStringKeys
 * @author		FWM DEV Team
 * @link		
 */
namespace Library\Enums;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

/**
 * Lists all the SessionKeys used throughout the applications so that we don't use hard-coded strings.
 */
abstract class QueryStringKeys {
  const EditionMode = "mode";
  const EditionModeAdd = "add";
  const EditionModeEdit = "edit";
}

?>
