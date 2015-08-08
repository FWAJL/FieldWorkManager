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
 * CommonHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	MobileHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class MobileHelper {

  /**
  * The following function returns the Field Data Matrix from DB
  * in Json format
  */
  public static function GetFieldMatrixDataFromDB($caller, $task_id, $loc_id) {
  	$dal = $caller->managers()->getManagerOf("FieldAnalyteLocation");
  	$data = $dal->getAllMatrixDataForTaskLocation($task_id, $loc_id);

  	if(empty($data)) {
      $output_arr = array('msg' => 'No field analyte found', 'data_matrix' => $data);
    } else {
      $final_result_arr = array();
      //loop and format the array
      foreach ($data as $key => $value) {
        $fa = \Applications\PMTool\Helpers\AnalyteHelper::getAnalyteRecordFromDB($caller, $value->field_analyte_id());
        $matrix_data = array(
            'matrix_rec'  => $value,
            'fa_name'     => $fa[0]->field_analyte_name_unit()
          );
        array_push($final_result_arr, $matrix_data);
      }
      //Stuff up
      $output_arr = array('msg' => 'Records found', 'data_matrix' => $final_result_arr);
    }
    //Return json
    //return json_encode($output_arr);
    return $output_arr;
  }
}
