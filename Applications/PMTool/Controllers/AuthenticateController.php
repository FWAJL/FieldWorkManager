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
        "user_sent" => array(),
        "user_returned" => array()
    );

    $manager = $this->managers->getManagerOf('Login');

    //Let's retrieve the inputs from POST
    //First, check the inputs. If valid, we'll continue.
    if (\Library\Core\Utilities\FormUtility::CleanseInput($rq->postData("username")) &&
            \Library\Core\Utilities\FormUtility::CleanseInput($rq->postData("pwd"))) {
      $result["user_sent"] = array(
          "username" => "test",//$rq->postData("username"),
          "pwd" => "password"//$rq->postData("pwd")
      );
      $result["user_returned"] = $manager->selectOne($result["user_sent"]);
      header('Content-Type: application/json');
      echo json_encode($result["user_returned"], 128);
    } else {
      echo json_encode($result["user_returned"], 128);
    }
  }

}