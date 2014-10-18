<?php

/**
 *
 * @package		Basic MVC framework
 * @author		FWM DEV Team
 * @copyright	Copyright (c) 2014
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Authenticate controller Class
 *
 * @package		Application/PMTool
 * @subpackage	Controllers
 * @category	FacilityController
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class FacilityController extends \Library\BaseController {

  /**
   * Method that adds a facility and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeAdd(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();

    $facility = $this->PrepareUserObject($this->dataPost());
    $result["data"] = $facility;
    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module());
    $result_insert = $manager->add($facility);

    //Clear the project and facility list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjects);
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjectFacilityList);

    //Process DB result and send result
    if ($result_insert)
      $result = $this->SendResponseWS(
              $result,
              array(
                  "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Facility, 
                  "resx_key" => $this->action(), "step" => $result_insert ? "success" : "error"));
  }

  /**
   * Method that edits a a facility and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeEdit(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module());
    $result_edit = $manager->edit($this->PrepareUserObject($this->dataPost()));

    if ($result_edit) {
      //Clear the facility list from session for the connected PM
      $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjectFacilityList);
    }
    $result = $this->SendResponseWS(
            $result,
            array(
                "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Facility, 
                "resx_key" => $this->action(), "step" => $result_edit ? "success" : "error"));
  }
  /**
   * Method that delete a facility and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $manager = $this->managers->getManagerOf($this->module());
    $result_db = $manager->delete($this->dataPost["facility_id"]);

    if ($result_db) {
      $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjectFacilityList); 
    }
    $result = $this->SendResponseWS(
            $result,
            array(
                "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Facility, 
                "resx_key" => $this->action(), "step" => $result_db ? "success" : "error"));    
  }
  /**
   * Method that retrieves a list of facilities for a project
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeGetList(\Library\HttpRequest $rq, $isNotAjaxCall = FALSE) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $data_sent["pm_id"] = $pm === NULL ? NULL : $pm[0]->pm_id();
    $project = $this->PrepareUserObject($data_sent);
    $result["data"] = $project;
    /* Get list from DB */
    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module());
    $result["facilities"] = $manager->selectMany($project);

    if ($isNotAjaxCall) {
      return $result["facilities"];
    } else {
      $result = $this->SendResponseWS(
              $result,
              array(
                  "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Facility, 
                  "resx_key" => $this->action(), "step" => $result["facilities"] !== NULL? "success" : "error"
                   ));
    }
  }

  /**
   * Method that adds a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeGetItem(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    $projects = array();
    $project_selected = NULL;
    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserProjects)) {
      $projects = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserProjects);
    }

    foreach ($projects as $project) {
      if ($project->project_id() === $this->dataPost["project_id"]) {
        $project_selected = $project;
      }
    }
    $result["project"] = $project_selected;
    $result = $this->SendResponseWS(
            $result,
            array(
                "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Facility, 
                "resx_key" => $this->action(), "step" => $project_selected !== NULL ? "success" : "error"));
  }

  /**
   * Prepare the Facility Object before calling the DB.
   * 
   * @param array $data_sent from POST request
   * @return \Applications\PMTool\Models\Dao\Facility
   */
  private function PrepareUserObject($data_sent) {
    $facility = new \Applications\PMTool\Models\Dao\Facility();
    foreach ($data_sent as $key => $value) {
      $method = "set" .ucfirst($key);
      $facility->$method(!array_key_exists($key, $data_sent) ? NULL : $value);
    }
    return $facility;
  }

}