<?php

function autoload($class) {
  $file = __ROOT__ . str_replace('\\', '/', $class) . '.php';
  if (file_exists($file)) {
    try {
      require_once $file;
    } catch (Exception $exc) {
      echo "<!--" . $exc->getMessage() . "-->";
    }
  }
}
define('__EXECUTION_ACCESS_RESTRICTION__', true);
define('__BASEURL__', '/FieldWorkManager/');
define('__ROOT__', dirname(dirname(__FILE__)) . '/');

spl_autoload_register('autoload');
