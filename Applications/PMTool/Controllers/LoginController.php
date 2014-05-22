<?php

namespace Applications\PMTool\Controllers;

class LoginController extends \Library\BaseController {

  public function executeIndex(\Library\HttpRequest $rq) {
    //TODO: add resource using a Resource manager
    //$authenticate_js_script_path = "authenticate.js";
    $resourceFileKey = "login";

    $this->app->pageTitle = $this->app->i8n->getLocalResource($resourceFileKey, "page_title");
    $this->page->addVar("login_image_url", $this->app->imageUtil->getImageUrl("logibn.png"));

    $this->page->addVar('resx', $this->app->i8n->getLocalResourceArray($resourceFileKey));
    $this->page->addVar('resume_url', $this->app->router->pageUrls[\Library\Enums\ResourceKeys\PublicPageUrls::ResumeUrl]);
  }

//  public function executeAuthenticate(\Library\BL\Core\HTTPRequest $rq) {
//    $resourceFileKey = "login";
//    $result = [
//        "result" => 0,
//        "message" => $this->app->i8n->getLocalResource($resourceFileKey, "message_default_authenticate")
//    ];
//
//    $manager = $this->managers->getManagerOf('Login');
//
//    //Let's retrieve the inputs from POST
//    //First, check the inputs. If valid, we'll continue.
//    if (\Library\BL\Utilities\FormUtility::CleanseInput($rq->postData("username")) &&
//            \Library\BL\Utilities\FormUtility::CleanseInput($rq->postData("pwd"))) {
//      $result["user"] = [
//          "username" => $rq->postData("username"),
//          "pwd" => $rq->postData("pwd")
//      ];
//      $pm = $manager->selectOne($result["user"]);
//      $result["user_returned"] = $pm;
//      echo json_encode($result);
//    } else {
//      echo json_encode($result);
//    }
//  }

}