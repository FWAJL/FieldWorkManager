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

  	if(!empty($relation_data) && is_array($relation_data)){
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
          $data = array(
              'task_id' => $task_id, 
              'location_id' => $location_id, 
              'field_analyte_id' => $analyte_id,
              'field_analyte_location_result' => ''); 
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

  /**
  * Checks if any analyte (based on analyte type)
  * and location exists for the passed task.
  *
  * Returns true if both are present, false if
  * either doesn't.
  */
  public static function DoesAnalytesAndLocationsExistsFor($sessionTask, $caller, $analyte_type = 'Lab') {
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($caller->app()->user());
    //Store all analytes for this task
    \Applications\PMTool\Helpers\AnalyteHelper::StoreListsData($caller);

    //Task specific locations
    $project_locations = \Applications\PMTool\Helpers\LocationHelper::GetProjectLocations($caller, $sessionProject);
    $task_locations = \Applications\PMTool\Helpers\LocationHelper::GetAndStoreTaskLocations($caller, $sessionTask);
    $analyte_count = 0;
    if($analyte_type === 'Lab'){
      //check for LAB analytes
      //Get task specific lab analytes
      $task_lab_analytes = \Applications\PMTool\Helpers\AnalyteHelper::GetAndStoreTaskLabAnalytes($caller, $sessionTask);
      //\Applications\PMTool\Helpers\CommonHelper::pr($task_locations);
      $analyte_count = count($task_lab_analytes);
      
    }
    else {
      //check for FIELD analytes
      $task_field_analytes = \Applications\PMTool\Helpers\AnalyteHelper::GetAndStoreTaskFieldAnalytes($caller, $sessionTask);
      $analyte_count = count($task_field_analytes);
    }

    if($analyte_count > 0 && count($task_locations) > 0){
      return true;
    } else { return false; }
  }

  /**
  * Saves the field data matrix result to the db
  */
  public static function SaveAnalyteMatrixResult($caller, $task_id, $matrix_data) {
    //Field_analyte_location
    //First thing, split the in coming data on '&'
    $data_pairs = explode('&', $matrix_data);
    //loop and further isolate the ids and the actual data
    foreach($data_pairs as $the_data_pair) {
      $data_nodes = explode('=', $the_data_pair);
      
      //The result part is isolated
      $matrix_result = $data_nodes[1];

      //Get the location and field analyte id
      $loc_analyte_ids = explode('_', str_replace('field_data_result_', '', $data_nodes[0]));
      $location_id = $loc_analyte_ids[0];
      $field_analyte_id = $loc_analyte_ids[1];

      //Get manager
      $manager = $caller->managers()->getManagerOf('FieldAnalyteLocation');
      $ret_val = $manager->updateFieldDataMatrixResult($location_id, $field_analyte_id, urldecode($matrix_result));
      if($ret_val == false) {
        break;
      }
    }
    
    return $ret_val;
  }

  /**
  * Returns a page of objects based
  * on config like records per page
  * and current page number
  */
  public static function returnPagedAnalyteObjects($analytes, $pg_no, $app) {
    //CommonHelper::pr($analytes);
    $rec_per_pg = $app->config->get('AnalytesPerPage');
    $end_index = $pg_no * $rec_per_pg;
    $start_index = $end_index - $rec_per_pg;
    
    $paged_objects = array();
    //Loop within the boundary we defined
    for($i = $start_index; $i < $end_index; $i++) {
      //Check if the analyte exists at that poition
      if(isset($analytes[$i])) {
        //Push to return var
        array_push($paged_objects, $analytes[$i]);
      }
    }

    //Return
    return $paged_objects;
  }

  /**
  * Returns the number of pages possible
  * based on the number of pages in the 
  * config file
  */
  public static function returnTotalPagesOfAnalytes($analytes, $app) {
    $rec_per_pg = $app->config->get('AnalytesPerPage');
    $total_records = count($analytes);

    $pages = ($total_records % $rec_per_pg == 0) ? ($total_records / $rec_per_pg) : (($total_records - ($total_records % $rec_per_pg)) / $rec_per_pg) + 1;

    return $pages;
  }

  /**
  * Fetches the relationship between location and analyte
  * for the passed Task and Analyte Type, along with the 
  * result if any (Only valid for field_analyte_location)
  */
  public static function GetFieldDataMatrixForTaskWithResult($caller, $task_id) {
    $id_map = array();
    $fmatrix_result_data = array();
    
    //FIELD Analyte
    $matrixDAO = new \Applications\PMTool\Models\Dao\Field_analyte_location();
    $matrixDAO->setTask_id($task_id);
    $dal = $caller->managers()->getManagerOf("FieldLabAnalyte");
    $relation_data = $dal->selectMany($matrixDAO, "task_id");

    if(!empty($relation_data) && is_array($relation_data)){
      foreach($relation_data as $relation){
        
        $id_str = $relation->location_id() . '_' . $relation->field_analyte_id();
        
        array_push($id_map, $id_str);
        array_push($fmatrix_result_data, $relation->field_analyte_location_result());
      }
    }

    return array('id_map' => $id_map, 'result_map' => $fmatrix_result_data);
  }

  /**
  * Creates a new relation in the "field_analyte_location" table based on 
  * the passed task_id, location_id
  */
  public static function CreateFALocationRelationForFT($caller, $task_id, $location_id) {
    //Get the Field data for this task id
    //FIELD Analyte
    $tfaDAO = new \Applications\PMTool\Models\Dao\Task_field_analyte();
    $tfaDAO->setTask_id($task_id);
    $dal = $caller->managers()->getManagerOf("TaskFieldAnalyte");
    $relation_data = $dal->selectMany($tfaDAO, "task_id");

    //Loop on the above and start preparing data for "field_analyte_location"
    if(count($relation_data) > 0) {
      foreach($relation_data as $fa_rec) {
        //Add to the "field_analyte_location" table
        $data = array(
                'task_id'                         => $task_id, 
                'location_id'                     => $location_id, 
                'field_analyte_id'                => $fa_rec->field_analyte_id(),
                'field_analyte_location_result'   => ''
              ); 
        //Init PDO
        $field_analyte_location = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($data, new \Applications\PMTool\Models\Dao\Field_analyte_location());
        $manager = $caller->managers()->getManagerOf('FieldAnalyteLocation');
        $result_save_relation = $manager->add($field_analyte_location);
      }
    }
  
  }
}