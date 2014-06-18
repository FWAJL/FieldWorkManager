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
  protected $scriptsToAdd = "";

  public function __construct($url, $module, $action, array $varsNames, $type, $scriptsToAdd) {
    $this->setUrl($url);
    $this->setModule($module);
    $this->setAction($action);
    $this->setVarsNames($varsNames);
    $this->setType($type);
    $this->setScriptsToAdd($scriptsToAdd);
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
  
  public function setScriptsToAdd($scriptsToAdd) {
    return $this->scriptsToAdd = $scriptsToAdd;
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
  public function scriptsToAdd() {
    return $this->scriptsToAdd;
  }

}