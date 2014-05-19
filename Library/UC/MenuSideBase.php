<?php

/**
 * MenuSideBase class
 * 
 * @author Jeremie Litzler
 * @copyright (c) 2014, Jeremie Litzler
 * @link http://jeremielitzler.net/Blog
 * 
 */
namespace Library\UC;

abstract class MenuSideBase {

  /**
   * Contains a SimpleXML Object that will be reaad to build the menu
   * 
   * @var SimpleXML object 
   */
  private $xml = null;

  /**
   * HTML output rendered onto the page
   * 
   * @var string 
   */
  public $output = "";

  /**
   * List all the params needed to retrieve the right xml data
   * 
   * @var array 
   */
  public $fileParams = array();

  function __construct() {
    $this->fileParams["filePath"] = __CONFIGDIR__ . "xml/menus.xml";
    $this->fileParams["rootNode"] = "menu";
    $this->fileParams["menuId"] = "side";
    $this->xml = new XmlLoader($this->fileParams);
  }

  public function Render() {
    foreach ($this->xml->Load()->menu as $menu) {
      if ((string) $menu['id'] === "side") {
        $this->PrepareData($menu);
        echo $this->output;
      }
    }
  }

  /**
   * 
   */
  abstract public function PrepareData($menu);

  /**
   * 
   */
  abstract public function Build($item);
}