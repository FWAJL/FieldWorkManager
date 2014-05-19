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

abstract class FileNameConst {
  /**
   * File name suffixes
   */

  const ControllerSuffix = "Controller";
  const ClassSuffix = ".php";
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
  const SideMenuTemplate = "/Templates/genericHeaderApp.php";
  const HeaderAppTemplate = "/Templates/genericSideMenu.php";
  const TopMenuTemplate = "/Templates/menu/genericTopMenu.php";
}

?>
