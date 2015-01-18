<?php
/**
 *
 * @package		Basic MVC framework
 * @author		FWM DEV Team
 * @copyright	Copyright (c) 2015
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * GenericAppKeys Class
 *
 * @package		Library
 * @subpackage	Enums
 * @category	GenericAppKeys
 * @author		FWM DEV Team
 * @link		
 */
namespace Library\Enums;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

/**
 * Lists all the SessionKeys used throughout the applications so that we don't use hard-coded strings.
 */
abstract class GenericAppKeys {
    const GET_METHOD = "get";
    const POST_METHOD = "post";
    const PUT_METHOD = "put";
}

?>
