<?php
require '../Applications/autoload.php';

error_reporting(E_ALL);
ini_set("display_errors", 1);
try {
  $app = new Applications\PMTool\PMToolApplication;
  $app->run();
} catch (Exception $exc) {
  echo '<!--'.$exc->getMessage().'-->';
}