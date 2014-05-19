<?php

function autoload($class) {
  if (file_exists('../' . str_replace('\\', '/', $class) . '.php')) {
    require __ROOT__ . str_replace('\\', '/', $class) . '.php';
  }
}

define('__ROOT__', dirname(dirname(dirname(__FILE__))).'/BaseApplication/');
spl_autoload_register('autoload');