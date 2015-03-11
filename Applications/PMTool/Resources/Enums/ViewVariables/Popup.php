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
 * Popup Class
 *
 * @package		Applications/PMTool
 * @subpackage	Resources/Enum
 * @author		FWM DEV Team
 * @link		
 */


namespace Applications\PMTool\Resources\Enums\ViewVariables;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Popup {
  const popup_prompt_list = "popup_prompt_list";
  //Modules keys
  const popup_prompt_module = "popup_prompt_module";
    //For tooltip
  const tooltip_message = "tooltip_message"; 
  //The module for populating the tooltip hiddens
  const tooltip_message_module = "tooltip_message_module"; 
  //For messages in confirm boxes
  const confirm_message = "confirm_message"; 
  //For messages in prompt boxes
  const prompt_message = "prompt_message";
  const popup_msg = "popup_msg_module";
  const prompt_msg = "prompt_msg_module";
  const prompt_projectselect = "prompt_projectselect_module";
  
}

