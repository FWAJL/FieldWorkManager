<?php

namespace Library;

class Router extends ApplicationComponent{

  protected $routes = array();
  public $pageUrls = array();

  const NO_ROUTE = 1;

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

    throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
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
    $xml->load(__ROOT__ . 'Applications/' . $currentApp->name . '/Config/routes.xml');
    
    $routes = $xml->getElementsByTagName('route');
    // On parcourt les routes du fichier XML.
    foreach ($routes as $route) {
      $vars = array();

      // On regarde si des variables sont présentes dans l'URL.
      if ($route->hasAttribute('vars')) {
        $vars = explode(',', $route->getAttribute('vars'));
      }
      // We store the page Url to be used globally in the app
      $this->pageUrls[$route->getAttribute('module')."Url"] = $route->getAttribute('url');

      // On ajoute la route au routeur.
      $this->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action'), $vars));
    }
  }

}
