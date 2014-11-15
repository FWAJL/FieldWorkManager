<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ResourceController extends \Library\BaseController {
    
    public function executeIndex(\Library\HttpRequest $rq) {
    //Get list of resources and store in session
    $lists = $this->_GetAndStoreResourcesInSession($rq);

    if (count($lists[\Library\Enums\SessionKeys::UserResources]) > 0) {
      header('Location: ' . __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ResourceListAll);
    } else {
      header('Location: ' . __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ResourceShowForm . "?mode=add&test=true");
    }
  }
    
public function executeShowForm(\Library\HttpRequest $rq) {
    //Load Modules for view
    $this->page->addVar(
            \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $this->app()->router()->selectedRoute()->phpModules());
  } 
  
  public function executeListAll(\Library\HttpRequest $rq) {
    //Get list of resources stored in session
    $this->_GetAndStoreResourcesInSession($rq);
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects => $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserResources),
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
    $resource = $this->_PrepareResourceObject($this->dataPost());
    $result["dataIn"] = $resource;

    //Load interface to query the database
    $manager = $this->managers->getManagerOf($this->module);
    $result["dataId"] = $manager->add($resource);

    //Clear the resource list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserResources);
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserResourceList);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Resource,
        "resx_key" => $this->action(),
        "step" => (intval($result["dataId"])) > 0 ? "success" : "error"
    ));
  }
  
  public function executeEdit(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();

    //Init PDO
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->dataPost["pm_id"] = $pm === NULL ? NULL : $pm[0]->pm_id();
    $resource = $this->_PrepareResourceObject($this->dataPost());
    $result["data"] = $resource;

    $manager = $this->managers->getManagerOf($this->module);
    $result_insert = $manager->edit($resource);
    
    //Clear the resource list from session for the connect PM
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserResources);
    $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserResourceList);

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Resource,
        "resx_key" => $this->action(),
        "step" => $result_insert ? "success" : "error"
    ));
  }
  
    public function executeDelete(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $db_result = FALSE;
    $resource_id = intval($this->dataPost["resource_id"]);

    //Check if the resource to be deleted is the Project manager's
    $resource_selected = $this->_GetResourceFromSession($resource_id);
    //Load interface to query the database
    if ($resource_selected !== NULL) {
      $manager = $this->managers->getManagerOf($this->module());
      $db_result = $manager->delete($resource_id);
      //Clear the resource from session for the connect PM
      $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserResources);
      $this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserResourceList);
//      \Applications\PMTool\Helpers\CommonHelper::UnsetUserSessionResource($this->app()->user(), $resource_id);
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Resource,
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
    $resource = $this->_PrepareResourceObject($this->dataPost());
    $result["data"] = $resource;

    //Load interface to query the database for resources
    $manager = $this->managers->getManagerOf($this->module);
    $list[\Library\Enums\SessionKeys::UserResources] = $manager->selectMany($resource);

    $result["lists"] = $list;
    if ($isNotAjaxCall) {
      return $list;
    } else {
      $step_result =
             $step_result = $result[\Library\Enums\SessionKeys::UserResources] !== NULL ? "success" : "error";
      $this->SendResponseWS(
              $result, array(
          "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Resource,
          "resx_key" => $this->action(),
          "step" => $step_result
      ));
    }
  }
   
public function executeGetItem(\Library\HttpRequest $rq) {
    // Init result
    $result = $this->InitResponseWS();
    $resource_id = intval($this->dataPost["resource_id"]);

    $resource_selected = $this->_GetResourceFromSession($resource_id);

    $result["resource"] = $resource_selected;
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Resource,
        "resx_key" => $this->action(),
        "step" => ($resource_selected !== NULL) ? "success" : "error"
    ));
  }
  
    public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS(); // Init result

    $rows_affected = 0;
    //Get the resource objects from ids received
    $resource_ids = str_getcsv($this->dataPost["resource_ids"], ',');
    $resources = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserResources);
    $matchedElements = $this->FindObjectsFromIds(
            array(
                "filter" => "resource_id",
                "ids" => $resource_ids,
                "objects" => $resources)
    );

    //Update the resource objects in DB and get result (number of rows affected)
    //$this->app()->user->unsetAttribute(\Library\Enums\SessionKeys::UserResources);
    foreach ($matchedElements as $resource) {
      $resource->setResource_active($this->dataPost["action"] === "active" ? TRUE : FALSE);
      $manager = $this->managers->getManagerOf($this->module);
      $rows_affected += $manager->edit($resource) ? 1 : 0;
    }

    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Resource,
        "resx_key" => $this->action(),
        "step" => ($rows_affected === count($resource_ids)) ? "success" : "error"
    ));
  }
  
  /**
   * Find a resource from an id
   * 
   * @param int $resource_id : the id of the resource to find
   * @return \Applications\PMTool\Models\Dao\Resource $resourceMatch : the match
   */
  private function _GetResourceFromSession($resource_id) {
    $resources = array();
    $resourceMatch = NULL;
    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserResources)) {
      $resources = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserResources);
    }
    foreach ($resources as $resource) {
      if (intval($resource->resource_id()) === $resource_id) {
        $resourceMatch = $resource;
        break;
      }
    }
    return $resourceMatch;
  }
  
    /**
   * Check if the current pm has resources to decide where to send him: stay on the resource list or asking him to add a resource
   * 
   * @param \Applications\PMTool\Models\Dao\Resources $pm
   * @return boolean
   */
  private function _CheckIfPmHasResources(\Applications\PMTool\Models\Dao\Resources $pm) {

    if ($this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserResources)) {
      $resources = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserResources);
      return count($resources) > 0 ? TRUE : FALSE;
    }
    $manager = $this->managers->getManagerOf($this->module);
    $count = $manager->countById($pm->pm_id());
    return $count > 0 ? TRUE : FALSE;
  }
  
  private function _PrepareResourceObject($data_sent) {
    $resource = new \Applications\PMTool\Models\Dao\Resources();
    $resource->setPm_id($data_sent["pm_id"]);
    $resource->setResource_id(!array_key_exists('resource_id', $data_sent) ? NULL : $data_sent["resource_id"]);
    $resource->setResource_type(!array_key_exists('resource_type', $data_sent) ? NULL : $data_sent["resource_type"]);
    $resource->setResource_name(!array_key_exists('resource_name', $data_sent) ? NULL : $data_sent["resource_name"]);
    $resource->setResource_url(!array_key_exists('resource_url', $data_sent) ? NULL : $data_sent["resource_url"]);
    $resource->setResource_address(!array_key_exists('resource_address', $data_sent) ? "" : $data_sent["resource_address"]);
    $resource->setResource_contact_name(!array_key_exists('resource_contact_name', $data_sent) ? "" : $data_sent["resource_contact_name"]);
    $resource->setResource_contact_phone(!array_key_exists('resource_contact_phone', $data_sent) ? "" : $data_sent["resource_contact_phone"]);
    $resource->setResource_contact_email(!array_key_exists('resource_contact_email', $data_sent) ? "" : $data_sent["resource_contact_email"]);
    $resource->setResource_active(!array_key_exists('resource_active', $data_sent) ? 0 : ($data_sent["resource_active"] === "1"));

    return $resource;
  }
  
  /**
   * Checks if the user resources  are not stored in Session.
   * Stores the resources and facilities after call to WS to retrieve them
   * Set the data into the session for later use.
   * 
   * @param /Library/HttpRequest $rq
   * @return array $lists : the lists of objects if any 
   */
  private function _GetAndStoreResourcesInSession($rq) {
    $lists = array();
    if (!$this->app()->user->keyExistInSession(\Library\Enums\SessionKeys::UserResources)) {

      $lists = $this->executeGetList($rq, TRUE);

      $this->app()->user->setAttribute(
              \Library\Enums\SessionKeys::UserResources, $lists[\Library\Enums\SessionKeys::UserResources]
      );
    } else {
      $lists[\Library\Enums\SessionKeys::UserResources] = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserResources);
    }
    return $lists;
  }

}