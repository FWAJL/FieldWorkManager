<?php

namespace Library\BL\Core;

abstract class Application {

  protected $httpRequest;
  protected $httpResponse;
  public $name;
  public $locale;
  public $localeDefault;
  public $pageTitle;
  public $pageUrls;
  public $logoImageUrl;

  protected $user;
  protected $config;
  public $i8n;
  public $imageUtil;

  public function __construct() {
    $this->httpRequest = new HttpRequest($this);
    $this->httpResponse = new HttpResponse($this);

    $this->router = new Router($this);
    $this->user = new User($this);
    $this->config = new \Library\BL\Utilities\Config($this);
    $this->context = new Context($this);
    $this->i8n = new \Library\BL\Utilities\Globalization($this);    
    $this->imageUtil = new \Library\BL\Utilities\ImageUtility($this);    
    $this->locale = $this->httpRequest->initLanguage($this, "browser");
    
    echo '<!-- locale=' . $this->locale . ' -->';
    $this->name = '';
  }
  public function initConfig() {
    
  }

  public function getController() {
//    //
//    $router = new \Library\BL\Core\Router;
//    $router->LoadAvailableRoutes($this);
    $this->router->LoadAvailableRoutes($this);
    $matchedRoute = $this->FindRouteMatch();

    // On ajoute les variables de l'URL au tableau $_GET.
    $_GET = array_merge($_GET, $matchedRoute->vars());

    $controllerClass = $this->BuildControllerClass($matchedRoute);
    if (!file_exists(__ROOT__ . str_replace('\\', '/', $controllerClass) . \Library\Enums\FileNameConst::ClassSuffix)) {
      $this->httpResponse->displayError(\Library\Enums\ErrorCodes::ControllerNotExist); //Controller doesn't exist
    }
    return new $controllerClass($this, $matchedRoute->module(), $matchedRoute->action());
  }

  abstract public function run();

  public function httpRequest() {
    return $this->httpRequest;
  }

  public function httpResponse() {
    return $this->httpResponse;
  }

  public function user() {
    return $this->user;
  }

  public function config() {
    return $this->config;
  }

  public function context() {
    return $this->context;
  }
  
  public function i8n() {
    return $this->i8n;
  }
  
  public function imageUtil() {
    return $this->imageUtil;
  }
  
  public function pageUrls() {
    return array();
  }  
  
  public function router() {
    return $this->router;
  }

  public function name() {
    return $this->name;
  }

  private function FindRouteMatch() {
    try {
      // On récupère la route correspondante à l'URL.
      return $this->router->getRoute($this->httpRequest->requestURI());
    } catch (\RuntimeException $e) {
      if ($e->getCode() == \Library\BL\Core\Router::NO_ROUTE) {
        // Si aucune route ne correspond, c'est que la page demandée n'existe pas.
        $this->httpResponse->displayError(\Library\Enums\ErrorCodes::PageNotFound);
      }
    }
  }

  private function BuildControllerClass($route) {
    return \Library\Enums\NameSpaceName::AppsFolderName
            . $this->name
            . \Library\Enums\NameSpaceName::ControllersFolderName
            . $route->module()
            . \Library\Enums\FileNameConst::ControllerSuffix;
  }

}