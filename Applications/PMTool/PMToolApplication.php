<?php

namespace Applications\PMTool;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class PMToolApplication extends \Library\Application {

  public function __construct() {
    parent::__construct();

    $this->name = 'PMTool';
    $this->context()->setLanguage();
    $this->logoImageUrl = $this->imageUtil->getImageUrl("logo.jpg");
    
  }

  public function run() {
    $this->i8n->loadResources();
    $controller = $this->getController();
    $controller->execute();

    $this->httpResponse->setPage($controller->page());
    $this->httpResponse->send();
  }

}