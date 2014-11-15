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

    $facility = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Facility());
    $result["data"] = $facility;
    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module());
    $result["dataId"] = $manager->add($facility);
    $facility->setFacility_id($result["dataId"]);
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetUserSessionProject($this->app()->user(), $facility->project_id());
    $sessionProject[\Library\Enums\SessionKeys::FacilityObject] = $facility;
    \Applications\PMTool\Helpers\ProjectHelper::UpdateUserSessionProject($this->app()->user(), $sessionProject);
    
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
    $result_edit = $manager->edit(\Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Facility()));
    $result["dataId"] = $this->dataPost("facility_id");

    if ($result_edit) {
      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetUserSessionProject($this->app()->user(), $this->dataPost["project_id"]);
      $sessionProject[\Library\Enums\SessionKeys::FacilityObject] = 
              \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Facility());
      \Applications\PMTool\Helpers\ProjectHelper::UpdateUserSessionProject($this->app()->user(), $sessionProject);
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

}