<?php

namespace Library;

class ImageUtility extends \Library\ApplicationComponent {

  protected $settings = array();

  public function getImageUrl($image_name) {
    if (isset($image_name) || $image_name === "") {
      $imageFolderPath = $this->app->config()->get("RootImageFolderPath");

      return $imageFolderPath . $image_name;
    }
    throw InvalidArgumentException("Missing Image name!");
  }
  public function buildImageTag($params) {
    
  }

}