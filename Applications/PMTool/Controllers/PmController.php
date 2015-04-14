<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class PmController extends \Library\BaseController {
    
    public function executeIndex(\Library\HttpRequest $rq) {  }
    
public function executeShowForm(\Library\HttpRequest $rq) {
    //Load Modules for view
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  } 
  
  public function executeListAll(\Library\HttpRequest $rq) {
    //Get list of pms stored in session
    $this->_GetAndStorePmsInSession($rq);
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => $this->app()->user->getAttribute(\Library\Enums\SessionKeys::AllUsers),
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
    $pmSession = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $this->dataPost["pm_id"] = $pmSession === NULL ? NULL : $pmSession[\Library\Enums\SessionKeys::PmObject]->pm_id();
    $pm = $this->_PreparePmObject($this->dataPost());
    $result["dataIn"] = $pm;

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module);
    $result["dataId"] = $manager->add($pm);

    //Clear the pm list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::AllUsers);
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserPmList);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Pms\Enums\ResxFileNameKeys::Pm,
        "resx_key" => $this->action(),
        "step" => (intval($result["dataId"])) > 0 ? "success" : "error"
    ));
  }
  
  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pmSession = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $this->dataPost["pm_id"] = $pmSession === NULL ? NULL : $pmSession[\Library\Enums\SessionKeys::PmObject]->pm_id();
    $pm = $this->_PreparePmObject($this->dataPost());
    $result["data"] = $pm;

    $manager = $this->managers->getManagerOf($this->module);
    $result_insert = $manager->edit($pm, "pm_id");
    if($result_insert) {
      \Applications\PMTool\Helpers\PmHelper::StoreSessionPm($this,$pm,true);
    }
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Pm,
        "resx_key" => $this->action(),
        "step" => $result_insert ? "success" : "error"
    ));
  }
  
    public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $db_result = FALSE;
    $pm_id = intval($this->dataPost["pm_id"]);

    //Check if the pm to be deleted is the Project manager's
    $pm_selected = $this->_GetPmFromSession($pm_id);
    //Load interface to query the database
    if ($pm_selected !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($pm_selected, "pm_id");
      //Clear the pm from session for the connect PM
      $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::AllUsers);
      $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserPmList);
//      \Applications\PMTool\Helpers\CommonHelper::UnsetUserSessionPm($this->app()->user(), $pm_id);
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Pm,
        "resx_key" => $this->action(),
        "step" => $db_result !== FALSE ? "success" : "error"
    ));
  }
  
  public function executeGetList(\Library\HttpRequest $rq, $isNotAjaxCall = FALSE) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = $this->_PreparePmObject($this->dataPost());
    $result["data"] = $pm;

    //Load interface to query the database for pms
    $manager = $this->managers->getManagerOf($this->module);
    $list[\Library\Enums\SessionKeys::AllUsers] = $manager->selectMany($pm, "");

    $result["lists"] = $list;
    if ($isNotAjaxCall) {
      return $list;
    } else {
      $step_result =
             $step_result = $result[\Library\Enums\SessionKeys::AllUsers] !== NULL ? "success" : "error";
      $this->SendResponseWS(
              $result, array(
          "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Pm,
          "resx_key" => $this->action(),
          "step" => $step_result
      ));
    }
  }
   
public function executeGetItem(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $pm_id = intval($this->dataPost["pm_id"]);

    $pm_selected = $this->_GetPmFromSession($pm_id);

    $result["pm"] = $pm_selected;
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Pm,
        "resx_key" => $this->action(),
        "step" => ($pm_selected !== NULL) ? "success" : "error"
    ));
  }
    
  /**
   * Find a pm from an id
   * 
   * @param int $pm_id : the id of the pm to find
   * @return \Applications\PMTool\Models\Dao\Pm $pmMatch : the match
   */
  private function _GetPmFromSession($pm_id) {
    $pms = array();
    $pmMatch = NULL;
    $pm = \Applications\PMTool\Helpers\PmHelper::GetSessionPm($this->app->user(),$pm_id);
    $pmMatch = $pm[\Library\Enums\SessionKeys::PmObject];
    return $pmMatch;
  }
  
    /**
   * Check if the current pm has pms to decide where to send him: stay on the pm list or asking him to add a pm
   * 
   * @param \Applications\PMTool\Models\Dao\Pms $pm
   * @return boolean
   */
  private function _CheckIfPmHasPms(\Applications\PMTool\Models\Dao\Pms $pm) {

    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::AllUsers)) {
      $pms = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::AllUsers);
      return count($pms) > 0 ? TRUE : FALSE;
    }
    $manager = $this->managers->getManagerOf($this->module);
    $count = $manager->countById($pm->pm_id());
    return $count > 0 ? TRUE : FALSE;
  }
  
  private function _PreparePmObject($data_sent) {
    $pm = new \Applications\PMTool\Models\Dao\Project_manager();
    $pm->setPm_id($data_sent["pm_id"]);
    $pm->setPm_name($data_sent["pm_name"]);
    $pm->setPm_address(!array_key_exists('pm_address', $data_sent) ? "" : $data_sent["pm_address"]);
    $pm->setPm_comp_name(!array_key_exists('pm_comp_name', $data_sent) ? "" : $data_sent["pm_comp_name"]);
    $pm->setPm_phone(!array_key_exists('pm_phone', $data_sent) ? "" : $data_sent["pm_phone"]);
    $pm->setPm_email(!array_key_exists('pm_email', $data_sent) ? "" : $data_sent["pm_email"]);
//    $pm->setPm_active(!array_key_exists('pm_active', $data_sent) ? 0 : ($data_sent["pm_active"] === "1"));

    return $pm;
  }
  
  /**
   * Checks if the user pms  are not stored in Session.
   * Stores the pms and facilities after call to WS to retrieve them
   * Set the data into the session for later use.
   * 
   * @param /Library/HttpRequest $rq
   * @return array $lists : the lists of objects if any 
   */
  private function _GetAndStorePmsInSession($rq) {
    $lists = array();
    if (!$this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::AllUsers)) {

      $lists = $this->executeGetList($rq, TRUE);

      $this->app()->user->setAttribute(
              \Library\Enums\SessionKeys::AllUsers, $lists[\Library\Enums\SessionKeys::AllUsers]
      );
    } else {
      $lists[\Library\Enums\SessionKeys::AllUsers] = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::AllUsers);
    }
    return $lists;
  }

}
