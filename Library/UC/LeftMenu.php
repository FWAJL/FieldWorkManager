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
  protected $OPEN_LI = "<li>";
  protected $CLOSE_LI = "</li>";
  protected $OPEN_UL = "<ul>";
  protected $CLOSE_UL = "</ul>";
  protected $OPEN_SPAN = "<span>";
  protected $CLOSE_SPAN = "</span>";
  protected $NOTALLOWED = "NOTALLOWED";
  protected $NOSUBMENUS = "NOSUBMENUS";
  protected $NOHEADERMENU = "NOHEADERMENU";

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
    $left_menu_output .= $this->ProcessMainMenus($main_menus);
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
    $filename = __ROOT__ . \Library\Enums\ApplicationFolderName::AppsFolderName . $this->app->name() . '/Config/menus.xml';
    if (file_exists($filename)) {
      $xml->load($filename);
    } else {
      throw new \Exception("In " . __CLASS__ . "->" . __METHOD__);
    }
    return $xml->getElementsByTagName("main_menu");
  }

  private function ProcessMainMenus($main_menus) {
    $output = "";
    foreach ($main_menus as $main_menu) {
      if ($main_menu->getAttribute('active') === "true") {
        $output .= $this->_AddMainMenu($main_menu);
      }
    }
    return $output;
  }

  private function _AddMainMenu($main_menu) {
    $menuHeaderOutput = $menuSubsOutput = "";
    $headers = $main_menu->getElementsByTagName("header");
    if ($headers !== NULL) {
      foreach ($headers as $link) {
        $menuHeaderOutput .= $this->_AddLinkHeader($link);
      }
    }

    $menuSubsOutput = $this->_AddSubMenus($main_menu);

    $mainMenuBlockOutput = $this->ProcessMainMenuOutputResult($menuHeaderOutput, $menuSubsOutput);
    return $mainMenuBlockOutput;
  }

  private function ProcessMainMenuOutputResult($menuHeaderOutput, $menuSubsOutput) {
    $finalOutput = "";
    if ($menuHeaderOutput["output"] === $this->NOHEADERMENU && $menuSubsOutput === $this->NOSUBMENUS) {
      //Return ""
    }  else if ($menuHeaderOutput["hasLink"] && $menuSubsOutput === $this->NOSUBMENUS) {
      $finalOutput = $menuHeaderOutput;
    } else {
      
    }
    return $finalOutput;
  }

  private function _AddSubMenus($main_menu) {
    $ulElement = $this->OPEN_UL;
    $subMenusXml = $main_menu->getElementsByTagName("submenu");
    $menuSubsOutput = $this->_ProcessSubMenus($subMenusXml);
    if ($menuSubsOutput === $this->NOSUBMENUS) {
      $ulElement = $menuSubsOutput;
    } else {
      $ulElement .= $menuSubsOutput . $this->CLOSE_UL;
    }

    return $ulElement;
  }

  private function _ProcessSubMenus($subMenusXml) {
    $output = "";
    if ($subMenusXml !== NULL) {
      foreach ($subMenusXml as $subMenuXml) {
        $isAvailable = $this->_CanDisplayMenuItem($subMenuXml);
        if ($isAvailable) {
          $output .= $this->_AddLinkSubMenus($subMenuXml);
        }
      }
      if ($output === "")
        $output = $this->NOSUBMENUS;
    } else {
      $output = $this->NOSUBMENUS;
    }
    return $output;
  }

  private function _CanDisplayMenuItem($menuItem) {
    if ($menuItem->getAttribute('href')) {
      $href = $this->base_url . current(explode('?', $menuItem->getAttribute('href')));
      $routes = $this->app->user->getAttribute(\Library\Enums\SessionKeys::UserRoutes);
      $result =
              $routes && $menuItem->getAttribute('active') === "true" ?
              array_reduce(
                      $routes, function ($carry, $route) use ($href) {
                        return $carry || ($href == $route->url());
                      }, false) :
              false;
      return $result;
    }
    return TRUE;
  }

  private function _AddLinkHeader($link) {
    $placeholders = array(
        "{{href}}" => $this->base_url . $link->getAttribute("href"),
        "{{linkText}}" => $this->resx_left_menu[$link->getAttribute("resourcekey")]
    );
    $html["hasLink"] = FALSE;
    if ($link->getAttribute("enablelink") === "true") {
      $html["output"] = $this->OPEN_LI . "<a href=\"{{href}}\">{{linkText}}</a>" . $this->CLOSE_LI;
      $html["hasLink"] = TRUE;
    } else {
      $html["output"] = $this->OPEN_LI . $this->resx_left_menu[$link->getAttribute("resourcekey")] . $this->CLOSE_LI;
    }
    return $html;
  }

  private function _AddLinkSubMenus($link) {
    $placeholders = array(
        "{{href}}" => $this->base_url . $link->getAttribute("href"),
        "{{itemId}}" => $link->getAttribute("id"),
        "{{linkText}}" => $this->resx_left_menu[$link->getAttribute("resourcekey")]
    );
    return
            $this->OPEN_LI . strtr("<a href=\"{{href}}\" id=\"{{itemId}}\">{{linkText}}</a>", $placeholders) . $this->CLOSE_LI;
  }

}