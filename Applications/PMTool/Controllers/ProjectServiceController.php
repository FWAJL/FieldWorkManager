<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class ProjectServiceController extends \Library\BaseController {

  public function executeUpdateItems(\Library\HttpRequest $rq) {
    $result = \Applications\PMTool\Helpers\ServiceHelper::UpdateProjectServices($this);
    $this->SendResponseWS(
            $result, array(
        "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Project,
        "resx_key" => $this->action(),
        "step" => ($result["rows_affected"] === count($result["service_ids"] )) ? "success" : "error"
    ));
  }

  public function executeManageServices(\Library\HttpRequest $rq) {
       // Set $current_project for breadcrumb
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

    // \Applications\PMTool\Helpers\ProjectHelper::SetActiveTab($this->user(), \Applications\PMTool\Resources\Enums\ProjectTabKeys::ServicesTab);
    $sessionPm = \Applications\PMTool\Helpers\PmHelper::GetCurrentSessionPm($this->user());
    $pm_services = \Applications\PMTool\Helpers\ServiceHelper::GetPmServices($this, $sessionPm);
    $project_services = \Applications\PMTool\Helpers\ServiceHelper::GetAndStoreProjectServices($this, $sessionProject);
    // filter the pm services after we retrieve the project services
    $pm_services = \Applications\PMTool\Helpers\ServiceHelper::FilterServicesToExcludeProjectServices($pm_services, $project_services);

    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentPm, $sessionPm[\Library\Enums\SessionKeys::PmObject]);
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);
    // $this->page->addVar("HasItemsToDisplay", \Applications\PMTool\Helpers\PmHelper::DoesPmHaveActiveServices($this->user()));
    $data = array(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::module => strtolower($this->module()),
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_right => $pm_services,
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left => $project_services,
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

}
