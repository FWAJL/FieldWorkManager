<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ServiceController extends \Library\BaseController {
    
public function executeIndex(\Library\HttpRequest $rq) {  }
    
  public function executeShowForm(\Library\HttpRequest $rq) {
	$confirm_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"service", "targetaction": "view", "operation": ["delete", "addNullCheck", "addUniqueCheck"]}', $this->app->name());
	$this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $confirm_msg);
	
	//Get confirm msg for Project deletion from showForm screen
    $confirm_msg = \Applications\PMTool\Helpers\PopUpHelper::getConfirmBoxMsg('{"targetcontroller":"service", "targetaction": "view", "operation": ["delete"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::confirm_message, $confirm_msg);
	
    //Load Modules for view
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  } 
  
  public function executeListAll(\Library\HttpRequest $rq) {
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
	
	//Fetch tooltip data from xml and pass to view as an array
    $tooltip_array = \Applications\PMTool\Helpers\PopUpHelper::getTooltipMsgForAttribute('{"targetcontroller":"service", "targetaction": "list", "targetattr": ["active-service-header","inactive-service-header"]}', $this->app->name());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariables\Popup::tooltip_message, $tooltip_array);

    $sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $pm_services = \Applications\PMTool\Helpers\ServiceHelper::GetPmServices($this, $sessionPm, NULL, TRUE);
    $project_services = \Applications\PMTool\Helpers\ServiceHelper::GetAndStoreProjectServices($this, $sessionProject);
    // filter the pm services after we retrieve the project services
    $pm_services = \Applications\PMTool\Helpers\ServiceHelper::FilterServicesToExcludeProjectServices($pm_services, $project_services);

    $project_services = \Applications\PMTool\Helpers\ServiceHelper::CategorizeTheList($project_services, "service_type");
    $pm_services = \Applications\PMTool\Helpers\ServiceHelper::CategorizeTheList($pm_services, "service_type");

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentPm, $sessionPm[\Library\Enums\SessionKeys::PmObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => "service",
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_right => $pm_services,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_left => $project_services,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower("service")),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower("service"))  
    );
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);

    //tab status
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::tabStatus, \Applications\PMTool\Helpers\TaskHelper::GetTabsStatus($this->user()));
    //form modules
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
 
  }
  
//  ListAll replaced from ProjectService
//  public function executeListAll(\Library\HttpRequest $rq) {
//	
//	  
//    $sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
//    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
//    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
//	  
//    $list = \Applications\PMTool\Helpers\ServiceHelper::GetServiceList($this, $sessionPm);
//    
//    $data = array(
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => $list[\Library\Enums\SessionKeys::PmServices],
//        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties => \Applications\PMTool\Helpers\CommonHelper::SetPropertyNamesForDualList(strtolower($this->module()))
//    );
//    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data, $data);
//	
//    $modules = $this->app()->router()->selectedRoute()->phpModules();
//    $this->page->addVar(
//            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::active_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::active_list]);
//    $this->page->addVar(
//            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::inactive_list, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::inactive_list]);
//    $this->page->addVar(
//            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::promote_buttons, $modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::promote_buttons]);
//  }
  
   public function executeAdd(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $this->dataPost["pm_id"] = $pm[\Library\Enums\SessionKeys::PmObject]->pm_id();
    $service = $this->_PrepareServiceObject($this->dataPost());
    $result["dataIn"] = $service;

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module);
    $result["dataOut"] = $manager->add($service);
    
    if ($result["dataOut"] > 0) {
     $service->setService_id($result["dataOut"]);
     array_push($pm[\Library\Enums\SessionKeys::PmServices], $service);
     \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->user(), $pm);
    }    

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
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $this->dataPost["pm_id"] = $pm[\Library\Enums\SessionKeys::PmObject]->pm_id();
    $service = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Service());
    $result["data"] = $service;

    $manager = $this->managers->getManagerOf($this->module);
    $result_edit = $manager->edit($service, "service_id");
    
    if($result_edit){
      $match = \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById($service->service_id(), "service_id", $pm, \Library\Enums\SessionKeys::PmServices);
      $pm[\Library\Enums\SessionKeys::PmServices][$match["key"]] = $service;
      \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->user(), $pm);
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Service,
        "resx_key" => $this->action(),
        "step" => $result_edit ? "success" : "error"
    ));
  }
  
    public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $pm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $db_result = FALSE;
    $service_id = intval($this->dataPost["itemId"]);

    //Check if the service to be deleted is the Project manager's
    $service_selected = \Applications\PMTool\Helpers\ServiceHelper::GetAService($this->user(), $service_id);
    //Load interface to query the database
    if ($service_selected !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($service_selected, "service_id");
      if ($db_result) {
        $match = \Applications\PMTool\Helpers\CommonHelper::FindIndexInObjectListById($service_selected->service_id(), "service_id", $pm, \Library\Enums\SessionKeys::PmServices);
        unset($pm[\Library\Enums\SessionKeys::PmServices][$match["key"]]);
        \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->user(), $pm);
      }
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
    $list[\Library\Enums\SessionKeys::PmServices] = $manager->selectMany($service, "pm_id");

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

    $service_selected = \Applications\PMTool\Helpers\ServiceHelper::GetAService($this->user(), $service_id);

    $result["service"] = $service_selected;
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Service,
        "resx_key" => $this->action(),
        "step" => ($service_selected !== NULL) ? "success" : "error"
    ));
  }
  
    public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = \Applications\PMTool\Helpers\ServiceHelper::UpdateProjectServices($this);
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Project,
        "resx_key" => $this->action(),
        "step" => ($result["rows_affected"] === count($result["service_ids"] )) ? "success" : "error"
    ));
  }
 
//  Updateitems replaced by method from ProjectService  
//  public function executeUpdateItems(\Library\HttpRequest $rq) {
//    $result = $this->InitResponseWS(); // Init result
//
//    $rows_affected = 0;
//    //Get the service objects from ids received
//    $service_ids = str_getcsv($this->dataPost["service_ids"], ',');
//    $services = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::PmServices);
//    $matchedElements = $this->FindObjectsFromIds(
//            array(
//                "filter" => "service_id",
//                "ids" => $service_ids,
//                "objects" => $services)
//    );
//
//    //Update the service objects in DB and get result (number of rows affected)
//    //$this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::PmServices);
//    foreach ($matchedElements as $service) {
//      $service->setService_active($this->dataPost["action"] === "active" ? TRUE : FALSE);
//      $manager = $this->managers->getManagerOf($this->module);
//      $rows_affected += $manager->edit($service, "service_id") ? 1 : 0;
//    }
//
//    $this->SendResponseWS(
//            $result, array(
//        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Service,
//        "resx_key" => $this->action(),
//        "step" => ($rows_affected === count($service_ids)) ? "success" : "error"
//    ));
//  }
  
  public function executeIfProviderExists(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result
	$pmSession = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
	
	//Check if already list exists in Session
	$service = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($this->dataPost(), new \Applications\PMTool\Models\Dao\Service());
	if (!array_key_exists(\Library\Enums\SessionKeys::PmServices, $pmSession)) {
	  //No, we have to query db and then populate the list into Session
	  $manager = $this->managers->getManagerOf($this->module());
	  $allServiceProviders = $manager->selectMany($service, "", true);
	  
	  $pmSession[\Library\Enums\SessionKeys::PmServices] = $allServiceProviders;
	  \Applications\PMTool\Helpers\PmHelper::SetSessionPm($this->user(), $pmSession);
    }
	
	//Now check if Service provider already exists
	$match = \Applications\PMTool\Helpers\CommonHelper::FindObjectByStringValue(
				$service->service_name(), "service_name",
				$pmSession[\Library\Enums\SessionKeys::PmServices]
    		);
	
	$result['record_count'] = (!$match || empty($match)) ? 0 : 1;
	
	$this->SendResponseWS(
      $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Service,
        "resx_key" => $this->action(),
        "step" => ($result['record_count'] > 0) ? "success" : "error"
      ));
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
