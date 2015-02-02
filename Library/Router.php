<?php

namespace Library;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class Router extends ApplicationComponent {

  public $pageUrls = array();
  public $isWsCall = false;
  public $routesXmlPath;
  protected $routes = array();
  protected $lastModified = 0; //of the routes xml file
  protected $selectedRoute;
  private $routesXml;

  const NO_ROUTE = 1;

  // SET AND GET $selectedRoute
  // @type \Library\ROute
  public function setSelectedRoute($route) {
    $this->selectedRoute = $route;
  }

  public function selectedRoute() {
    return $this->selectedRoute;
  }

  // SET AND GET $lastModified
  public function setLastModified($time_updated) {
    $this->lastModified = $time_updated;
  }

  public function lastModified() {
    return $this->lastModified;
  }

  // SET AND GET RoutesXmlPath
  public function setRoutesXmlPath($path) {
    $this->routesXmlPath = $path;
  }

  public function routesXmlPath() {
    return $this->routesXmlPath;
  }

  // SET AND GET Routes
  public function setRoutes($routes) {
    $this->routes = $routes;
  }

  public function routes() {
    return $this->routes;
  }

  public function addRoute(Route $route) {
    if (!in_array($route, $this->routes)) {
      $this->routes[] = $route;
    }
  }

  public function getRoute($url) {
    foreach ($this->routes as $route) {
      // Si la route correspond à l'URL.
      if (($varsValues = $route->match($url)) !== false) {
        $varsNames = $route->varsNames();
        $listVars = array();
        $this->createListOfVars($varsValues, $varsNames, $listVars);

        // On assigne ce tableau de variables à la route.
        $route->setVars($listVars);
        return $route;
      }
    }

    throw new \RuntimeException('No route match for URL:' . $url, self::NO_ROUTE);
  }

  private function createListOfVars($varsValues, $varsNames, $listVars) {
    // On créé un nouveau tableau clé/valeur.
    // (Clé = nom de la variable, valeur = sa valeur.)
    foreach ($varsValues as $key => $match) {
      // La première valeur contient entièrement la chaine capturée (voir la doc sur preg_match).
      if ($key !== 0) {
        $listVars[$varsNames[$key - 1]] = $match;
      }
    }
    return $listVars;
  }

  public function LoadAvailableRoutes(Application $currentApp) {
    $xml = new \DOMDocument;
    $xml->load($this->routesXmlPath);

    $this->routesXml = $xml->getElementsByTagName('route');
    // On parcourt les routes du fichier XML.
    foreach ($this->routesXml as $route) {
      $vars = array();

      // On regarde si des variables sont présentes dans l'URL.
      if ($route->hasAttribute('vars')) {
        $vars = explode(',', $route->getAttribute('vars'));
      }
      // We store the page Url to be used globally in the app
      $this->pageUrls[$route->getAttribute('url') . "Url"] = $route->getAttribute('url');

      // Get and calculate the relative path to add to add js and css to view properly
      $path_to_add = $this->_GetRelativePath($route->getAttribute('url'));
      // On ajoute la route au routeur.
      $route_config = array(
          "route_xml" => $route,
          "vars" => $vars,
          "js_head" => $this->_GetJsFiles($route, "head", __BASEURL__),
          "js_html" => $this->_GetJsFiles($route, "html", __BASEURL__),
          "css" => $this->_LoadCssFiles($route, __BASEURL__),
          "php_modules" => $this->_LoadPhpModules($route),
          "relative_path" => $path_to_add,
          "resxfile" => $route->getAttribute('resxfile')
      );
      $this->addRoute(new Route($route_config));
    }
  }

  /**
   * Returns the script urls to add to the loading view or to the head element
   * 
   * @param DoMNode $route
   * @return string
   */
  private function _GetJsFiles($route, $destination, $path_to_add) {
    $scripts = "";
    foreach ($route->getElementsByTagName('js_file') as $script) {
      if ($script->getAttribute('use') === $destination) {
          $scripts .= '<script type="application/javascript" src="' . $path_to_add . $script->getAttribute('value') . '"></script>';
      } else if($script->getAttribute('use') !== "head" && $script->getAttribute('use') !== "html") {
        //Select js files from parent route
        $parent_route = $this->getRoute(__BASEURL__ . $script->getAttribute('use'));
        $scripts .= $this->_GetFilesForSibbling($parent_route, $destination, $path_to_add);
      }
    }
    return $scripts;
  }

  /**
   * Get the scripts for a sibbling route
   * 
   * Ex: 2 routes have the same scripts, then we use the route properties 
   * in the routes list to set
   * the script or css for the sibbling route. 
   * 
   * @param type $route \Library\Route
   * @param type $destination
   * @param type $path_to_add
   * @return type
   */
  private function _GetFilesForSibbling($route, $destination, $path_to_add) {
    $files = "";
    switch ($destination) {
      case "head":
        $files = $route->headJsScripts();
        break;
      case "html":
        $files = $route->htmlJsScripts();
        break;
      default://css
        $files = $route->cssFiles();
        break;
    }
    return $files;
  }

  /**
   * Returns the css files urls to add to the loading view
   * 
   * @param DoMNode $route
   */
  private function _LoadCssFiles($route, $path_to_add) {
    $css_files = "";
    foreach ($route->getElementsByTagName('css_file') as $css_file) {
      if ($css_file->getAttribute("use") !== "") {
        $parent_route = $this->getRoute(__BASEURL__ . $css_file->getAttribute('use'));
        $css_files .= $this->_GetFilesForSibbling($parent_route, "css", $path_to_add);
      } else {
        $css_files .= '<link rel="stylesheet" type="text/css" href="' . $path_to_add . $css_file->getAttribute('value') . '"/>';
      }
    }
    return $css_files;
  }

  /**
   * Returns the absolute file paths of PHP modules to load per route
   * There are 2 cases: 
   *  - shared modules (available to any route)
   *  - dedicated modules (to the module specified in the route config)
   * 
   * @param DoMNode $route
   * @return array
   */
  public function _LoadPhpModules($route) {
    $modules = array();
    foreach ($route->getElementsByTagName('php_module') as $module) {
      if ($module->getAttribute('shared')) {
        $modules[$module->getAttribute('key')] =
                __ROOT__ . \Library\Enums\ApplicationFolderName::AppsFolderName
                . $this->app->name()
                . rtrim(\Library\Enums\ApplicationFolderName::ViewsFolderName, '/') . \Library\Enums\ApplicationFolderName::ModulesFolderName
                . $module->getAttribute('file_name');
      } else {
        $modules[$module->getAttribute('key')] =
                __ROOT__ . \Library\Enums\ApplicationFolderName::AppsFolderName
                . $this->app->name()
                . \Library\Enums\ApplicationFolderName::ViewsFolderName . $route->getAttribute('module') . \Library\Enums\ApplicationFolderName::ModulesFolderName
                . $module->getAttribute('file_name');
      }
    }
    return $modules;
  }

  /**
   * Calculate the relative path to use for the css and js files declaration in HTML views
   * 
   * @param DOMNode $route
   * @return string
   */
  private function _GetRelativePath($route) {
    $route = rtrim($route, '/');
    $relative_path_count = explode("/", $route);
    $relative_path = "";
    for ($i = 1; $i < count($relative_path_count); $i++) {
      $relative_path .= "../";
    }
    return $relative_path;
  }

  public function hasRoutesXmlChanged(\Library\User $user) {
    if (file_exists($this->routesXmlPath)) {
      $currentLastModifiedTime = filemtime($this->routesXmlPath);

      if (!$user->keyExistInSession(Enums\SessionKeys::SessionRoutesXmlLastModified)) {
        $user->setAttribute(Enums\SessionKeys::SessionRoutesXmlLastModified, $currentLastModifiedTime);
        return FALSE;
      } else {
        $result = $currentLastModifiedTime > $user->getAttribute(Enums\SessionKeys::SessionRoutesXmlLastModified);
        if (($result === TRUE)) {
          $user->setAttribute(Enums\SessionKeys::SessionRoutesXmlLastModified, $currentLastModifiedTime);
        }
        return $result;
      }
    }
  }

}
