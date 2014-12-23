<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AppSettings
 *
 * @author jl
 */
namespace Library\Enums;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

abstract class AppSettingKeys {
  const ApplicationMode = "ApplicationMode";
  
  const DefaultLanguage = "DefaultLanguage";
  const BaseUrl = "base_url";
  const BaseUrlRelease = "BaseUrlRelease";
  const RootImageFolderPath = "RootImageFolderPath";
  const DalFolderPath = "DalFolderPath";
  
  const Myslq_host = "Myslq_host";
  const Mysql_user = "Mysql_user";
  const Mysql_pwd = "Mysql_pwd";
  const Mysql_db_name = "Mysql_db_name";
  
}

?>
