<?php
echo "<!-- test -->";
require '../Applications/autoload.php';
echo "<!-- autoload require done -->";

error_reporting(1);
try {
  $app = new Applications\PMTool\PMToolApplication;
  echo "<!-- init front app done -->";

  $app->run();
  echo "<!-- run front app done -->"; 
} catch (Exception $exc) {
  echo '<!--'.$exc->getTraceAsString().'-->';
}