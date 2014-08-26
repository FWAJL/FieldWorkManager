<?php

namespace Applications\PMTool\Controllers;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class LocationController extends \Library\BaseController {

  public function executeIndex(\Library\HttpRequest $request) {
    $pm = $this->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $this->page->addVar('pm', $pm[0]);

    $resourceFileKey = "location";

    $this->app->pageTitle = $this->app->i8n->getLocalResource($resourceFileKey, "page_title");
    $this->page->addVar('resx', $this->app->i8n->getLocalResourceArray($resourceFileKey));
    $this->page->addVar('logout_url', __BASEURL__ . "logout");
  }

}