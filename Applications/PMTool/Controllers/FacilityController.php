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
   * Method that adds a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeAdd(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->ManageResponseWS();
    //Process data received from Post
    $data_sent = $rq->retrievePostAjaxData(NULL, FALSE);

    //Init PDO
    $facility = $this->PrepareUserObject($data_sent);
    $result["data"] = $facility;
    /* Add to DB */
    //Load interface to query the database
    $manager = $this->managers->getManagerOf('Facility');
    $result_insert = $manager->add($facility);

    //Clear the project and facility list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjects);
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjectFacilityList);

    //Process DB result and send result
    if ($result_insert)
      $result = $this->ManageResponseWS(array("resx_file" => "facility", "resx_key" => "_insert", "step" => "success"));
    //return the JSON data
    echo \Library\HttpResponse::encodeJson($result);
  }

  /**
   * Method that edits a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->ManageResponseWS();
    //Process data received from Post
    $data_sent = $rq->retrievePostAjaxData(NULL, FALSE);

    //Init PDO
    $facility = $this->PrepareUserObject($data_sent);
    /* Add to DB */
    //Load interface to query the database
    $manager = $this->managers->getManagerOf('Facility');
    $result_insert = $manager->edit($facility);

    //Clear the facility list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjectFacilityList);

    //Process DB result and send result
    if ($result_insert)
      $result = $this->ManageResponseWS(array("resx_file" => "facility", "resx_key" => "_edit", "step" => "success"));
    //return the JSON data
    echo \Library\HttpResponse::encodeJson($result);
  }
  /**
   * Method that delete a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->ManageResponseWS();

    $data_sent = $rq->retrievePostAjaxData(NULL, FALSE);

     //Load interface to query the database
    $manager = $this->managers->getManagerOf('Facility');
    $result_insert = $manager->delete($data_sent["facility_id"]);

        //Clear the facility list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserProjectFacilityList);

    $result = $this->ManageResponseWS(array("resx_file" => "facility", "resx_key" => "_delete", "step" => "success"));
    //return the JSON data
    echo \Library\HttpResponse::encodeJson($result);
  }
  /**
   * Method that adds a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeGetList(\Library\HttpRequest $rq, $isNotAjaxCall = FALSE) {
    // Init result
    $result = $this->ManageResponseWS();

    //Init PDO
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $data_sent["pm_id"] = $pm === NULL ? NULL : $pm[0]->pm_id();
    $project = $this->PrepareUserObject($data_sent);
    $result["data"] = $project;
    /* Get list from DB */
    //Load interface to query the database
    $manager = $this->managers->getManagerOf('Facility');
    $project_list = $manager->selectMany($project);

    //Process DB result and send result
    $result = $this->ManageResponseWS(array("resx_file" => "facility", "resx_key" => "_getlist", "step" => "success"));
    $result["projects"] = $project_list;
    //return the JSON data
    if ($isNotAjaxCall) {
      return $project_list;
    } else {
      echo \Library\HttpResponse::encodeJson($result);
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
    $result = $this->ManageResponseWS();

    $data_sent = $rq->retrievePostAjaxData(NULL, FALSE);

    $projects = array();
    $project_selected = NULL;
    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserProjects)) {
      $projects = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserProjects);
    }

    foreach ($projects as $project) {
      if ($project->project_id() === $data_sent["project_id"]) {
        $project_selected = $project;
      }
    }

    $result = $this->ManageResponseWS(array("resx_file" => "project", "resx_key" => "_getItem", "step" => "success"));
    $result["project"] = $project_selected;
    //return the JSON data
    echo \Library\HttpResponse::encodeJson($result);
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