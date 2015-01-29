<?php

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class MapController extends \Library\BaseController {

  public function executeLoadView($rq) {
    $modules = $this->app()->router()->selectedRoute()->phpModules();
    $this->page->addVar(
        \Applications\PMTool\Resources\Enums\ViewVariablesKeys::form_modules, $modules);
  }

  public function executeListAll(\Library\HttpRequest $rq) {
    $result = $this->InitResponseWS();
    $dataPost = $this->dataPost();
    $sessionProject = \Applications\PMTool\Helpers\ProjectHelper::GetCurrentSessionProject($this->app()->user());
    $this->page->addVar(\Applications\PMTool\Resources\Enums\ViewVariablesKeys::currentProject, $sessionProject[\Library\Enums\SessionKeys::ProjectObject]);

    $objects = \Applications\PMTool\Helpers\CommonHelper::GetObjectListFromSessionArrayBySessionKey(
            $this->user(), \Applications\PMTool\Helpers\ProjectHelper::GetSessionProjects($this->user()), $dataPost["objectType"]);

    $coordinates = \Applications\PMTool\Helpers\MapHelper::BuildLatAndLongCoordFromGeoObjects(
            $objects, $dataPost["objectLatPropName"], $dataPost["objectLngPropName"]);
    
    $result["defaultPosition"] = \Applications\PMTool\Helpers\MapHelper::GetCoordinatesToCenterOverUsa($this->app()->config());
    $result["items"] = $coordinates;
    $this->SendResponseWS(
        $result, array(
      "resx_file" => \Applications\PMTool\Resources\Enums\ResxFileNameKeys::Map,
      "resx_key" => $this->action(),
      "step" => (count($coordinates) >= 0) ? "success" : "error"
    ));
  }

}
