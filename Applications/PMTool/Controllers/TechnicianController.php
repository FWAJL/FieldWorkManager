<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TechnicianController extends \Library\BaseController {

  public function executeIndex(\Library\HttpRequest $rq) {  }

  public function executeShowForm(\Library\HttpRequest $rq) {
    //Load Modules for view
    $this->page->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  }

  public function executeListAll(\Library\HttpRequest $rq) {
    //Get list of object stored in session
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->app()->user());
    $technicians = \Applications\PMTool\Helpers\TechnicianHelper::GetPmTechnicians($this, $pm);
	 
	//Fetch tooltip data from xml and pass to view as an array
	$tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('data-technician-id', $this->app->name());
	$this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::tooltip_message, $tooltip_array);
	
    $data = array(
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => $technicians,
      \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower($this->module()))
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $this->page->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::active_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::active_list]);
    $this->page->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::inactive_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::inactive_list]);
  }

  public function executeAdd(\Library\HttpRequest $rq) {
    // Init result sent to client (e.g. browser)
    $result = $this->InitResponseWS();

    //Get the current PM Session
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->app()->user());
    //Store the pm_id in the dataPost...
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[\Library\Enums\SessionKeys::PmObject]->pm_id();
    //.. and build the object to query the DB
    $technician = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Technician());
    $result["dataIn"] = $technician;

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module);
    $result["dataId"] = $manager->add($technician);
    if ($pm !== NULL) {
      //Update the object with last inserted Id
      $technician->setTechnician_id($result["dataId"]);
      //Update the PM Session
      array_push($pm[\Library\Enums\SessionKeys::PmTechnicians], $technician);
      //And update the Sessiom
      \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->app()->user(), $pm);
    }
    //Send the response to browser
    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Technician,
      "resx_key" => $this->action(),
      "step" => (intval($result["dataId"])) > 0 ? "success" : "error"
    ));
  }

  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->app()->user());
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[\Library\Enums\SessionKeys::PmObject]->pm_id();
    $technician = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Technician());
    $result["data"] = $technician;

    $manager = $this->managers->getManagerOf($this->module);
    $result_insert = $manager->edit($technician, "technician_id");

    if ($result_insert) {
      //Find what is the index of the current edited object in a list of object
      $filter = \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById($technician->technician_id(), "technician_id", $pm, \Library\Enums\SessionKeys::PmTechnicians);
      $pm[\Library\Enums\SessionKeys::PmTechnicians][$filter["key"]] = $technician;
      \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->app()->user(), $pm);
    }

    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Technician,
      "resx_key" => $this->action(),
      "step" => $result_insert ? "success" : "error"
    ));
  }

  public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $db_result = FALSE;
    $technician_id = intval($this->dataPost["technician_id"]);
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->app()->user());
    //Check if the technician to be deleted is the Project manager's
    $filter = \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById($technician_id, "technician_id", $pm, \Library\Enums\SessionKeys::PmTechnicians);
    //Load interface to query the database
    if ($filter["object"] !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($filter["object"], "technician_id");
      if ($db_result) {
        unset($pm[\Library\Enums\SessionKeys::PmTechnicians][$filter["key"]]);
        \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->app()->user(), $pm);
      }
    }
    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Technician,
      "resx_key" => $this->action(),
      "step" => $db_result !== FALSE ? "success" : "error"
    ));
  }

  public function executeGetList(\Library\HttpRequest $rq, $isNotAjaxCall = FALSE, $pm = NULL) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[\Library\Enums\SessionKeys::PmObject]->pm_id();
    $technician = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject(
        $this->dataPost(), new \Applications\PMTool\Models\Dao\Technician()
    );
    $result["data"] = $technician;

    //Load interface to query the database for technicians
    $manager = $this->managers->getManagerOf($this->module);
    $pm[\Library\Enums\SessionKeys::PmTechnicians] = $manager->selectMany($technician, "pm_id");
    if ($pm !== NULL) {
      \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->app()->user(), $pm);
    }

    $result["technicians"] = $pm[\Library\Enums\SessionKeys::PmTechnicians];//Can be used for an AJAX call
    if (!$isNotAjaxCall) {
      $step_result = $step_result = $result[\Library\Enums\SessionKeys::UserTechnicianList] !== NULL ? "success" : "error";
      $this->SendResponseWS(
          $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Technician,
        "resx_key" => $this->action(),
        "step" => $step_result
      ));
    }
  }

  public function executeGetItem(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $technician_id = intval($this->dataPost["technician_id"]);

    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->app()->user());
    $technician_selected = \Applications\PMTool\Helpers\CommonHelper::FindObject($technician_id, "technician_id", $pm[\Library\Enums\SessionKeys::PmTechnicians]);

    $result["technician"] = $technician_selected;
    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Technician,
      "resx_key" => $this->action(),
      "step" => ($technician_selected !== NULL) ? "success" : "error"
    ));
  }

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $rows_affected = 0;
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->app()->user());
    //Get the technician objects from ids received
    $technician_ids = str_getcsv($this->dataPost["technician_ids"], ',');
    $matchedElements = $this->FindObjectsFromIds(
        array(
          "filter" => "technician_id",
          "ids" => $technician_ids,
          "objects" => $pm[\Library\Enums\SessionKeys::PmTechnicians])
    );

    foreach ($matchedElements as $technician) {
      //With the line below, you will update the item $pm[\Library\Enums\SessionKeys::PmTechnicians]
      //Therefore, you just need to save the variable $pm at the end of the processing
      $technician->setTechnician_active($this->dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $this->managers->getManagerOf($this->module);
      $rows_affected += $manager->edit($technician, "technician_id") ? 1 : 0;
    }
    if ($rows_affected === count($technician_ids)) {
      \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->app()->user(), $pm);
    }
    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Technician,
      "resx_key" => $this->action(),
      "step" => ($rows_affected === count($technician_ids)) ? "success" : "error"
    ));
  }

}
