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
 * AnalyteHelper Class
 *
 * @package		Application/PMTool
 * @subpackage	Helpers
 * @category	AnalyteHelper
 * @author		FWM DEV Team
 * @link
 */

namespace Applications\PMTool\Helpers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TaskChecklistHelper {

  /**
  * Adds checklist
  */
  public static function AddChecklist($caller, $result, $task_id) {
    
    $manager = $caller->managers()->getManagerOf($caller->module());
    $dataPost = $caller->dataPost();

    $checklists = self::_PrepareManyChecklistObjects($dataPost, $task_id);

    foreach ($checklists as $cl) {
      $manager->add($cl);
    }

    $result['rec_count'] = count($checklists);
    return $result;
  }

  /**
  * Gets all checklist items for this task
  */
  public static function GetAllChecklistsfor($task_id, $caller){
    $checklistDAO = new \Applications\PMTool\Models\Dao\Task_check_list();
    $checklistDAO->setTask_id($task_id);
    $dal = $caller->managers()->getManagerOf("TaskCheckList");
    $checklist_data = $dal->selectMany($checklistDAO, "task_id");
    return $checklist_data;
  }

  /**
  * Deletes a checklist
  */
  public static function DelChecklist($caller, $dataPost) {
    $checklistDAO = new \Applications\PMTool\Models\Dao\Task_check_list();
    $checklistDAO->setTask_check_list_id($dataPost['check_list_id']);
    $manager = $caller->managers()->getManagerOf('TaskCheckList');
    return $manager->delete($checklistDAO, 'task_check_list_id');
  }

  /**
  * Updates the checklist
  */
  public static function UpdateCheckList ($caller, $dataPost) {
    $checklistDAO = new \Applications\PMTool\Models\Dao\Task_check_list();
    $checklistDAO->setTask_check_list_id($dataPost['check_list_id']);
    $checklistDAO->setTask_check_list_detail($dataPost['checklist_detail']);
    $manager = $caller->managers()->getManagerOf('TaskCheckList');
    return $manager->edit($checklistDAO, 'task_check_list_id');
  }

  /**
  * checks duplicate check list
  */
  public static function IsDuplicateCheckList($caller, $dataPost) {
    $checklistDAO = new \Applications\PMTool\Models\Dao\Task_check_list();
    $checklistDAO->setTask_check_list_detail($dataPost['checklist_detail']);
    $dal = $caller->managers()->getManagerOf("TaskCheckList");
    $checklist_data = $dal->selectMany($checklistDAO, "task_check_list_detail");
    return (count($checklist_data) > 0)? true : false ; 
  }

  public static function SetPropertyNamesForDualList($module) {
    return array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id => $module . "_id",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name => $module . "_detail",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_active => $module . "_active",
    );
  }


  private static function _PrepareManyChecklistObjects($dataPost, $task_id) {
    $checklists = array();
    if (preg_match("`^.*,*$`", $dataPost["checklists"])) {
      $checklist_arr = \Applications\PMTool\Helpers\CommonHelper::StringToArray(",", $dataPost["checklists"]);
    } else {
      $checklist_arr = \Applications\PMTool\Helpers\CommonHelper::StringToArray("\n", $dataPost["checklists"]);
    }
    foreach ($checklist_arr as $name) {
      $checklist = new \Applications\PMTool\Models\Dao\Task_check_list();
      $checklist->setTask_id($task_id);
      $checklist->setTask_check_list_complete('0');
      $checklist->setTask_check_list_detail($name);

      array_push($checklists, $checklist);
    }
    return $checklists;
  }

}
