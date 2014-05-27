<?php

namespace Library\Core\Utilities;

class FormUtility {
  /**
   * Returns if the string is valid or not
   * 
   * @param type string $data_to_clean
   * @return type bool
   */
  static function CleanseInput($data_to_clean) {
    return $data_to_clean !== mysql_real_escape_string($data_to_clean);
  }
  

}