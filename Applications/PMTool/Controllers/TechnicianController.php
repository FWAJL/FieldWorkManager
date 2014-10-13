<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class TechnicianController extends \Library\BaseController {
    
    public function executeIndex(\Library\HttpRequest $rq) {
    //Get list of technicians and store in session
    $lists = $this->_GetAndStoreTechniciansInSession($rq);

    if (count($lists[\Library\Enums\SessionKeys::UserTechnicians]) > 0) {
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
    $this->_GetAndStoreTechniciansInSession($rq);
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserTechnicians),
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
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[0]->pm_id();
    $technician = $this->_PrepareTechnicianObject($this->dataPost());
    $result["dataIn"] = $technician;

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module);
    $result["dataOut"] = $manager->add($technician);

    //Clear the technician list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserTechnicians);
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserTechnicianList);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Technician,
        "resx_key" => $this->action(),
        "step" => (intval($result["dataOut"])) > 0 ? "success" : "error"
    ));
  }
  
  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[0]->pm_id();
    $technician = $this->_PrepareTechnicianObject($this->dataPost());
    $result["data"] = $technician;

    $manager = $this->managers->getManagerOf($this->module);
    $result_insert = $manager->edit($technician);
    

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Technician,
        "resx_key" => $this->action(),
        "step" => $result_edit ? "success" : "error"
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
      $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserTechnicians);
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
  
  public function executeGetList(\Library\HttpRequest $rq, $isNotAjaxCall = FALSE) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[0]->pm_id();
    $technician = $this->_PrepareTechnicianObject($this->dataPost());
    $result["data"] = $technician;

    //Load interface to query the database for technicians
    $manager = $this->managers->getManagerOf($this->module);
    $list[\Library\Enums\SessionKeys::UserTechnicians] = $manager->selectMany($technician);

    $result["lists"] = $list;
    if ($isNotAjaxCall) {
      return $list;
    } else {
      $step_result =
             $step_result = $result[\Library\Enums\SessionKeys::UserTechnicians] !== NULL ? "success" : "error";
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
    $technicians = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserTechnicians);
    $matchedElements = $this->FindObjectsFromIds(
            array(
                "filter" => "technician_id",
                "ids" => $technician_ids,
                "objects" => $technicians)
    );

    //Update the technician objects in DB and get result (number of rows affected)
    //$this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserTechnicians);
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
  
  /**
   * Find a technician from an id
   * 
   * @param int $technician_id : the id of the technician to find
   * @return \Applications\PMTool\Models\Dao\Technician $technicianMatch : the match
   */
  private function _GetTechnicianFromSession($technician_id) {
    $technicians = array();
    $technicianMatch = NULL;
    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserTechnicians)) {
      $technicians = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserTechnicians);
    }
    foreach ($technicians as $technician) {
      if (intval($technician->technician_id()) === $technician_id) {
        $technicianMatch = $technician;
        break;
      }
    }
    return $technicianMatch;
  }
  
    /**
   * Check if the current pm has technicians to decide where to send him: stay on the technician list or asking him to add a technician
   * 
   * @param \Applications\PMTool\Models\Dao\Technician_manager $pm
   * @return boolean
   */
  private function _CheckIfPmHasTechnicians(\Applications\PMTool\Models\Dao\Technician_manager $pm) {

    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserTechnicians)) {
      $technicians = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserTechnicians);
      return count($technicians) > 0 ? TRUE : FALSE;
    }
    $manager = $this->managers->getManagerOf($this->module);
    $count = $manager->countById($pm->pm_id());
    return $count > 0 ? TRUE : FALSE;
  }
  
  private function _PrepareTechnicianObject($data_sent) {
    $technician = new \Applications\PMTool\Models\Dao\Technician();
    $technician->setPm_id($data_sent["pm_id"]);
    $technician->setTechnician_id(!array_key_exists('technician_id', $data_sent) ? NULL : $data_sent["technician_id"]);
    $technician->setTechnician_name(!array_key_exists('technician_name', $data_sent) ? NULL : $data_sent["technician_name"]);
    $technician->setTechnician_phone(!array_key_exists('technician_phone', $data_sent) ? "" : $data_sent["technician_phone"]);
    $technician->setTechnician_email(!array_key_exists('technician_email', $data_sent) ? "" : $data_sent["technician_email"]);
    $technician->setTechnician_document(!array_key_exists('technician_document', $data_sent) ? "" : $data_sent["technician_document"]);
    $technician->setTechnician_active(!array_key_exists('technician_active', $data_sent) ? 0 : ($data_sent["technician_active"] === "1"));

    return $technician;
  }
  
  /**
   * Checks if the user technicians  are not stored in Session.
   * Stores the technicians and facilities after call to WS to retrieve them
   * Set the data into the session for later use.
   * 
   * @param /Library/HttpRequest $rq
   * @return array $lists : the lists of objects if any 
   */
  private function _GetAndStoreTechniciansInSession($rq) {
    $lists = array();
    if (!$this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserTechnicians)) {

      $lists = $this->executeGetList($rq, TRUE);

      $this->app()->user->setAttribute(
              \Library\Enums\SessionKeys::UserTechnicians, $lists[\Library\Enums\SessionKeys::UserTechnicians]
      );
    } else {
      $lists[\Library\Enums\SessionKeys::UserTechnicians] = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserTechnicians);
    }
    return $lists;
  }

}