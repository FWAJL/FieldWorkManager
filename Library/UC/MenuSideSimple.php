<?php

/**
 * MenuSideSimple class
 * 
 * @author Jeremie Litzler
 * @copyright (c) 2014, Jeremie Litzler
 * @link http://jeremielitzler.net/Blog
 * 
 */
namespace Library\UC;

class MenuSideSimple {

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
  /**
   * Hold the methods to access Session data cleanly
   * 
   * @var SessionManager
   */
  public $sessionManager = null;
  /**
   * Current session with the DATA for config and co will be accessed via the SessionManager.
   * 
   * @param type $session
   */
  function __construct($sessionManager) {
    $this->sessionManager =$sessionManager;
  }

  public function Init() {
    $xmlFiles = $this->sessionManager->get("xml");
    $this->fileParams["filePath"] = $xmlFiles->xml_menus;
    $this->fileParams["rootNode"] = "menu";
    $this->fileParams["menuId"] = "side";

    $this->xml = new XmlLoader($this->fileParams);
    $this->Render();
  }

  public function Render() {
    foreach ($this->xml->Load()->menu as $menu) {
      if ((string) $menu['id'] === "side") {
        $this->PrepareData($menu);
        echo $this->output;
      }
    }
  }

  private function PrepareData($menu) {
    foreach ($menu->items->item as $item) {
      if (SimpleXMLWithAddOns::GetElementValueBool($item, "enabled")) {
        switch (SimpleXMLWithAddOns::GetAttributeValueString($item, "type")) {
          case "simple":
            $this->BuildLiWithLink($item);
            break;
          case "ddl":
            $this->BuildDropDown($item);
            break;

          default:
            break;
        }
      }
    }
  }

  private function BuildLiWithLink($link) {
    $href = $link->href;
    $cssClass = $link->cssClass;
    $label = $link->label;
    $this->output .= '<li><a href="' . $href . '" ';
    $this->output .= ($cssClass === NULL || $cssClass === '') ? '>' : 'class="' . $cssClass . '"' . '>';
    $this->output .= $label . '</a></li>';
  }

  private function BuildDropDown($item) {
    //*** Menu with dropdown ***//
    $this->output .= '<li class="dropdown">';
    $this->output .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-collapse"></i> ';
    $this->output .= SimpleXMLWithAddOns::GetAttributeValueString($item, "label");
    $this->output .= '<b class="caret"></b></a>';
    $this->output .= '<ul class="dropdown-menu">';
    foreach ($item->links->link as $link) {
      $this->BuildLiWithLink($link);
    }
    $this->output .= '</ul>';
    $this->output .= '</li>';
  }

}