<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class UserController extends \Library\BaseController {

  public function executeIndex(\Library\HttpRequest $rq) {  }

  public function executeShowDetails(\Library\HttpRequest $rq) {
    //Load Modules for view
    if($this->app->user()->getUserType()=="pm_id") {
      $modules = $this->app()->router()->selectedRoute()->phpModules();
      $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_details, $modules[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::pm_form]);
    }
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_details_buttons, $modules[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::user_details_buttons]);

  }

  public function executeEditCurrent(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    if($this->app->user()->getUserType()=="pm_id") {
      $pmSession = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
      $this->dataPost["pm_id"] = $pmSession === NULL ? NULL : $pmSession[\Library\Enums\SessionKeys::PmObject]->pm_id();
      $pm = $this->_PreparePmObject($this->dataPost());
      $result["data"] = $pm;

      $manager = $this->managers->getManagerOf($this->module);
      $result_insert = $manager->edit($pm, "pm_id");
      if($result_insert) {
        $pmSession[\Library\Enums\SessionKeys::PmObject] = $pm;
        \Applications\PMTool\Helpers\PmHelper::StoreSessionPm($this->app->user(),$pmSession,true);
      }
    }
    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::User,
      "resx_key" => $this->action(),
      "step" => $result_insert ? "success" : "error"
    ));
  }

  public function executeGetCurrent(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $user_id = $this->app->user()->getAttribute(\Library\Enums\SessionKeys::UserAuthenticated);
    $result["user"] = NULL;
    if($this->app->user()->getUserType()=="pm_id") {
      $pm_selected = $this->_GetPmFromSession($this->app->user()->getUserTypeId());
      $result["user"] = $pm_selected;
      $result["user_type"] = $this->app->user()->getUserType();
    }

    $this->SendResponseWS(
      $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::User,
      "resx_key" => $this->action(),
      "step" => ( $result["user"] !== NULL) ? "success" : "error"
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
