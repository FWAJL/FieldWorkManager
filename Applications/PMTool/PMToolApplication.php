<?php

namespace Applications\PMTool;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class PMToolApplication extends \Library\Application {

  public function __construct() {
    parent::__construct();

    $this->name = 'PMTool';
    $this->context()->setLanguage();
    $this->logoImageUrl = $this->imageUtil->getImageUrl("logo.png");
    
  }

  public function run() {
    $this->i8n->loadResources();
    $controller = $this->getController();
    $controller->execute();
    
    //Get add the Project Manager object to the page
    $pm = $controller->app()->user->getAttribute(\Library\Enums\SessionKeys::UserConnected);
    $controller->page()->addVar('pm', $pm[0]);

    $this->httpResponse->setPage($controller->page());
    $this->httpResponse->send();
  }

}