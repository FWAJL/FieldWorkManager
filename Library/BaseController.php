<?php

namespace Library;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

abstract class BaseController extends ApplicationComponent {

  protected $action = '';
  protected $module = '';
  protected $page = null;
  protected $view = '';
  protected $managers = null;
  protected $resxfile = "";
  protected $dataPost = array();


  public function __construct(Application $app, $module, $action, $resxfile) {
    parent::__construct($app);
    $this->managers = new \Library\DAL\Managers('PDO', $app);
    $this->page = new Page($app);

    $this->setModule($module);
    $this->setAction($action);
    $this->setView($action);
    $this->setResxFile($resxfile);
    $this->setDataPost($this->app->HttpRequest()->retrievePostAjaxData(NULL, FALSE));
  }

  public function execute() {
    $method = 'execute' . ucfirst($this->action);

    if (!is_callable(array($this, $method))) {
      throw new \RuntimeException('The action "' . $this->action . '" is not defined for this module');
    }
    //
    if ($this->resxfile !== NULL) {
      $this->AddCommonVarsToPage();
    }

    $br = new UC\Breadcrumb($this->app());
    //Load controller method
    $result = $this->$method($this->app->HttpRequest());
    if ($result !== NULL) {
      $result["br"] = $br->Build();
      echo \Library\HttpResponse::encodeJson($result);
    } else {
      $this->page->addVar("br", $br->Build());
    }
  }

  public function page() {
    return $this->page;
  }

  public function leftMenu() {
    return $this->leftMenu;
  }
  
  public function module() {
    return $this->module;
  }
  
  public function action() {
    return $this->action;
  }

  public function dataPost() {
    return $this->dataPost;
  }

  public function setModule($module) {
    if (!is_string($module) || empty($module)) {
      throw new \InvalidArgumentException('the module value must be a string and not be empty');
    }

    $this->module = $module;
  }

  public function setAction($action) {
    if (!is_string($action) || empty($action)) {
      throw new \InvalidArgumentException('The action value must be a string and not be empty');
    }

    $this->action = $action;
  }

  public function setView($view) {
    if (!is_string($view) || empty($view)) {
      throw new \InvalidArgumentException('The view value must be a string and not be empty');
    }

    $this->view = $view;

    $this->page->setContentFile(
            __ROOT__ . Enums\FolderName::AppsFolderName
            . $this->app->name()
            . Enums\FolderName::ViewsFolderName
            . $this->module
            . '/'
            . $this->view . '.php');
  }
  
  public function setResxFile($resxfile) {
    if (!is_string($resxfile) || empty($resxfile)) {
      throw new \InvalidArgumentException('The resx file must be a string and not be empty');
    }

    $this->resxfile = $resxfile;
  }
  
  public function setDataPost($dataPost) {
    if (!is_array($dataPost) || empty($dataPost)) {
      $this->dataPost = array();
    }

    $this->dataPost = $dataPost;
  }
  /**
   * Set the default response from WS
   * 
   * @param string $resxKey
   * @param string $step
   * @param \Applications\PMTool\Models\Dao\Project_manager $user
   * @return aeeay
   */
  public function InitResponseWS($params = array("resx_file" => "ws_defaults", "resx_key" => "", "step" => "error")) {
    if ($params["step"] === "success") {
      return array(
          "result" => 1,
          "message" => $params["resx_file"] === "ws_defaults" ?
                  $this->app->i8n->getCommonResource($params["resx_file"], "message_success" . $params["resx_key"]) :
                  $this->app->i8n->getLocalResource($params["resx_file"], "message_success" . $params["resx_key"])
      );
    } else {
      return array(
          "result" => 0,
          "message" => $params["resx_file"] === "ws_defaults" ?
                  $this->app->i8n->getCommonResource($params["resx_file"], "message_error" . $params["resx_key"]) :
                  $this->app->i8n->getLocalResource($params["resx_file"], "message_error" . $params["resx_key"])
      );
    }
  }

  /**
   * Set the default response from WS
   * 
   * @param string $resxKey
   * @param string $step
   * @param \Applications\PMTool\Models\Dao\Project_manager $user
   * @return aeeay
   */
  public function SendResponseWS($result, $params) {
    if ($params["step"] === "success") {
      $result["result"] = 1;
      $result["message"] = $params["resx_file"] === "ws_defaults" ?
              $this->app->i8n->getCommonResource($params["resx_file"], "message_success_" . $params["resx_key"]) :
              $this->app->i8n->getLocalResource($params["resx_file"], "message_success_" . $params["resx_key"]);
    } else {
      $result["result"] = 0;
      $result["message"] = $params["resx_file"] === "ws_defaults" ?
              $this->app->i8n->getCommonResource($params["resx_file"], "message_error_" . $params["resx_key"]) :
              $this->app->i8n->getLocalResource($params["resx_file"], "message_error_" . $params["resx_key"]);
    }
    echo \Library\HttpResponse::encodeJson($result);
  }

  /**
   * Retrieve the objects from a list filtering them by a list of IDs
   * 
   * @param type $params
   *    array(
   *      "filter" => "property_name_of_object_type",
          "ids" => ids_to_filter_objects, 
          "objects" => the_objects
   *    )
   * @return array of objects
   */
  public function FindObjectsFromIds($params) {
    //TODO: use LINQ helper to loop array more efficiently
    $matchedElements = array();
    foreach ($params["objects"] as $object) {
      foreach ($params["ids"] as $id) {
        if($object->$params["filter"]() === $id) {
          array_push($matchedElements, $object);
          break;
        }
      }
    }
    return $matchedElements;
  }
  
  /**
   * Add to the page object the common variables to use in the views
   * 
   * Variables:
   *    - left_menu
   *    - resx
   *    - logout_url
   *    - pageTitle (not local variable but a variable in the application
   */
  protected function AddCommonVarsToPage() {
    //Get resources for the left menu
    $resx_left_menu = $this->app->i8n->getCommonResourceArray(Enums\ResourceKeys\ResxFileNameKeys::MenuLeft);
    //Init left menu
    $leftMenu = new UC\LeftMenu($this->app(), $resx_left_menu);
    //Add left menu to layout
    $this->page->addVar("leftMenu", $leftMenu->Build());
    $this->page->addVar('resx', $this->app->i8n->getLocalResourceArray($this->resxfile));
    $this->app->pageTitle = $this->app->i8n->getLocalResource($this->resxfile, "page_title");
    $this->page->addVar("logout_url", __BASEURL__ . Enums\ResourceKeys\UrlKeys::LogoutUrl);
  }


//  protected function executeIndex($params) {
//    
//  }
//  protected function executeAdd($params) {
//    
//  }
//  protected function executeUpdate($params) {
//    
//  }
//  protected function executeDelete($params) {
//    
//  }
//  protected function executeGetItem($params) {
//    
//  }
//  protected function executeGetList($params) {
//    
//  }
//  protected function executeListAll($params) {
//    
//  }
//  protected function executeShowForm($params) {
//    
//  }
  
}
