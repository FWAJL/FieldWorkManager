<?php

namespace Applications\PMTool;

class PMToolApplication extends \Library\Application {

  public function __construct() {
    parent::__construct();

    $this->name = 'PMTool';
    $this->context()->setLanguage();
    
  }

  public function run() {
    $this->i8n->loadResources();
    $controller = $this->getController();
    $controller->execute();

    $this->httpResponse->setPage($controller->page());
    $this->httpResponse->send();
  }

}