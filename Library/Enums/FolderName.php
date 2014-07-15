<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Paths
 *
 * @author jl
 */

namespace Library\Enums;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

/**
 * Constants to load files
 */
abstract class FolderName {

  const AppsFolderName = "Applications/";
  const ControllersFolderName = "/Controllers/";
  const ViewsFolderName = "/Views/";
  const TemplatesFolderName = "/Templates/";
  const ConfigFolderName = "/Config/";
  const ResourceCommonFolderName = "/Resources/Common/";
  const ResourceLocalFolderName = "/Resources/Local/";
  const WebJsAppFolderName = "";
  const WebJsAppControllersFolderName = "";
  const ModulesFolderName = "/Modules/";

}

?>
