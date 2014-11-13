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
    $result["dataId"] = $manager->add($facility);
    $facility->setFacility_id($result["dataId"]);
    $sessionProject = \Applications\PMTool\Helpers\CommonHelper::GetUserSessionProject($this->app()->user(), $facility->project_id());
    $sessionProject[\Library\Enums\SessionKeys::FacilityObject] = $facility;
    \Applications\PMTool\Helpers\CommonHelper::UpdateUserSessionProject($this->app()->user(), $sessionProject);
    
    //Process DB result and send result
    if ($result["dataId"] > 0)
    {
      $result = $this->SendResponseWS(
              $result,
              array(
                  "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Facility, 
                  "resx_key" => $this->action(), "step" => $result["dataId"] > 0 ? "success" : "error"));
    }
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
    $result["dataId"] = $this->dataPost("facility_id");

    if ($result_edit) {
      $sessionProject = \Applications\PMTool\Helpers\CommonHelper::GetUserSessionProject($this->app()->user(), $this->dataPost["project_id"]);
      $sessionProject[\Library\Enums\SessionKeys::FacilityObject] = $this->PrepareUserObject($this->dataPost());
      \Applications\PMTool\Helpers\CommonHelper::UpdateUserSessionProject($this->app()->user(), $sessionProject);
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
    //Delete is done in ProjectController->executeDelete (also see ProjectDal->delete)
  }
  /**
   * Method that retrieves a list of facilities for a project
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeGetList(\Library\HttpRequest $rq, $isNotAjaxCall = FALSE) {
    //The logic is found in ProjectController->executeGetList
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