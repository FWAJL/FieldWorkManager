<?php

define('__VERSION_NUMBER__', '1.6.23');

require '../Applications/autoload.php';

set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

ini_set("display_errors", 1);
try {
  $app = new Applications\PMTool\PMToolApplication;
  //Only run the requested view if logged in
  if (isset($_GET["debug"]) 
      || strstr($app->HttpRequest->requestURI(), "login") !== FALSE
      || strstr($app->HttpRequest->requestURI(), "auth") !== FALSE
      || strstr($app->HttpRequest->requestURI(), "serviceprovider/discussion") !== FALSE
      || $app->user->isAuthenticated()) {
    $app->run();
  } else {//Otherwise, redirect to login page :)
    header('Location: ' . __BASEURL__ . "login");
    die();
  }
} catch (Exception $exc) {
  echo "<!--" . $exc->getMessage() . "\n\r" . $exc->getTraceAsString() . "-->";
}