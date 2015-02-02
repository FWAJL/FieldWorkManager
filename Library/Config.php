<?php

namespace Library;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Config extends ApplicationComponent {

  protected $settings = array();

  public function get($key) {
    if (!$this->settings) {
      $xml = new \DOMDocument;
      $xml->load(__ROOT__.Enums\ApplicationFolderName::AppsFolderName. $this->app->name() . '/Config/appsettings.xml');

      $elements = $xml->getElementsByTagName('define');

      foreach ($elements as $element) {
        $this->settings[$element->getAttribute('key')] = $element->getAttribute('value');
      }
    }

    if (isset($this->settings[$key])) {
      return $this->settings[$key];
    }

    return null;
  }

}