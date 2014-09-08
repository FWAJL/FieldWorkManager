<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileNameConst
 *
 * @author jl
 */

namespace Library\Enums;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

abstract class FileNameConst {
  /**
   * File name suffixes
   */

  const ControllerSuffix = "Controller";
  const Extension = ".php";
  /**
   * File name prefixes
   */
  /*
   * Templates file names
   */
  const LayoutTemplate = "/Templates/layout.php";
  const HeaderTemplate = "/Templates/header.php";
  const ContenTemplate = "/Templates/content.php";
  const FooterTemplate = "/Templates/footer.php";
  const MenuTopTemplate = "/Templates/menus/top.php";
  const MenuLeftTemplate = "/Templates/menus/left.php";
  const BreadcrumbTemplate = "/Templates/breadcrumb.php";
}

?>
