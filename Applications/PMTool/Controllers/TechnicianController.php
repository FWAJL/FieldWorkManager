<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TechnicianController extends \Library\BaseController {
    
  public function executeIndex(\Library\HttpRequest $rq) {
    $pm = \Applications\PMTool\Helpers\UserHelper::GetCurrentSessionPm($this->app()->user());
    $toList = FALSE;
    if ($rq->getData("target") !== "listAll") {
      //Continue
    }
    elseif (count($pm[\Library\Enums\SessionKeys::PmTechnicians]) > 0) {
      $toList = TRUE;
    } else {
      $this->executeGetList($rq, FALSE, $pm);
      $pm = \Applications\PMTool\Helpers\UserHelper::GetCurrentSessionPm($this->app()->user());
      $toList = count($pm[\Library\Enums\SessionKeys::PmTechnicians]) > 0;
    }

    if ($toList && $rq->getData("target") === "listAll") {
      header('Location: ' . __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::TechnicianListAll);
      } else {
      header('Location: ' . __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::TechnicianShowForm . "?mode=add&test=true");
      }
    }
    
public function executeShowForm(\Library\HttpRequest $rq) {
    //Load Modules for view
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  } 
  
  public function executeListAll(\Library\HttpRequest $rq) {
    //Get list of technicians stored in session
    $pm = \Applications\PMTool\Helpers\UserHelper::GetCurrentSessionPm($this->app()->user());
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => $pm[\Library\Enums\SessionKeys::PmTechnicians],
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
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = \Applications\PMTool\Helpers\UserHelper::GetCurrentSessionPm($this->app()->user());
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[\Library\Enums\SessionKeys::PmObject]->pm_id();
    $technician = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Technician());
    $result["dataIn"] = $technician;

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module);
    $result["dataId"] = $manager->add($technician);
    if ($pm !== NULL) {
      $technician->setTechnician_id($result["dataId"]);
      array_push($pm[\Library\Enums\SessionKeys::PmTechnicians], $technician);
      \Applications\PMTool\Helpers\UserHelper::SetSessionPm($this->app()->user(), $pm);
    }

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
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[0]->pm_id();
    $technician = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost());
    $result["data"] = $technician;

    $manager = $this->managers->getManagerOf($this->module);
    $result_insert = $manager->edit($technician);
    
    //Clear the technician list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserTechnicianList);
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserTechnicianList);

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

    //Check if the technician to be deleted is the Project manager's
    $technician_selected = $this->_GetTechnicianFromSession($technician_id);
    //Load interface to query the database
    if ($technician_selected !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($technician_id);
      //Clear the technician from session for the connect PM
      $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserTechnicianList);
      $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserTechnicianList);
//      \Applications\PMTool\Helpers\CommonHelper::UnsetUserSessionTechnician($this->app()->user(), $technician_id);
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
    $technician = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Technician());
    $result["data"] = $technician;

    //Load interface to query the database for technicians
    $manager = $this->managers->getManagerOf($this->module);
    $pm[\Library\Enums\SessionKeys::PmTechnicians] = $manager->selectMany($technician);
    if ($pm !== NULL) {
      \Applications\PMTool\Helpers\UserHelper::SetSessionPm($this->app()->user(), $pm);
    }
    
    $result["lists"] = $pm[\Library\Enums\SessionKeys::PmTechnicians];
    if (!$isNotAjaxCall) {
      $step_result =
             $step_result = $result[\Library\Enums\SessionKeys::UserTechnicianList] !== NULL ? "success" : "error";
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

    $technician_selected = $this->_GetTechnicianFromSession($technician_id);

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
    //Get the technician objects from ids received
    $technician_ids = str_getcsv($this->dataPost["technician_ids"], ',');
    $technicians = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserTechnicianList);
    $matchedElements = $this->FindObjectsFromIds(
            array(
                "filter" => "technician_id",
                "ids" => $technician_ids,
                "objects" => $technicians)
    );

    //Update the technician objects in DB and get result (number of rows affected)
    //$this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserTechnicianList);
    foreach ($matchedElements as $technician) {
      $technician->setTechnician_active($this->dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $this->managers->getManagerOf($this->module);
      $rows_affected += $manager->edit($technician) ? 1 : 0;
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Technician,
        "resx_key" => $this->action(),
        "step" => ($rows_affected === count($technician_ids)) ? "success" : "error"
    ));
  }
}