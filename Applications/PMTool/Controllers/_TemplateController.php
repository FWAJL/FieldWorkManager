<?php

namespace Applications\PMTool\Controllers;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class ThisController extends \Library\BaseController {

  public function executeIndex(\Library\HttpRequest $request) {
    //TODO: add js resource using a Resource manager
    //$file_path_to_include = "filename.js";
    $resourceFileKey = "_tmp";

    $this->app->pageTitle = $this->app->i8n->getLocalResource($resourceFileKey, "page_title");
    $this->page->addVar('resx', $this->app->i8n->getLocalResourceArray($resourceFileKey));
    $this->page->addVar('tmp_url', $this->app->router->pageUrls[\Library\Enums\ResourceKeys\PublicPageUrls::TemplateUrl]);
  }

}