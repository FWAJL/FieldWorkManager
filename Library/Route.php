<?php

namespace Library;
if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');

class Route {

  protected $action;
  protected $module;
  protected $url;
  protected $varsNames;
  protected $vars = array();
  protected $type;
  protected $headJsScripts = "";
  protected $htmlJsScripts = "";
  protected $cssFiles = "";
  public $relative_path = "";
  protected $phpModules;
  protected $resxfile = "";
  protected $role = [];

  public function __construct($config) {
    $this->setUrl($config['route_xml']->getAttribute('url'));
    $this->setModule($config['route_xml']->getAttribute('module'));
    $this->setAction($config['route_xml']->getAttribute('action'));
    $this->setType($config['route_xml']->getAttribute('type'));
    $this->setRole($config['route_xml']->getAttribute('role'));

    $this->setVarsNames($config['vars']);
    
    $this->setJsScripts($config['js_head'], TRUE);
    $this->setJsScripts($config['js_html'], FALSE);
    $this->setCssFiles($config['css']);
    $this->setRelativePath($config["relative_path"]);
    $this->setPhpModules($config["php_modules"]);
    $this->setResxFile($config["resxfile"]);
  }

  public function hasVars() {
    return !empty($this->varsNames);
  }

  public function match($url) {
    if (preg_match('`^' . $this->url . '$`', $url, $matches)) {
      return $matches;
    } else {
      return false;
    }
  }

  public function setAction($action) {
    if (is_string($action)) {
      $this->action = $action;
    }
  }

  public function setModule($module) {
    if (is_string($module)) {
      $this->module = $module;
    }
  }

  public function setUrl($url) {
    if (is_string($url)) {
      $this->url = __BASEURL__ . $url;
    }
  }

  public function setVarsNames(array $varsNames) {
    $this->varsNames = $varsNames;
  }

  public function setVars(array $vars) {
    $this->vars = $vars;
  }

  public function setType($type) {
    if (is_string($type)) {
      $this->type = $type;
    }
  }

  public function setJsScripts($js_scripts, $forHead) {
    if ($forHead) {
     return $this->headJsScripts = $js_scripts; 
    } else {
     return $this->htmlJsScripts = $js_scripts; 
    }
  }

  public function setCssFiles($css_files) {
    return $this->cssFiles = $css_files;
  }
  
  public function setRelativePath($path) {
    return $this->relative_path = $path;
  }
  
  public function setPhpModules($php_modules) {
    return $this->phpModules = $php_modules;
  }

  public function setResxFile($resxfile) {
    return $this->resxfile = $resxfile;
  }


  public function setRole($roleString) {
    $this->role = explode(",", $roleString);
  }
  
  public function action() {
    return $this->action;
  }

  public function module() {
    return $this->module;
  }

  public function vars() {
    return $this->vars;
  }

  public function varsNames() {
    return $this->varsNames;
  }

  public function type() {
    return $this->type;
  }
  public function headJsScripts() {
    return $this->headJsScripts;
  }
  public function htmlJsScripts() {
    return $this->htmlJsScripts;
  }
  public function cssFiles() {
    return $this->cssFiles;
  }
  public function relative_path() {
    return $this->relative_path;
  }
  public function phpModules() {
    return $this->phpModules;
  }
  public function resxfile() {
    return $this->resxfile;
  }

  public function role(){
    return $this->role;
  }
  
}