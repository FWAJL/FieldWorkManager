<?php

/**
 *
 * @package		Basic MVC framework
 * @author		FWM DEV Team
 * @copyright	Copyright (c) 2015
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
 * @category	ClientController
 * @author		FWM DEV Team
 * @link		
 */

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ClientController extends \Library\BaseController {

  /**
   * Method that adds a client and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeAdd(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();

    $client = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Client());
    $result["data"] = $client;
    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module());
    $result["dataId"] = $manager->add($client);

    $client->setClient_id($result["dataId"]);
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetUserSessionProject($this->app()->user(), $client->project_id());
    $sessionProject[\Library\Enums\SessionKeys::ClientObject] = $client;
    \Applications\PMTool\Helpers\ProjectHelper::UpdateUserSessionProject($this->app()->user(), $sessionProject);
    
    if(intval($result["dataId"])>0) {
      $this->dataPost['user_email'] = $this->dataPost['client_contact_email'];
      $userId = \Applications\PMTool\Helpers\UserHelper::AddUser($this, $result["dataId"], \Library\Enums\UserRole::Visitor, "client_id");
    }

    //Process DB result and send result
    if ($result["dataId"] > 0) {
      $result = $this->SendResponseWS(
              $result,
              array(
                  "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Client, 
                  "resx_key" => $this->action(), "step" => $result["dataId"] > 0 && $userId > 0 ? "success" : "error"));
    }
  }

  /**
   * Method that edits a a client and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeEdit(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module());
    $result_edit = $manager->edit(
        \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject(
            $this->dataPost(), new \Applications\PMTool\Models\Dao\Client()),
        "client_id"
        );
    $result["dataId"] = $this->dataPost["client_id"];
    if ($result_edit) {
      \Applications\PMTool\Helpers\UserHelper::EditUser($this,'client_id');
      $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetUserSessionProject($this->app()->user(), $this->dataPost["project_id"]);
      $sessionProject[\Library\Enums\SessionKeys::ClientObject] = 
              \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Client());
      \Applications\PMTool\Helpers\ProjectHelper::UpdateUserSessionProject($this->app()->user(), $sessionProject);
    }
    $this->SendResponseWS(
            $result,
            array(
                "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Client, 
                "resx_key" => $this->action(), "step" => $result_edit ? "success" : "error"));
  }
  /**
   * Method that delete a client and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeDelete(\Library\HttpRequest $rq) {
    //Delete is done in ProjectController->executeDelete (also see ProjectDal->delete)
  }
  /**
   * Method that retrieves a list of clients for a project
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeGetList(\Library\HttpRequest $rq, $isNotAjaxCall = FALSE) { /* No current use from the front end side */ }

  /**
   * Method that adds a project and returns the result of operation
   * 
   * @param \Library\HttpRequest $rq
   * @return JSON
   */
  public function executeGetItem(\Library\HttpRequest $rq) { /* No current use from the front end side */ }
}
