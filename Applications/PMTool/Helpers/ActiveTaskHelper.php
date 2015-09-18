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
      \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskNotesTab => "",       
      \Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskFieldDataTab => ""
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
    $dal = $caller->managers()->getManagerOf("Document");
    $documents = $dal->selectManyByCategoryAndId("task_location");

    $docData = array();
    //Return empty array if there are no documents found
    if (count($documents) === 0) {
      return $docData;
    }
    //Otherwise loop on the location data    
    foreach ($location_data as $location) {
      foreach ($documents as $doc) {
        if (strpos($doc->document_value(), $location->task_location_id() . '_') === 0) {
          array_push($docData, $doc);
        }
      }
    }

    //Return the doc array
    return $docData;
  }

  /**
  * Queries the database for the task (tas_id passed)
  * and returns it as task object
  */
  public static function QueryDBForActiveTaskData($task_id, $caller) {
    $taskDAO = new \Applications\PMTool\Models\Dao\Task();
    $taskDAO->setTask_id($task_id);
    $dal = $caller->managers()->getManagerOf("Task");
    $taskObj = $dal->selectMany($taskDAO, "task_id");
    
    return $taskObj[0];
  }

  /**
  * Starts communication with 
  * entities with passed parameters
  */
  public static function StartCommunicationWith($caller, $selection_type, $selection_id) {

    $sessionTask = \Applications\PMTool\Helpers\TaskHelper::GetCurrentSessionTask($caller->user());
    $taskDiscussions = \Applications\PMTool\Helpers\DiscussionHelper::GetAllTaskDiscussions($caller, $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());


    if($selection_type == 'technician_id') {
      $technicians = \Applications\PMTool\Helpers\TechnicianHelper::GetAndStoreTaskTechnicians($caller,$sessionTask);
      foreach($technicians as $technician) {
        if($technician->technician_id() == $selection_id) {
          $manager = $caller->managers()->getManagerOf('User');
          $user = $manager->selectUserByTypeId('technician_id', $technician->technician_id());
          break;
        }
      }
    } else if($selection_type == 'service_id') {
      $services = \Applications\PMTool\Helpers\ServiceHelper::GetAndStoreTaskServices($caller, $sessionTask);
      foreach($services as $service) {
        if($service->service_id() == $selection_id) {
          $manager = $caller->managers()->getManagerOf('User');
          $user = $manager->selectUserByTypeId('service_id', $service->service_id());
          break;
        }
      }
    } else if($selection_type == 'pm_id') {
      $sessionPm = $caller->user->getAttribute(\Library\Enums\SessionKeys::CurrentPm);
      $currentPmObject = $sessionPm[\Library\Enums\SessionKeys::PmObject];
      if($currentPmObject->pm_id() == $selection_id) {
        $manager = $caller->managers()->getManagerOf('User');
        $user = $manager->selectUserByTypeId('pm_id', $currentPmObject->pm_id());
      }
    }
    //we can add more users later if we choose to add more people in same discussion
    $discussionUsers = array($caller->user()->getAttribute(\Library\Enums\SessionKeys::UserConnected), $user);

    $discussion = \Applications\PMTool\Helpers\DiscussionHelper::CheckIfDiscussionExistsByUsers($caller, $caller->user()->getAttribute(\Library\Enums\SessionKeys::UserConnected), $user, $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
    if ($discussion === false) {
      $discussion = \Applications\PMTool\Helpers\DiscussionHelper::CreateNewDiscussion($caller, $discussionUsers, $sessionTask[\Library\Enums\SessionKeys::TaskObj]->task_id());
    }
    //in case create discussion returned false we will check if discussion is false again
    if ($discussion !== false) {
      $manager = $caller->managers()->getManagerOf('DiscussionPerson');
      $discussion_person = new \Applications\PMTool\Models\Dao\Discussion_person();
      $discussion_person->setDiscussion_id($discussion->discussion_id());
      //select all connected people so we can store them in session
      $discussion_people = $manager->selectMany($discussion_person, 'discussion_id');
      \Applications\PMTool\Helpers\DiscussionHelper::SetCurrentDiscussion($caller->user(), $discussion, $discussion_people);
      $return_val = true;
    } else {
      $return_val = false;
    }

    return $return_val;

  }

}
