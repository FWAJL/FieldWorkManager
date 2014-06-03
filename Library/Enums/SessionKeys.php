<?php
/**
 *
 * @package		Basic MVC framework
 * @author		FWA DEV Team
 * @copyright	Copyright (c) 2014
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * SessionKeys Class
 *
 * @package		Library
 * @subpackage	Enums
 * @category	SessionKeys
 * @author		FWA Dev Team
 * @link		
 */
namespace Library\Enums;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

/**
 * Lists all the SessionKeys used throughout the applications so that we don't use hard-coded strings.
 */
abstract class SessionKeys {
  const UserAuthenticated = "user_auth";
  const UserFlash = 'user_flash';
  
}

?>
