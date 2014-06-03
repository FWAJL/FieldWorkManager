<?php

/**
 *
 * @package		Basic MVC framework
 * @author		FWA DEV Team
 * @copyright	Copyright (c) 2014
 * @license		
 * @link		
 * @since		
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Authenticate controller Class
 *
 * @package		Application/PMTool
 * @subpackage	Controllers
 * @category	AuthenticateController
 * @author		FWA Dev Team
 * @link		
 */

namespace Applications\PMTool\Controllers;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class AuthenticateController extends \Library\BaseController {

  /**
   * Method that loads the Login view.
   * 
   * It loads the page title and the resources to load in the placeholders
   * 
   * @param \Library\HttpRequest $rq: the request
   */
  public function executeLoadView(\Library\HttpRequest $rq) {
    //TODO: add resource using a Resource manager
    //$authenticate_js_script_path = "authenticate.js";
    $resourceFileKey = "login";

    $this->app->pageTitle = $this->app->i8n->getLocalResource($resourceFileKey, "page_title");
    $this->page->addVar('resx', $this->app->i8n->getLocalResourceArray($resourceFileKey));
  }

  /**
   * Method that receives the call from JS Client to login a user
   * 
   * @param \Library\HttpRequest $rq: the request
   * @return json object A JSON object with the result bool value and success/error message
   */
  public function executeAuthenticate(\Library\HttpRequest $rq) {
    $resourceFileKey = "login";
    //Initialize the response to error.
    $result = array(
        "result" => 0,
        "message" => $this->app->i8n->getLocalResource($resourceFileKey, "message_error")
    );

    //Load interface to query the database
    $manager = $this->managers->getManagerOf('Login');

    //Let's retrieve the inputs from AJAX POST request
    $data_sent = $rq->post_ajax(NULL, FALSE);

    //Then, retrieve the login and password.
    $user = new \Library\BO\ProjectManager();
    $user->setPmEmail($data_sent["email"]);
    $user->setUserName($data_sent["username"]);
    $user->setPassword($data_sent["pwd"]);

    //Search for user in DB and return him
    $user_db = $manager->selectOne($user);

    //Decrypt password to check if match is found
    
    
    //If user_db is null or not matching, set error message
    if (is_null($user_db)) {
      //TODO: redirect after 3 sec
      header('Location: ' . __BASEURL__ . "login");
    } else {
      //User is correct so log him in and set result to success
      $protect = new \Library\BL\Core\Encryption();
//      echo "<!--pwd:" . $user->password() . "-->";
      $user->setPassword($protect->Encrypt($this->app->config->get("encryption_key"), $user->password()));
//      echo "<!--pwd:" . $user->password() . "-->";
      $manager->update($user);
      $this->LoginUser();
      $result["result"] = 1;
      $result["message"] = $this->app->i8n->getLocalResource($resourceFileKey, "message_success");
    }
    
    header('Content-Type: application/json');
    echo json_encode($result, 128);//Encode response to pretty JSON
  }

  /**
   * Method that logout a user from the session and then redirect him to Login page.
   * 
   * @param \Library\HttpRequest $rq
   */
  public function executeDisconnect(\Library\HttpRequest $rq) {
    $this->app->user->setAuthenticated(FALSE);
    header('Location: ' . __BASEURL__ . "login");
  }

  /**
   * Method that logs in a user in the session.
   * 
   */
  private function LoginUser() {
    $this->app->user->setAuthenticated();
  }

}