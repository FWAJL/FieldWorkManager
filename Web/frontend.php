<?php
echo "<!-- test -->";
require '../Applications/autoload.php';
echo "<!-- autoload require done -->";

try {
  $app = new Applications\FrontEnd\FrontEndApplication;
  echo "<!-- init front app done -->";

  $app->run();
  echo "<!-- run front app done -->"; 
} catch (Exception $exc) {
  echo '<!--'.$exc->getTraceAsString().'-->';
}