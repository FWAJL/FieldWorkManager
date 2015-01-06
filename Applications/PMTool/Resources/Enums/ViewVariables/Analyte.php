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
  const analyte_tabs_close = "analyte_tabs_close";
  
  const field_analyte_lists = "field_analyte_lists";
  const lab_analyte_lists = "lab_analyte_lists";

  const project_field_analyte_list = "project_field_analyte_list";
  const field_analyte_list = "field_analyte_list";
  const project_lab_analyte_list = "project_lab_analyte_list";
  const lab_analyte_list = "lab_analyte_list";
  
  const field_analyte_buttons = "field_analyte_buttons";
  const lab_analyte_buttons = "lab_analyte_buttons";
  
  //Data keys
  const data_field_analyte = "data_field_analyte";
  const data_lab_analyte = "data_lab_analyte";
  
}

?>
