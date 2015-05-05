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

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

abstract class AppSettingKeys {

  const ApplicationMode = "ApplicationMode";
  const DefaultLanguage = "DefaultLanguage";
  const BaseUrl = "base_url";
  const BaseUrlRelease = "BaseUrlRelease";
  const RootImageFolderPath = "RootImageFolderPath";
  const RootUploadsFolderPath = "RootUploadsFolderPath";
  const DalFolderPath = "DalFolderPath";
  const RootDocumentUpload = "RootDocumentUpload";
  const Myslq_host = "Myslq_host";
  const Mysql_user = "Mysql_user";
  const Mysql_pwd = "Mysql_pwd";
  const Mysql_db_name = "Mysql_db_name";
  
  const GoogleMapsCenterLat = "GoogleMapsCenterLat";
  const GoogleMapsCenterLng = "GoogleMapsCenterLng";
  const GoogleMapsProjectActiveIcon = "GoogleMapsProjectActiveIcon";
  const GoogleMapsProjectInactiveIcon = "GoogleMapsProjectInactiveIcon";
  const GoogleMapsLocationActiveIcon = "GoogleMapsLocationActiveIcon";
  const GoogleMapsLocationInactiveIcon = "GoogleMapsLocationInactiveIcon";
  const GoogleMapsTaskIcon = "GoogleMapsTaskIcon";
  const GoogleMapsNoLatLngIcon = "GoogleMapsNoLatLngIcon";

  const DefaultEmailDomainValue = "DefaultEmailDomainValue";
}

?>
