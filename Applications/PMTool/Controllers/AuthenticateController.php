<?php

namespace Applications\PMTool\Controllers;

class AuthenticateController extends \Library\BaseController {

  public function executeIndex(\Library\HttpRequest $rq) {
    $resourceFileKey = "login";
//    $result = [
//        "result" => 0,
//        "message" => $this->app->i8n->getLocalResource($resourceFileKey, "message_default_authenticate")
//    ];

//    $manager = $this->managers->getManagerOf('Login');
//
//    //Let's retrieve the inputs from POST
//    //First, check the inputs. If valid, we'll continue.
//    if (\Library\FormUtility::CleanseInput($rq->postData("username")) &&
//            \Library\FormUtility::CleanseInput($rq->postData("pwd"))) {
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
  }

}