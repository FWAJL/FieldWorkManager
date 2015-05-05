<?php
/**
 * CommonHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	TaskHelper
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ActiveTaskHelper {
	
  public static function AddTabsStatus(\Library\User $user) {
    $tabs = array(
        \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskStatusTab => "active",
        \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskMapTab => "",
        \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskFormsTab => "",
        \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskCommTab => "",
    );
    $user->setAttribute(\Library\Enums\SessionKeys::ActiveTaskTabsStatus, $tabs);
  }
	
  public static function GetTabsStatus(\Library\User $user) {
    return $user->getAttribute(\Library\Enums\SessionKeys::ActiveTaskTabsStatus);
  }
  
  public static function SetActiveTab(\Library\User $user, $tab_name) {
    $tabs = $user->getAttribute(\Library\Enums\SessionKeys::ActiveTaskTabsStatus);
    foreach ($tabs as $key => $value) {
      $tabs[$key] = "";
    }
    $tabs[$tab_name] = "active";
    $user->setAttribute(\Library\Enums\SessionKeys::ActiveTaskTabsStatus, $tabs);
  }

  public static function SetPropertyNamesOfDocumentsForDualList() {
    return array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id => "document_id",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name => "document_title",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_active => "document_id",
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::data_identifier => "document_category",
    );
  }

  /**
  * Returns the task specific documents from the 
  * Document table for this task
  */
  public static function GetDocumentsForActiveTask($activeTask, $caller) {
    //First thing, let's get the task location id's for this active task
    $taskLocationDAO = new \Applications\PMTool\Models\Dao\Task_location();
    $taskLocationDAO->setTask_id($activeTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
    $dal = $caller->managers()->getManagerOf("Task");
    $location_data = $dal->selectMany($taskLocationDAO, "task_id");

    //Fetch all documents
    //Ideally we need a method in BaseManager which would let us
    //query a db field with "LIKE operator" or selectMany should 
    //be modified to do the same
    $documentDAO = new \Applications\PMTool\Models\Dao\Document();
    $dal = $caller->managers()->getManagerOf("Task");
    $documents = $dal->selectMany($documentDAO, "");

    $docData = array();
    //Loop on the location data
    foreach($location_data as $location) {
      foreach($documents as $doc){
        if(strpos($doc->document_value(), $location->task_location_id() . '_') === 0){
          array_push($docData, $doc);
        }
      }
    }

    //Return the doc array
    return $docData;
  }
}