<?php

namespace Library\Utility;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class StringHelper {
  public static function Equals($string1, $string2) {
    return strcmp($string1, $string2) === 0 ? TRUE : FALSE;
  }
  
  public static function IsNullOrEmpty($string) {
    return is_nan($string) || empty($string);
  }
}