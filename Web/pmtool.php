<?php

require '../Applications/autoload.php';

error_reporting(E_ALL);
ini_set("display_errors", 1);
try {
  $app = new Applications\PMTool\PMToolApplication;
  //Only run the requested view if logged in
  if (strstr($app->httpRequest->requestURI(), "login") !== FALSE ||
          strstr($app->httpRequest->requestURI(), "auth") !== FALSE ||
          $app->user->isAuthenticated()) {
    $app->run();
  } else {//Otherwise, redirect to login page :)
    header('Location: ' . __BASEURL__ . "login");
  }
} catch (Exception $exc) {
  echo '<!--' . $exc->getMessage() . '-->';
}