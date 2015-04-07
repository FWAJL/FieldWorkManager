<?php

/**
 *
 * @package		Basic MVC framework
 * @author		FWM DEV Team
 * @copyright	Copyright (c) 2015
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
 * @author		FWM DEV Team
 * @link
 */

namespace Applications\PMTool\Controllers;

use Applications\PMTool\AuthProvider;
use Library\Interfaces\IUser;

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
  public function executeLoadLoginView(\Library\HttpRequest $rq) {
    //TODO: add resource using a Resource manager
    //$authenticate_js_script_path = "authenticate.js";
    $resourceFileKey = "login";

    $this->app->pageTitle = $this->app->i8n->getLocalResource($resourceFileKey, "page_title");
    $this->page->addVar('resx', $this->app->i8n->getLocalResourceArray($resourceFileKey));
    
    $this->executeDisconnect($rq,FALSE);
  }

  /**
   * Method that receives the call from JS Client to login a user
   *
   * @param \Library\HttpRequest $rq: the request
   * @return json object A JSON object with the result bool value and success/error message
   */
  public function executeAuthenticate(\Library\HttpRequest $rq) {
    //Initialize the response to error.
    $result = $this->InitResponseWS();

    //Let's retrieve the inputs from AJAX POST request
    $data_sent = $this->dataPost();

    $authProvider = new AuthProvider($this->app->config->get("encryption_key"), $this->managers->getManagerOf('Login'));
    $authProvider->prepareUser($data_sent);
    if($authProvider->getUser() instanceof \Library\Interfaces\IUser) {
      $this->app->auth->authenticate($authProvider->getUser());

      if ($authProvider->getUser()) {
        $user = $this->app->user;
        $routes = array_filter($this->app->router->routes(), function ($route) use ($user) {
          return (count($route->role()) == 0) || in_array($user->getRole(), $route->role());
        });
        \Applications\PMTool\Helpers\UserHelper::SaveRoutes($user, $routes);
        switch ($authProvider->getUser()->getType()) {
          case 'technician_id':
            break;
          case 'pm_id':
            \Applications\PMTool\Helpers\PmHelper::StoreSessionPm($this, $authProvider->getUserType(), true);
            break;
        }
        $result = $this->InitResponseWS("success");
        $result['role'] = $user->getRole();
      }
    }

    //return the JSON data
    echo \Library\HttpResponse::encodeJson($result);
  }

  /**
   * Method that logout a user from the session and then redirect him to Login page.
   *
   * @param \Library\HttpRequest $rq
   */
  public function executeDisconnect(\Library\HttpRequest $rq, $redirect = TRUE) {
    $this->app->auth->deauthenticate();
    if ($redirect) { $this->Redirect("login"); }
  }
  
    /**
   * Method that logout a user from the session and then redirect him to Login page.
   *
   * @param \Library\HttpRequest $rq
   */
  public function executeCreate(\Library\HttpRequest $rq) {
    $protect = new \Library\BL\Core\Encryption();
    $data = array(
      "user_login" => $rq->getData("login"),
      "user_password" => $rq->getData("password"),
      "user_type" => $rq->getData("type"),
      "user_role" => \Applications\PMTool\Helpers\UserHelper::GetRoleFromType($rq->getData("type"))
    );
    $user = \Applications\PMTool\Helpers\CommonHelper::PrepareUserObject($data, new Applications\PMTool\Models\Dao\User());
    
    $user->setUser_password($protect->Encrypt($this->app->config->get("encryption_key"), $user->user_password()));

    $loginDal = $this->managers->getManagerOf("Login");
    $id = $loginDal->add($pm);
    $redirect = intval($id) > 0 ? TRUE : FALSE;
    
    if ($redirect) { $this->Redirect("login"); }
  }

  /**
   * 
   * @param string $step
   * @return array
   */
  public function InitResponseWS($step = "init", $user = NULL) {
    $resourceFileKey = "login";
    if ($step === "success") {
      return array(
        "result" => 1,
        "message" => $this->app->i8n->getLocalResource($resourceFileKey, "message_success")
      );
    } else {
      return array(
        "result" => 0,
        "message" => $this->app->i8n->getLocalResource($resourceFileKey, "message_error")
      );
    }
  }

}
