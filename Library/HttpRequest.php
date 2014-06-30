<?php

namespace Library;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class HttpRequest {

  public function cookieData($key) {
    return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
  }

  public function cookieExists($key) {
    return isset($_COOKIE[$key]);
  }

  public function getData($key) {
    return isset($_GET[$key]) ? $_GET[$key] : null;
  }

  public function getExists($key) {
    return isset($_GET[$key]);
  }

  public function method() {
    return $_SERVER['REQUEST_METHOD'];
  }

  public function postData($key) {
    return isset($_POST[$key]) ? $_POST[$key] : null;
  }

  public function postExists($key) {
    return isset($_POST[$key]);
  }

  public function requestURI() {
    return strtok($_SERVER['REQUEST_URI'], '?');
  }

  public function initLanguage(Application $currentApp, $type) {
    if ($type === "default") {
      return $currentApp->config()->get(Enums\AppSettingKeys::DefaultLanguage);
    }
    if ($type === "browser") {
      return substr(strtok($_SERVER['HTTP_ACCEPT_LANGUAGE'], '?'), 0, 2);
    }
  }

  /**
   * Fetch an item from the php://input array which is compatible with ajax post requests
   * FYI, the regular post method doesn't work with ajax 
   *
   * @access	public
   * @param	string
   * @param	bool
   * @return	string
   */
  public function retrievePostAjaxData($index = NULL, $xss_clean = FALSE) {
    if (file_get_contents('php://input') != "") {
      // Create an array from the JSON object in the POST request
      $post_raw = get_object_vars(json_decode(file_get_contents('php://input')));
      // Check if a field has been provided
      if ($index === NULL AND ! empty($post_raw)) {
        $post_cleaned = array();
        foreach (array_keys($post_raw) as $key) {
          $post_cleaned[$key] = $this->_fetch_from_array($post_raw, $key, $xss_clean);
        }
        // Return all the post values
        return $post_cleaned;
      }
      // Return the value for index
      return $this->_fetch_from_array($post_raw, $index, $xss_clean);
    }
    return FALSE; // Nothing in post request
  }

  // --------------------------------------------------------------------

  /**
   * Fetch from array
   *
   * This is a helper function to retrieve values from global arrays
   *
   * @access	private
   * @param	array
   * @param	string
   * @param	bool
   * @return	string
   */
  private function _fetch_from_array(&$array, $index = '', $xss_clean = FALSE) {
    if (!isset($array[$index])) {
      return FALSE;
    }

    if ($xss_clean === TRUE) {
      $security = new BL\Core\Security();
      return $security->xss_clean($array[$index]);
    }

    return $array[$index];
  }
}
