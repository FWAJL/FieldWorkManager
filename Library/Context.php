<?php

namespace Library;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Context extends ApplicationComponent {

  public $defaultLang = null;

  public function __construct(Application $app) {
    parent::__construct($app);
  }
  public function setLanguage() {
    $this->defaultLang = $this->app->config()->get(Enums\AppSettingKeys::DefaultLanguage);
  }
}