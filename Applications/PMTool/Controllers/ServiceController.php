<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ServiceController extends \Library\BaseController {
    
    public function executeIndex(\Library\HttpRequest $rq) {
    //Get list of services and store in session
    $lists = $this->_GetAndStoreServicesInSession($rq);

    if (count($lists[\Library\Enums\SessionKeys::PmServices]) > 0) {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ServiceListAll);
    } else {
      $this->Redirect(\Library\Enums\ResourceKeys\UrlKeys::ServiceShowForm . "?mode=add&test=true");
    }
  }
    
public function executeShowForm(\Library\HttpRequest $rq) {
    //Load Modules for view
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  } 
  
  public function executeListAll(\Library\HttpRequest $rq) {
    //Get list of services stored in session
    
    // Set $current_project
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
      
    $this->_GetAndStoreServicesInSession($rq);
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => $this->app()->user->getAttribute(\Library\Enums\SessionKeys::PmServices),
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
    $service = $this->_PrepareServiceObject($this->dataPost());
    $result["dataIn"] = $service;

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module);
    $result["dataOut"] = $manager->add($service);

    //Clear the service list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::PmServices);
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserServiceList);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Service,
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
    $service = $this->_PrepareServiceObject($this->dataPost());
    $result["data"] = $service;

    $manager = $this->managers->getManagerOf($this->module);
    $result_insert = $manager->edit($service);
    
    //Clear the service list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::PmServices);
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserServiceList);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Service,
        "resx_key" => $this->action(),
        "step" => $result_insert ? "success" : "error"
    ));
  }
  
    public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $db_result = FALSE;
    $service_id = intval($this->dataPost["service_id"]);

    //Check if the service to be deleted is the Project manager's
    $service_selected = $this->_GetServiceFromSession($service_id);
    //Load interface to query the database
    if ($service_selected !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($service_id);
      //Clear the service from session for the connect PM
      $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::PmServices);
      $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserServiceList);
//      \Applications\PMTool\Helpers\CommonHelper::UnsetUserSessionService($this->app()->user(), $service_id);
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Service,
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
    $service = $this->_PrepareServiceObject($this->dataPost());
    $result["data"] = $service;

    //Load interface to query the database for services
    $manager = $this->managers->getManagerOf($this->module);
    $list[\Library\Enums\SessionKeys::PmServices] = $manager->selectMany($service);

    $result["lists"] = $list;
    if ($isNotAjaxCall) {
      return $list;
    } else {
      $step_result =
             $step_result = $result[\Library\Enums\SessionKeys::PmServices] !== NULL ? "success" : "error";
      $this->SendResponseWS(
              $result, array(
          "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Service,
          "resx_key" => $this->action(),
          "step" => $step_result
      ));
    }
  }
   
public function executeGetItem(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $service_id = intval($this->dataPost["service_id"]);

    $service_selected = $this->_GetServiceFromSession($service_id);

    $result["service"] = $service_selected;
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Service,
        "resx_key" => $this->action(),
        "step" => ($service_selected !== NULL) ? "success" : "error"
    ));
  }
  
    public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $rows_affected = 0;
    //Get the service objects from ids received
    $service_ids = str_getcsv($this->dataPost["service_ids"], ',');
    $services = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::PmServices);
    $matchedElements = $this->FindObjectsFromIds(
            array(
                "filter" => "service_id",
                "ids" => $service_ids,
                "objects" => $services)
    );

    //Update the service objects in DB and get result (number of rows affected)
    //$this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::PmServices);
    foreach ($matchedElements as $service) {
      $service->setService_active($this->dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $this->managers->getManagerOf($this->module);
      $rows_affected += $manager->edit($service) ? 1 : 0;
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Service,
        "resx_key" => $this->action(),
        "step" => ($rows_affected === count($service_ids)) ? "success" : "error"
    ));
  }
  
  /**
   * Find a service from an id
   * 
   * @param int $service_id : the id of the service to find
   * @return \Applications\PMTool\Models\Dao\Service $serviceMatch : the match
   */
  private function _GetServiceFromSession($service_id) {
    $services = array();
    $serviceMatch = NULL;
    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::PmServices)) {
      $services = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::PmServices);
    }
    foreach ($services as $service) {
      if (intval($service->service_id()) === $service_id) {
        $serviceMatch = $service;
        break;
      }
    }
    return $serviceMatch;
  }
  
    /**
   * Check if the current pm has services to decide where to send him: stay on the service list or asking him to add a service
   * 
   * @param \Applications\PMTool\Models\Dao\Services $pm
   * @return boolean
   */
  private function _CheckIfPmHasServices(\Applications\PMTool\Models\Dao\Service $pm) {

    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::PmServices)) {
      $services = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::PmServices);
      return count($services) > 0 ? TRUE : FALSE;
    }
    $manager = $this->managers->getManagerOf($this->module);
    $count = $manager->countById($pm->pm_id());
    return $count > 0 ? TRUE : FALSE;
  }
  
  private function _PrepareServiceObject($data_sent) {
    $service = new \Applications\PMTool\Models\Dao\Service();
    $service->setPm_id($data_sent["pm_id"]);
    $service->setService_id(!array_key_exists('service_id', $data_sent) ? NULL : $data_sent["service_id"]);
    $service->setService_type(!array_key_exists('service_type', $data_sent) ? NULL : $data_sent["service_type"]);
    $service->setService_name(!array_key_exists('service_name', $data_sent) ? NULL : $data_sent["service_name"]);
    $service->setService_url(!array_key_exists('service_url', $data_sent) ? NULL : $data_sent["service_url"]);
    $service->setService_address(!array_key_exists('service_address', $data_sent) ? "" : $data_sent["service_address"]);
    $service->setService_contact_name(!array_key_exists('service_contact_name', $data_sent) ? "" : $data_sent["service_contact_name"]);
    $service->setService_contact_phone(!array_key_exists('service_contact_phone', $data_sent) ? "" : $data_sent["service_contact_phone"]);
    $service->setService_contact_email(!array_key_exists('service_contact_email', $data_sent) ? "" : $data_sent["service_contact_email"]);
    $service->setService_active(!array_key_exists('service_active', $data_sent) ? 0 : ($data_sent["service_active"] === "1"));

    return $service;
  }
  
  /**
   * Checks if the user services  are not stored in Session.
   * Stores the services and facilities after call to WS to retrieve them
   * Set the data into the session for later use.
   * 
   * @param /Library/HttpRequest $rq
   * @return array $lists : the lists of objects if any 
   */
  private function _GetAndStoreServicesInSession($rq) {
    $lists = array();
    if (!$this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::PmServices)) {

      $lists = $this->executeGetList($rq, TRUE);

      $this->app()->user->setAttribute(
              \Library\Enums\SessionKeys::PmServices, $lists[\Library\Enums\SessionKeys::PmServices]
      );
    } else {
      $lists[\Library\Enums\SessionKeys::PmServices] = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::PmServices);
    }
    return $lists;
  }

}