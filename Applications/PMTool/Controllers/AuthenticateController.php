<?php

namespace Applications\PMTool\Controllers;

class AuthenticateController extends \Library\BaseController {

  public function executeLoadView(\Library\HttpRequest $rq) {
    //TODO: add resource using a Resource manager
    //$authenticate_js_script_path = "authenticate.js";
    $resourceFileKey = "login";

    $this->app->pageTitle = $this->app->i8n->getLocalResource($resourceFileKey, "page_title");
    $this->page->addVar("login_image_url", $this->app->imageUtil->getImageUrl("logibn.png"));

    $this->page->addVar('resx', $this->app->i8n->getLocalResourceArray($resourceFileKey));
  }

  /*
   * Method that receives the call from JS Client to login a user
   * Return the result whether the user can be logged in
   */

  public function executeAuthenticate(\Library\HttpRequest $rq) {
    $resourceFileKey = "login";
    $result = array(
        "result" => 0,
        "message" => $this->app->i8n->getLocalResource($resourceFileKey, "message_default_authenticate"),
    );

    $manager = $this->managers->getManagerOf('Login');

    //Let's retrieve the inputs from POST
    //First, check the inputs. If valid, we'll continue.
    $user_sent = array(
        "username" => $rq->postData("email"), //$rq->postData("username"),
        "pwd" => $rq->postData("pwd")//$rq->postData("pwd")
    );
    //Search for user in DB and return him
    $user_db = $manager->selectOne($user_sent);
    
    //If user_db is null, set error message
    //TODO: add resources
    if (is_null($user_db)) {
      $result["result"] = 0;
      $result["message"] = "User not found! Please check your credentials.";
    } else {
      $this->LoginUser($user_sent, $user_db);
      $result["result"] = 1;
      $result["message"] = "Logged in! Going to your projects... Please wait.";      
    }
    header('Content-Type: application/json');
    echo json_encode($result, 128);
  }
  
  private function LoginUser($user_in, $user_out) {
    
    return true;
  }

}