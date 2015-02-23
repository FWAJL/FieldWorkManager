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
 * Analyte Class
 *
 * @package		Applications/PMTool
 * @subpackage	Resources/Enum
 * @category	Analyte
 * @author		FWM DEV Team
 * @link		
 */


namespace Applications\PMTool\Resources\Enums\ViewVariables;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Analyte {
  //Modules keys
  const analyte_tabs_open = "analyte_tabs_open";
  const tabs_close = "tabs_close";
  
  const field_analyte_lists = "field_analyte_lists";
  const lab_analyte_lists = "lab_analyte_lists";
  const up_field_analyte_lists = "up_field_analyte_lists";
  const up_lab_analyte_lists = "up_lab_analyte_lists";
  const field_analyte_form = "field_analyte_form";
  const lab_analyte_form = "lab_analyte_form";
  const popup_prompt_module = "popup_prompt_module";

  const project_field_analyte_list = "project_field_analyte_list";
  const field_analyte_list = "field_analyte_list";
  const project_lab_analyte_list = "project_lab_analyte_list";
  const lab_analyte_list = "lab_analyte_list";
  const common_field_analyte_list = "common_field_analyte_list";
  const common_lab_analyte_list = "common_lab_analyte_list";
  
  
  const analyte_buttons = "analyte_buttons";
  const common_analyte_buttons = "common_analyte_buttons";
  const field_analyte_buttons = "field_analyte_buttons";
  const lab_analyte_buttons = "lab_analyte_buttons";
  const up_analyte_list_buttons = "up_analyte_list_buttons";
  
  //Data keys
  const data_field_analyte = "data_field_analyte";
  const data_lab_analyte = "data_lab_analyte";
  const data_common_field_analyte = "data_common_field_analyte";
  const data_common_lab_analyte = "data_common_lab_analyte";
  
}

?>
