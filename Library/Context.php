<?php

namespace Library;

class Context extends ApplicationComponent {

  public $defaultLang = null;

  public function __construct(Application $app) {
    parent::__construct($app);
  }
  public function setLanguage() {
    $this->defaultLang = $this->app->config()->get(Enums\AppSettingKeys::DefaultLanguage);
  }
}