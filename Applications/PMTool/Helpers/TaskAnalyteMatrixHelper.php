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
 * TechnicianHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	TechnicianHelper
 * @author		FWM DEV Team
 * @link
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskAnalyteMatrixHelper {

  /**
  * Fetches the relationship between location and analyte
  * for the passed Task and Analyte Type
  */
  public static function GetAnalyteMatrixForTask($caller, $task_id, $analyte_type = 'Lab') {
  	$id_map = array();
  	if($analyte_type === 'Lab') {
  	  //LAB analyte
  	  $matrixDAO = new \Applications\PMTool\Models\Dao\Lab_analyte_location();
  	  $matrixDAO->setTask_id($task_id);
  	  $dal = $caller->managers()->getManagerOf("TaskLabAnalyte");
  	  $relation_data = $dal->selectMany($matrixDAO, "task_id");
  	} else {
  	  //FIELD Analyte
      $matrixDAO = new \Applications\PMTool\Models\Dao\Field_analyte_location();
      $matrixDAO->setTask_id($task_id);
      $dal = $caller->managers()->getManagerOf("FieldLabAnalyte");
      $relation_data = $dal->selectMany($matrixDAO, "task_id");
  	}

  	if(!empty($relation_data)){
  	  foreach($relation_data as $relation){
  	  	if($analyte_type === 'Lab'){
  	  	  $id_str = $relation->location_id() . '_' . $relation->lab_analyte_id();
  	  	} else {
          $id_str = $relation->location_id() . '_' . $relation->field_analyte_id();
        }
        array_push($id_map, $id_str);
  	  }
  	}

  	return $id_map;
  }

  /**
  *	Saves an Analyte to Location relationship data 
  * for the passed in task_id
  */
  public static function SaveAnalyteMatrixForTask($caller, $task_id, $matrix_data, $analyte_type = 'Lab') {
		
    //create data for deletion
    $data = array('task_id' => $task_id);

    if($analyte_type === 'Lab') {
  	  //FOR LAB ANALYTE
	  	//First delete the existing relationship
      
      $data = array('task_id' => $task_id);
      //Init PDO
      $lab_analyte_location = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($data, new \Applications\PMTool\Models\Dao\Lab_analyte_location());
      $manager = $caller->managers()->getManagerOf('TaskLabAnalyte');
      $result_del_relation = $manager->delete($lab_analyte_location, 'task_id');

  	} else {
  	  //FOR FIELD ANALYTE
      //First delete the existing relationship
      
      //Init PDO
      $field_analyte_location = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($data, new \Applications\PMTool\Models\Dao\Field_analyte_location());
      $manager = $caller->managers()->getManagerOf('TaskFieldAnalyte');
      $result_del_relation = $manager->delete($field_analyte_location, 'task_id');      
  	}

  	$ret_val = true;

  	//location_analyte id pairs incoming, process the variable
    $id_pairs = array();
    if(trim($matrix_data) !== '') {
      $data_pairs = explode('&', $matrix_data);
    	foreach($data_pairs as $data_pair){
        $data_nodes = explode('=', $data_pair);
        array_push($id_pairs, $data_nodes[1]);
    	}

    	//Finally loop and save the relationship
    	foreach ($id_pairs as $key => $id_pair) {
        $id_arr = explode('_', $id_pair);
        $location_id = $id_arr[0];
        $analyte_id = $id_arr[1];

        //Save
        //create data
        unset($data);
        if($analyte_type === 'Lab'){
        	$data = array('task_id' => $task_id, 'location_id' => $location_id, 'lab_analyte_id' => $analyte_id);	
        	//Init PDO
	        $lab_analyte_location = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($data, new \Applications\PMTool\Models\Dao\Lab_analyte_location());
	        $manager = $caller->managers()->getManagerOf('TaskLabAnalyte');
	        $result_save_relation = $manager->add($lab_analyte_location);
        } else {
          $data = array('task_id' => $task_id, 'location_id' => $location_id, 'field_analyte_id' => $analyte_id); 
          //Init PDO
          $field_analyte_location = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($data, new \Applications\PMTool\Models\Dao\Field_analyte_location());
          $manager = $caller->managers()->getManagerOf('TaskFieldAnalyte');
          $result_save_relation = $manager->add($field_analyte_location);
        }    
      }
    }
    else{
    	$ret_val = false;
    }
    return $ret_val;
  }
}