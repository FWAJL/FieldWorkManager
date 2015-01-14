<?php

/**
 *
 * @package		Basic MVC framework
 * @author		FWM DEV Team
 * @copyright	Copyright (c) 2015
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * LeftMenu Class
 *
 * @package		Library
 * @subpackage	UC
 * @category	LeftMenu
 * @author		FWM DEV Team
 * @link		
 */

namespace Library\UC;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class LeftMenu {

  protected $app = null;
  protected $base_url = "";
  protected $resx_left_menu = array();

  public function __construct($app, $resx_left_menu) {
    $this->app = $app;
    $this->resx_left_menu = $resx_left_menu;
    $this->base_url = $this->app->config->get("base_url");
  }

  /**
   * Returns a string representing the left menu
   * 
   * @return type string
   */
  public function Build() {
    $left_menu_output = "<ul class=\"content_left nav-sidebar\">";
    $main_menus = $this->_LoadXml();
    foreach ($main_menus as $main_menu) {
      if ($main_menu->getAttribute('active') === "true") {
        $left_menu_output .= $this->_AddMainMenu($main_menu);
      }
    }
    $left_menu_output .= "</ul>";
    return $left_menu_output;
  }

  /**
   * Load the left menu from xml and returns the data to process
   * The list of DOMElementNode is the list of main menus to display
   * 
   * @return type DOMELementNodeList
   * @throws Exception when file is not found
   */
  private function _LoadXml() {
    $xml = new \DOMDocument;
    $filename = __ROOT__ . \Library\Enums\FolderName::AppsFolderName . $this->app->name() . '/Config/menus.xml';
    if (file_exists($filename)) {
      $xml->load($filename);
    } else {
      throw new Exception("In " . __CLASS__ . " > Method: " . __METHOD__);
    }
    return $xml->getElementsByTagName("main_menu");
  }

  private function _AddMainMenu($main_menu) {
    $li = "<li>";
    $header = $main_menu->getElementsByTagName("header");
    if ($header !== NULL) {
      foreach ($header as $link) {
        $li .= $this->_AddLinkHeader($link);
      }
    }
    $li .= $this->_AddSubMenus($main_menu);
    $li .= "</li>";
    return $li;
  }

  private function _AddSubMenus($main_menu) {
    $li = "<ul>";
    $sub_menus = $main_menu->getElementsByTagName("submenu");
    if ($sub_menus !== NULL) {
      foreach ($sub_menus as $sub_menu) {
        if ($sub_menu->getAttribute('active') === "true") {
          $li .= $this->_AddLinkSubMenus($sub_menu);
        }
      }
      $li .= "</ul>";
    }
    return $li;
  }

  private function _AddLinkHeader($link) {
    $html = "";
    if ($link->getAttribute("enablelink") === "true") {
      $html = "<a href=\"" . $this->base_url . $link->getAttribute("href") . "\">"
              . $this->resx_left_menu[$link->getAttribute("resourcekey")] .
              "</a>";
    } else {
      $html = $this->resx_left_menu[$link->getAttribute("resourcekey")];
    }
    return $html;
  }

  private function _AddLinkSubMenus($link) {
    return
            "<li><a href=\"" . $this->base_url . $link->getAttribute("href")
            . "\" id=\"" . $link->getAttribute("id") . "\">"
            . $this->resx_left_menu[$link->getAttribute("resourcekey")]
            . "</a></li>";
  }

}