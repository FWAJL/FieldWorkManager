<?php

namespace Library;

class Config extends ApplicationComponent {

  protected $settings = array();

  public function get($key) {
    if (!$this->settings) {
      $xml = new \DOMDocument;
      $xml->load(__ROOT__.Enums\FolderName::AppsFolderName. $this->app->name() . '/Config/appsettings.xml');

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