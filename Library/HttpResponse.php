<?php

namespace Library;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class HttpResponse extends ApplicationComponent {

  protected $page;

  public function addHeader($header) {
    header($header);
  }

  public function redirect($location) {
    header('Location: ' . $location);
    exit;
  }

  public function displayError($errorCode) {
    $this->page = new Page($this->app);
    $this->page->setContentFile(__ROOT__ . 'Errors/' . $errorCode . '.html');

    //$this->addHeader('HTTP/1.0 404 Not Found');

    $this->send();
  }

  public function send() {
    // Actuellement, cette ligne a peu de sens dans votre esprit.
    // Promis, vous saurez vraiment ce qu'elle fait d'ici la fin du chapitre
    // (bien que je suis sûr que les noms choisis sont assez explicites !).
    exit($this->page->getGeneratedPage());
  }

  public function setPage(Page $page) {
    $this->page = $page;
  }

  // Changement par rapport à la fonction setcookie() : le dernier argument est par défaut à true.
  /*
   * Set cookie
   * 
   * params = [
   *    'name' => '',
   *    'value' => '',
   *    'expire' => '',
   *    'path' => '', 
   *    'domain' => '', 
   *    'secure' => '', 
   *    'httpOnly' => ''
   * ]
   */
  public function setCookie($params) {
    setcookie(
        $params['name'], $params['value'], $params['expire'], $params['path'], $params['domain'], $params['secure'], $params['httpOnly']
    );
  }

  /**
   * Set content type to JSON when replying to AJAX call and encode the data sent back.
   * 
   * @param array $response
   * @return json
   */
  public static function encodeJson($response) {
    header('Content-Type: application/json');
    return json_encode($response, 128); //Encode response to pretty JSON
  }

}
