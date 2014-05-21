<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ResourceLoader
 *
 * @author jl
 */
namespace Library;

class ResourceLoader extends ApplicationComponent {
  protected $resources = array();
  public function get($file) {
    if (!$this->settings) {
      $xml = new Utility\XmlLoader($file);
      $xml->Load();

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

?>
